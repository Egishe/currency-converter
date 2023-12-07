<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public function resolve($request = null)
    {
        $this->with = [
            'status' => 'success',
            'code' => 200,
        ];
        return parent::resolve($request);
    }
}
