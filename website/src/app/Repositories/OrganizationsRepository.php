<?php

namespace  App\Repositories;

use Illuminate\Http\Request;


class OrganizationsRepository extends BaseRepository
{

    public function listOrganizations(Request $request){
        return $this->get('https://api.frisbo.ro/v1/organizations', $request->all(), $request->bearerToken());
    }

    public function orders($organizationId, Request $request){
        $data = $this->get('https://api.frisbo.ro/v1/organizations/'.$organizationId.'/orders', $request->all(), $request->bearerToken());
        return $data['data'];
    }
}
