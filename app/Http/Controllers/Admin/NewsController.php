<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\StoreNewsRequest;
use App\Http\Requests\Admin\News\UpdateNewsRequest;
use App\Models\News;
use App\Models\Tag;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $news = News::latest()->paginate(10);
        return view('admin.news.index' , compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        $tags = Tag::all();
        return view('admin.news.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // check
            $newsImageController = new NewsImageController();
            $imagesTitle = $newsImageController->upload($request->image);

            $news = News::create([
                'title' => $request->input('title'),
                'is_active' => $request->is_active,
                'primary_image' => $imagesTitle['image'],
                'text' => $request->text,
                'user_id' => auth()->id()
            ]);

            $news->tags()->attach($request->tag_ids);

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
     * Display the specified resource.
     */
    public function show(News $news): View|Application|Factory
    {
        return view('admin.news.show' , compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news): View|Application|Factory
    {
        $tags = Tag::all();
        return view('admin.news.edit' , compact('news', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, News $news): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if ($request->has('image')) {
                $newsImageController = new NewsImageController();
                $imagesFileName = $newsImageController->upload($request->image);
            }

            $news->update([
                'title' => $request->title,
                'is_active' => $request->is_active,
                'primary_image' => $request->has('image') ? $imagesFileName['image'] : $news->primary_image,
                'text' => $request->text,
                'user_id' => auth()->id()
            ]);

            $news->tags()->sync($request->tag_ids);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->back();
        }

        toastr()->success('با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function search(): View|Application|Factory
    {
        $news = News::search('title', trim(request()->keyword))->latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news): RedirectResponse
    {
        $news->delete();

        toastr()->success('موفقیت حذف شد!');
        return redirect()->back();
    }
}
