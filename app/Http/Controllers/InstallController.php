<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallController extends Controller
{
    public function dbConfiguration(): View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('install.db');
    }

    public function submitDbConfiguration(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'db_host' => 'required',
            'db_name' => 'required',
            'db_user' => 'required',
            'db_password' => 'required',
        ]);
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['db_connection' => 'Impossible de se connecter à la base de données.']);
        }

        return redirect()->route('install.user.creation');
    }

    public function userCreation(): View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('install.user');
    }

    public function submitUserCreation(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        return redirect('/')->with('success', 'Installation réussie !');
    }
}

