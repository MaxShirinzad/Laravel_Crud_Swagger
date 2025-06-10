<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ProductResource extends JsonResource
{
    public static $wrap = false;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            //'group_id' => $this->group_id,

            'Title' => $this->Title,
            'Desc' => $this->Desc,
            'Price' => $this->Price,
            'slug' => $this->slug,
            'viewCount' => $this->viewCount,
            'status' => $this->status,
            'IsImage' => $this->IsImage,

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
