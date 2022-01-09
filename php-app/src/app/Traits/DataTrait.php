<?php

namespace App\Traits;

trait DataTrait {
    /**
     * Request POST to api: http://api.frisbo.ro/v1/auth/login
     * @return $response => return decoded json user data after login
     */
    public function getUserData() 
    {
        // create GuzzleHttp object
        // verify = false => ignores invalid SSL certificate
        $client = new \GuzzleHttp\Client(['verify' => false ]);

        // create var that holds the api URL
        $URL = 'http://api.frisbo.ro/v1/auth/login';

        // create the GuzzleHttp request using post method 
        // with user credentials data
        // sent to api URL
        $response = $client->request('POST', $URL, [
            // 'headers' => [
            //     'Accept' => 'application/json',
            //     'Content-Type' => 'application/json'
            // ],
            'form_params' => [
                'email' => 'takeathome@frisbo.ro',
                'password' => 'TakeAtHomeFris2021'
            ]
        ]);

        // return data
        return json_decode($response->getBody(), true);
    }

    /**
     * Request GET data from api: https://api.frisbo.ro/v1/organizations
     * @param string $userToken => param accepts a user token for authorization bearer
     * @return $response => return all organizations data
     */
    public function getOrganizationsData($userToken)
    {
        // create GuzzleHttp object
        // verify = false => ignores invalid SSL certificate
        $client = new \GuzzleHttp\Client(['verify' => false ]);

        // create var that holds the api URL
        $URL = 'https://api.frisbo.ro/v1/organizations';

        // create the GuzzleHttp request using get method
        // sent to api URL with Authorization Bearer token taken from $user
        $response = $client->request('GET', $URL, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $userToken,
            ],
        ]);

        // return data
        return json_decode($response->getBody(), true);
    }
}