<?php

namespace App\Updates;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use RuntimeException;

class UpdateManager
{
    protected Filesystem $files;
    protected string $apiUrl = 'https://market.vexato.tech/api/download/panel';
    protected string $currentVersion;

    public function __construct(Filesystem $files, string $currentVersion)
    {
        $this->files = $files;
        $this->currentVersion = $currentVersion;
    }

    /**
     * Fetch update info from remote API
     */
    public function fetchUpdateInfo(): ?array
    {
        try {
            $response = Http::timeout(10)->get($this->apiUrl);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('UpdateManager fetch error: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Check if a new version is available
     */
    public function hasUpdate(?array $info = null): bool
    {
        $info = $info ?: $this->fetchUpdateInfo();
        if (!$info || empty($info['version'])) {
            return false;
        }
        return version_compare($info['version'], $this->currentVersion, '>');
    }

    /**
     * Download the update zip file
     */
    public function downloadUpdate(array $info): string
    {
        $updatesPath = storage_path('app/updates/');
        if (!$this->files->exists($updatesPath)) {
            $this->files->makeDirectory($updatesPath, 0755, true);
        }
        $filePath = $updatesPath . $info['file'];
        if ($this->files->exists($filePath)) {
            $this->files->delete($filePath);
        }
        $response = Http::timeout(60)->withOptions(['sink' => $filePath])->get($info['url']);
        if (!$response->successful()) {
            throw new RuntimeException('Failed to download update file.');
        }
        // Verify hash
        if (!empty($info['hash']) && hash_file('sha256', $filePath) !== $info['hash']) {
            $this->files->delete($filePath);
            throw new RuntimeException('Downloaded file hash mismatch.');
        }
        return $filePath;
    }

    /**
     * Install the update zip file
     */
    public function installUpdate(string $zipPath): void
    {
        if (!is_writable(base_path())) {
            throw new RuntimeException('Base path is not writable.');
        }
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new RuntimeException('Unable to open zip file.');
        }
        if (!$zip->extractTo(base_path())) {
            $zip->close();
            throw new RuntimeException('Unable to extract zip file.');
        }
        $zip->close();
        $this->files->delete($zipPath);
        // Run migrations and clear cache
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('cache:clear');
    }

    /**
     * Main update workflow
     */
    public function updateIfAvailable(): bool
    {
        $info = $this->fetchUpdateInfo();
        if ($this->hasUpdate($info)) {
            $zipPath = $this->downloadUpdate($info);
            $this->installUpdate($zipPath);
            return true;
        }
        return false;
    }
}
