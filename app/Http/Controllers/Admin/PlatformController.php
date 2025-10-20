<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Platform\StorePlatformRequest;
use App\Http\Requests\Admin\Platform\UpdatePlatformRequest;
use App\Models\Platform;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlatformController extends Controller
{
    protected mixed $uploadPath;
    public function __construct(private readonly FileUploadService $fileUpload)
    {
        $this->uploadPath = config('upload.platform_path');
        $this->middleware('permission:platforms-index', ['only' => ['index']]);
        $this->middleware('permission:platforms-create', ['only' => ['store']]);
        $this->middleware('permission:platforms-edit', ['only' => ['update']]);
        $this->middleware('permission:platforms-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Platform::query();
        if ($request->input('q')) {
            $query->search('title', trim(request()->input('q')));
        }
        $platforms = $query->latest()->paginate(15)->withQueryString();

        return view('admin.platforms.index', compact('platforms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlatformRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = $this->fileUpload->upload($request->file('image'), $this->uploadPath);
            }

            Platform::create([
                ...$request->validated(),
                'image' => $imageName
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.platform.create_failed'));
            return redirect()->back()->withInput();
        }

        flash()->success(config('flasher.platform.created'));
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlatformRequest $request, Platform $platform): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $imageName = $this->fileUpload->replace($request->file('image'), $platform->image, $this->uploadPath);
                $platform->update([
                    'image' => $imageName,
                ]);
            }

            $platform->update($request->validated());

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.platform.update_failed'));
            return redirect()->back()->withInput();
        }

        flash()->success(config('flasher.platform.updated'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Platform $platform): RedirectResponse
    {
        if ($platform->products()->count() > 0) {
            flash()->success('پلتفرم قابل حذف نیسش');
            return redirect()->back();
        }else{
            try {
                $this->fileUpload->delete(
                    $this->uploadPath,
                    $platform->image
                );
                $platform->delete();

                flash()->success(config('flasher.platform.deleted'));
                return redirect()->back();
            } catch (Exception $ex) {
                report($ex);
                flash()->error(config('flasher.platform.delete_failed'));
                return redirect()->back();
            }
        }
    }
}
