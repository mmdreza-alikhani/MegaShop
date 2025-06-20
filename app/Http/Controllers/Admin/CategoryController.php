<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\Attribute;
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
        $categories = Category::latest()->paginate(10);
        $parentCategories = Category::where('parent_id', '=', 0)->get();
        $attributes = Attribute::all();
        return view('admin.categories.index' , compact('categories', 'parentCategories', 'attributes'));
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

            foreach ($request->input('attribute_ids') as $attribute_id){
                $attribute = Attribute::findOrFail($attribute_id);
                $attribute->categories()->attach($category->id , [
                    'is_filter' => in_array($attribute_id , $request->attribute_is_filter_ids) ? 1 : 0,
                    'is_variation' => $request->attribute_is_variation_id == $attribute_id ? 1 : 0
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
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'parent_id' => $request->parent_id,
                'is_active' => $request->is_active,
                'description' => $request->description,
                'icon' => $request->icon,
            ]);

            $category->attributes()->detach();

            foreach ($request->attribute_ids as $attribute_id){
                $attribute = Attribute::findOrFail($attribute_id);
                $attribute->categories()->attach($category->id , [
                    'is_filter' => in_array($attribute_id , $request->attribute_is_filter_ids) ? 1 : 0,
                    'is_variation' => $request->attribute_is_variation_id == $attribute_id ? 1 : 0
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
        $attributes = $category->attributes()->wherePivot('is_variation', 0)->get();
        $variation = $category->attributes()->wherePivot('is_variation', 1)->get();
        return ['attributes' => $attributes , 'variation' => $variation];
    }
}
