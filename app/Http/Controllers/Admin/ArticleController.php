<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index' , compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        return view('admin.articles.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'text' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,svg'
        ]);

        try {
            DB::beginTransaction();

            $articleImageController = new ArticleImageController();
            $imgsFileName = $articleImageController->upload($request->image);

            $article = Article::create([
                'title' => $request->title,
                'is_active' => $request->is_active,
                'primary_image' => $imgsFileName['image'],
                'text' => $request->text,
                'user_id' => auth()->id()
            ]);

            $article->tags()->attach($request->tag_ids);

            DB::commit();
        }catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->route('admin.articles.create');
        }

        toastr()->success('با موفقیت مقاله اضافه شد.');
        return redirect()->route('admin.articles.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('admin.articles.show' , compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $tags = Tag::all();
        return view('admin.articles.edit' , compact('article', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'text' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,svg'
        ]);

        try {
            DB::beginTransaction();

            if ($request->has('image')) {
                $articleImageController = new ArticleImageController();
                $imgsFileName = $articleImageController->upload($request->image);
            }

            $article->update([
                'title' => $request->title,
                'is_active' => $request->is_active,
                'primary_image' => $request->image ? $imgsFileName['image'] : $article->primary_image,
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

        toastr()->success('با موفقیت مقاله ویرایش شد.');
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != ''){
            $articles = Article::where('title', 'LIKE', '%'.trim($keyWord).'%')->latest()->paginate(10);
            return view('admin.articles.index' , compact('articles'));
        }else{
            $articles = Article::latest()->paginate(10);
            return view('admin.articles.index' , compact('articles'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
//        Article::destroy($request->article);
//
//        toastr()->success('مقاله مورد نظر با موفقیت حذف شد!');
//        return redirect()->back();
    }
}
