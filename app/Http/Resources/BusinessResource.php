<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            "id"=> $this->id,
            "alias"=> $this->alias,
            "name"=> $this->name,
            "image_url"=> $this->image_url,
            "is_closed"=> $this->is_closed===1?true:false,
            "url"=> $this->url,
            "review_count"=> $this->url,
            "categories"=> $this->categories,
            "rating"=> $this->url,
            "coordinates"=> [
                "latitude"=> $this->latitude,
                "longitude"=> $this->longitude
            ],
            //"transactions": [],
            "price"=> $this->price,
            "location"=> [
                "address1"=> $this->address1,
                "address2"=> $this->url,
                "address3"=> "",
                "city"=> $this->url,
                "zip_code"=> $this->zip_code,
                "country"=> $this->country,
                "state"=> $this->state,
                "display_address" => $this->display_address
            ],
            "phone"=> $this->phone,
            "display_phone"=> $this->phone,
            "distance"=> $this->distance
        ];
    }
}
