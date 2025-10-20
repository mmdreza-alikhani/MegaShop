<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\StorePostRequest;
use App\Http\Requests\Admin\Post\UpdatePostRequest;
use App\Models\Attribute;
use App\Models\Post;
use App\Models\Tag;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    protected mixed $uploadPath;
    public function __construct(private readonly FileUploadService $fileUpload)
    {
        $this->uploadPath = config('upload.post_path');
        $this->middleware('permission:posts-index', ['only' => ['index']]);
        $this->middleware('permission:posts-create', ['only' => ['store']]);
        $this->middleware('permission:posts-edit', ['only' => ['update']]);
        $this->middleware('permission:posts-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Post::query();
        if ($request->input('q')) {
            $query->search('title', trim(request()->input('q')));
        }
        $posts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        $tags = Cache::remember('tags', now()->addHour(), function () {
            return Tag::pluck('title', 'id');
        });

        return view('admin.posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $imageName = $this->fileUpload->upload($request->file('image'), $this->uploadPath);

            $post = Post::create([
                ...$request->validated(),
                'image' => $imageName,
                'user_id' => auth()->id(),
            ]);

            $post->tags()->attach($request->array('tag_ids'));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            toastr()->error(config('flasher.post.create_failed'));
            report($e);
            return redirect()->back();
        }

        toastr()->success(config('flasher.post.created'));
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
        $tags = Cache::remember('tags', now()->addHour(), function () {
            return Tag::pluck('title', 'id');
        });

        return view('admin.posts.edit', compact('post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $imageName = $this->fileUpload->replace($request->file('image'), $post->image, $this->uploadPath);
                $post->update([
                    'image' => $imageName,
                ]);
            }

            $post->update([
                ...$request->validated(),
                'user_id' => auth()->id(),
            ]);

            $post->tags()->sync($request->array('tag_ids'));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            toastr()->error(config('flasher.post.update_failed'));
            report($e);
            return redirect()->back();
        }

        toastr()->success(config('flasher.post.updated'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        try {
            $post->delete();

            flash()->success(config('flasher.post.deleted'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.post.delete_failed'));
            return redirect()->back();
        }
    }
}
