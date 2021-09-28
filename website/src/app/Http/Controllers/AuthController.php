<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use phpDocumentor\Reflection\Types\Null_;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{


    public function __construct()
    {

    }


    public function login( Request $request)
    {

        $client = app(Client::class);
        $url = 'http://api.frisbo.ro/v1/auth/login';
        $params['headers'] = ['Content-Type' => 'application/json'];
        $response = NULL;

        $data = $request->only(['email', 'password']);

        try {
            $response = $client->post($url, ['json' => $data]);
            $data = json_decode($response->getBody()->getContents(), true);
            return response()->json($data, Response::HTTP_OK);

        }catch (Exception $e){
            Log::error("Failed response" . $e->getMessage());
            return response()->json($response, Response::HTTP_OK);
        }

    }

}
