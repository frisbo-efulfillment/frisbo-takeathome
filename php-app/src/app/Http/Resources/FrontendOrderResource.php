<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FrontendOrderResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this['order_id'],
            'organization_name' => $this['organization_name'],
            'organization_id' => $this['organization_id'],
            'ordered_date' => $this['ordered_date'],
            'number_of_products' => count($this['products'] ?? []),
            'fulfillment_status' => $this['fulfillment']['status'] ?? 'Unknown',
        ];
    }
}
