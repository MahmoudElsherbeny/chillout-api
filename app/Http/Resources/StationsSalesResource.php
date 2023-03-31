<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class StationsSalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'key' => $this->id,
            'station' => $this->user->name,
            'sales' => $this->sales,
            'last_update' => $this->updated_at->format('Y-m-d g:i a'),
            'by' =>$this->created_by,
        ];
    }
}
