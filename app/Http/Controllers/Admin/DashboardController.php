<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SimplePie\SimplePie;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupérer le nombre d'utilisateurs
        $userCount = User::count();

        // Récupérer les notes de version depuis le flux RSS
        $releases = $this->getReleases();

        return view('admin.index', compact('userCount', 'releases'));
    }

    private function getReleases()
    {
        try {
            $response = Http::get('https://github.com/CentralCorp/CentralCorp-Panel/releases.atom');
            
            if (!$response->successful()) {
                return [];
            }

            $xml = simplexml_load_string($response->body());
            $releases = [];

            foreach ($xml->entry as $entry) {
                $releases[] = (object)[
                    'title' => (string)$entry->title,
                    'description' => strip_tags((string)$entry->content),
                    'date' => date('d/m/Y H:i', strtotime((string)$entry->updated)),
                    'author' => (string)$entry->author->name,
                    'link' => (string)$entry->link['href']
                ];
            }

            return $releases;
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des releases: ' . $e->getMessage());
            return [];
        }
    }
} 