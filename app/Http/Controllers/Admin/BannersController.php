<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;
use App\Models\Banner;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class BannersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $banners = Banner::latest()->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $imageName = generateFileName($request->image->getClientOriginalName());
            $request->file('image')->storeAs(env('BANNER_IMAGE_UPLOAD_PATH'), $imageName, 'public');

            Banner::create([
                'title' => $request->input('title'),
                'text' => $request->input('text'),
                'is_active' => $request->input('is_active'),
                'type' => $request->input('type'),
                'button_text' => $request->input('button_text'),
                'button_link' => $request->input('button_link'),
                'priority' => $request->input('priority'),
                'image' => $imageName,
            ]);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            toastr()->error($ex->getMessage().'مشکلی پیش آمد!');

            return redirect()->back();
        }

        toastr()->success('با موفقیت اضافه شد!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner): View|Application|Factory
    {
        return view('admin.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner): View|Application|Factory
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, Banner $banner): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if ($request->has('image')) {
                $imageName = generateFileName($request->image->getClientOriginalName());
                $request->file('image')->storeAs(env('BANNER_IMAGE_UPLOAD_PATH'), $imageName, 'public');
                $banner->update([
                    'image' => $imageName,
                ]);
            }

            $banner->update([
                'title' => $request->title,
                'text' => $request->text,
                'is_active' => $request->is_active,
                'type' => $request->type,
                'button_text' => $request->button_text,
                'button_link' => $request->button_link,
                'priority' => $request->priority,
            ]);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!', (array)$ex->getMessage());

            return redirect()->back();
        }

        toastr()->success('با موفقیت مقاله ویرایش شد.');

        return redirect()->back();
    }

    public function search(): View|Application|Factory
    {
        $banners = Banner::search('title', trim(request()->keyword))->latest()->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        toastr()->success('با موفقیت حذف شد!');

        return redirect()->back();
    }
}
