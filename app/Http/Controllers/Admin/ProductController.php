<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Http\Requests\Admin\Product\UploadProductNewImagesRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Platform;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ShortLink;
use App\Models\Tag;
use App\Services\AttributeService;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected mixed $primaryUploadPath;

    protected mixed $othersUploadPath;

    public function __construct(
        private readonly FileUploadService $fileUpload,
        private readonly AttributeService $attributeService
    ) {
        $this->primaryUploadPath = config('upload.product_primary_path');
        $this->othersUploadPath = config('upload.product_others_path');
        $this->middleware('permission:products-index', ['only' => ['index', 'show']]);
        $this->middleware('permission:products-create', ['only' => ['store', 'create']]);
        $this->middleware('permission:products-edit', ['only' => ['update', 'edit', 'addImages', 'removeImages', 'setToPrimary']]);
        $this->middleware('permission:products-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View|Application|Factory
    {
        $query = Product::query()->with([
            'category:id,title',
            'brand:id,title',
            'variations' => fn ($q) => $q->orderBy('price'),
        ]);

        if ($request->filled('q')) {
            $query->search('title', trim((string) $request->input('q')));
        }

        if ($category = $request->input('category')) {
            $query->where('category_id', $category);
        }

        if ($request->input('status') === 'active') {
            $query->where('is_active', 1);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', 0);
        }

        match ($request->input('sort')) {
            'oldest' => $query->oldest(),
            'cheap' => $query->orderBy('price'),
            'expensive' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        $products = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', 1)->count(),
            'out_of_stock' => Product::whereDoesntHave('variations', fn ($q) => $q->where('quantity', '>', 0))->count(),
            'discounted' => Product::whereHas('variations', fn ($q) => $q->where('sale_price', '>', 0)->whereColumn('sale_price', '<', 'price'))->count(),
        ];

        $categories = \App\Models\Category::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.products.index', compact('products', 'stats', 'categories'));
    }

    public function create(): View|Application|Factory
    {
        return view('admin.products.create', $this->formData());
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $images = $this->fileUpload->uploadWithGallery(
                $request->file('primary_image'),
                $request->file('other_images', []),
                $this->primaryUploadPath,
                $this->othersUploadPath
            );

            $data = $request->validated();
            unset($data['faqs'], $data['code'], $data['short_link'], $data['primary_image'], $data['other_images']);

            $product = Product::create([
                ...$data,
                'primary_image' => $images['primary'],
            ]);

            foreach ($images['gallery'] as $imageName) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imageName,
                ]);
            }

            ShortLink::create([
                'code' => $request->filled('short_link')
                    ? $request->string('short_link')->toString()
                    : generateUniqueShortCode(),
                'type' => 'product',
                'target_id' => $product->id,
            ]);

            foreach ($request->input('faqs', []) as $faqData) {
                if (empty($faqData['question']) || empty($faqData['answer'])) {
                    continue;
                }

                $product->faqs()->create([
                    'question' => $faqData['question'],
                    'answer' => $faqData['answer'],
                ]);
            }

            $product->tags()->sync($request->input('tag_ids', []));

            $this->attributeService->updateFilters(
                $request->input('filters_value') ?: [],
                $product,
                true
            );

            $variationAttributeId = $this->variationAttributeId($product);

            if (! $variationAttributeId && $request->filled('variation_values')) {
                throw new Exception('دسته‌بندی انتخابی هیچ ویژگی تنوعی ندارد؛ ابتدا برای این دسته ویژگی «تنوع» تعریف کنید.');
            }

            if ($variationAttributeId && $request->filled('variation_values')) {
                $this->attributeService->syncVariations(
                    $product->id,
                    $variationAttributeId,
                    $request->input('variation_values')
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.product.create_failed'));

            return redirect()->back()->withInput();
        }

        flash()->success(config('flasher.product.created'));

        return redirect()->route('admin.products.index');
    }

    public function show(Product $product): Factory|Application|View
    {
        $product->load('shortLink', 'tags', 'faqs');

        $filters = $product->filters()
            ->with('attribute:id,title')
            ->get()
            ->map(fn ($attribute) => [
                'value' => $attribute->value,
                'title' => $attribute->attribute?->title,
            ])
            ->filter(fn ($filter) => $filter['title'] !== null)
            ->values();

        $variations = $product->variations()
            ->with('attribute:id,title')
            ->get()
            ->map(fn ($variation) => [
                'title' => $variation->attribute?->title,
                'value' => $variation->value,
                'price' => $variation->price,
                'quantity' => $variation->quantity,
                'sku' => $variation->sku,
                'sale_price' => $variation->sale_price,
                'date_on_sale_from' => $variation->date_on_sale_from,
                'date_on_sale_to' => $variation->date_on_sale_to,
            ]);

        return view('admin.products.show', compact('product', 'filters', 'variations'));
    }

    public function edit(Product $product): Factory|View
    {
        $filters = $product->filters()
            ->with('attribute:id,title')
            ->get()
            ->map(fn ($attribute) => [
                'id' => $attribute->id,
                'value' => $attribute->value,
                'title' => $attribute->attribute?->title,
            ])
            ->filter(fn ($filter) => $filter['title'] !== null)
            ->values();

        $variations = $product->variations()
            ->with('attribute:id,title')
            ->get()
            ->map(fn ($variation) => [
                'id' => $variation->id,
                'title' => $variation->attribute?->title,
                'value' => $variation->value,
                'price' => $variation->price,
                'quantity' => $variation->quantity,
                'sku' => $variation->sku,
                'sale_price' => $variation->sale_price,
                'date_on_sale_from' => $variation->date_on_sale_from,
                'date_on_sale_to' => $variation->date_on_sale_to,
            ]);

        return view('admin.products.edit', [
            ...$this->formData(),
            'product' => $product,
            'filters' => $filters,
            'variations' => $variations,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            unset($data['faqs'], $data['newFaqs'], $data['code'], $data['short_link']);

            $product->update($data);

            // --- سوالات متداول ---
            $existingFaqs = $product->faqs()->pluck('id')->all();
            $requestFaqs = $request->input('faqs', []) ?: [];
            $requestFaqIds = array_map('intval', array_keys($requestFaqs));

            foreach ($requestFaqs as $key => $faqData) {
                $faq = $product->faqs()->find($key);

                if (! $faq) {
                    continue;
                }

                $faq->update([
                    'question' => $faqData['question'],
                    'answer' => $faqData['answer'],
                ]);
            }

            $removedFaqs = array_diff($existingFaqs, $requestFaqIds);

            if (! empty($removedFaqs)) {
                $product->faqs()->whereIn('id', $removedFaqs)->delete();
            }

            foreach ($request->input('newFaqs', []) ?: [] as $faqData) {
                if (empty($faqData['question']) || empty($faqData['answer'])) {
                    continue;
                }

                $product->faqs()->create([
                    'question' => $faqData['question'],
                    'answer' => $faqData['answer'],
                ]);
            }

            $product->tags()->sync($request->input('tag_ids', []));

            $this->attributeService->updateFilters(
                $request->input('filters_value') ?: [],
                $product,
                $product->wasChanged('category_id')
            );

            $variationAttributeId = $this->variationAttributeId($product);

            if (! $variationAttributeId
                && ($request->filled('variation_values') || $request->filled('new_variation_values'))) {
                throw new Exception('دسته‌بندی انتخابی هیچ ویژگی تنوعی ندارد؛ ابتدا برای این دسته ویژگی «تنوع» تعریف کنید.');
            }

            if ($variationAttributeId) {
                $this->attributeService->handleVariationsUpdate(
                    $product->id,
                    $variationAttributeId,
                    $request->input('variation_values', []) ?: [],
                    $request->input('new_variation_values', []) ?: []
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.product.update_failed'));

            return redirect()->back()->withInput();
        }

        flash()->success(config('flasher.product.updated'));

        return redirect()->route('admin.products.edit', $product->slug);
    }

    public function addImages(UploadProductNewImagesRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $images = $this->fileUpload->uploadToGallery($request->file('images', []), $this->othersUploadPath);

            foreach ($images as $imageName) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imageName,
                ]);
            }

            DB::commit();

            flash()->success(config('flasher.product.add_images'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.product.add_images_failed'));
        }

        return redirect()->route('admin.products.edit', $product->slug);
    }

    public function removeImages(Product $product, ProductImage $image): RedirectResponse
    {
        if ((int) $image->product_id !== (int) $product->id) {
            flash()->error('تصویر متعلق به این محصول نیست');

            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $this->fileUpload->delete($this->othersUploadPath, $image->image);
            $image->delete();

            DB::commit();

            flash()->success(config('flasher.product.removed_images'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.product.remove_images_failed'));
        }

        return redirect()->route('admin.products.edit', $product->slug);
    }

    public function setToPrimary(Product $product, ProductImage $image): RedirectResponse
    {
        if ((int) $image->product_id !== (int) $product->id) {
            flash()->error('تصویر متعلق به این محصول نیست');

            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $oldPrimary = $product->primary_image;

            $product->update(['primary_image' => $image->image]);
            $image->delete();

            // تصویر اصلی قبلی به گالری برمی‌گردد (اگر خالی نباشد)
            if ($oldPrimary) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $oldPrimary,
                ]);
            }

            DB::commit();

            flash()->success(config('flasher.product.set_primary_image'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.product.set_primary_failed'));
        }

        return redirect()->route('admin.products.edit', $product->slug);
    }

    public function destroy(Product $product): RedirectResponse
    {
        // محصولی که در سفارش‌ها استفاده شده حذف نمی‌شود تا تاریخچه سفارش نشکند.
        if (OrderItem::where('product_id', $product->id)->exists()) {
            flash()->warning('این محصول در سفارش‌های ثبت‌شده استفاده شده و قابل حذف نیست. آن را غیرفعال کنید.');

            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $this->fileUpload->delete($this->primaryUploadPath, $product->primary_image);

            foreach ($product->images()->pluck('image') as $imageName) {
                $this->fileUpload->delete($this->othersUploadPath, $imageName);
            }

            $product->tags()->detach();
            $product->faqs()->delete();
            $product->filters()->delete();
            $product->images()->delete();
            $product->variations()->delete();
            $product->shortLink()->delete();
            $product->delete();

            DB::commit();

            flash()->success(config('flasher.product.deleted') ?? 'محصول با موفقیت حذف شد.');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.product.delete_failed') ?? 'حذف محصول با خطا مواجه شد.');
        }

        return redirect()->route('admin.products.index');
    }

    /**
     * داده‌های مشترک فرم ایجاد و ویرایش محصول.
     */
    private function formData(): array
    {
        return [
            'brands' => Cache::remember('product_brands', now()->addHour(), fn () => Brand::active()->pluck('title', 'id')),
            'platforms' => Cache::remember('product_platforms', now()->addHour(), fn () => Platform::active()->pluck('title', 'id')),
            'tags' => Cache::remember('product_tags', now()->addHour(), fn () => Tag::pluck('title', 'id')),
            'categories' => Cache::remember('product_categories', now()->addHour(), fn () => Category::active()->pluck('title', 'id')),
        ];
    }

    /**
     * شناسه ویژگی «مدل/تنوع» دسته‌بندی محصول.
     */
    private function variationAttributeId(Product $product): ?int
    {
        return $product->category?->variation()->value('attributes.id');
    }
}
