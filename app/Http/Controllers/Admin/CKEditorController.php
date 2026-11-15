<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CKEditorController extends Controller
{
    public function __construct(
        private readonly FileUploadService $fileUpload
    ) {}

    /**
     * آپلود تصویر ادیتور متن (TinyMCE).
     *
     * نام مسیر از دوره CKEditor باقی مانده و برای سازگاری تغییر نکرده است.
     * خروجی: { uploaded: true, url, fileName }
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:' . config('upload.max_file_kb'),
            ], [
                'upload.max' => 'حجم تصویر نباید بیشتر از ' . config('upload.max_file_kb') . ' کیلوبایت باشد.',
                'upload.image' => 'فایل انتخاب‌شده باید تصویر باشد.',
                'upload.mimes' => 'فرمت تصویر باید jpg، png، gif یا webp باشد.',
            ]);

            $fileName = $this->fileUpload->upload(
                $request->file('upload'),
                config('upload.ckeditor_path', 'ckeditor')
            );

            $url = $this->fileUpload->url(
                config('upload.ckeditor_path', 'ckeditor'),
                $fileName
            );

            return response()->json([
                'uploaded' => true,
                'url' => $url,
                'fileName' => $fileName,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => 'خطا در آپلود تصویر: '.$e->getMessage(),
                ],
            ], 400);
        }
    }
}
