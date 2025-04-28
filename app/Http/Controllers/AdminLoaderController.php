<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OptionsLoader;
use Illuminate\Support\Facades\Http;

class AdminLoaderController extends Controller
{
    public function index()
    {
        $row = OptionsLoader::first(); // Assume you have only one settings row

        return view('admin.loader', compact('row'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'minecraft_version' => 'required|string',
            'loader_activation' => 'required|boolean',
            'loader_type' => 'required|string',
            'loader_forge_version' => 'nullable|string',
            'loader_build_version' => 'nullable|string',
        ]);

        $optionsLoader = OptionsLoader::first();
        if (!$optionsLoader) {
            $optionsLoader = new OptionsLoader();
        }

        $optionsLoader->fill($request->all());
        $optionsLoader->save();

        return redirect()->back()->with('success', 'Paramètres du loader mis à jour.');
    }

    public function getForgeBuilds(Request $request)
    {
        $mcVersion = $request->query('mc_version');
        $url = "https://files.minecraftforge.net/net/minecraftforge/forge/index_$mcVersion.html";
        $response = Http::get($url);
        $builds = [];

        if ($response->successful()) {
            $html = $response->body();
            $dom = new \DOMDocument;
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            $xpath = new \DOMXPath($dom);
            $versionsNodeList = $xpath->query('//td[@class="download-version"]');

            foreach ($versionsNodeList as $versionNode) {
                $version = trim($versionNode->nodeValue);
                if (!empty($version)) {
                    $builds[] = "$mcVersion-$version";
                }
            }
        }

        return response()->json(['builds' => $builds]);
    }
}

