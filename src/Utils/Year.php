<?php

namespace App\Utils;

  /**
   * class Year
   *
   * - Generisk klasse til h�ndtering af kalender�r
   *   Foruds�tter kun at $y er heltal st�rre end 0 
   **/
   
class Year
{
  private $Year;
  
  public function getYear() : integer
  {
    return $this->year;
  }  
    

  public function setYear($y)
  {
    $this->year= $y;
  }  
    

}