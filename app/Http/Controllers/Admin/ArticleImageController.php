<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleImageController extends Controller
{
    public function upload($image)
    {
        $imageFileName = generateFileName($image->getClientOriginalName());
        $image->move(public_path(env('ARTICLE_IMAGE_UPLOAD_PATH')) , $imageFileName);

        return ['image' => $imageFileName];
    }
}
