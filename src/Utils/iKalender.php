<?php

namespace App\Utils;

/**
 *	interface iKalender 
 *
 *	Generisk klasse for alle kalendere 
 *  - En kalender er inddelt i dage, uger, m�neder og �r
 *  
 *  - Ugedage nummereres fra 1..
 *  - M�neder nummereres fra 1..
 *
 */

interface iKalender
{
  public function dato(Jday $param) : Dato;
  public function jday(Dato $param) : Jday;  	
}