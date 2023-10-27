<?php

namespace App\Repositories\Interfaces;

interface MyActivityInterface
{
    public function myActivities();

    public function acceptInvitation($array);
}
