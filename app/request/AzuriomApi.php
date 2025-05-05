<?php

namespace App\Request;

use Illuminate\Support\Facades\Http;
use App\Models\OptionsGeneral;

class AzuriomApi
{
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $options = OptionsGeneral::first();
        if (!$options) {
            throw new \RuntimeException('Les options générales ne sont pas configurées. Veuillez configurer l\'URL Azuriom et la clé API dans les paramètres généraux.');
        }

        if (!$options->azuriom_url || !$options->azuriom_api_key) {
            throw new \RuntimeException('L\'URL Azuriom et la clé API doivent être configurées dans les paramètres généraux.');
        }

        $this->baseUrl = rtrim($options->azuriom_url, '/');
        $this->apiKey = $options->azuriom_api_key;
    }

    private function makeRequest($endpoint)
    {
        return Http::withOptions([
            'verify' => false
        ])->withHeaders([
            'API-Key' => $this->apiKey
        ])->get($this->baseUrl . $endpoint);
    }

    public function getServers()
    {
        return $this->makeRequest('/api/apiextender/servers');
    }

    public function getRoles()
    {
        $response = $this->makeRequest('/api/apiextender/roles');
        if ($response->successful()) {
            return $response->json();
        }
        return [];
    }

    public function getUsers()
    {
        $response = $this->makeRequest('/api/apiextender/users');
        if ($response->successful()) {
            return $response->json();
        }
        return [];
    }

    public function getMoney()
    {
        return $this->makeRequest('/api/apiextender/money');
    }

    public function getSocial()
    {
        return $this->makeRequest('/api/apiextender/social');
    }
}
