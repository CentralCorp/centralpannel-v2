<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminConfigController extends Controller
{
    public function updateConfig(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255'
        ]);

        $envPath = base_path('.env');
        $envContent = File::get($envPath);
        
        // Remplacer l'ancienne valeur APP_NAME
        $newEnvContent = preg_replace(
            '/^APP_NAME=.*/m',
            'APP_NAME="' . $request->app_name . '"',
            $envContent
        );

        File::put($envPath, $newEnvContent);

        return redirect()->back()->with('success', 'Le nom de l\'application a été mis à jour avec succès.');
    }
}
