<?php

namespace App\Utils;

/**
 *	CLass Kalender 
 *
 *	Beskriver deb grænseflade som alle Kelendere skal tilbyde. 
 */

abstract class Kalender implements iKalender
{
	abstract public function jdag($d, $m, $y) : integer; 
	
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