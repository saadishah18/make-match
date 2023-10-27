<?php
namespace App\Repositories\Interfaces;

interface NikahInterface
{

    public function nikahTypes();

    public function nikahServices($array);

    public function calendarDates($array);

    public function getDateSlots($array);

    public function saveNikahAsDraft($array);

    public function saveNikKah($array,$parms);

    public function resendInvitation($parms);
}
