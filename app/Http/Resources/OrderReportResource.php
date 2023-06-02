<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'city'                          => [
                'id'                           => $this->city_id,
                'name'                         => $this->city_name,
            ],
            'total_price_of_orders'         => $this->total_price_of_orders,
            'number_of_orders'              => $this->number_of_orders,
            'number_of_items'               => $this->number_of_items,
        ];
    }
}
