<?php

namespace App\Http\Controllers;

use App\Models\OptionsServer;
use Illuminate\Http\Request;

class AdminServerController extends Controller
{
    public function show()
    {
        $serverOptions = OptionsServer::first();
        if (!$serverOptions) {
            $serverOptions = OptionsServer::create([
                'server_name' => 'Craftok',
                'server_ip' => 'craftok.fr',
                'server_port' => '25565',
                'icon' => null, // Ajouter l'icône par défaut
            ]);
        }

        return view('admin.server', compact('serverOptions'));
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
}
