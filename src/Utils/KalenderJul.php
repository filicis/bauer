<?php

namespace App\Utils;

/**
 *	CLass KalenderJul 
 *
 *	Juliansk kalender
 *	- Gyldig fra �rene 600 - 	
 */

class KalenderJul extends Kalender
{
	const NULDAG= 1721117;
	const CYKLGD= 146100;

 

  	/**
  	 *	cyklgd()
  	 *
  	 **/
  
  protected function cyklgd() : int
  {
  	return self::CYKLGD;
  }


		/**
		 *	dato()
		 *
		 *
		 **/

  public function dato(Jday $param) : Dato
  {
  	$d;
  	$m;
  	$y;
  	
  	$korrdag= $paren->getJd() - $this->nuldag();
  	
  		/* Korriger for m�nederne Januar og Februar */
  	if (12 < $m)
  	{
  		$m-= 12;
  		$y+= 1;
  	}
  	
  	return new Dato($d, $m, $y); 
  }


	  /**
	   *	epaktJul()
	   *
	   *	Betegner for en dato M�nens alder, dvs hvor mange dage der er g�et frasidste nym�ne til den p�g�ldende dato.
	   *	V�rdier mellem 1 og 30.
	   **/ 
	
	protected function epaktJul($y) : int
	{
		return ((11 * $this->gyldental($y)) - 4) % 30 + 1;
	}
	

	
	  /**
	   *	gyldental()
	   *
	   *	�ret nummer i den 19-�rige Metoncycklus, hvorefter m�nefaserne omtrent falder p� de samme datorer
	   *  V�rdier mellem 1 og 19
	   **/ 
	
	protected function gyldental($y) : int
	{
		return ($y % 19) + 1;
	}
	
		  /**
	   *	jday()
	   *
	   *  Beregner juliansk dagtal for datoen i den Julianske kalender.
	   *  - Dvs antallet af dag der er passeret side 1. Januar -4712
	   **/ 

  public function jday(Dato $param) : Jday
  {
  	$cykantal;
  	$aaricyk;
  	$centicyk;
  	$aarrest;
  	$jd;
  	
  	$d= $param->getDay();
		$m= $param->getMonth();
		$y= $param->getYear();
  	
  	  /* Januar og Februar henregnes til �pret f�r */
  	if ($m < 3)
  	{
  		$m+= 12;
  		$y-= 1;
  	}
  	
  	$cykantal= entdiv($y, 400);									 /* Antal cyklusser � 400 �r */
  	$aaricyk= $y % 400;
  	$centicyk= intdiv($aaricyk, 100);
  	$aarrest= aaricyk % 100;
  	
  	$jd= $this->nuldag() + $cykantal * $this->cyklgd()
  	     + intdiv(($centicyk * $this->cyklgd()), 4)
  	     + intdiv(($aarrest * 1461), 4)
  	     + intdiv((153 * $md - 457), 5)
  	     + 4;

  	return new Jday(jd);
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
	   *	ugedagposJul()
	   *
	   **/ 

	protected function ugedagposJul($y) : int
	{
		return intdiv((5 * $y), 4);
	}
	

	  /**
	   *	pascha()
	   *
	   *	Beregner P�skedag for det givne �rstal - Og her efter den Julianske kalender. 
	   **/ 
	
	public function pascha($y) : Dato
	{
		return $this->_pascha($y);
	}
	

	  /**
	   *	_pascha()
	   *
	   *	Beregner P�skedag for det givne �rstal - Og her efter den Julianske kalender. 
	   **/ 
	
	public function _paschaJul($y) : Dato
	{
	
		
		$dg;
		$md= 3;
		
		$pborder= 44 - $this->epaktJul($y);
		if ($pborder < 21)
		  $pborder+= 30;
		$dg= $pborder + 7 - (($this->ugedagposJul($y) + $pborder) % 7);
		if (31 < $dg)
		{
			$md= 4;
			$dg-= 31;
		} 
		return new Dato($dg, $md, $y);
	}
	
}