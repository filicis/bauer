<?php

namespace App\Utils;

/**
 *	CLass Kalender 
 *
 *	Beskriver den grænseflade som alle Kalendere skal tilbyde. 
 */

abstract class Kalender implements iKalender
{
    
  abstract public function dato(Jday $param) : Dato;

  abstract public function jday(Dato $param) : Jday;
		/**
		 *
		 *
		 */ 
		 
	protected function getYeartypeJulian($y) : integer
	{
		return ($y % 4) ? 1 : 2;
	} 	

		/**
		 *
		 *
		 */ 
		 
	protected function getYeartypeGregorian($y) : integer
	{
		return ($y % 4) ? 1 : 2;
	} 	


}