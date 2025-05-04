<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OptionsGeneral;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    public function updateGeneral(Request $request)
    {
        // Validation des champs
        $validator = Validator::make($request->all(), [
            'mods_enabled' => 'boolean',
            'file_verification' => 'boolean',
            'embedded_java' => 'boolean',
            'game_folder_name' => 'required|string|max:100',
            'email_verified' => 'boolean',
            'role_display' => 'nullable|integer',
            'money_display' => 'nullable|integer',
            'min_ram' => 'required|integer|min:512|max:65536',
            'max_ram' => 'required|integer|min:512|max:65536',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.general')
                ->withErrors($validator)
                ->withInput();
        }

        $options = OptionsGeneral::first();
        if ($options) {
            $options->update($request->all());
        }

        return redirect()->route('admin.general')->with('success', 'Options mises à jour avec succès !');
    }

    public function general()
    {
        // Récupérer les options générales
        $options = OptionsGeneral::first(); // Récupère la première entrée (si elle existe)

        // Si aucune option n'est trouvée, créer une nouvelle entrée par défaut
        if (!$options) {
            $options = OptionsGeneral::create([
                'mods_enabled' => 1,
                'file_verification' => 1,
                'embedded_java' => 0,
                'game_folder_name' => null,
                'email_verified' => 0,
                'role_display' => null,
                'money_display' => null,
                'azuriom_url' => null,
            ]);
        }

        return view('admin.general', compact('options'));
    }
}
