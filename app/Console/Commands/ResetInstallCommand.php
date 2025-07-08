<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\InstallController;

class ResetInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:reset {--force : Force la réinitialisation sans confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Réinitialise l\'installation pour permettre une nouvelle installation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            $confirmed = $this->confirm('Êtes-vous sûr de vouloir réinitialiser l\'installation ? Cela supprimera toutes les données !', false);
            
            if (!$confirmed) {
                $this->info('Réinitialisation annulée.');
                return 0;
            }
        }

        $this->info('🔄 Début de la réinitialisation de l\'installation...');

        // 1. Supprimer le fichier d'installation
        if (File::exists(storage_path('installed'))) {
            File::delete(storage_path('installed'));
            $this->line('✅ Fichier installed supprimé');
        } else {
            $this->line('ℹ️  Fichier installed déjà absent');
        }

        // 2. Réinitialiser la base de données
        try {
            $this->info('🗄️  Réinitialisation de la base de données...');
            Artisan::call('migrate:reset', ['--force' => true]);
            $this->line('✅ Tables supprimées');
        } catch (\Exception $e) {
            $this->line('⚠️  Erreur lors de la suppression des tables (normal si elles n\'existent pas)');
        }

        // 3. Supprimer le fichier SQLite s'il existe
        $sqlitePath = database_path('database.sqlite');
        if (File::exists($sqlitePath)) {
            File::delete($sqlitePath);
            $this->line('✅ Fichier SQLite supprimé');
        }

        // 4. Remettre la clé temporaire dans le .env
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            
            // Remplacer la clé actuelle par la clé temporaire
            $envContent = preg_replace(
                '/^APP_KEY=.*/m', 
                'APP_KEY=' . InstallController::TEMP_KEY, 
                $envContent
            );
            
            // Remettre APP_NAME par défaut
            $envContent = preg_replace(
                '/^APP_NAME=.*/m', 
                'APP_NAME="CentralCorp Panel"', 
                $envContent
            );
            
            // Remettre APP_ENV en local
            $envContent = preg_replace(
                '/^APP_ENV=.*/m', 
                'APP_ENV=local', 
                $envContent
            );
            
            // Remettre APP_DEBUG en true
            $envContent = preg_replace(
                '/^APP_DEBUG=.*/m', 
                'APP_DEBUG=true', 
                $envContent
            );

            File::put($envPath, $envContent);
            $this->line('✅ Fichier .env réinitialisé avec la clé temporaire');
        }

        // 5. Vider les caches
        try {
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            $this->line('✅ Caches vidés');
        } catch (\Exception $e) {
            $this->line('⚠️  Erreur lors du vidage des caches : ' . $e->getMessage());
        }

        // 6. Supprimer le storage link s'il existe
        $linkPath = public_path('storage');
        if (File::exists($linkPath) || is_link($linkPath)) {
            if (is_link($linkPath)) {
                unlink($linkPath);
            } else {
                File::deleteDirectory($linkPath);
            }
            $this->line('✅ Lien storage supprimé');
        }

        $this->newLine();
        $this->info('🎉 Réinitialisation terminée !');
        $this->info('👉 Vous pouvez maintenant accéder à l\'installation sur : http://localhost:8000');
        $this->newLine();
        
        return 0;
    }
}
