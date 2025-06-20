<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brand\StoreBrandRequest;
use App\Http\Requests\Admin\Brand\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $brands = Brand::latest()->paginate(10);
        return view('admin.brands.index' , compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request): RedirectResponse
    {
        Brand::create([
            'title' => $request->input('title'),
            'is_active' => $request->input('is_active'),
        ]);

        toastr()->success('با موفقیت اضافه شد!');
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        $brand->update([
            'name' => $request->input('name'),
            'is_active' => $request->input('is_active'),
        ]);

        toastr()->success('با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function search(): View|Application|Factory
    {
        $brands = Brand::search('title', trim(request()->keyword))->latest()->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        toastr()->success('با موفقیت حذف شد!');
        return redirect()->back();
    }
}
