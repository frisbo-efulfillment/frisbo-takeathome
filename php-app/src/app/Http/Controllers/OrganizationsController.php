<?php

namespace App\Http\Controllers;

use App\Traits\DataTrait;
use Exception;

class OrganizationsController extends Controller
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

    public function index() 
    {
        try {
            // get user data
            $user = $this->getUserData();
    
            // check if user response exist
            if ($user) {
                return $this->getOrganizationsData($user['access_token']);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
