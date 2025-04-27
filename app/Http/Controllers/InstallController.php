<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Http\Request;

class InstallController extends Controller
{
    private function ensureDatabaseExists()
    {
        $databasePath = database_path('database.sqlite');

        if (!file_exists($databasePath)) {
            file_put_contents($databasePath, '');
        }
    }

    public function showInstallForm()
    {
        $this->ensureDatabaseExists();

        if (Schema::hasTable('users') && User::count() > 0) {
            return redirect('/login')->with('status', 'Le système est déjà installé.');
        }

        return view('install');
    }

    public function install(Request $request)
{
    $this->ensureDatabaseExists();

    Artisan::call('migrate', [
        '--force' => true,
    ]);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect('/login')->with('status', 'Installation terminée. Vous pouvez vous connecter.');
}

}
