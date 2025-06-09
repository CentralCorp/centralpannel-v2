<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\OptionsIgnore;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function getFiles(): JsonResponse
    {
        $dir = storage_path('app/public/data');
        $ignoredFolders = OptionsIgnore::pluck('folder_name')->toArray();

        return response()->json($this->dirToArray($dir, '', $ignoredFolders), 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function dirToArray($dir, $basePath = '', $ignoredFolders = [])
    {
        $files = [];
        $cdir = scandir($dir);

        foreach ($cdir as $value) {
            if (!in_array($value, [".", ".."]) && !in_array($value, $ignoredFolders)) {
                $path = $dir . '/' . $value;
                $relativePath = ltrim($basePath . '/' . $value, '/');

                if (is_dir($path)) {
                    $files = array_merge($files, $this->dirToArray($path, $relativePath, $ignoredFolders));
                } else {
                    $hash = hash_file('sha1', $path);
                    $size = filesize($path);
                    $url = url('storage/data/' . $relativePath);

                    $files[] = [
                        'path' => $relativePath,
                        'size' => $size,
                        'hash' => $hash,
                        'url' => $url
                    ];
                }
            }
        }

        return $files;
    }
}
