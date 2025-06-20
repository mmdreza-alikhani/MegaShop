<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attribute\StoreAttributeRequest;
use App\Http\Requests\Admin\Attribute\UpdateAttributeRequest;
use App\Models\Attribute;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $attributes = Attribute::latest()->paginate(10);
        return view('admin.attributes.index' , compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request): RedirectResponse
    {
        Attribute::create([
            'title' => $request->input('title')
        ]);

        toastr()->success('با موفقیت اضافه شد!');
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute): RedirectResponse
    {
        $attribute->update([
            'title' => $request->input('title'),
        ]);

        toastr()->success('با موفقیت ویرایش شد!');
        return redirect()->back();
    }

    public function search(): View|\Illuminate\Contracts\Foundation\Application|Factory
    {
        $attributes = Attribute::search('title', trim(request()->keyword))->latest()->paginate(10);
        return view('admin.attributes.index', compact('attributes'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute): RedirectResponse
    {
        $attribute->delete();

        toastr()->success('موفقیت حذف شد!');
        return redirect()->back();
    }
}
