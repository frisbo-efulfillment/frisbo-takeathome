<?php

namespace App\Http\Controllers;

use App\Http\Resources\FrontendOrderResource;
use App\Http\Resources\Organization\OrganizationResource;
use App\Services\FrisboClient;
use Illuminate\Http\Request;

class FrisboApiController extends Controller
{
    private FrisboClient $frisboClient;

    public function __construct(FrisboClient $frisboClient)
    {
        $this->frisboClient = $frisboClient;
    }

    /**
     * Get organizations
     *
     * @return OrganizationResource::collection
     */
    public function getOrganizations()
    {
        $organizations = $this->frisboClient->getOrganizations();

        return OrganizationResource::collection($organizations);
    }

    /**
     * Get orders
     *
     * @param Request $request
     * @return FrontendOrderResource::collection
     */
    public function getOrders(Request $request)
    {
        $organizationId = $request->input('organization_id');
        $orders = $this->frisboClient->getOrders($organizationId);
        return FrontendOrderResource::collection($orders);
    }
}
