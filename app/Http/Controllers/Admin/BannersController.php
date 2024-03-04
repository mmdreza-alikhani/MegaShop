<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::latest()->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:20',
            'text' => 'required',
            'is_active' => 'required',
            'type' => 'required',
            'button_text' => 'required',
            'button_link' => 'required',
            'button_icon' => 'required',
            'priority' => 'required|integer',
            'image' => 'required|mimes:jpg,jpeg,png,svg'
        ]);

        $imageName = generateFileName($request->image->getClientOriginalName());

        $request->image->move(public_path(env('BANNER_IMAGES_UPLOAD_PATH')), $imageName);

        Banner::create([
            'title' => $request->title,
            'text' => $request->text,
            'is_active' => $request->is_active,
            'type' => $request->type,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'button_icon' => $request->button_icon,
            'priority' => $request->priority,
            'image' => $imageName,
        ]);

        toastr()->success($request->title . ' ' . 'با موفقیت به بنر ها اضافه شد');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return view('admin.banners.show' , compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit' , compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|min:3|max:20',
            'text' => 'required',
            'is_active' => 'required',
            'type' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'button_text' => 'required',
            'button_link' => 'required',
            'button_icon' => 'required',
            'priority' => 'required|integer'
        ]);

        if ($request->has('image')) {
            $imageName = generateFileName($request->image->getClientOriginalName());
            $request->image->move(public_path(env('BANNER_IMAGES_UPLOAD_PATH')), $imageName);
        }

        $banner->update([
            'title' => $request->title,
            'text' => $request->text,
            'is_active' => $request->is_active,
            'type' => $request->type,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'button_icon' => $request->button_icon,
            'priority' => $request->priority,
            'image' => $request->image ? $imageName : $banner->image,
        ]);

        toastr()->success('بنر موردنظر با موفقیت ویرایش شد');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Banner::destroy($request->banner);

        toastr()->success('بنر مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }
}
