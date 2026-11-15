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
    public function index(Request $request): View|Application|Factory
    {
        $query = Comment::query()->with(['user:id,username,first_name,last_name', 'commentable'])->latest();

        if ($request->filled('q')) {
            $query->search('text', trim((string) $request->input('q')));
        }

        if ($status = $request->input('status')) {
            $query->where('status', (int) $status);
        }

        if ($type = $request->input('type')) {
            $query->where('commentable_type', $type === 'post' ? \App\Models\Post::class : \App\Models\Product::class);
        }

        $comments = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Comment::count(),
            'pending' => Comment::query()->where('status', 1)->count(),
            'approved' => Comment::query()->where('status', 3)->count(),
            'rejected' => Comment::query()->where('status', 2)->count(),
        ];

        return view('admin.comments.index', compact('comments', 'stats'));
    }

    /**
     * تایید نظر.
     */
    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update([
            'status' => 3,
            'is_active' => 1,
        ]);

        flash()->success('نظر با موفقیت تایید شد.');

        return redirect()->back();
    }

    /**
     * رد نظر.
     */
    public function reject(Comment $comment): RedirectResponse
    {
        $comment->update([
            'status' => 2,
            'is_active' => 0,
        ]);

        flash()->success('نظر رد شد.');

        return redirect()->back();
    }

    /**
     * تغییر وضعیت انتشار (تایید <-> در انتظار بررسی).
     */
    public function toggle(Comment $comment): RedirectResponse
    {
        $isPublished = (int) $comment->getRawOriginal('status') === 3;

        $comment->update([
            'status' => $isPublished ? 1 : 3,
            'is_active' => $isPublished ? 0 : 1,
        ]);

        flash()->success('وضعیت نظر با موفقیت تغییر کرد!');

        return redirect()->back();
    }

    /**
     * حذف نظر (به همراه پاسخ‌هایش).
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        try {
            $comment->child()->delete();
            $comment->delete();

            flash()->success('نظر با موفقیت حذف شد.');
        } catch (\Throwable $e) {
            report($e);
            flash()->error('حذف نظر با خطا مواجه شد.');
        }

        return redirect()->back();
    }
}
