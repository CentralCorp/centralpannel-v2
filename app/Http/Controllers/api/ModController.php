<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Mod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ModController extends Controller
{
    public function index(): JsonResponse
    {
        $mods = Mod::all();

        $modsData = [];
        $optionalMods = [];

        foreach ($mods as $mod) {
            $modsId = $mod->id;
            $modsName = $mod->name;
            $modsDescription = $mod->description;
            $modsFile = basename($mod->file);

            $modsIcon = !empty($mod->icon) ? $this->getAbsoluteUrl($mod->icon) : "";
            $isRecommended = !empty($mod->recommended) && $mod->recommended == 1;

            $modsData[$modsFile] = [
                "name" => $modsName,
                "description" => $modsDescription,
                "icon" => $modsIcon,
                "recommanded" => $isRecommended
            ];

            $optionalMods[] = $modsFile;
        }

        $output = [
            "optionalMods" => $optionalMods,
            "mods" => $modsData
        ];

        return response()->json($output, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
    private function getAbsoluteUrl($imagePath)
    {
        $relativeUrl = Storage::disk('public')->url($imagePath);

        return url($relativeUrl);
    }
}




