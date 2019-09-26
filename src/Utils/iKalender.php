<?php

namespace App\Utils;

/**
 *	interface iKalender 
 *
 *	Generisk klasse for alle kalendere 
 *  - En kalender er inddelt i dage, uger, måneder og år
 *  
 *  - Ugedage nummereres fra 1..
 *  - Måneder nummereres fra 1..
 *
 */

interface iKalender
{
  public function dato(Jday $param) : Dato;
  public function jday(Dato $param) : Jday;  	
}