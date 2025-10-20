<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $comments = Comment::latest()->paginate(10);

        return view('admin.comments.index', compact('comments'));
    }

    public function toggle(Comment $comment): RedirectResponse
    {
        $comment->update([
            'status' => $comment->status == 1 ? 0 : 1,
        ]);

        toastr()->success('وضعیت نظر با موفقیت تغییر کرد!');

        return redirect()->back();
    }

    public function search(): View|Application|Factory
    {
        $comments = Comment::search('title', trim(request()->keyword))->latest()->paginate(10);

        return view('admin.comments.index', compact('comments'));
    }
}
