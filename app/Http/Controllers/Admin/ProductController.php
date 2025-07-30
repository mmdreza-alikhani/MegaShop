<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tag;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|Application|View
    {
        $products = Product::latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        $brands = Brand::active()->pluck('title', 'id');
        $platforms = Platform::active()->pluck('title', 'id');
        $tags = Tag::pluck('title', 'id');
        $categories = Category::active()->parents()->pluck('title', 'id');

        return view('admin.products.create', compact('brands', 'tags', 'categories', 'platforms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $productImageController = new ProductImageController;
            $imagesFileName = $productImageController->upload($request->primary_image, $request->other_images);

            $product = Product::create([
                'title' => $request->input('title'),
                'is_active' => $request->input('is_active'),
                'brand_id' => $request->input('brand_id'),
                'platform_id' => $request->input('platform_id'),
                'description' => $request->input('description'),
                'delivery_amount' => $request->input('delivery_amount'),
                'delivery_amount_per_product' => $request->input('delivery_amount_per_product'),
                'category_id' => $request->input('category_id'),
                'primary_image' => $imagesFileName['primaryImage'],
            ]);

            foreach ($imagesFileName['otherImages'] as $imgFileName) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imgFileName,
                ]);
            }

            // Store Filters
            $ProductAttributeController = new ProductAttributeController;
            $ProductAttributeController->store($request->input('filters_value'), $product->id);

            // Store Variation
            $attributeId = Category::findOrFail($request->input('category_id'))->variation()->pluck('id')->first();
            $ProductVariationController = new ProductVariationController;
            $ProductVariationController->store($request->input('variation_values'), $attributeId, $product->id);

            $product->tags()->attach($request->input('tag_ids'));

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!', $ex->getMessage());

            return redirect()->route('admin.products.create');
        }

        toastr()->success('با موفقیت محصول اضافه شد.');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Factory|Application|View
    {
        $filters = $product->attributes()
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
    public function edit(Product $product)
    {
        $brands = Brand::active()->pluck('title', 'id');
        $platforms = Platform::active()->pluck('title', 'id');
        $tags = Tag::pluck('title', 'id');
        $categories = Category::active()->parents()->pluck('title', 'id');
        $productAttributes = $product->attributes()->with('attribute')->get();
        $productVariations = $product->variations;

        return view('admin.products.edit', compact('product', 'brands', 'tags', 'categories', 'productVariations', 'platforms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //        dd($request->all());
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required',
            'platform_id' => 'nullable',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'description' => 'required',
            'delivery_amount' => 'required|integer',
            'delivery_amount_per_product' => 'nullable|integer',
            'attribute_values' => 'required',
            'attribute_values.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.price' => 'required|integer',
            'variation_values.*.quantity' => 'required|integer',
            'variation_values.*.sku' => 'required|integer',
            'variation_values.*.sale_price' => 'nullable|integer',
            'variation_values.*.date_on_sale_from' => 'nullable|date',
            'variation_values.*.date_on_sale_to' => 'nullable|date',
        ]);
        try {
            DB::beginTransaction();

            $product->update([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'platform_id' => $request->platform_id,
                'is_active' => $request->is_active,
                'description' => $request->description,
                'delivery_amount' => $request->delivery_amount,
                'delivery_amount_per_product' => $request->delivery_amount_per_product,
            ]);

            $product->tags()->sync($request->tag_ids);

            $ProductAttributeController = new ProductAttributeController;
            $ProductAttributeController->update($request->attribute_values);

            $ProductVariationController = new ProductVariationController;
            $ProductVariationController->update($request->variation_values);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!', $ex->getMessage());

            return redirect()->back();
        }

        toastr()->success('با موفقیت محصول ویرایش شد.');

        return redirect()->back();
    }

    public function search(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != '') {
            $products = Product::where('name', 'LIKE', '%'.trim($keyWord).'%')->latest()->paginate(10);

            return view('admin.products.index', compact('products'));
        } else {
            $products = Product::latest()->paginate(10);

            return view('admin.products.index', compact('products'));
        }
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
            toastr()->error('مشکلی پیش آمد!', $ex->getMessage());

            return redirect()->back();
        }

        toastr()->success('با موفقیت دسته بندی محصول ویرایش شد.');

        return redirect()->back();
    }
}
