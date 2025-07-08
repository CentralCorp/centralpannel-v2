<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\InstallController;
use ZipArchive;

class BuildProdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:prod {--output= : Chemin de sortie du fichier ZIP}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère un ZIP du panel prêt pour la production (sans DB, vendor, avec .env temporaire)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Génération du build de production...');

        // Créer un dossier temporaire pour le build
        $tempDir = storage_path('build-temp');
        $this->prepareTemporaryDirectory($tempDir);

        try {
            // Copier les fichiers nécessaires
            $this->copyFiles($tempDir);
            
            // Créer le .env temporaire
            $this->createTemporaryEnv($tempDir);
            
            // Créer un composer.json optimisé pour la production
            $this->createProductionComposer($tempDir);
            
            // Créer le fichier ZIP
            $zipPath = $this->createZip($tempDir);
            
            $this->info("✅ Build de production généré : {$zipPath}");
            $this->info("📦 Le fichier ZIP est prêt pour le déploiement en production");
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la génération : " . $e->getMessage());
            return 1;
        } finally {
            // Nettoyer le dossier temporaire
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
        }

        return 0;
    }

    /**
     * Prépare le dossier temporaire
     */
    private function prepareTemporaryDirectory(string $tempDir): void
    {
        if (File::exists($tempDir)) {
            File::deleteDirectory($tempDir);
        }
        File::makeDirectory($tempDir, 0755, true);
    }

    /**
     * Copie les fichiers nécessaires en excluant certains dossiers/fichiers
     */
    private function copyFiles(string $tempDir): void
    {
        $this->info('📁 Copie des fichiers...');
        
        $basePath = base_path();
        $excludePatterns = [
            'node_modules',
            'storage/app',
            'storage/framework/cache',
            'storage/framework/sessions',
            'storage/framework/testing',
            'storage/framework/views',
            'storage/logs',
            'storage/debugbar',
            'storage/build-temp',
            'database/database.sqlite',
            'public/storage',
            '.env',
            '.env.backup',
            '.env.example',
            '.git',
            '.gitignore',
            '.gitattributes',
            'package-lock.json',
            'yarn.lock',
            'tests',
            'phpunit.xml',
            '*.log',
            'storage/installed'
        ];

        // Copier tous les fichiers sauf ceux exclus
        $this->recursiveCopy($basePath, $tempDir, $excludePatterns);

        // Créer les dossiers storage nécessaires
        $this->createStorageStructure($tempDir);
    }

    /**
     * Copie récursive en excluant certains patterns
     */
    private function recursiveCopy(string $source, string $dest, array $excludePatterns): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relativePath = str_replace($source . DIRECTORY_SEPARATOR, '', $item->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath);
            
            // Vérifier si le fichier/dossier doit être exclu
            $shouldExclude = false;
            foreach ($excludePatterns as $pattern) {
                if (str_starts_with($relativePath, $pattern) || 
                    fnmatch($pattern, $relativePath) ||
                    fnmatch($pattern, basename($relativePath))) {
                    $shouldExclude = true;
                    break;
                }
            }

            if ($shouldExclude) {
                continue;
            }

            $target = $dest . DIRECTORY_SEPARATOR . $relativePath;

            if ($item->isDir()) {
                if (!File::exists($target)) {
                    File::makeDirectory($target, 0755, true);
                }
            } else {
                $targetDir = dirname($target);
                if (!File::exists($targetDir)) {
                    File::makeDirectory($targetDir, 0755, true);
                }
                File::copy($item->getPathname(), $target);
            }
        }
    }

    /**
     * Crée la structure des dossiers storage nécessaires
     */
    private function createStorageStructure(string $tempDir): void
    {
        $storageDirs = [
            'storage/app/public',
            'storage/framework/cache/data',
            'storage/framework/sessions',
            'storage/framework/views',
            'storage/logs'
        ];

        foreach ($storageDirs as $dir) {
            $fullPath = $tempDir . DIRECTORY_SEPARATOR . $dir;
            if (!File::exists($fullPath)) {
                File::makeDirectory($fullPath, 0755, true);
            }
        }

        // Créer un fichier .gitkeep dans chaque dossier vide
        foreach ($storageDirs as $dir) {
            $gitkeepPath = $tempDir . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . '.gitkeep';
            if (!File::exists($gitkeepPath)) {
                File::put($gitkeepPath, '');
            }
        }
    }

    /**
     * Crée le fichier .env temporaire pour l'installation
     */
    private function createTemporaryEnv(string $tempDir): void
    {
        $this->info('⚙️  Création du .env temporaire...');
        
        $envContent = "APP_NAME=\"CentralCorp Panel\"\n";
        $envContent .= "APP_ENV=production\n";
        $envContent .= "APP_KEY=" . InstallController::TEMP_KEY . "\n";
        $envContent .= "APP_DEBUG=false\n";
        $envContent .= "APP_URL=http://localhost\n\n";
        
        $envContent .= "LOG_CHANNEL=stack\n";
        $envContent .= "LOG_DEPRECATIONS_CHANNEL=null\n";
        $envContent .= "LOG_LEVEL=error\n\n";
        
        $envContent .= "DB_CONNECTION=sqlite\n";
        $envContent .= "DB_HOST=127.0.0.1\n";
        $envContent .= "DB_PORT=3306\n";
        $envContent .= "DB_DATABASE=\n";
        $envContent .= "DB_USERNAME=\n";
        $envContent .= "DB_PASSWORD=\n\n";
        
        $envContent .= "BROADCAST_DRIVER=log\n";
        $envContent .= "CACHE_DRIVER=file\n";
        $envContent .= "FILESYSTEM_DISK=local\n";
        $envContent .= "QUEUE_CONNECTION=sync\n";
        $envContent .= "SESSION_DRIVER=file\n";
        $envContent .= "SESSION_LIFETIME=120\n\n";
        
        $envContent .= "MEMCACHED_HOST=127.0.0.1\n\n";
        
        $envContent .= "REDIS_HOST=127.0.0.1\n";
        $envContent .= "REDIS_PASSWORD=null\n";
        $envContent .= "REDIS_PORT=6379\n\n";
        
        $envContent .= "MAIL_MAILER=smtp\n";
        $envContent .= "MAIL_HOST=mailpit\n";
        $envContent .= "MAIL_PORT=1025\n";
        $envContent .= "MAIL_USERNAME=null\n";
        $envContent .= "MAIL_PASSWORD=null\n";
        $envContent .= "MAIL_ENCRYPTION=null\n";
        $envContent .= "MAIL_FROM_ADDRESS=\"hello@example.com\"\n";
        $envContent .= "MAIL_FROM_NAME=\"\${APP_NAME}\"\n\n";

        File::put($tempDir . DIRECTORY_SEPARATOR . '.env', $envContent);
        
        // Créer un .gitignore approprié pour la production
        $this->createProductionGitignore($tempDir);
        
        // Créer un README d'installation
        $this->createInstallationReadme($tempDir);
    }

    /**
     * Crée un README spécifique pour l'installation en production
     */
    private function createInstallationReadme(string $tempDir): void
    {
        $readmeContent = "# CentralCorp Panel - Installation Production\n\n";
        $readmeContent .= "Cette archive contient une version prête pour la production du CentralCorp Panel.\n\n";
        $readmeContent .= "## Prérequis\n\n";
        $readmeContent .= "- PHP >= 8.1\n";
        $readmeContent .= "- Extensions PHP : bcmath, ctype, json, mbstring, openssl, PDO, tokenizer, xml, xmlwriter, curl, fileinfo, zip\n";
        $readmeContent .= "- Serveur web (Apache/Nginx) avec support PHP\n\n";
        $readmeContent .= "## Installation\n\n";
        $readmeContent .= "1. **Télécharger et extraire**\n";
        $readmeContent .= "   - Extrayez cette archive dans le répertoire de votre serveur web\n";
        $readmeContent .= "   - Assurez-vous que le dossier `public` est le document root de votre serveur\n\n";
        $readmeContent .= "2. **Permissions**\n";
        $readmeContent .= "   ```bash\n";
        $readmeContent .= "   chmod -R 755 storage bootstrap/cache\n";
        $readmeContent .= "   chown -R www-data:www-data storage bootstrap/cache\n";
        $readmeContent .= "   ```\n\n";
        $readmeContent .= "3. **Configuration du serveur web**\n";
        $readmeContent .= "   - Pointez le document root vers le dossier `public`\n";
        $readmeContent .= "   - Activez la réécriture d'URL (mod_rewrite pour Apache)\n\n";
        $readmeContent .= "4. **Installation automatique**\n";
        $readmeContent .= "   - Visitez votre site web\n";
        $readmeContent .= "   - L'assistant d'installation se lancera automatiquement\n";
        $readmeContent .= "   - Suivez les étapes de configuration\n\n";
        $readmeContent .= "## Structure des dossiers\n\n";
        $readmeContent .= "- `public/` - Document root du serveur web\n";
        $readmeContent .= "- `storage/` - Fichiers de cache et logs (doit être en écriture)\n";
        $readmeContent .= "- `bootstrap/cache/` - Cache Laravel (doit être en écriture)\n";
        $readmeContent .= "- `vendor/` - Dépendances PHP (incluses dans cette distribution)\n";
        $readmeContent .= "- `.env` - Fichier de configuration temporaire (sera modifié lors de l'installation)\n\n";
        $readmeContent .= "## Support\n\n";
        $readmeContent .= "Pour toute assistance, consultez la documentation ou contactez le support.\n\n";
        $readmeContent .= "---\n";
        $readmeContent .= "Archive générée le : " . now()->format('Y-m-d H:i:s') . "\n";

        File::put($tempDir . DIRECTORY_SEPARATOR . 'INSTALLATION.md', $readmeContent);
    }

    /**
     * Crée un fichier .gitignore approprié pour la production
     */
    private function createProductionGitignore(string $tempDir): void
    {
        $gitignoreContent = "# Production GitIgnore\n";
        $gitignoreContent .= "# Note: vendor/ est inclus dans cette distribution de production\n";
        $gitignoreContent .= "/node_modules/\n";
        $gitignoreContent .= "/public/storage\n";
        $gitignoreContent .= "/storage/*.key\n";
        $gitignoreContent .= "/storage/app/*\n";
        $gitignoreContent .= "!/storage/app/.gitkeep\n";
        $gitignoreContent .= "!/storage/app/public/\n";
        $gitignoreContent .= "/storage/framework/cache/*\n";
        $gitignoreContent .= "!/storage/framework/cache/.gitkeep\n";
        $gitignoreContent .= "/storage/framework/sessions/*\n";
        $gitignoreContent .= "!/storage/framework/sessions/.gitkeep\n";
        $gitignoreContent .= "/storage/framework/testing/*\n";
        $gitignoreContent .= "!/storage/framework/testing/.gitkeep\n";
        $gitignoreContent .= "/storage/framework/views/*\n";
        $gitignoreContent .= "!/storage/framework/views/.gitkeep\n";
        $gitignoreContent .= "/storage/logs/*\n";
        $gitignoreContent .= "!/storage/logs/.gitkeep\n";
        $gitignoreContent .= "/storage/debugbar/\n";
        $gitignoreContent .= "/storage/installed\n";
        $gitignoreContent .= "/bootstrap/cache/*\n";
        $gitignoreContent .= "!/bootstrap/cache/.gitkeep\n";
        $gitignoreContent .= "/.env\n";
        $gitignoreContent .= "/.env.backup\n";
        $gitignoreContent .= "/.env.production\n";
        $gitignoreContent .= "/.phpunit.result.cache\n";
        $gitignoreContent .= "/Homestead.json\n";
        $gitignoreContent .= "/Homestead.yaml\n";
        $gitignoreContent .= "/auth.json\n";
        $gitignoreContent .= "/npm-debug.log\n";
        $gitignoreContent .= "/yarn-error.log\n";
        $gitignoreContent .= "/.idea/\n";
        $gitignoreContent .= "/.vscode/\n";
        $gitignoreContent .= "/database/database.sqlite\n";
        $gitignoreContent .= "*.log\n";

        File::put($tempDir . DIRECTORY_SEPARATOR . '.gitignore', $gitignoreContent);
    }

    /**
     * Crée un fichier composer.json optimisé pour la production
     */
    private function createProductionComposer(string $tempDir): void
    {
        $this->info('📦 Création du composer.json de production...');
        
        $originalComposer = json_decode(File::get(base_path('composer.json')), true);
        
        // Supprimer les dépendances de développement
        unset($originalComposer['require-dev']);
        
        // Optimiser les scripts pour la production
        $originalComposer['scripts'] = [
            'post-autoload-dump' => [
                'Illuminate\\Foundation\\ComposerScripts::postAutoloadDump',
                '@php artisan package:discover --ansi'
            ],
            'post-update-cmd' => [
                '@php artisan vendor:publish --tag=laravel-assets --ansi --force'
            ],
            'post-root-package-install' => [
                '@php -r "file_exists(\'.env\') || copy(\'.env.example\', \'.env\');"'
            ],
            'post-create-project-cmd' => [
                '@php artisan key:generate --ansi',
                '@php -r "file_exists(\'database/database.sqlite\') || touch(\'database/database.sqlite\');"',
                '@php artisan migrate --ansi'
            ]
        ];
        
        // Ajouter des configurations d'optimisation
        $originalComposer['config'] = array_merge($originalComposer['config'] ?? [], [
            'optimize-autoloader' => true,
            'preferred-install' => 'dist',
            'sort-packages' => true
        ]);
        
        File::put(
            $tempDir . DIRECTORY_SEPARATOR . 'composer.json', 
            json_encode($originalComposer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Crée le fichier ZIP
     */
    private function createZip(string $tempDir): string
    {
        $this->info('🗜️  Création du fichier ZIP...');
        
        $outputPath = $this->option('output');
        if (!$outputPath) {
            $timestamp = date('Y-m-d_H-i-s');
            $outputPath = base_path("centralpanel-prod-{$timestamp}.zip");
        }

        // S'assurer que le dossier de destination existe
        $outputDir = dirname($outputPath);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        $zip = new ZipArchive();
        $result = $zip->open($outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($result !== TRUE) {
            throw new \Exception("Impossible de créer le fichier ZIP : {$outputPath}");
        }

        // Ajouter tous les fichiers du dossier temporaire au ZIP
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($tempDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                $relativePath = str_replace($tempDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $zip->addEmptyDir($relativePath);
            } else {
                $relativePath = str_replace($tempDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $zip->addFile($file->getPathname(), $relativePath);
            }
        }

        // Ajouter un fichier README pour l'installation
        $readmeContent = "# CentralCorp Panel - Build de Production\n\n";
        $readmeContent .= "## Installation\n\n";
        $readmeContent .= "1. Décompressez ce fichier sur votre serveur\n";
        $readmeContent .= "2. Configurez votre serveur web pour pointer vers le dossier `public`\n";
        $readmeContent .= "3. Assurez-vous que PHP " . InstallController::MIN_PHP_VERSION . "+ est installé\n";
        $readmeContent .= "4. Installez Composer et exécutez : `composer install --no-dev --optimize-autoloader`\n";
        $readmeContent .= "5. Définissez les permissions appropriées sur les dossiers `storage` et `bootstrap/cache`\n";
        $readmeContent .= "6. Accédez à votre site pour commencer l'installation\n\n";
        $readmeContent .= "## Extensions PHP requises\n\n";
        foreach (InstallController::REQUIRED_EXTENSIONS as $extension) {
            $readmeContent .= "- {$extension}\n";
        }
        $readmeContent .= "\n## Support\n\n";
        $readmeContent .= "Pour plus d'informations, consultez la documentation du projet.\n";
        
        $zip->addFromString('INSTALLATION.md', $readmeContent);

        // Ajouter un script d'installation pour Linux/Unix
        $installScript = "#!/bin/bash\n\n";
        $installScript .= "echo \"🚀 Installation de CentralCorp Panel\"\n\n";
        $installScript .= "# Vérifier si Composer est installé\n";
        $installScript .= "if ! command -v composer &> /dev/null; then\n";
        $installScript .= "    echo \"❌ Composer n'est pas installé. Veuillez l'installer d'abord.\"\n";
        $installScript .= "    exit 1\n";
        $installScript .= "fi\n\n";
        $installScript .= "# Installer les dépendances\n";
        $installScript .= "echo \"📦 Installation des dépendances...\"\n";
        $installScript .= "composer install --no-dev --optimize-autoloader\n\n";
        $installScript .= "# Définir les permissions\n";
        $installScript .= "echo \"🔒 Configuration des permissions...\"\n";
        $installScript .= "chmod -R 755 storage\n";
        $installScript .= "chmod -R 755 bootstrap/cache\n";
        $installScript .= "chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true\n\n";
        $installScript .= "echo \"✅ Installation terminée !\"\n";
        $installScript .= "echo \"👉 Configurez votre serveur web et accédez à votre site pour commencer l'installation.\"\n";
        
        $zip->addFromString('install.sh', $installScript);

        // Ajouter un script d'installation pour Windows
        $installBat = "@echo off\n";
        $installBat .= "echo 🚀 Installation de CentralCorp Panel\n\n";
        $installBat .= "REM Vérifier si Composer est installé\n";
        $installBat .= "composer --version >nul 2>&1\n";
        $installBat .= "if %errorlevel% neq 0 (\n";
        $installBat .= "    echo ❌ Composer n'est pas installé. Veuillez l'installer d'abord.\n";
        $installBat .= "    pause\n";
        $installBat .= "    exit /b 1\n";
        $installBat .= ")\n\n";
        $installBat .= "REM Installer les dépendances\n";
        $installBat .= "echo 📦 Installation des dépendances...\n";
        $installBat .= "composer install --no-dev --optimize-autoloader\n\n";
        $installBat .= "echo ✅ Installation terminée !\n";
        $installBat .= "echo 👉 Configurez votre serveur web et accédez à votre site pour commencer l'installation.\n";
        $installBat .= "pause\n";
        
        $zip->addFromString('install.bat', $installBat);

        $zip->close();

        return $outputPath;
    }
}
