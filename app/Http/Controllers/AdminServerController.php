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
                $isFirstServer = true;
                $hasDefaultServer = OptionsServer::where('is_default', true)->exists();
                
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

                    // Si aucun serveur n'est par défaut, définir le premier comme par défaut
                    if (!$hasDefaultServer && $isFirstServer) {
                        $serverModel->is_default = true;
                        $serverModel->save();
                        $hasDefaultServer = true;
                    }

                    $defaultServers[$server['id']] = $serverModel->is_default;
                    $isFirstServer = false;
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

        \Log::info('Tentative de définition du serveur par défaut', ['server_id' => $request->server_id]);

        // Mettre à jour tous les serveurs pour désélectionner le serveur par défaut
        $updatedCount = OptionsServer::where('is_default', true)->update(['is_default' => false]);
        \Log::info('Serveurs désélectionnés', ['count' => $updatedCount]);

        // Mettre à jour le serveur sélectionné comme serveur par défaut
        $server = OptionsServer::where('server_id', $request->server_id)->first();
        if ($server) {
            $server->is_default = true;
            $saved = $server->save();
            
            \Log::info('Serveur par défaut mis à jour', [
                'server_id' => $request->server_id, 
                'server_name' => $server->server_name,
                'saved' => $saved,
                'is_default' => $server->is_default
            ]);
            
            // Vérification supplémentaire
            $verification = OptionsServer::where('server_id', $request->server_id)->first();
            \Log::info('Vérification après sauvegarde', [
                'server_id' => $verification->server_id,
                'is_default' => $verification->is_default
            ]);
            
            return redirect()->route('admin.server')->with('success', "Le serveur \"{$server->server_name}\" a été défini comme serveur par défaut.");
        }

        \Log::error('Serveur non trouvé', ['server_id' => $request->server_id]);
        return redirect()->route('admin.server')->with('error', 'Serveur non trouvé.');
    }
}
