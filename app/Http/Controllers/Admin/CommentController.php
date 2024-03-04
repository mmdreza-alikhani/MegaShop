<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::latest()->paginate(10);
        return view('admin.comments.index' , compact('comments'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return view('admin.comments.show' , compact('comment'));
    }

    public function changeStatus(Comment $comment){
        if ($comment->getRawOriginal('approved') == 1){
            $comment->update([
                'approved' => 0
            ]);
        }else{
            $comment->update([
                'approved' => 1
            ]);
        }

        toastr()->success('وضعیت نظر با موفقیت تغییر کرد');
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != ''){
            $comments = Comment::where('text', 'LIKE', '%'.trim($keyWord).'%')->latest()->paginate(10);
            return view('admin.comments.index' , compact('comments'));
        }else{
            $comments = Comment::latest()->paginate(10);
            return view('admin.comments.index' , compact('comments'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Comment::destroy($request->comment);

        toastr()->success('نظر مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }
}
