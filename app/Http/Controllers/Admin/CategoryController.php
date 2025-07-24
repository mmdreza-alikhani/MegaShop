<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use App\Models\Category;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $categories = Category::latest()->with('parent', 'filters', 'variation')->paginate(10);
        return view('admin.categories.index' , compact('categories'));
    }

    public function create(): View|Application|Factory
    {
        $parentCategories = Category::parents()->pluck('title', 'id');
        $attributes = Attribute::pluck('title', 'id');

        return view('admin.categories.create' , compact('parentCategories' , 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $category = Category::create([
                'title' => $request->input('title'),
                'parent_id' => $request->input('parent_id'),
                'is_active' => $request->input('is_active'),
                'description' => $request->input('description'),
                'icon' => $request->input('icon'),
            ]);

            AttributeCategory::create([
                'category_id' => $category->id,
                'attribute_id' => $request->input('variation_attribute_id'),
                'type' => 'variation'
            ]);

            foreach ($request->input('filter_attribute_ids') as $attribute_id){
                AttributeCategory::create([
                    'category_id' => $category->id,
                    'attribute_id' => $attribute_id,
                    'type' => 'filter'
                ]);
            }

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            toastr()->error($ex->getMessage() . 'مشکلی پیش آمد!');
            return redirect()->back();
        }

        toastr()->success('با موفقیت اضافه شد!');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View|Application|Factory
    {
        $relatedAttributes = $this->getCategoryAttribute($category);
        $parentCategories = Category::parents()->pluck('title', 'id');
        $attributes = Attribute::pluck('title', 'id');
        return view('admin.categories.edit' , compact('category', 'parentCategories', 'attributes', 'relatedAttributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $category->update([
                'title' => $request->input('title'),
                'parent_id' => $request->input('parent_id'),
                'is_active' => $request->input('is_active'),
                'description' => $request->input('description'),
                'icon' => $request->input('icon'),
            ]);

            $category->attributes()->detach();

            AttributeCategory::create([
                'category_id' => $category->id,
                'attribute_id' => $request->input('variation_attribute_id'),
                'type' => 'variation'
            ]);

            foreach ($request->input('filter_attribute_ids') as $attribute_id){
                AttributeCategory::create([
                    'category_id' => $category->id,
                    'attribute_id' => $attribute_id,
                    'type' => 'filter'
                ]);
            }

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->back();
        }

        toastr()->success('با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        toastr()->success('با موفقیت حذف شد!');
        return redirect()->back();
    }

    public function getCategoryAttribute(Category $category): array
    {
        $filters = $category->attributes()->filter()->pluck('title', 'id');
        $variation = $category->attributes()->variation()->take(1)->pluck('title', 'id');
        return ['filters' => $filters , 'variation' => $variation];
    }

    public function search(): View|Application|Factory
    {
        $categories = Category::search('title', trim(request()->keyword))->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }
}
