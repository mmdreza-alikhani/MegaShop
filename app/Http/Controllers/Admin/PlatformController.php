<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Platform\StorePlatformRequest;
use App\Http\Requests\Admin\Platform\UpdatePlatformRequest;
use App\Models\Platform;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $platforms = Platform::latest()->paginate(10);
        return view('admin.platforms.index' , compact('platforms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlatformRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $imageFileName = null;

            if ($request->image){
                $imageFileName = generateFileName($request->image->getClientOriginalName());
                $request->image->move(public_path(env('CATEGORY_IMAGE_PATH')) , $imageFileName);
            }

            Platform::create([
                'title' => $request->input('title'),
                'is_active' => $request->input('is_active'),
                'image' => $imageFileName
            ]);

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
    public function update(UpdatePlatformRequest $request, Platform $platform): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if ($request->image){
                $imageFileName = generateFileName($request->image->getClientOriginalName());
                $request->image->move(public_path(env('CATEGORY_IMAGE_PATH')) , $imageFileName);
            }

            $platform->update([
                'name' => $request->name,
                'is_active' => $request->is_active,
                'image' => $request->image ? $imageFileName : $platform->image
            ]);

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
    public function destroy(Platform $platform): RedirectResponse
    {
        $platform->delete();

        toastr()->success('با موفقیت حذف شد!');
        return redirect()->back();
    }
}
