<?php

namespace App\Utils;

use App\Utils\Jday;
use App\Utils\Dato;

/**
 *	interface iCalendar
 *
 *	Generisk klasse for alle kalendere
 *  - En kalender er inddelt i dage, uger, måneder og år
 *
 *  - Ugedage nummereres fra 1..
 *  - Måneder nummereres fra 1..
 *
 */

interface iCalendar
{
  public function dato(Jday $param) : Dato;
  public function jday(Dato $param) : Jday;
}