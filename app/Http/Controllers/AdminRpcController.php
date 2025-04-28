<?php

namespace App\Http\Controllers;

use App\Models\OptionsRPC;
use Illuminate\Http\Request;

class AdminRpcController extends Controller
{
    public function show()
    {
        $rpcOptions = OptionsRPC::first();
        if (!$rpcOptions) {
            $rpcOptions = OptionsRPC::create([
                'rpc_activation' => 1,
                'rpc_id' => '1144257170561581097',
                'rpc_details' => 'Dans le launcher',
                'rpc_state' => 'En exploration',
                'rpc_large_text' => 'Minecraft',
                'rpc_small_text' => 'Multiplayer server',
                'rpc_button1' => 'Discord',
                'rpc_button1_url' => 'https://discord.gg/VCmNXHvf77',
                'rpc_button2' => 'Site Web',
                'rpc_button2_url' => 'https://conflictura.eu',
            ]);
        }

        return view('admin.rpc', compact('rpcOptions'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'rpc_activation' => 'boolean',
            'rpc_id' => 'required|string|max:100',
            'rpc_details' => 'required|string|max:255',
            'rpc_state' => 'required|string|max:255',
            'rpc_large_text' => 'required|string|max:255',
            'rpc_small_text' => 'required|string|max:255',
            'rpc_button1' => 'nullable|string|max:50',
            'rpc_button1_url' => 'nullable|url|max:200',
            'rpc_button2' => 'nullable|string|max:50',
            'rpc_button2_url' => 'nullable|url|max:200',
        ]);

        $rpcOptions = OptionsRPC::first();

        if ($rpcOptions) {
            $rpcOptions->update($request->all());
        }

        return redirect()->route('admin.rpc')->with('success', 'Options RPC mises à jour avec succès !');
    }
}
