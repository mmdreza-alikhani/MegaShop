<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PostImageController extends Controller
{
    public function upload($image): array
    {
        $imageName = generateFileName($image->getClientOriginalName());
        $image->storeAs(env('POST_IMAGE_UPLOAD_PATH'), $imageName, 'public');

        return ['image' => $imageName];
    }
}
