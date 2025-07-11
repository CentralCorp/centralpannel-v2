<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Updates\UpdateManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function index(Request $request)
    {
        $currentVersion = config('app.version', '0.0.0');
        $manager = new UpdateManager(new Filesystem(), $currentVersion);
        $info = $manager->fetchUpdateInfo();
        $hasUpdate = $manager->hasUpdate($info);
        return view('admin.update', [
            'info' => $info,
            'hasUpdate' => $hasUpdate,
            'currentVersion' => $currentVersion,
        ]);
    }

    public function update(Request $request)
    {
        $currentVersion = config('app.version', '0.0.0');
        $manager = new UpdateManager(new Filesystem(), $currentVersion);
        try {
            $updated = $manager->updateIfAvailable();
            if ($updated) {
                return redirect()->back()->with('success', 'Mise à jour installée avec succès.');
            } else {
                return redirect()->back()->with('info', 'Aucune mise à jour disponible.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }
}
