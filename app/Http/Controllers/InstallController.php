<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class InstallController extends Controller
{
    private const TEMP_KEY = 'base64:hmU1T3OuvHdi5t1wULI8Xp7geI+JIWGog9pBCNxslY8=';

    public function __construct()
    {
        // Ne pas changer le session driver ici !
    }

    private function prepareEnvironment()
    {
        // Créer les dossiers nécessaires
        $directories = [
            storage_path('app'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
        }

        // Créer le fichier de log si nécessaire
        $logFile = storage_path('logs/laravel.log');
        if (!File::exists($logFile)) {
            File::put($logFile, '');
            chmod($logFile, 0644);
        }
    }

    private function createEnvFile()
    {
        if (!File::exists(base_path('.env'))) {
            $envContent = "APP_NAME=CentralCorp Panel\n";
            $envContent .= "APP_ENV=local\n";
            $envContent .= "APP_KEY=" . self::TEMP_KEY . "\n";
            $envContent .= "APP_DEBUG=true\n";
            $envContent .= "APP_URL=" . url('/') . "\n\n";
            $envContent .= "LOG_CHANNEL=stack\n";
            $envContent .= "DB_CONNECTION=sqlite\n";
            $envContent .= "DB_DATABASE=database/database.sqlite\n";
            $envContent .= "CACHE_DRIVER=file\n";
            $envContent .= "SESSION_DRIVER=file\n"; // SESSION_DRIVER doit être "file" ici
            $envContent .= "SESSION_LIFETIME=120\n";

            File::put(base_path('.env'), $envContent);
        }
    }

    private function ensureDatabaseExists()
    {
        $databasePath = database_path('database.sqlite');

        if (!file_exists($databasePath)) {
            file_put_contents($databasePath, '');
            chmod($databasePath, 0666);
        }
    }

    public function show()
    {
        try {
            if (File::exists(storage_path('installed'))) {
                return redirect('/')->with('error', 'Le système est déjà installé.');
            }

            $this->prepareEnvironment();
            $this->createEnvFile();
            $this->ensureDatabaseExists();

            return view('install');
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    public function install(Request $request)
    {
        try {
            if (File::exists(storage_path('installed'))) {
                return redirect('/')->with('error', 'Le système est déjà installé.');
            }

            $this->prepareEnvironment();
            $this->createEnvFile();
            $this->ensureDatabaseExists();

            // Exécuter les migrations AVANT la validation
            Artisan::call('migrate:fresh', ['--force' => true]);

            // Maintenant on valide
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Créer l'utilisateur administrateur
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => true,
            ]);

            // Générer une nouvelle clé d'application après l'installation
            Artisan::call('key:generate', ['--force' => true]);
            Artisan::call('storage:link');

            // Marquer que l'installation est faite
            File::put(storage_path('installed'), 'Installation complétée le ' . now());

            return redirect('/')->with('success', 'Installation terminée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'installation: ' . $e->getMessage());
        }
    }
}
