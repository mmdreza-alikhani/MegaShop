<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsImageController extends Controller
{
    public function upload($image): array
    {
        $imageFileName = generateFileName($image->getClientOriginalName());
        $image->move(public_path(env('NEWS_IMAGE_UPLOAD_PATH')) , $imageFileName);

        return ['image' => $imageFileName];
    }
}
