<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CKEditorController extends Controller
{
    public function __construct(
        private readonly FileUploadService $fileUpload
    ) {}

    /**
     * آپلود تصویر CKEditor
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            // ✅ Validation
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB
            ]);

            // ✅ آپلود تصویر
            $fileName = $this->fileUpload->upload(
                $request->file('upload'),
                config('upload.ckeditor_path', 'ckeditor')
            );

            // ✅ URL کامل تصویر
            $url = $this->fileUpload->url(
                config('upload.ckeditor_path', 'ckeditor'),
                $fileName
            );

            // ✅ فرمت response برای CKEditor
            Log::info(response()->json([
                'uploaded' => true,
                'url' => $url,
                'fileName' => $fileName,
            ]));
            return response()->json([
                'uploaded' => true,
                'url' => $url,
                'fileName' => $fileName,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => 'خطا در آپلود تصویر: ' . $e->getMessage()
                ]
            ], 400);
        }
    }
}
