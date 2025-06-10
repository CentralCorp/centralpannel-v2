<?php

namespace App\Http\Controllers;

use App\Request\AzuriomApi;
use Illuminate\Http\Request;

class AzuriomApiController extends Controller
{
    private $azuriomApi;

    public function __construct()
    {
        $this->azuriomApi = new AzuriomApi();
    }

    public function getServers()
    {
        $response = $this->azuriomApi->getServers();
        
        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->json()
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Impossible de récupérer la liste des serveurs'
        ], $response->status());
    }

    public function getRoles()
    {
        return $this->azuriomApi->getRoles();
    }

    public function getUsers()
    {
        return $this->azuriomApi->getUsers();
    }

    public function getMoney()
    {
        return $this->azuriomApi->getMoney();
    }

    public function getSocial()
    {
        return $this->azuriomApi->getSocial();
    }
} 