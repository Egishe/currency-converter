<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TechHealthResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;

class TechController extends BaseController
{
    public function health(): TechHealthResource
    {
        return new TechHealthResource(null);
    }

    public function guardedHealth(): JsonResource
    {
        return new TechHealthResource(null);
    }
}
