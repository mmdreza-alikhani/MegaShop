<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attribute\StoreAttributeRequest;
use App\Http\Requests\Admin\Attribute\UpdateAttributeRequest;
use App\Models\Attribute;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attributes-index', ['only' => ['index']]);
        $this->middleware('permission:attributes-create', ['only' => ['store']]);
        $this->middleware('permission:attributes-edit', ['only' => ['update']]);
        $this->middleware('permission:attributes-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Attribute::query();
        if ($request->input('q')) {
            $query->search('title', trim(request()->input('q')));
        }
        $attributes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.attributes.index', compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request): RedirectResponse
    {
        try {
            Attribute::create($request->validated());

            flash()->success('ویژگی با موفقیت ایجاد شد');
            return redirect()->route('admin.attributes.index');
        } catch (Exception $e) {
            report($e);
            flash()->error('مشکلی در ایجاد ویژگی پیش آمد');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute): RedirectResponse
    {
        try {
            $attribute->update($request->validated());

            flash()->success('ویژگی با موفقیت ویرایش شد');
            return redirect()->route('admin.attributes.index');
        } catch (Exception $e) {
            report($e);
            flash()->error('مشکلی در ویرایش ویژگی پیش آمد');
            return redirect()->back()
                ->withInput()
                ->with('attribute_id', $attribute->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute): RedirectResponse
    {
        try {
            if ($attribute->categories()->exists()) {
                flash()->warning('این ویژگی در دسته بندی ها استفاده شده و قابل حذف نیست');
                return redirect()->back();
            }

            $attribute->delete();

            flash()->success('ویژگی با موفقیت حذف شد');
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error('مشکلی در حذف ویژگی پیش آمد');
            return redirect()->back();
        }
    }
}
