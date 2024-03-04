<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('admin.news.index' , compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        return view('admin.news.create', compact('tags'));
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

            $newsImageController = new NewsImageController();
            $imgsFileName = $newsImageController->upload($request->image);

            $article = News::create([
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
            return redirect()->route('admin.news.create');
        }

        toastr()->success('با موفقیت مقاله اضافه شد.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return view('admin.news.show' , compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        $tags = Tag::all();
        return view('admin.news.edit' , compact('news', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
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
                $newsImageController = new NewsImageController();
                $imgsFileName = $newsImageController->upload($request->image);
            }

            $news->update([
                'title' => $request->title,
                'is_active' => $request->is_active,
                'primary_image' => $request->image ? $imgsFileName['image'] : $news->primary_image,
                'text' => $request->text,
                'user_id' => auth()->id()
            ]);

            $news->tags()->sync($request->tag_ids);

            DB::commit();
        }catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->back();
        }

        toastr()->success('با موفقیت خبر ویرایش شد.');
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != ''){
            $news = News::where('title', 'LIKE', '%'.trim($keyWord).'%')->latest()->paginate(10);
            return view('admin.news.index' , compact('news'));
        }else{
            $news = News::latest()->paginate(10);
            return view('admin.news.index' , compact('news'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
//        News::destroy($request->news);
//
//        toastr()->success('خبر مورد نظر با موفقیت حذف شد!');
//        return redirect()->back();
    }
}
