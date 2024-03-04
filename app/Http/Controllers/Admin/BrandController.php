<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return view('admin.brands.index' , compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:20|unique:App\Models\Brand,name',
            'slug' => 'nullable|min:3|max:20|unique:App\Models\Brand,slug',
        ]);

        if (!empty($request->slug)){
            Brand::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'is_active' => $request->is_active,
            ]);
        }else{
            Brand::create([
                'name' => $request->name,
                'is_active' => $request->is_active,
            ]);
        }

        toastr()->success($request->name . '' . 'با موفقیت به برند ها اضافه شد');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return view('admin.brands.show' , compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit' , compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $id = $request->all()['id'];
        $request->validate([
            'name' => ['required','min:3','max:20',Rule::unique('brands')->ignore($id)],
            'slug' => ['max:20',Rule::unique('brands')->ignore($id)],
        ]);

        if (!empty($request->slug)){
            $brand->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'is_active' => $request->is_active,
            ]);
        }else{
            $brand->update([
                'name' => $request->name,
                'is_active' => $request->is_active,
            ]);
        }

        toastr()->success('با موفقیت برند ویرایش شد');
        return redirect()->route('admin.brands.index');
    }

    public function search(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != ''){
            $brands = Brand::where('name', 'LIKE', '%'.trim($keyWord).'%')->latest()->paginate(10);
            return view('admin.brands.index' , compact('brands'));
        }else{
            $brands = Brand::latest()->paginate(10);
            return view('admin.brands.index' , compact('brands'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Brand::destroy($request->brand);

        toastr()->success('برند مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }
}
