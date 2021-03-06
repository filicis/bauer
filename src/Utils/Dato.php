<?php

namespace App\Utils;

  /**
   * class Dato
   *
   * - Generisk klasse til h�ndtering af datoer
   *   Foruds�tter kun at $d, $m og $y er heltal st�rre end 0 
   **/
   
class Dato
{
  private $day=     0;
  private $month=   0;
  private $year=    0;  
  
  // public function __construct() {}
  
  public function __construct($d= 0, $m= 0, $y= 0) 
  {
    $this->setDay($d);
    $this->setMonth($m);
    $this->setYear($y);
  }


    /**
     *  getDay()
     *
     */

  public function getDay() : int
  {
    return $this->day;
  }
  

    /**
     *  getMonth()
     *
     */

  public function getMonth() : int
  {
    return $this->month;
  }
  
  
    /**
     *  getYear()
     *
     */

  public function getYear() : int
  {
    return $this->year;
  }
  
  
    /**
     *  setDay()
     *
     */

  public function setDay($d)
  {
    $this->day= $d;
  }

    /**
     *  setMonth()
     *
     */

  public function setMonth($m)
  {
    $this->month= $m;
  }
  
  

    /**
     * setDay()
     *
     */

  public function setYear($y)
  {
    $this->year= $y;
  }
  
}   