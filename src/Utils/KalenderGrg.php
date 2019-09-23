<?php

namespace App\Utils;

/**
 *	CLass KalenderGrg 
 *
 *	Gregoriansk kalender
 *	- Gyldig fra �rene 600 - 	
 */

class KalenderGrg extends KalenderJul
{
	const NULDAG= 1721119;
	const CYKLGD= 146097;


  	/**
  	 *	cyklgd()
  	 *
  	 **/
  
  protected function cyklgd() : int
  {
  	return self::CYKLGD;
  }
  
  
  	  /**
	   *	epaktGrg()
	   *
	   *	Betegner for en dato M�nens alder, dvs hvor mange dage der er g�et frasidste nym�ne til den p�g�ldende dato.
	   *	V�rdier mellem 1 og 30.
	   **/ 
	
	protected function epaktGrg($y) : int
	{
		$e= (11 * $this->gyldental($y) + 19 + $this->_moon($y) - $this->_sun($y)) % 30 + 1;

			/* Korriger de 2 gregorianske undtagelser */	
		if (($e == 25 && 11 < $this->gyldental($y)) or $e == 24)
		  $e+= 1;
		return $e;  		
	}
	


	
	  	/**
  	 *	nuldag()
  	 *
  	 **/
  
  protected function nuldag() : int
  {
  	return self::NULDAG;
  }
  
  
  	  /**
	   *	pascha()
	   *
	   *	Beregner P�skedag for det givne �rstal - Og her efter den Julianske kalender. 
	   **/ 
	
	public function pascha($y) : Dato
	{
		return (1582 < $y) ? $this->_paschaGrg($y) : $this->_paschaJul($y);
	}
	
	
		  /**
	   *	_pascha()
	   *
	   *	Beregner P�skedag for det givne �rstal - Og her efter den Gregorianske kalender. 
	   **/ 
	
	public function _paschaGrg($y) : Dato
	{
		$dg;
		$md= 3;
		
		$pborder= 44 - $this->epaktGrg($y);
		if ($pborder < 21)
		  $pborder+= 30;
		$dg= $pborder + 7 - (($this->ugedagposGrg($y) + $pborder) % 7);
		if (31 < $dg)
		{
			$md= 4;
			$dg-= 31;
		} 
		return new Dato($dg, $md, $y);
	}
	


  
  
 	  /**
	   *	ugedagposGrg()
	   *
	   **/ 
	
	protected function ugedagposGrg($y) : int
	{
		return $this->ugedagposJul($y) - 10 -$this->_sun($y);
	}
	

  

    /**
     *	_sekel()
     *
     *	Beregner �rhundrede
     *
     **/
  
  private function _sekel($y)
  {
		return intdiv ($y, 100);
  }


    /**
     *	_moon()
     *
     *	Beregner m�nej�vning
     *
     **/
  
  private function _moon($y)
  {
		return intdiv (8 * $this->_sekel($y) - 112, 25);
  }


    /**
     *	_sun()
     *
     *	Beregner solj�vning
     *
     **/
  
  private function _sun($y)
  {
		return intdiv(3 * $this->_sekel($y) - 45, 4);
  }

}	
