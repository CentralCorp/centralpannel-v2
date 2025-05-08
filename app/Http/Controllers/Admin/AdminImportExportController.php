<?php

namespace App\Http\Controllers\Admin;

use App\Models\OptionsGeneral;
use App\Models\OptionsUI;
use App\Models\OptionsSecurity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminImportExportController extends Controller
{
    public function export()
    {
        // Récupérer toutes les configurations
        $config = [
            'general' => OptionsGeneral::first(),
            'ui' => OptionsUI::first(),
            'security' => OptionsSecurity::first(),
        ];

        // Convertir en JSON
        $json = json_encode($config, JSON_PRETTY_PRINT);

        // Créer un nom de fichier unique avec l'extension .centralcorp
        $filename = 'config_backup_' . date('Y-m-d_H-i-s') . '.centralcorp';

        // Sauvegarder le fichier
        Storage::put('backups/' . $filename, $json);

        // Télécharger le fichier
        return response()->download(storage_path('app/backups/' . $filename))->deleteFileAfterSend();
    }

    public function import(Request $request)
    {
        $request->validate([
            'config_file' => 'required|file|mimes:centralcorp'
        ]);

        try {
            // Lire le fichier JSON
            $json = file_get_contents($request->file('config_file')->path());
            $config = json_decode($json, true);

            if (!$config) {
                throw new \Exception('Format de fichier invalide');
            }

            // Démarrer une transaction
            DB::beginTransaction();

            // Importer les configurations
            if (isset($config['general'])) {
                $general = OptionsGeneral::first();
                if ($general) {
                    $general->update($config['general']);
                } else {
                    OptionsGeneral::create($config['general']);
                }
            }

            if (isset($config['ui'])) {
                $ui = OptionsUI::first();
                if ($ui) {
                    $ui->update($config['ui']);
                } else {
                    OptionsUI::create($config['ui']);
                }
            }

            if (isset($config['security'])) {
                $security = OptionsSecurity::first();
                if ($security) {
                    $security->update($config['security']);
                } else {
                    OptionsSecurity::create($config['security']);
                }
            }

            DB::commit();

            return redirect()->route('admin.index')->with('success', 'Configuration importée avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.index')->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
        }
    }
} 