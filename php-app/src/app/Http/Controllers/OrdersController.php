<?php

namespace App\Http\Controllers;

use App\Services\Frisbo\Exception;
use App\Services\Frisbo\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    private Client $client;

    public function __construct(Client $client)
    {

        $this->client = $client;
    }

    public function get(Request $request): JsonResponse
    {
        try {
            return response()->json($this->client->getOrders($request->get('organization_id')), 200);
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }
    }
}
