<?php

namespace App\Factories\Calendar;

use App\Factories\Calendar\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {

     $results = DB::select("
        SELECT *  FROM event_calendar
        WHERE MONTH(start_date) = MONTH(CURRENT_DATE)");

        return collect($results);
    } 
}