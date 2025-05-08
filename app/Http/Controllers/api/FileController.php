<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $dir = storage_path('app/public/data');

        return response()->json($this->dirToArray($dir));
    }

    private function dirToArray($dir, $basePath = '')
    {
        $files = [];
        $cdir = scandir($dir);

        foreach ($cdir as $value) {
            if (!in_array($value, [".", ".."])) {
                $path = $dir . '/' . $value;
                $relativePath = ltrim($basePath . '/' . $value, '/');

                if (is_dir($path)) {
                    $files = array_merge($files, $this->dirToArray($path, $relativePath));
                } else {
                    $hash = hash_file('sha1', $path);
                    $size = filesize($path);

                    $url = url('storage/data/' . $relativePath);

                    $files[] = [
                        'path' => $relativePath,
                        'size' => $size,
                        'hash' => $hash,
                        'url' => $url,
                    ];
                }
            }
        }

        return $files;
    }
}
