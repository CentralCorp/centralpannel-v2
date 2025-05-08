<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\OptionsGeneral;
use App\Models\Server;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getOptions()
    {
        $domain = request()->getHost();
        $protocol = request()->isSecure() ? 'https' : 'http';
        $port = request()->getPort();
        $baseURL = $protocol . '://' . $domain . (($port && !in_array($port, [80, 443])) ? ":$port" : '');

        $options = OptionsGeneral::pluck('value', 'name');
        $server = Server::first();

        $data = [
            "maintenance" => false,
            "maintenance_message" => "Please define a maintenance message",
            "game_version" => "1.7.10",
            "client_id" => "",
            "verify" => true,
            "modde" => true,
            "java" => true,
            "dataDirectory" => "centralcorp",
            "status" => [
                "nameServer" => "Syphera",
                "ip" => "84.235.238.100",
                "port" => 25566
            ],
            "loader" => [
                "type" => "forge",
                "build" => "1.7.10-10.13.4.1614-1.7.10",
                "enable" => true
            ],
            "ram_min" => 2,
            "ram_max" => 4,
            "online" => "true",
            "game_args" => [],
            "money" => true,
            "role" => true,
            "splash" => "Ceci est du code",
            "splash_author" => "Riptiaz",
            "rpc_activation" => true,
            "rpc_id" => "114425717056158109",
            "rpc_details" => "Dans le launcher 👀",
            "rpc_state" => "En exploration 👀",
            "rpc_large_image" => "large",
            "rpc_large_text" => "Minecraft",
            "rpc_small_image" => "small",
            "rpc_small_text" => "Multiplayer server",
            "rpc_button1" => "Discord",
            "rpc_button1_url" => "https://discord.gg/VCmNXHvf77",
            "rpc_button2" => "Site Web",
            "rpc_button2_url" => "https://conflictura.eu",
            "whitelist_activate" => false,
            "alert_activate" => true,
            "alert_scroll" => true,
            "alert_msg" => "Test",
            "video_activate" => true,
            "video_url" => "a336KPLjsZU",
            "video_type" => "short",
            "email_verified" => false,
            "server_icon" => "https://conflictura.eu/storage/server_icon/tp4hf0FQJTiSBfH30uOYy0XA0f1ieMvfFvl2VOBp.png",
            "role_data" => [
                "role1" => [
                    "name" => "Admin",
                    "background" => "https://conflictura.eu/storage/role_backgrounds/uU8Vzu9V3bh8Y36xbZzxBtV2RrmLX2luNtI8lpvs.jpg"
                ]
            ],
            "ignored" => ["config", "options.txt"],
            "whitelist" => [],
            "whitelist_roles" => []
        ];

        return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function cleanImageUrl($imagePath, $baseURL)
    {
        return $baseURL . '/' . ltrim($imagePath, './');
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