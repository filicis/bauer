<?php

namespace App\Utils;

/**
 *	CLass KalenderGrg 
 *
 *	Gregoriansk kalender
 *	- Gyldig fra årene 600 - 	
 */

class KalenderDA extends KalenderGrg
{
  
	
	  /**
	   *	jday()
	   *
	   *  Beregner juliansk dagtal for datoen i den Julianske kalender.
	   *  - Dvs antallet af dag der er passeret siden 1. Januar -4712
	   **/ 

  public function jday(Dato $param) : Jday
  {
    $totdato= $param->getYear() * 100 + $param->getMonth();
    return (170002 < $totdato) ? $this->jdayGrg($param) : $this->jdayJul($param);
  }  
}    