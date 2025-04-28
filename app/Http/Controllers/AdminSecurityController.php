<?php

namespace App\Http\Controllers;
use App\Models\OptionsSecurity;
use Illuminate\Http\Request;

class AdminSecurityController extends Controller
{
    public function show()
    {
        $securityOptions = OptionsSecurity::first();
        if (!$securityOptions) {
            $securityOptions = OptionsSecurity::create([
                'maintenance' => 0,
                'maintenance_message' => 'Maintenance en cours.',
            ]);
        }

        return view('admin.security', compact('securityOptions'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'maintenance' => 'boolean',
            'maintenance_message' => 'required|string|max:255',
        ]);

        $SecurityOptions = OptionsSecurity::first();

        if ($SecurityOptions) {
            $SecurityOptions->update($request->all());
        }

        return redirect()->route('admin.security')->with('success', 'Options Security mises à jour avec succès !');
    }
}
