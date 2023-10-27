<?php

namespace App\Repositories\Interfaces;

interface ImamManagementInterface
{
    public function getImams();

    public function changeImamStatus($array);
}
