<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function upload($primaryImg , $otherImages): array
    {
        $primaryImgFileName = generateFileName($primaryImg->getClientOriginalName());
        $primaryImg->move(public_path(env('PRODUCT_PRIMARY_IMAGE_UPLOAD_PATH')) , $primaryImgFileName);

        $otherImagesFileNames = [];
        foreach ($otherImages as $otherImage){
            $otherImageFileName = generateFileName($otherImage->getClientOriginalName());
            $otherImage->move(public_path(env('PRODUCT_OTHER_IMAGES_UPLOAD_PATH')) , $otherImageFileName);
            $otherImagesFileNames[] = $otherImageFileName;
        }

        return ['primaryImg' => $primaryImgFileName , '$otherImages' => $otherImagesFileNames];
    }

    public function edit(Product $product): View|Application|Factory
    {
        return view('admin.products.edit_images', compact('product'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
           'image_id' => 'required',
        ]);

        ProductImage::destroy($request->input('image_id'));

        File::delete(public_path('/upload/files/products/images/primary_images/'. $request->image_name));

        toastr()->success('تصویر مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }

    public function set_primary(Request $request, Product $product): RedirectResponse
    {

        $request->validate([
            'image_id' => 'required',
        ]);

        $product_image = ProductImage::findOrFail($request->image_id);

        $product->update([
           'primary_image' => $product_image->image
        ]);

        toastr()->success('تصویر مورد نظر با موفقیت ویرایش شد!');
        return redirect()->back();

    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'primary_img' => 'nullable|mimes:jpg,jpeg,png,svg',
            'other_imgs.*' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);

        if ($request->primary_img == null && $request->other_imgs == null){
            return redirect()->back()->withErrors(['msg' => 'تصویر یا تصاویر محصول الزامی است' ]);
        }
        try {
            DB::beginTransaction();

            if ($request->has("primary_img")) {
                $primaryImgFileName = generateFileName($request->primary_img->getClientOriginalName());
                $request->primary_img->move(public_path(env('PRODUCT_PRIMARY_IMAGE_UPLOAD_PATH')), $primaryImgFileName);

                $product->update([
                    'primary_image' => $primaryImgFileName
                ]);
            }

            if ($request->has("other_imgs")) {

                foreach ($request->other_imgs as $otherImage) {
                    $otherImageFileName = generateFileName($otherImage->getClientOriginalName());
                    $otherImage->move(public_path(env('PRODUCT_OTHER_IMAGES_UPLOAD_PATH')), $otherImageFileName);
                    ProductImage::create([
                        'image' => $otherImageFileName,
                        'product_id' => $product->id
                    ]);
                }

            }

            DB::commit();
        } catch (\Exception $ex){
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->back();
        }
        toastr()->success('تصویر یا تصاویر مورد نظر با موفقیت ویرایش شد!');
        return redirect()->back();
    }
}


