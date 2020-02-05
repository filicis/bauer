<?php

namespace App\Utils;

/**
 *	Class KalenderJul
 *
 *	Juliansk kalender
 *	- Gyldig fra årene 600 -
 */

class KalenderJul extends Calendar
{
	//
	// Vi arbejder indtil videre med år i intervallet 600-3199 efter Kristi fødsel
	//

	const MINAAR= 600;
	const MAXAAR= 3199;

	const NULDAG= 1721117;
	const CYKLGD= 146100;

	const MONTHS= ["bauer.januar",
							   "bauer.februar",
							   "bauer.marts",
							   "bauer.april",
							   "bauer.maj",
							   "bauer.juni",
							   "bauer.juli",
							   "bauer.august",
							   "bauer.september",
							   "bauer.oktober",
							   "bauer.november",
							   "bauer.december"];

	const WEEKDAYS= ["Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag", "Søndag"];

	/**
	 * iCalendarView relaterede variable
	 *
  protected $year;
  protected $yearClass= 0;

  /**
   * Påskeberegnings relaterede variable
   *
   **/

   protected $pday;
   protected $pmonth;
   protected $gyldental;		/* Den 19-årige Metoncyklus */
   protected $ugedagspos;
   protected $epakt;				/* Angiver månens alder, dvs hvor mange dage der er gået efter sidste nymåne */
   protected $pborder;


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

  		/* Korriger for månederne Januar og Februar */
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
	   *	Betegner for en dato Månens alder, dvs hvor mange dage der er gået fra sidste nymåne til den pågældende dato.
	   *	Værdier mellem 1 og 30.
	   **/

	protected function epaktJul($y) : int
	{
		return ((11 * $this->gyldental($y)) - 4) % 30 + 1;
	}


	/**
	 * getMonths()
	 *
	 * - del af iCalendarView snitfladen
	 *
	 **/

	public function getMonths() : array
	{
		return self::MONTHS;
	}





	  /**
	   *	gyldental()
	   *
	   *	Året nummer i den 19-årige Metoncycklus, hvorefter månefaserne omtrent falder på de samme datorer
	   *  Værdier mellem 1 og 19
	   **/

	protected function gyldental($y) : int
	{
		return ($y % 19) + 1;
	}


	/**
	 *	isValid()
	 *
	 *	- checker kun om der er indlæst et gyldigt år
   *
   **/

	public function isValid() : bool
	{
		return ($this->yearClass != 0);
	}




	  /**
	   *	jday()
	   *
	   *  Beregner juliansk dagtal for datoen i den Julianske kalender.
	   *  - Dvs antallet af dag der er passeret side 1. Januar -4712
	   **/

  public function jday(Dato $param) : Jday
  {
    return $this->jdayJul($param);
  }

    public function jdayJul(Dato $param) : Jday
  {
    return $this->_jday($param);
  }




   protected function _jday(Dato $param, $nuldag= self::NULDAG, $cyklgd= self::CYKLGD ) : Jday
  {

  	$cykantal;
  	$aaricyk;
  	$centicyk;
  	$aarrest;
  	$jd;

  	$d= $param->getDay();
		$m= $param->getMonth();
		$y= $param->getYear();

  	  /* Januar og Februar henregnes til året før */
  	if ($m < 3)
  	{
  		$m+= 12;
  		$y-= 1;
  	}

  	$cykantal= intdiv($y, 400);									 /* Antal cyklusser à 400 år */
  	$aaricyk= $y % 400;
  	$centicyk= intdiv($aaricyk, 100);
  	$aarrest= $aaricyk % 100;

  	$jd= $nuldag
  	     + $cykantal * $cyklgd
  	     + intdiv(($centicyk * $cyklgd), 4)
  	     + intdiv(($aarrest * 1461), 4)
  	     + intdiv((153 * $m - 457), 5)
  	     + $d;

  	return new Jday($jd);

  	// return new Jday(242424);
  }



	/**
	*	setYear()
	*
	*	- Checker at året ligger i intervalle 600-3200
	*	- Finder datoen for påskesøndag (som bestemmer de øvrige helligdage i kirkeåret
	*/

	public function setYear($year) : bool
	{
		if (! $this->isValid() || $this->year != $year)
		{
			$this->year= $year;
		  switch($this->_yearClass($year))
		  {
		 		case 0:  return False;
				default: $this->_pascha();
			}
	  }
		return true;
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
	   *	Beregner Påskedag for det givne årstal - Og her efter den Julianske kalender.
	   **/

	public function pascha($y) : Dato
	{
		return $this->_pascha($y);
	}


	  /**
	   *	_pascha()
	   *
	   *	Beregner Påskedag for det givne årstal - Og her efter den Julianske kalender.
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


	/**
	 *  ugedag()
	 *
	 *
	 **/

  public function ugedag(Jday $param) : int
  {
    return $param->getJd() % 7;
  }



	 /**
   *	_validDato()														** Parallel til Algorime 2 **
   *
   *	Forudsætter at vi har et gyldigt år
   */

  public function validateDato($d, $mmm) : bool
  {
    if ($this->aartype != 0)
   	{
   	  if (0 < $mmm && $mmm < 13)
   	  {
   	    if (0 < $d && $d < 32)
   	 	{
   	 	  switch($mmm)
   	 	  {
   	 	    case 2: switch ($this->aartype)
   	 	 	 	 	{
   	 	 	 	 	  case 1: if (28 < $d) return False;
   	 	 	 	 	 		  break;

   	 	 	 	 	  case 2: if (29 < $d) return False;
   	 	 	 	 	 		  break;

   	 	 	 	 	  case 3: if (18 < $d) return False;
   	 	 	 	 	}
   	 	 	 	 	break;

   	 	    case 4:
   	 	 	case 6:
   	 	 	case 9:
   	 	 	case 11: if (30 < $d) return False;
   	 	  }
   	 	  return True;

   	 	}
   	  }

   	}
   	return False;
  }



	  /**
	   *    validateYear()
	   *
	   **/

  public function validateYear($y) : int
  {
    return ($y % 4) ? Kalender::YEARNORMAL : Kalender::YEARLEAP;
  }


	/**
	*	private _yearClass()											** Parallel til Algoritme 1 **
	*
	*	0: Ugyldigt år
	*	1: Almindeligt år med 365 dage
	*	2: Skudår med 366 dage
	*/

	protected function _yearClass($year) : int
	{
		$this->yearClass= 0;

		if (self::MINAAR <= $year && $year <= self::MAXAAR)
		{
		  $this->yearClass= ($year % 4) ? 1 : 2;
		}
		return $this->yearClass;
	}




}