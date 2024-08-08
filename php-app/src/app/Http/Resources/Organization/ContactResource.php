<?php

namespace App\Http\Resources\Organization;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'person_id' => $this['person_id'] ?? null,
            'name' => $this['name'] ?? null,
            'email' => $this['email'] ?? null,
            'phone' => $this['phone'] ?? null,
            'created_at' => $this['created_at'] ?? null,
            'updated_at' => $this['updated_at'] ?? null,
        ];
    }
}
