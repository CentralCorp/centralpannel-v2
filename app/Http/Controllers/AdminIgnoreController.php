<?php

namespace App\Http\Controllers;

use App\Models\OptionsIgnore;
use Illuminate\Http\Request;

class AdminIgnoreController extends Controller
{
    public function index()
    {
        $folders = OptionsIgnore::all();

        $ignoreOptions = OptionsIgnore::first();
        return view('admin.ignore', compact('folders', 'ignoreOptions'));
    }

    public function store(Request $request)
    {
        $ignoreOptions = OptionsIgnore::first();

        if ($ignoreOptions) {
            $ignoreOptions->save();
        }

        if ($request->input('ignored_folders')) {
            $folders = explode(',', $request->input('ignored_folders'));
            foreach ($folders as $folder) {
                OptionsIgnore::create(['folder_name' => trim($folder)]);
            }
        }

        return redirect()->route('admin.ignore')->with('success', 'Dossiers ignorés mis à jour avec succès.');
    }

    public function destroyFolder($id)
    {
        OptionsIgnore::findOrFail($id)->delete();
        return redirect()->route('admin.ignore')->with('success', 'Dossier/fichier supprimé.');
    }
}
