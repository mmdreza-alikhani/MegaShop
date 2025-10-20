<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;
use App\Models\Banner;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannersController extends Controller
{
    protected mixed $uploadPath;
    public function __construct(private readonly FileUploadService $fileUpload)
    {
        $this->uploadPath = config('upload.banner_path');
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

            $imageName = $this->fileUpload->upload($request->file('image'), $this->uploadPath);

            Banner::create([
                ...$request->validated(),
                'image' => $imageName,
            ]);

            DB::commit();
            flash()->success(config('flasher.banner.created'));
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.banner.create_failed'));
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
            if ($request->hasFile('image')) {
                $imageName = $this->fileUpload->replace($request->file('image'), $banner->image, $this->uploadPath);
                $data['image'] = $imageName;
            }
            $data['is_active'] = $request->has('is_active');
            $banner->update($data);

            DB::commit();
            flash()->success(config('flasher.banner.updated'));
            return redirect()->back();
        } catch (Exception $ex) {
            DB::rollBack();
            report($ex);
            flash()->error(config('flasher.banner.update_failed'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner): RedirectResponse
    {
        try {
            $this->fileUpload->delete(
                $this->uploadPath,
                $banner->image
            );
            $banner->delete();

            flash()->success(config('flasher.banner.deleted'));
            return redirect()->back();
        } catch (Exception $ex) {
            report($ex);
            flash()->error(config('flasher.banner.delete_failed'));
            return redirect()->back();
        }
    }
}
