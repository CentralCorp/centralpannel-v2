<?php

namespace App\Http\Controllers;

use App\Models\OptionsGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminConfigController extends Controller
{
    public function show()
    {
        $options = OptionsGeneral::first();
        return view('admin.config', compact('options'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'azuriom_url' => 'required|url',
            'azuriom_api_key' => 'required|string|min:32|max:255'
        ]);

        // Mettre à jour les options dans la base de données
        $options = OptionsGeneral::firstOrNew([]);
        $options->fill([
            'azuriom_url' => $validated['azuriom_url'],
            'azuriom_api_key' => $validated['azuriom_api_key']
        ]);
        $options->save();

        // Vérifier si le nom de l'application a changé
        $currentAppName = env('APP_NAME');
        if ($validated['app_name'] !== $currentAppName) {
            // Mettre à jour le nom de l'application dans le fichier .env
            $envPath = base_path('.env');
            $envContent = File::get($envPath);
            $newEnvContent = preg_replace(
                '/^APP_NAME=.*/m',
                'APP_NAME="' . $validated['app_name'] . '"',
                $envContent
            );
            File::put($envPath, $newEnvContent);
        }

        return redirect()->route('admin.config')->with('success', 'Configuration mise à jour avec succès.');
    }
}
