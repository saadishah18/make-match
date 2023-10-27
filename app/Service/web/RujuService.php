<?php

namespace App\Service\web;

use App\Http\Resources\RujuResource;
use App\Models\Ruju;

class RujuService
{
    public function rujuService($request)
    {
        $rujus = Ruju::all();
        return RujuResource::collection($rujus);
    }
}
