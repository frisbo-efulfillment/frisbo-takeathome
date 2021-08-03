<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    private $access_token = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //For sure there are better solutions, maybe a middleware or something like that
        $authenticator = new AuthController();
        $authenticatorResponse = $authenticator->getAccessToken();
        if ($authenticatorResponse['success']) {
            $this->access_token = $authenticatorResponse['access_token'];
        }
    }

    /**
     * Will return orders of an Organization or all of them if no id provided
     *
     * @param int|null $orgId Organization Id
     * @return string Orders
     */
    public function getById(int $orgId = null): string
    {
        $orgIds = !empty($orgId) ? [$orgId] : $this->getOrganizationIds();
        $orders = $this->getOrders($orgIds);

        return json_encode($orders, true);
    }

    /**
     * Will get from the api all available organizationIds
     *
     * @return array
     */
    private function getOrganizationIds(): array
    {
        $encodedData = Http::baseUrl(env('API_BASE_URI'))
            ->withToken($this->access_token)
            ->get('organizations');
        return array_column(json_decode($encodedData), 'organization_id');
    }

    /**
     * Will get all orders from api for given Organization Ids
     *
     * @param array $orgIds Organization Ids
     * @return array Orders
     */
    private function getOrders(array $orgIds): array
    {
        $responses = Http::withToken($this->access_token)
            ->pool(function (Pool $pool) use ($orgIds) {
                $requests = [];
                foreach ($orgIds as $id) {
                    $requests[] = $pool->withToken($this->access_token)
                        ->baseUrl(env('API_BASE_URI', ''))
                        ->get("organizations/{$id}/orders");
                }
                return $requests;
            });

        $orders = [];

        //Extract order info from responses
        foreach ($responses as $response) {
            if ($response->status() === 200) {
                $responseBody = json_decode($response->body(), true);
                array_push($orders, ...$responseBody['data']);
            } else {
                //do something with failed requests
            }
        }

        if (sizeof($orgIds) > 1) {
            $orders = collect($orders)->sortByDesc('ordered_date')->take(100)->values()->toArray();
        }

        return $orders;
    }
}
