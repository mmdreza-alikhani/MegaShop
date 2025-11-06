<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * آپلود یک فایل
     */
    public function upload(UploadedFile $file, string $path, ?string $disk = 'public'): string
    {
        $fileName = $this->generateFileName($file->getClientOriginalName());
        $file->storeAs($path, $fileName, $disk);

        return $fileName;
    }

    /**
     * آپلود چند فایل
     */
    public function uploadMultiple(array $files, string $path, ?string $disk = 'public'): array
    {
        $fileNames = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $fileNames[] = $this->upload($file, $path, $disk);
            }
        }

        return $fileNames;
    }

    /**
     * آپلود تصویر اصلی + تصاویر دیگر (مثل محصول)
     */
    public function uploadWithGallery(
        UploadedFile $primaryImage,
        array $otherImages,
        string $primaryPath,
        string $galleryPath,
        ?string $disk = 'public'
    ): array {
        return [
            'primary' => $this->upload($primaryImage, $primaryPath, $disk),
            'gallery' => $this->uploadMultiple($otherImages, $galleryPath, $disk),
        ];
    }

    public function uploadToGallery(
        array $images,
        string $galleryPath,
        ?string $disk = 'public'
    ): array {
        return [
            $this->uploadMultiple($images, $galleryPath, $disk),
        ];
    }

    /**
     * حذف یک فایل
     */
    public function delete(string $path, string $fileName, ?string $disk = 'public'): bool
    {
        $fullPath = $path . '/' . $fileName;

        if (Storage::disk($disk)->exists($fullPath)) {
            return Storage::disk($disk)->delete($fullPath);
        }

        return false;
    }

    /**
     * حذف چند فایل
     */
    public function deleteMultiple(array $fileNames, string $path, ?string $disk = 'public'): void
    {
        foreach ($fileNames as $fileName) {
            $this->delete($path, $fileName, $disk);
        }
    }

    /**
     * جایگزینی فایل (حذف قدیمی + آپلود جدید)
     */
    public function replace(
        UploadedFile $newFile,
        ?string $oldFileName,
        string $path,
        ?string $disk = 'public'
    ): string {
        // حذف فایل قدیمی
        if ($oldFileName) {
            $this->delete($path, $oldFileName, $disk);
        }

        // آپلود فایل جدید
        return $this->upload($newFile, $path, $disk);
    }

    /**
     * تولید نام فایل یکتا
     */
    private function generateFileName(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return time() . '_' . Str::random(10) . '.' . $extension;
    }

    /**
     * گرفتن URL کامل فایل
     */
    public function url(string $path, string $fileName, ?string $disk = 'public'): string
    {
        $fullUrl = Storage::disk($disk)->url($path . '/' . $fileName);

        // فقط بخش /storage/... را نگه می‌داریم
        return parse_url($fullUrl, PHP_URL_PATH);
    }

    /**
     * چک کردن وجود فایل
     */
    public function exists(string $path, string $fileName, ?string $disk = 'public'): bool
    {
        return Storage::disk($disk)->exists($path . '/' . $fileName);
    }
}
