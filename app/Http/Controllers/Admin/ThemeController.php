<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Request\MarketApi;
use App\Models\OptionsGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ThemeController extends Controller
{
    public function index()
    {
        try {
            $marketApi = new MarketApi();
            $marketData = $marketApi->getMarketList();
            
            $themes = [];
            if ($marketData['success'] && isset($marketData['data'])) {
                $themes = $marketData['data'];
            }
            
            // Récupérer l'ID du thème actif et ses informations
            $options = OptionsGeneral::first();
            $activeThemeId = $options ? $options->active_theme_id : null;
            $activeThemeVersion = $options ? $options->active_theme_version : null;
            
            // Trouver le thème actif dans la liste des thèmes
            $activeTheme = null;
            if ($activeThemeId) {
                foreach ($themes as $theme) {
                    if ($theme['id'] == $activeThemeId) {
                        $activeTheme = $theme;
                        $activeTheme['saved_version'] = $activeThemeVersion;
                        break;
                    }
                }
            }
            
            return view('admin.theme.index', compact('themes', 'activeThemeId', 'activeTheme'));
        } catch (\Exception $e) {
            return view('admin.theme.index', [
                'themes' => [],
                'activeThemeId' => null,
                'activeTheme' => null,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function download($id)
    {
        try {
            $marketApi = new MarketApi();
            
            // Récupérer les détails de l'élément
            $itemData = $marketApi->getMarketItem($id);
            
            if (!$itemData['success'] || !$itemData['has_permission']) {
                return redirect()->route('admin.theme.index')
                    ->with('error', 'Vous n\'avez pas la permission de télécharger ce thème.');
            }
            
            // Télécharger le fichier
            $zipContent = $marketApi->downloadMarketItem($id);
            
            // Sauvegarder temporairement le fichier ZIP
            $tempPath = storage_path('app/temp_theme_' . $id . '.zip');
            File::put($tempPath, $zipContent);
            
            // Créer le dossier vue s'il n'existe pas
            $vueDir = public_path('vue');
            if (!File::exists($vueDir)) {
                File::makeDirectory($vueDir, 0755, true);
            }
            
            // Supprimer tout le contenu existant du dossier vue
            if (File::exists($vueDir)) {
                File::deleteDirectory($vueDir);
                File::makeDirectory($vueDir, 0755, true);
            }
            
            // Extraire le ZIP dans le dossier vue
            $zip = new ZipArchive;
            if ($zip->open($tempPath) === TRUE) {
                $zip->extractTo($vueDir);
                $zip->close();
                
                // Supprimer le fichier temporaire
                File::delete($tempPath);
                
                // Sauvegarder l'ID et la version du thème actif en base de données
                $options = OptionsGeneral::firstOrNew([]);
                $options->active_theme_id = $id;
                $options->active_theme_version = $itemData['data']['version'] ?? '1.0.0';
                $options->save();
                
                return redirect()->route('admin.theme.index')
                    ->with('success', 'Thème installé avec succès.');
            } else {
                File::delete($tempPath);
                return redirect()->route('admin.theme.index')
                    ->with('error', 'Impossible d\'extraire le thème.');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('admin.theme.index')
                ->with('error', 'Erreur lors du téléchargement: ' . $e->getMessage());
        }
    }

    public function purchase($id)
    {
        // Rediriger vers la page d'achat du marketplace
        return redirect("http://127.0.0.1:8000/marketplace/item/{$id}");
    }
}
