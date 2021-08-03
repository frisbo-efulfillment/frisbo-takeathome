<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class OrganizationController extends Controller
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

    public function getAll()
    {
        $response = Http::baseUrl(env('API_BASE_URI'))->withToken($this->access_token)->get('organizations');
        if ($response->status() === 200) {
            return $response->body();
        } else {
            //do something with failed requests
            return '';
        }
    }
}
