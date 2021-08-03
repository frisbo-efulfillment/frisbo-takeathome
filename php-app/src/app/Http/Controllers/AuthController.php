<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    /**
     * Will attempt to retrieve access token from cache
     * or make an api request if not in cache
     *
     * @return array Response
     */
    public function getAccessToken(): array
    {
        if (Cache::has('access_token')) {
            return $this->respondSuccessAuthentication(Cache::get('access_token'));
        } else {
            try {
                $credentials = [
                    'email' => env('API_EMAIL', ''),
                    'password' => env('API_PASSWORD', '')
                ];
                $response = Http::baseUrl(env('API_BASE_URI', ''))->post('auth/login', $credentials);

                $content = json_decode($response->body(), true);
                Cache::put('access_token', $content['access_token'], floor($content['expires_in'] / 60));

                return $this->respondSuccessAuthentication($content['access_token']);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return $this->respondFailedAuthentication($e->getMessage());
            }
        }
    }

    /**
     * Will return an error response containing error message
     *
     * @param string $message
     * @return array Response
     */
    protected function respondFailedAuthentication(string $message): array
    {
        return [
            'success' => false,
            'error' => $message
        ];
    }

    /**
     * Will return a success response containing authentication token
     *
     * @param string $access_token
     * @return array Response
     */
    protected function respondSuccessAuthentication(string $access_token): array
    {
        return [
            'success' => true,
            'access_token' => $access_token
        ];
    }
}
