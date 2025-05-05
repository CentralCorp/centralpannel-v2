<?php

namespace App\Http\Controllers;

use App\Models\OptionsWhitelist;
use App\Models\OptionsWhitelistRole;
use Illuminate\Http\Request;
use App\Models\OptionsSecurity;
use App\Request\AzuriomApi;

class AdminWhitelistController extends Controller
{
    private $azuriomApi;

    public function __construct()
    {
        try {
            $this->azuriomApi = new AzuriomApi();
        } catch (\RuntimeException $e) {
            $this->azuriomApi = null;
        }
    }

    public function index()
    {
        $users = OptionsWhitelist::all();
        $roles = OptionsWhitelistRole::all();
        $azuriomRoles = [];
        $azuriomUsers = [];

        if ($this->azuriomApi) {
            $azuriomRoles = $this->azuriomApi->getRoles();
            $azuriomUsers = $this->azuriomApi->getUsers();
            
            // Filtrer les utilisateurs déjà dans la whitelist et les comptes supprimés
            $whitelistedUsers = $users->pluck('users')->toArray();
            $azuriomUsers = array_filter($azuriomUsers, function($user) use ($whitelistedUsers) {
                return !in_array($user['name'], $whitelistedUsers) && 
                       !str_starts_with($user['name'], 'Deleted #');
            });

            // Trier les utilisateurs : admins d'abord, puis par nom
            usort($azuriomUsers, function($a, $b) {
                if ($a['role']['is_admin'] && !$b['role']['is_admin']) return -1;
                if (!$a['role']['is_admin'] && $b['role']['is_admin']) return 1;
                return strcasecmp($a['name'], $b['name']);
            });

            // Filtrer les rôles déjà dans la whitelist
            $whitelistedRoles = $roles->pluck('role')->toArray();
            $azuriomRoles = array_filter($azuriomRoles, function($role) use ($whitelistedRoles) {
                return !in_array($role['name'], $whitelistedRoles);
            });

            // Trier les rôles : admins d'abord, puis par nom
            usort($azuriomRoles, function($a, $b) {
                if ($a['is_admin'] && !$b['is_admin']) return -1;
                if (!$a['is_admin'] && $b['is_admin']) return 1;
                return strcasecmp($a['name'], $b['name']);
            });
        }

        $securityOptions = OptionsSecurity::first();
        return view('admin.whitelist', compact('users', 'roles', 'securityOptions', 'azuriomRoles', 'azuriomUsers'));
    }

    public function store(Request $request)
    {
        $whitelistActivation = $request->input('whitelist');

        $securityOptions = OptionsSecurity::first();

        if ($securityOptions) {
            $securityOptions->whitelist = $whitelistActivation;
            $securityOptions->save();
        }

        // Gérer les utilisateurs sélectionnés
        if ($request->input('whitelist_users')) {
            foreach ($request->input('whitelist_users') as $username) {
                if (trim($username) !== '') {
                    OptionsWhitelist::create(['users' => trim($username)]);
                }
            }
        }

        // Gérer les rôles d'Azuriom sélectionnés
        if ($request->input('azuriom_roles')) {
            foreach ($request->input('azuriom_roles') as $role) {
                if (trim($role) !== '') {
                    OptionsWhitelistRole::create(['role' => trim($role)]);
                }
            }
        }

        return redirect()->route('admin.whitelist')->with('success', 'Whitelist mise à jour avec succès.');
    }

    public function destroyUser($id)
    {
        OptionsWhitelist::findOrFail($id)->delete();
        return redirect()->route('admin.whitelist')->with('success', 'Utilisateur supprimé.');
    }

    public function destroyRole($id)
    {
        OptionsWhitelistRole::findOrFail($id)->delete();
        return redirect()->route('admin.whitelist')->with('success', 'Rôle supprimé.');
    }
}
