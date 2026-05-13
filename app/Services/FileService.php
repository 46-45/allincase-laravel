<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    const ALLOWED_DOCUMENT_TYPES = ['application/pdf', 'image/jpeg', 'image/png'];

    public static function saveUploadFile(UploadedFile $file, string $subfolder, array $allowedTypes): array
    {
        // Validate content type
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            abort(400, 'Tipe file tidak diizinkan. Gunakan: ' . implode(', ', $allowedTypes));
        }

        // Validate file size
        $maxSize = (int) config('services.upload.max_file_size_mb', 10) * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            abort(400, 'Ukuran file maksimal ' . config('services.upload.max_file_size_mb', 10) . 'MB');
        }

        // Generate unique filename
        $ext = $file->getClientOriginalExtension() ?: 'bin';
        $filename = Str::uuid()->toString() . '.' . $ext;

        // Store file
        $path = $file->storeAs($subfolder, $filename, 'public');

        return [
            'file_url' => '/storage/' . $path,
            'file_type' => $ext,
            'file_size' => $file->getSize(),
        ];
    }

    public static function saveAvatar(UploadedFile $file): string
    {
        $result = self::saveUploadFile($file, 'avatars', self::ALLOWED_IMAGE_TYPES);
        return $result['file_url'];
    }

    public static function saveDocument(UploadedFile $file): array
    {
        return self::saveUploadFile($file, 'documents', self::ALLOWED_DOCUMENT_TYPES);
    }

    public static function deleteFile(?string $fileUrl): void
    {
        if (!$fileUrl) {
            return;
        }

        // Convert URL to storage path
        $path = str_replace('/storage/', '', $fileUrl);

        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Failed to delete file {$path}: " . $e->getMessage());
        }
    }
}
