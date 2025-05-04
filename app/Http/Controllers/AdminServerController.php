<?php

namespace App\Http\Controllers;

use App\Models\OptionsServer;
use App\Models\OptionsGeneral;
use App\Request\AzuriomApi;
use Illuminate\Http\Request;

class AdminServerController extends Controller
{
    private $azuriomApi;

    public function __construct()
    {
        try {
            $this->azuriomApi = new AzuriomApi();
        } catch (\RuntimeException $e) {
            // On ne fait rien ici, on gérera l'erreur dans la méthode show()
        }
    }

    public function show()
    {
        $options = OptionsGeneral::first();
        $servers = [];
        $error = null;
        $defaultServers = [];

        try {
            if (!$this->azuriomApi) {
                $this->azuriomApi = new AzuriomApi();
            }

            // Récupérer la liste des serveurs depuis l'API Azuriom
            $serversResponse = $this->azuriomApi->getServers();
            if ($serversResponse->successful()) {
                $servers = $serversResponse->json();
                
                // Synchroniser les serveurs dans la base de données
                foreach ($servers as $server) {
                    $serverModel = OptionsServer::updateOrCreate(
                        ['server_id' => $server['id']],
                        [
                            'server_name' => $server['name'],
                            'server_ip' => $server['address'],
                            'server_port' => (string)$server['port'],
                            'icon' => $server['icon'] ?? null,
                            'type' => $server['type']
                        ]
                    );

                    // Si c'est le premier serveur et qu'aucun n'est par défaut, le définir comme par défaut
                    if (count($servers) === 1 && !OptionsServer::where('is_default', true)->exists()) {
                        $serverModel->is_default = true;
                        $serverModel->save();
                    }

                    $defaultServers[$server['id']] = $serverModel->is_default;
                }
            }
        } catch (\RuntimeException $e) {
            $error = $e->getMessage();
        }

        return view('admin.server', compact('servers', 'options', 'error', 'defaultServers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'server_name' => 'required|string|max:255',
            'server_ip' => 'required|string|max:255',
            'server_port' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'icône
        ]);

        $serverOptions = OptionsServer::first();

        // Gestion de l'upload de l'icône
        if ($request->hasFile('icon')) {
            // Supprimer l'ancienne icône si elle existe
            if ($serverOptions->icon) {
                \Storage::disk('uploads')->delete($serverOptions->icon);
            }

            // Enregistrer la nouvelle icône
            $path = $request->file('icon')->store('server_icon', 'uploads');
            // Stocker seulement le chemin relatif dans la base de données
            $serverOptions->icon = $path;
        }

        // Mettre à jour les autres options du serveur
        $serverOptions->update($request->except('icon')); // Ignorer l'icône ici, car elle a déjà été mise à jour

        return redirect()->route('admin.server')->with('success', 'Options Server mises à jour avec succès !');
    }

    public function setDefaultServer(Request $request)
    {
        $request->validate([
            'server_id' => 'required|integer'
        ]);

        // Mettre à jour tous les serveurs pour désélectionner le serveur par défaut
        OptionsServer::where('is_default', true)->update(['is_default' => false]);

        // Mettre à jour le serveur sélectionné comme serveur par défaut
        $server = OptionsServer::where('server_id', $request->server_id)->first();
        if ($server) {
            $server->is_default = true;
            $server->save();
        }

        return response()->json(['success' => true]);
    }
}
