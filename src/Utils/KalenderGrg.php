<?php

namespace App\Utils;

/**
 *	CLass KalenderGrg 
 *
 *	Gregoriansk kalender
 *	- Gyldig fra årene 600 - 	
 */

class KalenderGrg extends KalenderJul
{
	const NULDAG= 1721119;
	const CYKLGD= 146097;



  
  
  	  /**
	   *	epaktGrg()
	   *
	   *	Betegner for en dato Månens alder, dvs hvor mange dage der er gået fra sidste nymåne til den pågældende dato.
	   *	Værdier mellem 1 og 30.
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
	   *	jday()
	   *
	   *  Beregner juliansk dagtal for datoen i den Julianske kalender.
	   *  - Dvs antallet af dag der er passeret siden 1. Januar -4712
	   **/ 

  public function jday(Dato $param) : Jday
  {
    return $this->jdayGrg($param);
    
    //return ($param->getYear() < 1582) 
    //? parent::_jday($param) 
    //: parent::_jday($param, self::NULDAG, self::CYKLGD);
    //return parent::jday($param);
    //$x= new Jday(262626);
    //$x->setJd(232323);
    //return $x;
  }  


  public function jdayGrg(Dato $param) : Jday
  {
    return parent::_jday($param, self::NULDAG, self::CYKLGD);
  }  


	

  
  	  /**
	   *	pascha()
	   *
	   *	Beregner Påskedag for det givne årstal - Og her efter den Julianske kalender. 
	   **/ 
	
	public function pascha($y) : Dato
	{
		return (1582 < $y) ? $this->_paschaGrg($y) : $this->_paschaJul($y);
	}
	
	
		  /**
	   *	_pascha()
	   *
	   *	Beregner Påskedag for det givne årstal - Og her efter den Gregorianske kalender. 
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
     *	Beregner århundrede
     *
     **/
  
  private function _sekel($y)
  {
		return intdiv ($y, 100);
  }


    /**
     *	_moon()
     *
     *	Beregner månejævning
     *
     **/
  
  private function _moon($y)
  {
		return intdiv (8 * $this->_sekel($y) - 112, 25);
  }


    /**
     *	_sun()
     *
     *	Beregner soljævning
     *
     **/
  
  private function _sun($y)
  {
		return intdiv(3 * $this->_sekel($y) - 45, 4);
  }
  
  
  
  	
	  /**
	   *    validateYear()
	   *
	   **/
	   
  public function validateYear($y) : int
  {  
    return ($y % 4) ? Kalender::YEARNORMAL : ($y % 100) ? Kalender::YEARLEAP : ($y % 400) ? YEARNORMAL : YEARLEAP;
  }


}	
