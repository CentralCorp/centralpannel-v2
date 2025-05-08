<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\OptionsGeneral;
use App\Models\OptionsUI;
use App\Models\OptionsServer;
use App\Models\OptionsRPC;
use App\Models\OptionsSecurity;
use App\Models\OptionsLoader;
use App\Models\OptionsIgnore;
use App\Models\OptionsWhitelist;
use App\Models\OptionsWhitelistRole;
use Illuminate\Support\Facades\Schema;

class SettingsExportController extends Controller
{
    public function export()
    {
        $settings = [
            'version' => '1.0',
            'export_date' => now()->format('Y-m-d H:i:s'),
            'data' => [
                'options_general' => OptionsGeneral::all(),
                'options_ui' => OptionsUI::all(),
                'options_server' => OptionsServer::all(),
                'options_rpc' => OptionsRPC::all(),
                'options_security' => OptionsSecurity::all(),
                'options_loader' => OptionsLoader::all(),
                'ignored_folders' => OptionsIgnore::all(),
                'whitelist' => OptionsWhitelist::all(),
                'whitelist_roles' => OptionsWhitelistRole::all(),
            ]
        ];

        $json = json_encode($settings, JSON_PRETTY_PRINT);
        $filename = 'centralcorp_settings_' . date('Y-m-d_H-i-s') . '.centralcorp';

        return response($json)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function import(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:centralcorp,json'
        ]);

        $json = file_get_contents($request->file('settings_file')->path());
        $settings = json_decode($json, true);

        if (!$settings || !isset($settings['data'])) {
            return back()->with('error', 'Le fichier .centralcorp est invalide ou corrompu.');
        }

        // Vérifier la version du fichier
        if (!isset($settings['version']) || $settings['version'] !== '1.0') {
            return back()->with('error', 'Version du fichier .centralcorp non supportée.');
        }

        DB::beginTransaction();
        try {
            foreach ($settings['data'] as $table => $data) {
                // Vérifier si la table existe
                if (!Schema::hasTable($table)) {
                    throw new \Exception("La table {$table} n'existe pas.");
                }

                // Vider la table existante
                DB::table($table)->truncate();
                
                // Insérer les nouvelles données
                foreach ($data as $row) {
                    // Supprimer les timestamps si présents
                    unset($row['created_at'], $row['updated_at']);
                    DB::table($table)->insert((array) $row);
                }
            }
            
            DB::commit();
            return back()->with('success', 'Les paramètres ont été importés avec succès depuis le fichier .centralcorp.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'importation : ' . $e->getMessage());
        }
    }
} 