<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\OptionsBg;
use App\Request\AzuriomApi;

class AdminBgController extends Controller
{
    private $azuriomApi;

    public function __construct(AzuriomApi $azuriomApi)
    {
        $this->azuriomApi = $azuriomApi;
    }

    public function index()
    {
        $roles = $this->azuriomApi->getRoles();
        $backgrounds = OptionsBg::all()->keyBy('role_id');
        
        return view('admin.bg', compact('roles', 'backgrounds'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
            'bg_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $role = collect($this->azuriomApi->getRoles())->firstWhere('id', $request->role_id);
        
        if (!$role) {
            return back()->with('error', 'Rôle non trouvé');
        }

        $path = $request->file('bg_image')->store('backgrounds', 'public');

        OptionsBg::updateOrCreate(
            ['role_id' => $request->role_id],
            [
                'image_path' => $path,
                'role_name' => $role['name']
            ]
        );

        return back()->with('success', 'Image de fond mise à jour avec succès');
    }
}