<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FrisboClient
{
    private string $baseUrl = 'https://api.frisbo.ro/v1';
    private string $token;
    private array $organizationMapping = [];

    public function __construct()
    {
        $this->token = $this->authenticate();
        $this->createOrganizationMapping();
    }

    private function createOrganizationMapping(): void
    {
        $organizations = $this->getOrganizations();
        foreach ($organizations as $org) {
            $this->organizationMapping[$org['organization_id']] = $org['alias'] ?? $org['name'] ?? 'Unknown';
        }
    }

    /**
     * @return string
     */
    private function authenticate(): string
    {
        $response = Http::post($this->baseUrl . '/auth/login', [
            'email' => config('services.frisbo.email'),
            'password' => config('services.frisbo.password'),
        ]);

        return $response->json('access_token');
    }

    /**
     * @return array
     *
     */
    public function getOrganizations(): array
    {
        return $this->request('GET', '/organizations');
    }

    /**
     * @param int|null $organizationId
     * @return array
     */
    public function getOrders(?int $organizationId = null): array
    {
        $orders = $organizationId
            ? $this->getOrdersForOrganization($organizationId)
            : $this->getOrdersForAllOrganizations();

        return $this->attachOrganizationNames($orders);
    }


    /**
     * @param array $orders
     * @return array
     */
    private function attachOrganizationNames(array $orders): array
    {
        return array_map(function($order) {
            $order['organization_name'] = $this->organizationMapping[$order['organization_id']] ?? 'Unknown';
            return $order;
        }, $orders);
    }


    /**
     *
     * @param int $organizationId
     * @return array
     */
    private function getOrdersForOrganization(int $organizationId): array
    {
        $response = $this->request('GET', "/organizations/{$organizationId}/orders");
        return $response['data'] ?? [];
    }




    /**
     *
     * @return array
     */
    private function getOrdersForAllOrganizations(): array
    {
        $orders = [];
        foreach (array_keys($this->organizationMapping) as $orgId) {
            $orders = array_merge($orders, $this->getOrdersForOrganization($orgId));
        }
        return $orders;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    private function request(string $method, string $endpoint, array $data = []): array
    {
        $response = Http::withToken($this->token)->$method($this->baseUrl . $endpoint, $data);
        return $response->json();
    }
}
