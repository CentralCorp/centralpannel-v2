<?php

namespace App\Request;

use Illuminate\Support\Facades\Http;
use App\Models\OptionsGeneral;

class MarketApi
{
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $options = OptionsGeneral::first();
        if (!$options) {
            throw new \RuntimeException('Les options générales ne sont pas configurées. Veuillez configurer l\'URL Market et la clé API dans les paramètres généraux.');
        }

        if (!$options->market_api_key) {
            throw new \RuntimeException('La clé API Market doit être configurée dans les paramètres généraux.');
        }

        $this->baseUrl = 'http://localhost:8000'; // URL de base du marketplace
        $this->apiKey = $options->market_api_key;
    }

    private function makeRequest($endpoint, $method = 'GET', $data = [])
    {
        $response = Http::withOptions([
            'verify' => false
        ])->withHeaders([
            'X-API-Key' => $this->apiKey,
            'Accept' => 'application/json',
        ]);

        if ($method === 'GET') {
            $response = $response->get($this->baseUrl . $endpoint);
        } elseif ($method === 'POST') {
            $response = $response->post($this->baseUrl . $endpoint, $data);
        }

        if (!$response->successful()) {
            throw new \RuntimeException('Erreur API Market: ' . $response->body());
        }

        return $response->json();
    }

    public function getMarketList()
    {
        try {
            return $this->makeRequest('/api/market/list');
        } catch (\Exception $e) {
            throw new \RuntimeException('Impossible de récupérer la liste du marketplace: ' . $e->getMessage());
        }
    }

    public function getMarketItem($id)
    {
        try {
            return $this->makeRequest("/api/market/{$id}");
        } catch (\Exception $e) {
            throw new \RuntimeException('Impossible de récupérer l\'élément du marketplace: ' . $e->getMessage());
        }
    }

    public function downloadMarketItem($id)
    {
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . "/api/market/{$id}/download");

            if (!$response->successful()) {
                throw new \RuntimeException('Erreur lors du téléchargement: ' . $response->body());
            }

            return $response->body();
        } catch (\Exception $e) {
            throw new \RuntimeException('Impossible de télécharger l\'élément: ' . $e->getMessage());
        }
    }
}
