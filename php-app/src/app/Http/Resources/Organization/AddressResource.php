<?php

namespace App\Http\Resources\Organization;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'] ?? null,
            'name' => $this['name'] ?? null,
            'street' => $this['street'] ?? null,
            'city' => $this['city'] ?? null,
            'county' => $this['county'] ?? null,
            'country' => $this['country'] ?? null,
            'zip' => $this['zip'] ?? null,
            'hash' => $this['hash'] ?? null,
            'phone' => $this['phone'] ?? null,
            'locker' => $this['locker'] ?? null,
            'is_shipping_address' => $this['is_shipping_address'] ?? null,
            'is_billing_address' => $this['is_billing_address'] ?? null,
            'created_at' => $this['created_at'] ?? null,
            'updated_at' => $this['updated_at'] ?? null,
        ];
    }
}
