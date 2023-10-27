<?php

namespace App\Repositories\Interfaces;

interface WitnessInterface
{

    public function getActivewitness($params = null);

    public function storeWitness($params);

}
