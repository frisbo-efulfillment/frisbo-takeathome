<?php

namespace App\Http\Resources\Organization;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'organization_id' => $this['organization_id'] ?? null,
            'is_active' => isset($this['is_active']) ? (bool)$this['is_active'] : null,
            'name' => $this['name'] ?? null,
            'alias' => $this['alias'] ?? null,
            'website' => $this['website'] ?? null,
            'first_run_flag' => isset($this['first_run_flag']) ? (bool)$this['first_run_flag'] : null,
            'created_at' => $this['created_at'] ?? null,
            'updated_at' => $this['updated_at'] ?? null,
            'vat_registration_number' => $this['vat_registration_number'] ?? null,
            'trade_register_registration_number' => $this['trade_register_registration_number'] ?? null,
            'description' => $this['description'] ?? null,
            'contract_start_date' => $this['contract_start_date'] ?? null,
            'contract_end_date' => $this['contract_end_date'] ?? null,
            'address_id' => $this['address_id'] ?? null,
            'contact_id' => $this['contact_id'] ?? null,
            'contact' => $this['contact'] ? new ContactResource($this['contact']) : null,
            'address' => $this['address'] ? new AddressResource($this['address']) : null,
        ];
    }


}
