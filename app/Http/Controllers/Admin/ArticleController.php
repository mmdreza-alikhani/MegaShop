<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Article\StoreArticleRequest;
use App\Http\Requests\Admin\Article\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Tag;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index' , compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        $tags = Tag::all();
        return view('admin.articles.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // check
            $articleImageController = new ArticleImageController();
            $imagesTitle = $articleImageController->upload($request->image);

            $article = Article::create([
                'title' => $request->input('title'),
                'is_active' => $request->is_active,
                'primary_image' => $imagesTitle['image'],
                'text' => $request->text,
                'user_id' => auth()->id()
            ]);

            $article->tags()->attach($request->tag_ids);

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
    public function show(Article $article): View|Application|Factory
    {
        return view('admin.articles.show' , compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article): View|Application|Factory
    {
        $tags = Tag::all();
        return view('admin.articles.edit' , compact('article', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if ($request->has('image')) {
                $articleImageController = new ArticleImageController();
                $imagesFileName = $articleImageController->upload($request->image);
            }

            $article->update([
                'title' => $request->title,
                'is_active' => $request->is_active,
                'primary_image' => $request->has('image') ? $imagesFileName['image'] : $article->primary_image,
                'text' => $request->text,
                'user_id' => auth()->id()
            ]);

            $article->tags()->sync($request->tag_ids);

            DB::commit();
        }catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->back();
        }

        toastr()->success('با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function search(): View|Application|Factory
    {
        $articles = Article::search('title', trim(request()->keyword))->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        toastr()->success('موفقیت حذف شد!');
        return redirect()->back();
    }
}
