<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function ck(Request $request)
    {
        try {
            $file = $request->file('upload');
            if (!$file || !$file->isValid()) {
                throw new \RuntimeException('沒有收到檔案或檔案無效');
            }
            $request->validate([
                'upload' => 'file|max:20480|mimetypes:
                    image/jpeg,image/png,image/gif,image/webp,
                    application/pdf,
                    application/zip,application/x-zip-compressed,application/x-7z-compressed,
                    application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                    application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
                    application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,
                    text/plain,application/octet-stream'
            ]);

            $dir  = 'notice-uploads/' . date('Y/m');
            $ext  = strtolower($file->getClientOriginalExtension());
            $name = Str::random(16) . ($ext ? ('.' . $ext) : '');
            $path = $file->storeAs($dir, $name, 'public');

            return response()->json([
                'uploaded' => 1,
                'fileName' => $file->getClientOriginalName(),
                'url'      => asset('storage/' . $path),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'uploaded' => 0,
                'error'    => ['message' => $e->getMessage()],
            ], 422);
        }
    }
}
