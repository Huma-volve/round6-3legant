<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'date'=> optional($this->created_at)->toISOString(),
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'total_price' => $this->total_price ?? 0,
        ];
    }
}
