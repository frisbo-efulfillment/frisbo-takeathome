<?php

namespace App\Http\Controllers;

use App\Services\Frisbo\Client;
use App\Services\Frisbo\Exception;
use Illuminate\Http\JsonResponse;

class OrganizationsController extends Controller
{
    private Client $client;

    public function __construct(Client $client)
    {

        $this->client = $client;
    }

    public function get(): JsonResponse
    {
        try {
            return response()->json($this->client->getOrganizations(), 200);
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }
    }
}
