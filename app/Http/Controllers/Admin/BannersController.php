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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:banners-index', ['only' => ['show', 'index']]);
        $this->middleware('permission:banners-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:banners-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:banners-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Banner::query();
        if ($request->input('q')) {
            $query->search('title', trim(request()->input('q')));
        }
        $banners = $query->orderBy('priority')->latest()->paginate(15)->withQueryString();

        return view('admin.attributes.index', compact('banners'));
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

            $imageName = $this->uploadImage($request->file('image'));
//            $imageName = generateFileName($request->image->getClientOriginalName());
//            $request->file('image')->storeAs(env('BANNER_IMAGE_UPLOAD_PATH'), $imageName, 'public');

            Banner::create([
                ...$request->validated(),
                'image' => $imageName,
            ]);

            DB::commit();
            flash()->success('بنر با موفقیت ایجاد شد');
            return redirect()->route('admin.banners.index');
        } catch (Exception $ex) {
            DB::rollBack();
            report($ex); // ✅ Log error

            flash()->error('مشکلی در ایجاد بنر پیش آمد');
            return redirect()->back()->withInput();
        }
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

            $data = $request->validated();
            if ($request->has('image')) {
                $this->deleteImage($banner->image);
                $data['image'] = $this->uploadImage($request->file('image'));
//                $imageName = generateFileName($request->image->getClientOriginalName());
//                $request->file('image')->storeAs(env('BANNER_IMAGE_UPLOAD_PATH'), $imageName, 'public');
//                $banner->update([
//                    'image' => $imageName,
//                ]);
            }
            $data['is_active'] = $request->has('is_active');
            $banner->update($data);

            DB::commit();

            flash()->success('بنر با موفقیت ویرایش شد');
            return redirect()->route('admin.banners.index');
        } catch (Exception $ex) {
            DB::rollBack();
            report($ex);

            flash()->error('مشکلی در ویرایش بنر پیش آمد');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner): RedirectResponse
    {
        try {
            // ✅ حذف تصویر
            $this->deleteImage($banner->image);

            $banner->delete();

            flash()->success('بنر با موفقیت حذف شد');
            return redirect()->back();
        } catch (Exception $ex) {
            report($ex);
            flash()->error('مشکلی در حذف بنر پیش آمد');
            return redirect()->back();
        }
    }

//    private function uploadImage($file): string
//    {
//        $imageName = generateFileName($file->getClientOriginalName());
//        $file->storeAs(config('app.banner_image_path', 'banners'), $imageName, 'public');
//
//        return $imageName;
//    }
//
//    /**
//     * حذف تصویر
//     */
//    private function deleteImage(string $imageName): void
//    {
//        $path = config('app.banner_image_path', 'banners') . '/' . $imageName;
//
//        if (Storage::disk('public')->exists($path)) {
//            Storage::disk('public')->delete($path);
//        }
//    }
}
