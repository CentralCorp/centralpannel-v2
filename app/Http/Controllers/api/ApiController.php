<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\OptionsGeneral;
use App\Models\OptionsServer;
use App\Models\OptionsSecurity;
use App\Models\OptionsUI;
use App\Models\OptionsRPC;
use App\Models\OptionsLoader;
use App\Models\OptionsIgnore;
use App\Models\OptionsWhitelist;
use App\Models\OptionsWhitelistRole;
use App\Models\OptionsBg;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getOptions()
    {
        $domain = request()->getHost();
        $protocol = request()->isSecure() ? 'https' : 'http';
        $port = request()->getPort();
        $baseURL = $protocol . '://' . $domain . (($port && !in_array($port, [80, 443])) ? ":$port" : '');

        // Récupérer toutes les options
        $general = OptionsGeneral::first();
        $security = OptionsSecurity::first();
        $ui = OptionsUI::first();
        $rpc = OptionsRPC::first();
        $loader = OptionsLoader::first();
        $server = OptionsServer::where('is_default', true)->first();
        $ignored = OptionsIgnore::pluck('folder_name')->toArray();
        $whitelist = OptionsWhitelist::pluck('users')->toArray();
        $whitelistRoles = OptionsWhitelistRole::pluck('role')->toArray();
        $roles = OptionsBg::all();

        // Formater les données des rôles
        $roleData = [];
        foreach ($roles as $role) {
            $roleData['role' . $role->id] = [
                'name' => $role->role_name,
                'background' => $baseURL . '/storage/' . $role->image_path
            ];
        }

        $data = [
            "maintenance" => $security ? (bool)$security->maintenance : false,
            "maintenance_message" => $security ? $security->maintenance_message : "Please define a maintenance message",
            "game_version" => $loader ? $loader->minecraft_version : "1.7.10",
            "client_id" => "",
            "verify" => $general ? (bool)$general->file_verification : true,
            "modde" => $general ? (bool)$general->mods_enabled : true,
            "java" => $general ? (bool)$general->embedded_java : true,
            "dataDirectory" => $general ? $general->game_folder_name : "centralcorp",
            "status" => [
                "nameServer" => $server ? $server->server_name : "Syphera",
                "ip" => $server ? $server->server_ip : "84.235.238.100",
                "port" => $server ? $server->server_port : 25566
            ],
            "loader" => [
                "type" => $loader ? $loader->loader_type : "forge",
                "build" => $loader ? $loader->loader_build_version : "1.7.10-10.13.4.1614-1.7.10",
                "enable" => $loader ? (bool)$loader->loader_activation : true
            ],
            "ram_min" => $general ? ($general->min_ram / 1024) : 2,
            "ram_max" => $general ? ($general->max_ram / 1024) : 4,
            "online" => "true",
            "game_args" => [],
            "money" => $general ? (bool)$general->money_display : true,
            "role" => $general ? (bool)$general->role_display : true,
            "splash" => $ui ? $ui->splash : "Ceci est du code",
            "splash_author" => $ui ? $ui->splash_author : "Riptiaz",
            "azauth" => $general ? $general->azuriom_url : null,
            "rpc_activation" => $rpc ? (bool)$rpc->rpc_activation : true,
            "rpc_id" => $rpc ? $rpc->rpc_id : "114425717056158109",
            "rpc_details" => $rpc ? $rpc->rpc_details : "Dans le launcher 👀",
            "rpc_state" => $rpc ? $rpc->rpc_state : "En exploration 👀",
            "rpc_large_image" => $rpc ? $rpc->rpc_large_image : "large",
            "rpc_large_text" => $rpc ? $rpc->rpc_large_text : "Minecraft",
            "rpc_small_image" => $rpc ? $rpc->rpc_small_image : "small",
            "rpc_small_text" => $rpc ? $rpc->rpc_small_text : "Multiplayer server",
            "rpc_button1" => $rpc ? $rpc->rpc_button1 : "Discord",
            "rpc_button1_url" => $rpc ? $rpc->rpc_button1_url : "https://discord.gg/VCmNXHvf77",
            "rpc_button2" => $rpc ? $rpc->rpc_button2 : "Site Web",
            "rpc_button2_url" => $rpc ? $rpc->rpc_button2_url : "https://conflictura.eu",
            "whitelist_activate" => $security ? (bool)$security->whitelist : false,
            "alert_activate" => $ui ? (bool)$ui->alert_activation : true,
            "alert_scroll" => $ui ? (bool)$ui->alert_scroll : true,
            "alert_msg" => $ui ? $ui->alert_msg : "Test",
            "video_activate" => $ui ? (bool)$ui->video_activation : true,
            "video_url" => $ui ? $this->extractYouTubeVideoId($ui->video_url) : "a336KPLjsZU",
            "video_type" => $ui ? $this->detectVideoType($ui->video_url) : "short",
            "email_verified" => $general ? (bool)$general->email_verified : false,
            "server_icon" => $server ? $baseURL . '/storage/' . $server->icon : "https://conflictura.eu/storage/server_icon/tp4hf0FQJTiSBfH30uOYy0XA0f1ieMvfFvl2VOBp.png",
            "role_data" => $roleData,
            "ignored" => $ignored,
            "whitelist" => $whitelist,
            "whitelist_roles" => $whitelistRoles
        ];

        return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function extractYouTubeVideoId($url)
    {
        if (strpos($url, 'youtube.com/shorts/') !== false) {
            $pattern = '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/';
        } else {
            $pattern = '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.*v=|v=)?([a-zA-Z0-9_-]{11})/';
        }
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? "";
    }

    private function detectVideoType($url)
    {
        return strpos($url, 'youtube.com/shorts/') !== false ? 'short' : 'normal';
    }
}