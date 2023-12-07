<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TechHealthResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'isHealthy' => true,
        ];
    }
}
