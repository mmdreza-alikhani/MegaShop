<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\StoreTagRequest;
use App\Http\Requests\Admin\Tag\UpdateTagRequest;
use App\Models\Tag;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $tags = Tag::latest()->paginate(10);

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            Tag::create([
                'title' => $request->title,
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
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $tag) {
                $tag->update([
                    'title' => $request->title,
                ]);
            });

            toastr()->success('با موفقیت ویرایش شد!');
        } catch (Exception $ex) {
            toastr()->error($ex->getMessage().' مشکلی پیش آمد!');
        }

        return redirect()->back();
    }

    public function search(): View|\Illuminate\Contracts\Foundation\Application|Factory
    {
        $tags = Tag::search('title', trim(request()->keyword))->latest()->paginate(10);

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        toastr()->success('موفقیت حذف شد!');

        return redirect()->back();
    }
}
