<?php
namespace App\Repositories;

use App\Http\Resources\NikahResource;
use App\Models\Nikah;

class ImamNikahRepository
{
    public function getImamSpecificNikah($request = null)
    {
        $imam = auth()->user();
        $nikahs = $imam->imamNikahs;
        return NikahResource::collection($nikahs);
    }

    public function nikahDetail($id){
        $nikah = Nikah::find($id);
        return new NikahResource($nikah);
    }
}
