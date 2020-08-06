<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FertilizerRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'                    =>      $this->id,
            'profile_id'            =>      $this->profile_id,
            'name'                  =>      $this->profile->name,
            'quantity'              =>      $this->quantity,
            'weight'                =>      $this->weight,
            'price'                 =>      $this->price,
            'paid'                  =>      $this->paid,
            'type'                  =>      $this->type,
            'created_at'            =>      $this->created_at,
            'updated_at'            =>      $this->updated_at
        ];
    }
}
