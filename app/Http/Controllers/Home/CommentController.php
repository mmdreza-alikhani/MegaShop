<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\News;
use App\Models\Product;
use App\Models\ProductRate;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function storeInProducts(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            "text" => "required|min:3",
            "rate" => "required|digits_between:1,5"
        ]);

        if ($validator->fails()){
            return redirect()->to(url()->previous() . '#reviews')->withErrors($validator);
        }

        if (auth()->check()){
            try {
                DB::beginTransaction();

                    Comment::create([
                        'user_id' => auth()->id(),
                        'product_id' => $product->id,
                        'text' => $request->text
                    ]);

                    if ($product->rates()->where( 'user_id', auth()->id())->exists()){
                        $productRate = $product->rates()->where( 'user_id', auth()->id())->first();
                        $productRate->update([
                            'rate' => $request->rate,
                        ]);
                    }else{
                        ProductRate::create([
                            'user_id' => auth()->id(),
                            'product_id' => $product->id,
                            'rate' => $request->rate
                        ]);
                    }

                DB::commit();

                toastr()->success('نظر شما با موفقیت ثبت و در انتظار تایید است!');
                return redirect()->back();
            }catch (\Exception $ex){
                DB::rollBack();
                toastr()->warning( $ex->getMessage() .'مشکلی پیش آمد!');
                return redirect()->back();
            }
        }else{
            toastr()->warning('جهت ثبت نظر ابتدا ثبت نام کنید.');
            return redirect()->back();
        }
    }

    public function storeInArticles(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            "text" => "required|min:3"
        ]);

        if ($validator->fails()){
            return redirect()->to(url()->previous() . '#reviews')->withErrors($validator);
        }

        if (auth()->check()){
            try {
                DB::beginTransaction();

                Comment::create([
                    'user_id' => auth()->id(),
                    'article_id' => $article->id,
                    'text' => $request->text
                ]);

                DB::commit();

                toastr()->success('نظر شما با موفقیت ثبت و در انتظار تایید است!');
                return redirect()->back();
            }catch (\Exception $ex){
                DB::rollBack();
                toastr()->warning( $ex->getMessage() .'مشکلی پیش آمد!');
                return redirect()->back();
            }
        }else{
            toastr()->warning('جهت ثبت نظر ابتدا ثبت نام کنید.');
            return redirect()->back();
        }
    }

    public function storeInNews(Request $request, News $news)
    {
        $validator = Validator::make($request->all(), [
            "text" => "required|min:3"
        ]);

        if ($validator->fails()){
            return redirect()->to(url()->previous() . '#reviews')->withErrors($validator);
        }

        if (auth()->check()){
            try {
                DB::beginTransaction();

                Comment::create([
                    'user_id' => auth()->id(),
                    'news_id' => $news->id,
                    'text' => $request->text
                ]);

                DB::commit();

                toastr()->success('نظر شما با موفقیت ثبت و در انتظار تایید است!');
                return redirect()->back();
            }catch (\Exception $ex){
                DB::rollBack();
                toastr()->warning( $ex->getMessage() .'مشکلی پیش آمد!');
                return redirect()->back();
            }
        }else{
            toastr()->warning('جهت ثبت نظر ابتدا ثبت نام کنید.');
            return redirect()->back();
        }
    }

}
