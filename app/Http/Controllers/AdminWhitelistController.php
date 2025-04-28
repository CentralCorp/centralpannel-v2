<?php

namespace App\Http\Controllers;

use App\Models\OptionsWhitelist;
use App\Models\OptionsWhitelistRole;
use Illuminate\Http\Request;
use App\Models\OptionsSecurity;

class AdminWhitelistController extends Controller
{
    public function index()
    {
        $users = OptionsWhitelist::all();
        $roles = OptionsWhitelistRole::all();

        $securityOptions = OptionsSecurity::first();
        return view('admin.whitelist', compact('users', 'roles', 'securityOptions'));
    }

    public function store(Request $request)
    {

        $whitelistActivation = $request->input('whitelist');

        $securityOptions = OptionsSecurity::first();

        if ($securityOptions) {
            $securityOptions->whitelist = $whitelistActivation;
            $securityOptions->save();
        }

        if ($request->input('whitelist_users')) {
            $usernames = explode(',', $request->input('whitelist_users'));
            foreach ($usernames as $username) {
                OptionsWhitelist::create(['users' => trim($username)]);
            }
        }

        if ($request->input('whitelist_roles')) {
            $roles = explode(',', $request->input('whitelist_roles'));
            foreach ($roles as $role) {
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
