<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\StorePostRequest;
use App\Http\Requests\Admin\Post\UpdatePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $posts = Post::latest()->with('author')->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        $tags = Tag::pluck('title', 'id');

        return view('admin.posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // check
            $postImageController = new PostImageController;
            $imagesTitle = $postImageController->upload($request->image);

            $post = Post::create([
                'title' => $request->input('title'),
                'user_id' => auth()->id(),
                'is_active' => $request->input('is_active'),
                'image' => $imagesTitle['image'],
                'text' => $request->input('text'),
                'type' => $request->input('type'),
            ]);

            $post->tags()->attach($request->tag_ids);

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
     * Display the specified resource.
     */
    public function show(Post $post): View|Application|Factory
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View|Application|Factory
    {
        $tags = Tag::pluck('title', 'id');

        return view('admin.posts.edit', compact('post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if ($request->has('image')) {
                $postImageController = new PostImageController;
                $imagesFileName = $postImageController->upload($request->image);
                $post->update([
                    'image' => $imagesFileName,
                ]);
            }

            $post->update([
                'title' => $request->input('title'),
                'user_id' => auth()->id(),
                'is_active' => $request->input('is_active'),
                'text' => $request->input('text'),
                'type' => $request->input('type'),
            ]);

            $post->tags()->sync($request->tag_ids);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!', $ex->getMessage());

            return redirect()->back();
        }

        toastr()->success('با موفقیت ویرایش شد.');

        return redirect()->back();
    }

    public function search(): View|Application|Factory
    {
        $posts = Post::search('title', trim(request()->keyword))->latest()->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        toastr()->success('موفقیت حذف شد!');

        return redirect()->back();
    }
}
