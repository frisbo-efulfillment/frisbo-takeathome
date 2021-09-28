<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Illuminate\Support\Facades\Session;

class BaseRepository
{


    static function call($url, $method, $parameters, $token)
    {

        $client = app(Client::class);
        $params['headers'] = ['Content-Type' => 'application/json'];
        if ($token){
            $params['headers'] = [
                'Authorization' => 'Bearer ' . $token,
            ];
        }

        $response = null;

        try {
            switch ($method) {
                case "POST":
                    $response = $client->post($url, ['json' => $parameters]);
                    break;
                case "GET":
                    $response = $client->get($url, ['headers' => $params['headers']] );
                    break;
            }
            $data = json_decode($response->getBody()->getContents(), true);
            return $data;

        } catch (Exception $e) {
            Log::error("Failed response" . $e->getMessage());
            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function refreshToken()
    {
        return $this->post('http://api.frisbo.ro/v1/auth/login', ['email' => 'takeathome@frisbo.ro', 'password' => 'TakeAtHomeFris2021']);
    }

    public function post($url, $parameters, $token)
    {
        return self::call($url, "POST", $parameters, $token);
    }

    public function get($url, $parameters, $token)
    {
        return self::call($url, "GET", $parameters, $token);
    }
}
