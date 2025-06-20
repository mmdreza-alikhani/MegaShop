<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $comments = Comment::latest()->paginate(10);
        return view('admin.comments.index' , compact('comments'));
    }

    public function changeStatus(Comment $comment): RedirectResponse
    {
        if ($comment->getRawOriginal('approved') == 1){
            $comment->update([
                'approved' => 0
            ]);
        }else{
            $comment->update([
                'approved' => 1
            ]);
        }

        toastr()->success('وضعیت نظر با موفقیت تغییر کرد!');
        return redirect()->back();
    }

    public function search(): View|Application|Factory
    {
        $comments = Comment::search('title', trim(request()->keyword))->latest()->paginate(10);
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        toastr()->success('با موفقیت حذف شد!');
        return redirect()->back();
    }
}
