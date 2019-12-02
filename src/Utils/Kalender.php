<?php

namespace App\Utils;

/**
 *	CLass Kalender 
 *
 *	Beskriver den gr�nseflade som alle Kalendere skal tilbyde. 
 */

abstract class Kalender implements iKalender, iKalenderaar
{
  private const DAYMAX= 7;
  private const MONTHMAX= 12;  
  
  protected const YEARINVALID= 0;
  protected const YEARNORMAL= 1;
  protected const YEARLEAP= 2;
    
  protected $adg;
  protected $amn;
  protected $year;
  
  protected $dagType;
  protected $yeartype;
  protected $dagBetegnelse;
  protected $dagTooltip; 
  protected $ugedag;   
  
    
  abstract public function dato(Jday $param) : Dato;

  abstract public function jday(Dato $param) : Jday;

  abstract public function validateDato($d, $m) : bool;

  abstract public function validateYear($y) : int;
  
      


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
	
	
	public function getDayMax() : int               { return self::DAYMAX; }
    public function getMonthMax() : int             { return self::MONTHMAX; }
    
    public function getDagType() : int              { return $this->dagType; }
    public function getDagBetegnelse() : String     { return $this->dagType; }
    public function getDagTooltip() : String        { return $this->dagType; }
    public function getUgedag() : String            { return $this->ugedag; }

	
	
	public function selectYear($y) : bool
	{
	  $this->year= $y;
	  $this->yeartype= $this->validateYear($y);
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