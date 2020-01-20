<?php

namespace App\Utils;

/**
 *	CLass Kalender
 *
 *	Beskriver den grænseflade som alle Kalendere skal tilbyde.
 */

abstract class Kalender implements iKalender, iKalenderaar
{
	private const DAYMAX= 7;
	private const MONTHMAX= 12;

	const MONTHS= ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"];
	const WEEKDAYS= ["Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag", "Søndag"];

	protected const YEARINVALID= 0;
	protected const YEARNORMAL= 1;
	protected const YEARLEAP= 2;

	protected $adg;
	protected $amn;
	protected $year;

	protected $dagType;
	protected $dagBetegnelse;
	protected $dagTooltip;
	protected $ugedag;
	protected $aarType;


	abstract public function dato(Jday $param) : Dato;

	abstract public function jday(Dato $param) : Jday;

	abstract public function ugedag(Jday $param) : int;



	abstract public function validateDato($d, $m) : bool;

	abstract public function validateYear($y) : int;




	/**
	*
	*
	*/

	protected function getaarTypeJulian($y) : integer
	{
		return ($y % 4) ? 1 : 2;
	}

	/**
	*
	*
	*/

	protected function getaarTypeGregorian($y) : integer
	{
		return ($y % 4) ? 1 : 2;
	}


	public function getDayMax() : int               { return self::DAYMAX; }
	public function getMonthMax() : int             { return self::MONTHMAX; }

	public function getDagType() : int              { return $this->dagType; }
	public function getDagBetegnelse() : String     { return $this->dagType; }
	public function getDagTooltip() : String        { return $this->dagType; }
	public function getUgedag() : String            { return $this->ugedag; }


	/**
	*	getAarType()
	*
	* 	0: betyder ugyldigt år, men iøvrigt defineres parameteren individuelt for hver kalender
	* 		 For den Dansk/Norske kalender f.eks:
	*  1: Almindeligt år
	*  2: Almindeligt skudår
	*  3: Overgangsåret 1700
	*
	**/


	public function getAarType() : int
	{
		return $this->aarType();
	}



	/**
	*	isValid();
	*
	*	TRUE hvis vi arbejder med et gyldigt år
	*
	**/

	public function isValid() : bool
	{
		return ($this->aarType != 0);
	}




	public function selectYear($y) : bool
	{
		$this->year= $y;
		$this->aarType= $this->validateYear($y);
		return True;
	}

	public function selectDate($d, $m) : bool
	{
		$this->adg= $d;
		$this->amn= $m;
		return $this->ValidateDato($d, $m);
		return True;

	}



}