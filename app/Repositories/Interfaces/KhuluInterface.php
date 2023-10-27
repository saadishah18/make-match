<?php

namespace App\Repositories\Interfaces;

interface KhuluInterface
{
    public function applyKhulu($request);

    public function acceptKhuluRequest($request);

    public function rejectKhuluRequest($request);

}