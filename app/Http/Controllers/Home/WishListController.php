<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WishList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishListController extends Controller
{
    public function add(Product $product): RedirectResponse
    {
        if (auth()->check()){
            if ($product->checkUserWishlist(auth()->id())){
                toastr()->success('محصول مورد نظر از قبل در لیست علاقه مندی ها وجود دارد!');
            }else{
                WishList::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id
                ]);
                toastr()->success('با موفقیت به لیست علاقه مندیها اضافه شد');
            }
        }else{
            toastr()->warning('اول ثبت نام کنید یا وارد شوید!');
        }
        return redirect()->back();

    }

    public function remove(Product $product): RedirectResponse
    {

        if (auth()->check()){
            $wishList = WishList::where('product_id', $product->id)->where('user_id', auth()->id())->firstOrFail();
            if ($wishList){
                WishList::where('product_id', $product->id)->where('user_id', auth()->id())->delete();
                toastr()->success('با موفقیت از لیست علاقه مندیها حذف شد');
                return redirect()->back();
            }
            toastr()->success('مشکلی پیش آمد!');
        }else{
            toastr()->warning('اول ثبت نام کنید یا وارد شوید!');
        }
        return redirect()->back();

    }

}
