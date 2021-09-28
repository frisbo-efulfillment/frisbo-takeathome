<?php

namespace App\Http\Controllers;

use App\Repositories\OrganizationsRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class OrganizationController extends Controller
{

    private $organizationRepo;

    public function __construct()
    {
        $this->organizationRepo = new OrganizationsRepository();
    }


    public function index(Request $request)
    {

        try {
            $response['data'] = $this->organizationRepo->listOrganizations($request);
            $response['isError'] = false;
            return response()->json($response, Response::HTTP_OK);
        }catch (Exception $e){
            $response['isError'] = true;
            $response['error'] = $e->getMessage();
            Log::error("Failed response" . $e->getMessage());
            return response()->json($response, Response::HTTP_OK);
        }
    }
    public function orders($organizationId, Request $request)
    {
        try {
            $response['data'] = $this->organizationRepo->orders($organizationId, $request);
            $response['isError'] = false;
            return response()->json($response, Response::HTTP_OK);
        }catch (Exception $e){
            $response['isError'] = true;
            $response['error'] = $e->getMessage();
            Log::error("Failed response" . $e->getMessage());
            return response()->json($response, Response::HTTP_OK);
        }
    }

}
