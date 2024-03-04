<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $platforms = Platform::latest()->paginate(10);
        return view('admin.platforms.index' , compact('platforms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.platforms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:20|unique:App\Models\Brand,name',
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);

        try {
            DB::beginTransaction();

            $imageFileName = null;

            if ($request->image){
                $imageFileName = generateFileName($request->image->getClientOriginalName());
                $request->image->move(public_path(env('CATEGORY_IMAGE_PATH')) , $imageFileName);
            }

            Platform::create([
                'name' => $request->name,
                'is_active' => $request->is_active,
                'image' => $imageFileName
            ]);

            toastr()->success($request->name . '' . ' با موفقیت به پلتفرم ها اضافه شد');
            return redirect()->route('admin.platforms.create');

            DB::commit();
        }catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->route('admin.products.create');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Platform $platform)
    {
        return view('admin.platforms.show' , compact('platform'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Platform $platform)
    {
        return view('admin.platforms.edit' , compact('platform'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Platform $platform)
    {
        $id = $request->all()['id'];
        $request->validate([
            'name' => ['required','min:3','max:20',Rule::unique('platforms')->ignore($id)],
            'image' => 'nullable|mimes:jpg,jpeg,png,svg'
        ]);

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
        }catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->back();
        }

            toastr()->success('با موفقیت پلتفرم ویرایش شد');
            return redirect()->route('admin.platforms.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Platform::destroy($request->platform);

        toastr()->success('پلتفرم مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }
}
