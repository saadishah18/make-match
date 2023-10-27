<?php

namespace App\Repositories\Interfaces;

interface RujuInterface
{
    public function applyRuju($request);
    public function acceptRujuRequest($request);
    public function rejectRujuRequest($request);
}
