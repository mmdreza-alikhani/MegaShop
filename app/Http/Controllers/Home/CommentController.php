<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Comment\StoreCommentRequest;
use App\Models\Post;
use App\Models\Comment;
use App\Models\News;
use App\Models\Product;
use App\Models\ProductRate;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, $model, $modelId): RedirectResponse
    {
        $classMap = [
            'product' => Product::class,
            'article' => Post::class,
            'news' => News::class,
        ];

        if (!array_key_exists($model, $classMap)) {
            return redirect()->back()->withErrors(['error' => 'Invalid model type']);
        }

        $modelInstance = $classMap[$model]::findOrFail($modelId);

        try {
            DB::beginTransaction();

            Comment::create([
                'user_id' => auth()->id(),
                'text' => $request->text,
                'reply_of' => $request->replyOf,
                'commentable_id' => $modelInstance->id,
                'commentable_type' => get_class($modelInstance)
            ]);

            if ($request->has('rate') && $model === 'product') {
                $existingRate = $modelInstance->rates()->where('user_id', auth()->id())->first();

                if ($existingRate) {
                    $existingRate->update(['rate' => $request->rate]);
                } else {
                    ProductRate::create([
                        'user_id' => auth()->id(),
                        'product_id' => $modelInstance->id, // Using the actual instance
                        'rate' => $request->rate,
                    ]);
                }
            }
            DB::commit();
            toastr()->success('نظر شما با موفقیت ثبت و در انتظار تایید است!');
            return redirect()->back();
        } catch (Exception $ex) {
            DB::rollBack();
            toastr()->warning($ex->getMessage() . 'مشکلی پیش آمد!');
            return redirect()->back();
        }

    }


}
