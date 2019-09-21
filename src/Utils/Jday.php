<?php

namespace App\Utils;

  /**
   * class Jday
   *
   * - Den Julianske dagtælling 
   * - Angiver antallet af dage fra dag nr 0 den 1. Januar -4712, juliansk kalender
   *   som er de-facto international standard for fortløbende dagtælling.
   * 
   *   Defineret af Joseph Justus Scaliger i værket 'De emendatione temporum' fra 1583,
   *   er iøvrigt navngivet efter hans fader Julius Caesar Scaliger.  
   *   
   **/
   
class Jday
{
  private $jd;
  
    /**
     *
     *
     */
     
  public function __contruct($jd)
  {
    $this->setJd($jd);
  }
  

    /**
     *  getJd()
     *
     */
     
  public function getJd() : integer
  {
    return $this->jd;
  }    
  

    /**
     *  setJd()
     *
     */
     
  public function setJd($jd)
  {
    $this->jd= $jd;
  }    
  

  
}