<?php

namespace App\Http\Controllers;

use App\Traits\DataTrait;
use Exception;

class OrdersController extends Controller
{
    use DataTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index($organizationId)
    {
        try {
            // get user data
            $user = $this->getUserData();

            // check if user response exist
            if ($user) {
                // create GuzzleHttp object
                // verify = false => ignores invalid SSL certificate
                $client = new \GuzzleHttp\Client(['verify' => false]);

                // check if param id is == 0 => that means it will return all orders from all orgs.
                if ($organizationId == 0) {
                    // get all organizations
                    $organizations = $this->getOrganizationsData($user['access_token']);

                    // create array that will contain the ids from all orgs. retreived
                    $organizationsIds = array();

                    // loop through organizations
                    foreach ($organizations as $organization) {
                        // push ids into $organizationsIds array
                        array_push($organizationsIds, $organization['organization_id']);
                    }

                    // create array that will contain all orders
                    $ordersList = array();

                    // loop throught organizations ids
                    foreach ($organizationsIds as $id) {
                        // for each org. id assign it to the API URL
                        // it will retrieve all orders for said org. id
                        $URL = 'https://api.frisbo.ro/v1/organizations/' . $id . '/orders';

                        // create the GuzzleHttp request using get method
                        // sent to api URL with Authorization Bearer token taken from $user
                        $response = $client->request('GET', $URL, [
                            'headers' => [
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                                'Authorization' => 'Bearer ' . $user['access_token'],
                            ],
                        ]);

                        // decode json response
                        $decodedUserData = json_decode($response->getBody(), true);

                        // push all orders found into the array
                        array_push($ordersList, $decodedUserData);
                    }

                    // create array that will contain all orders
                    $allOrders = array();

                    // loop through ordersList array
                    foreach ($ordersList as $order) {
                        // loop through each order data
                        foreach ($order['data'] as $ord) {
                            // loop through each organisation data
                            foreach ($organizations as $org) {
                                // check if order org. id == org. id
                                if ($org['organization_id'] == $ord['organization_id']) {
                                    // add org. alias inside each order json obj.
                                    $ord['alias'] = $org['alias'];
                                }
                            }
                            // push all orders from inside the $order['data'] into the array
                            array_push($allOrders, $ord);
                        }
                    }

                    // return allOrders
                    return json_encode(array('data' => $allOrders));

                // else if the param id is bigger than 0
                } elseif ($organizationId > 0) {
                    // assign the param id to the API URL
                    // and retrieve all orders for the org. with the id == param
                    $URL = 'https://api.frisbo.ro/v1/organizations/' . $organizationId . '/orders';

                    // create the GuzzleHttp request using get method
                    // sent to api URL with Authorization Bearer token taken from $user
                    $response = $client->request('GET', $URL, [
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Bearer ' . $user['access_token'],
                        ],
                    ]);

                    // return data
                    return json_decode($response->getBody(), true);

                // else throw error message
                } else {
                    throw new \InvalidArgumentException('Invalid ID');
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
