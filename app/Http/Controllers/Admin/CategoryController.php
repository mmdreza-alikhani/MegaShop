<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::where('parent_id' , 0)->get();
        $attributes = Attribute::all();

        return view('admin.categories.create' , compact('parentCategories' , 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:20',
            'parent_id' => 'required',
            'is_active' => 'required',
            'attribute_ids' => 'required',
            'attributes_ids.*' => 'required',
            'attribute_is_variation_id' => 'required',
            'attribute_is_filter_ids' => 'required',
            'attribute_is_filter_ids.*' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $category = Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'parent_id' => $request->parent_id,
                'is_active' => $request->is_active,
                'description' => $request->description,
                'icon' => $request->icon,
            ]);

            foreach ($request->attribute_ids as $attribute_id){
                $attribute = Attribute::findOrFail($attribute_id);
                $attribute->categories()->attach($category->id , [
                    'is_filter' => in_array($attribute_id , $request->attribute_is_filter_ids) ? 1 : 0,
                    'is_variation' => $request->attribute_is_variation_id == $attribute_id ? 1 : 0
                ]);
            }

            DB::commit();

        }catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->route('admin.categories.create');
        }

        toastr()->success('با موفقیت به دسته بندی ها اضافه شد');
        return redirect()->route('admin.categories.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show' , compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::where('parent_id' , 0)->get();
        $attributes = Attribute::all();
        return view('admin.categories.edit' , compact('category', 'parentCategories', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'parent_id' => 'required',
            'is_active' => 'required',
            'attribute_ids' => 'required',
            'attributes_ids.*' => 'required',
            'attribute_is_variation_id' => 'required',
            'attribute_is_filter_ids' => 'required',
            'attribute_is_filter_ids.*' => 'required'
        ]);

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

        }catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->route('admin.categories.create');
        }

        toastr()->success('با موفقیت دسته بندی ویرایش شد.');
        return redirect()->route('admin.categories.create');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Category::destroy($request->category);

        toastr()->success('دسته بندی مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }

    public function getCategoryAttribute(Category $category)
    {
        $attributes = $category->attributes()->wherePivot('is_variation', 0)->get();
        $variation = $category->attributes()->wherePivot('is_variation', 1)->get();
        return ['attributes' => $attributes , 'variation' => $variation];
    }
}
