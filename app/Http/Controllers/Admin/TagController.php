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
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tags-index', ['only' => ['index']]);
        $this->middleware('permission:tags-create', ['only' => ['store']]);
        $this->middleware('permission:tags-edit', ['only' => ['update']]);
        $this->middleware('permission:tags-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Tag::query();
        if ($request->input('q')) {
            $query->search('title', trim(request()->input('q')));
        }
        $tags = $query->latest()->paginate(15)->withQueryString();

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): RedirectResponse
    {
        try {
            Tag::create($request->validated());

            toastr()->success(config('flasher.tag.created'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.tag.create_failed'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        try {
            $tag->update($request->validated());

            flash()->success(config('flasher.tag.updated'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.tag.update_failed'));
            return redirect()->back()
                ->withInput()
                ->with('tag_id', $tag->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        try {
            if ($tag->categories()->exists()) {
                flash()->warning('این ویژگی در دسته بندی ها استفاده شده و قابل حذف نیست');
                return redirect()->back();
            }

            $tag->delete();

            flash()->success(config('flasher.tag.deleted'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->warning(config('flasher.tag.delete_failed'));
            return redirect()->back();
        }
    }
}
