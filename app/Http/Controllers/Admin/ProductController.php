<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Http\Requests\Admin\Product\UploadProductNewImagesRequest;
use App\Models\Brand;
use App\Models\Category;
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
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected mixed $primaryUploadPath, $othersUploadPath;
    public function __construct(private readonly FileUploadService $fileUpload, private readonly AttributeService $attributeService)
    {
        $this->primaryUploadPath = config('upload.product_primary_path');
        $this->othersUploadPath = config('upload.product_others_path');
        $this->middleware('permission:products-index', ['only' => ['index', 'show']]);
        $this->middleware('permission:products-create', ['only' => ['store', 'create']]);
        $this->middleware('permission:products-edit', ['only' => ['update', 'edit']]);
        $this->middleware('permission:products-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Product::query();
        if ($request->input('q')) {
            $query->search('title', trim(request()->input('q')));
        }
        $products = $query->latest()->with('shortLink')->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        $brands = Cache::remember('roles', now()->addHour(), function () {
            return Brand::active()->pluck('title', 'id');
        });
        $platforms = Cache::remember('platforms', now()->addHour(), function () {
            return Platform::active()->pluck('title', 'id');
        });
        $tags = Cache::remember('tags', now()->addHour(), function () {
            return Tag::pluck('title', 'id');
        });
        $categories = Cache::remember('active_categories', now()->addHour(), function () {
            return Category::active()->pluck('title', 'id');
        });

        return view('admin.products.create', compact('brands', 'tags', 'categories', 'platforms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $images = $this->fileUpload->uploadWithGallery($request->file('primary_image'), $request->other_images, $this->primaryUploadPath, $this->othersUploadPath);

            $product = Product::create([
                ...$request->validated(),
                'primary_image' => $images['primary'],
            ]);

            foreach ($images['gallery'] as $imageName) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imageName,
                ]);
            }

            ShortLink::create([
                'code' => $request->has('code') ? $request->string('code') : generateUniqueShortCode(),
                'type' => 'product',
                'target_id' => $product->id,
            ]);

            if ($request->has('faqs')) {
                foreach ($request->faqs as $faqData) {
                    $product->faqs()->create([
                        'question' => $faqData['question'],
                        'answer' => $faqData['answer'],
                    ]);
                }
            }

            $product->tags()->attach($request->input('tag_ids'));

            // Handle filters
            if ($request->has('filters_value')) {
                $this->attributeService->syncFilters(
                    $product->id,
                    $request->filters_value
                );
            }

            // Handle variations
            if ($request->has('variation_values')) {
                $this->attributeService->syncVariations(
                    $product->id,
                    $product->category->variation->first()->id,
                    $request->variation_values
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error(config('flasher.product.create_failed'));
            report($e);
            return redirect()->back();
        }

        flash()->success(config('flasher.product.created'));
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Factory|Application|View
    {
        $product->load('shortLink', 'tags', 'faqs');
        $filters = $product->filters()
            ->with('attribute')
            ->get()
            ->map(function ($attr) {
                return [
                    'value' => $attr->value,
                    'title' => $attr->attribute->title,
                ];
            });
        $variations = $product->variations()
            ->with('attribute')
            ->get()
            ->map(function ($attr) {
                return [
                    'title' => $attr->attribute->title,
                    'value' => $attr->value,
                    'price' => $attr->price,
                    'quantity' => $attr->quantity,
                    'sku' => $attr->sku,
                    'sale_price' => $attr->sale_price,
                    'date_on_sale_from' => $attr->date_on_sale_from,
                    'date_on_sale_to' => $attr->date_on_sale_to,
                ];
            });

        return view('admin.products.show', compact('product', 'filters', 'variations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): Factory|View
    {
        $brands = Cache::remember('roles', now()->addHour(), function () {
            return Brand::active()->pluck('title', 'id');
        });
        $platforms = Cache::remember('platforms', now()->addHour(), function () {
            return Platform::active()->pluck('title', 'id');
        });
        $tags = Cache::remember('tags', now()->addHour(), function () {
            return Tag::pluck('title', 'id');
        });
        $categories = Cache::remember('active_categories', now()->addHour(), function () {
            return Category::active()->pluck('title', 'id');
        });
        $filters = $product->filters()
            ->with('attribute')
            ->get()
            ->map(function ($attr) {
                return [
                    'id'    => $attr->id,
                    'value' => $attr->value,
                    'title' => $attr->attribute->title,
                ];
            });
        $variations = $product->variations()
            ->with('attribute')
            ->get()
            ->map(function ($attr) {
                return [
                    'id' => $attr->id,
                    'title' => $attr->attribute->title,
                    'value' => $attr->value,
                    'price' => $attr->price,
                    'quantity' => $attr->quantity,
                    'sku' => $attr->sku,
                    'sale_price' => $attr->sale_price,
                    'date_on_sale_from' => $attr->date_on_sale_from,
                    'date_on_sale_to' => $attr->date_on_sale_to,
                ];
            });

        return view('admin.products.edit', compact('product', 'brands', 'tags', 'categories', 'filters', 'variations', 'platforms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $product->update([
                ...$request->validated(),
            ]);

            $existingFaqIds = $product->faqs()->pluck('id')->toArray();
            $requestFaqIds = array_keys($request->faqs);
            $removedFaqs = array_diff($existingFaqIds, $requestFaqIds);

            foreach ($request->faqs as $key => $faqData) {
                $faq = $product->faqs()->find($key);
                $faq->update([
                    'question' => $faqData['question'],
                    'answer' => $faqData['answer'],
                ]);
            }
            if (!empty($removedFaqs)) {
                $product->faqs()->whereIn('id', $removedFaqs)->delete();
            }

            if ($request->has('newFaqs')) {
                foreach ($request->newFaqs as $faqData) {
                    $product->faqs()->create([
                        'question' => $faqData['question'],
                        'answer' => $faqData['answer'],
                    ]);
                }
            }

            $product->tags()->sync($request->tag_ids);

            $this->attributeService->updateFilters($request->filters_value, $product, $product->wasChanged('category_id'));
            $this->attributeService->handleVariationsUpdate($product->id, $product->category->variation->first()->id, $request->variation_values, $request->new_variation_values);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error(config('flasher.product.update_failed'));
            report($e);
            return redirect()->back();
        }

        flash()->success(config('flasher.product.updated'));
        return redirect()->route('admin.products.edit', $product->slug);
    }

    public function addImages(UploadProductNewImagesRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $images = $this->fileUpload->uploadToGallery($request->images, $this->othersUploadPath);

            foreach ($images as $imageName) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imageName,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error(config('flasher.product.added_images'));
            report($e);
            return redirect()->back();
        }

        flash()->success(config('flasher.product.add_images_failed'));
        return redirect()->back();
    }

    public function removeImages(UploadProductNewImagesRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $this->fileUpload->deleteMultiple($request->images, $this->othersUploadPath);

            foreach ($request->images as $imageName) {
                ProductImage::where('product_id', $product->id)->where('image', $imageName)->delete();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error(config('flasher.product.removed_images'));
            report($e);
            return redirect()->back();
        }

        flash()->success(config('flasher.product.remove_images_failed'));
        return redirect()->back();
    }

    public function setToPrimary(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id',
        ]);
        try {
            DB::beginTransaction();

            $image = ProductImage::find($request->image_id);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $product->primary_image,
            ]);

            $product->update([
                'primary_image' => $image->image,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error(config('flasher.product.removed_images'));
            report($e);
            return redirect()->back();
        }

        flash()->success(config('flasher.product.remove_images_failed'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function edit_category(Request $request, Product $product)
    {
        $categories = Category::where([['is_active', 1], ['parent_id', '!=', '0']])->get();

        return view('admin.products.edit_category', compact('product', 'categories'));
    }

    public function update_category(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required',
            'attribute_ids' => 'required',
            'attribute_ids.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.*' => 'required',
            'variation_values.quantity.*' => 'integer',
            'variation_values.price.*' => 'integer',
            'variation_values.sku.*' => 'integer',
        ]);
        try {
            DB::beginTransaction();

            $product->update([
                'category_id' => $request->category_id,
            ]);

            $ProductAttributeController = new ProductAttributeController;
            $ProductAttributeController->change($request->attribute_ids, $product->id);

            $category = Category::find($request->category_id);
            $ProductVariationController = new ProductVariationController;
            $ProductVariationController->change($request->variation_values, $category->attributes()->wherePivot('is_variation', 1)->first()->id, $product);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            flash()->error('مشکلی پیش آمد!', $ex->getMessage());

            return redirect()->back();
        }

        flash()->success('با موفقیت دسته بندی محصول ویرایش شد.');

        return redirect()->back();
    }
}
