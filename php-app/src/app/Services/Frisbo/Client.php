<?php


namespace App\Services\Frisbo;

use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class Client
{
    public function getCachedAccessTokenOrAcquireNew(): string
    {
        $email = Config::get('frisbo.email');
        $password = Config::get('frisbo.password');

        if (!trim($email) || !trim($password)) {
            throw new Exception('Incomplete credentials provided for Frisbo api');
        }

        return Cache::get('accessToken', function () use ($email, $password) {
            $response = Http::asJson()->post(Config::get('frisbo.login'), [
                'email' => $email,
                'password' => $password,
            ]);

            Cache::put('accessToken', $response->json('access_token'), $response->json('expires_in'));

            return $response->json('access_token');
        });
    }

    public function getOrganizations(): array
    {
        $token = $this->getCachedAccessTokenOrAcquireNew();

        $response = Http::asJson()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])
            ->get(Config::get('frisbo.get_organizations'));

        if ($response->status() >= 400) {
            throw new Exception('Error while getting organizations.', $response->status());
        }

        return $response->json();
    }

    public function getOrders(string $organizationId = null): array
    {
        if (!$organizationId) {
            $organizationIds = $this->getAllOrganizationIdsForAuthenticatedUser();
        } else {
            $organizationIds = [$organizationId];
        }

        $responses = $this->getOrdersForOrganizationIdsConcurrently($organizationIds);

        return collect($responses)
            ->flatten(1)
            ->sortByDesc('ordered_date')
            ->take(100)
            ->values()
            ->toArray();
    }

    private function getAllOrganizationIdsForAuthenticatedUser(): array
    {
        return array_map(fn($org) => $org['organization_id'], $this->getOrganizations());
    }

    private function getOrdersForOrganizationIdsConcurrently(array $organizationIds): array
    {
        $token = $this->getCachedAccessTokenOrAcquireNew();

        $responses = Http::pool(function (Pool $pool) use ($organizationIds, $token) {
                return array_map(
                    function ($id) use ($pool, $token) {
                        $pool->asJson()
                            ->withHeaders(['Authorization' => 'Bearer ' . $token])
                            ->get(str_replace(':organizationId', $id, Config::get('frisbo.get_orders')));
                    }, $organizationIds);
            });

        return array_map(function (Response $response) {
            if (!$response->ok()) {
                throw new Exception('Error while getting orders for your organization.', $response->status());
            }
            return $response->json('data');
        }, $responses);
    }
}

