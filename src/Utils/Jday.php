<?php

namespace App\Utils;

  /**
   * class Jday
   *
   * - Den Julianske dagt�lling 
   * - Angiver antallet af dage fra dag nr 0 den 1. Januar -4712, juliansk kalender
   *   som er de-facto international standard for fortl�bende dagt�lling.
   * 
   *   Defineret af Joseph Justus Scaliger i v�rket 'De emendatione temporum' fra 1583,
   *   er i�vrigt navngivet efter hans fader Julius Caesar Scaliger.  
   *   
   **/
   
class Jday
{
  protected $jd= 0;
  
    /**
     *
     *
     */
     
  function __construct($param)
  {
    $this->setJd($param);
  }
  

    /**
     *  getJd()
     *
     */
     
  public function getJd() : int
  {
    return $this->jd;
  }    
  

    /**
     *  setJd()
     *
     */
     
  public function setJd($parem)
  {
    $this->jd= $parem;
  }    
  

  
}