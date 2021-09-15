<?php

namespace App\Http\Resources\API\V1\Calendar;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
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
            'id' => $this->id,
            'customer' => new \App\Http\Resources\API\V1\Customers\IndexResource($this->Customer),
            'consultant' => new \App\Http\Resources\API\V1\Consultant\IndexResource($this->Consultant),
            'appointment' => [
                'address' => $this->address,
                'code' => $this->code,
                'distance' => $this->distance . ' KM'
            ],
            'time'  => [
                'arrive_time' => $this->arrive_time,
                'turnaround_time' => $this->turnaround_time
            ],
            'status' => $this->status

        ];
    }
}
