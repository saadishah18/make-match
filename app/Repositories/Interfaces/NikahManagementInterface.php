<?php

namespace App\Repositories\Interfaces;

interface NikahManagementInterface{

    public function nikahListing($params);

    public function calenderNikahs($params);

    public function nikahDetail($id);

    public function getImams($parms);

    public function assignImam($parms);

    public function getWitnessToAssign($params);

    public function assignWitnessToNikah($params);
}
