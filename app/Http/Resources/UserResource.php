<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $new_user =  [
            'key' => $this->id,
            'station' => $this->name,
            'email' => $this->email,
            'type' => $this->admin_type($this->is_admin),
            'last_update' => $this->updated_at->format('Y-m-d g:i a'),
        ]; 

        if($this->is_admin == 0) {
            foreach($this->stations_petrol_types as $key => $petrol_type) {
                $new_user['petrol_products'][$key] = [
                    'type' => $petrol_type['petrol_type'],
                    'number_of_storages' => $petrol_type['storage_num'],
                    'each_storage_capacity' => $petrol_type['storage_capacity']
                ];
            }
        }

        return $new_user;   
    }
}
