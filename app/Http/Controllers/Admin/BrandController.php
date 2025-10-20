<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brand\StoreBrandRequest;
use App\Http\Requests\Admin\Brand\UpdateBrandRequest;
use App\Models\Brand;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:brands-index', ['only' => ['index']]);
        $this->middleware('permission:brands-create', ['only' => ['store']]);
        $this->middleware('permission:brands-edit', ['only' => ['update']]);
        $this->middleware('permission:brands-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Brand::query();
        if ($request->input('q')) {
            $query->search('title', trim(request()->input('q')));
        }
        $brands = $query->latest()->paginate(15)->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request): RedirectResponse
    {
        try {
            Brand::create($request->validated());

            flash()->success(config('flasher.brand.created'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.brand.create_failed'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        try {
            $brand->update($request->validated());

            flash()->success(config('flasher.brand.updated'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.brand.update_failed'));
            return redirect()->back()
                ->withInput()
                ->with('brand_id', $brand->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): RedirectResponse
    {
        try {
            if ($brand->products()->exists()) {
                flash()->warning('این برند در محصولات استفاده شده و قابل حذف نیست');
                return redirect()->back();
            }

            $brand->delete();

            flash()->success(config('flasher.brand.deleted'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.brand.delete_failed'));
            return redirect()->back();
        }
    }
}
