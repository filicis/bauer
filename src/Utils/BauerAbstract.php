<?php

/**
* This file is part of the Bauer package.
*
* @author Michael Lindhardt Rasmussen <filicis@gmail.com>
* @copyright 2000-2023 Filicis Software
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace App\Utils;

use Symfony\Contracts\Translation\TranslatorInterface;

/*
    Abtract klasse der præsenterer gundlæggende funktionalitet der indgår i alle kalendere
*/

abstract class BauerAbstract
{
	const MONTHS= ["bauer.januar", "bauer.februar", "bauer.marts", "bauer.april", "bauer.maj", "bauer.juni", "bauer.juli", "bauer.august", "bauer.september", "bauer.oktober", "bauer.november", "bauer.december"];
	const UGEDAG= ["bauer.mandag", "bauer.tirsdag", "bauer.onsdag", "bauer.torsdag", "bauer.fredag", "bauer.lørdag", "bauer.søndag"];

	const MINAAR= 600;
	const MAXAAR= 3199;

    // Kalender konstanter

    protected const JULIANSK=     1;
    protected const GREGORIANSK=  2;
    
    // Definistion af nuldag og cycklisk længde

    const NULDAG_J=     1721117;
    const CYKLGD_J=     146100;
    const NULDAG_G=     1721119;
    const CYKLGD_G=     146097;

    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/

    // Variable

	protected $year= 0;
	protected $yearClass= 0;



    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/

    // Abstrakte funktioner

	/**
	*	private _yearClass()											
	*
	*	0: Ugyldigt år
	*	1: Almindeligt år med 365 dage
	*	2: Skudår med 366 dage
	*	3...: Overgangsår, for Danmark 1700 med 355 dage
	*/

    /**
     *  ValidateDate()
     *  - Validerer dag og måned for det givne år...
     */

    //abstract protected function validateDate($month, $date) : bool

	abstract protected function yearClass($year) : int;


    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/

    // 
 


  	/**
	* getMonths()
	*
	**/

	public function getMonths() : array
	{
		return self::MONTHS;
	}


    public function getYear() : int
    {
        return $this->year;
    }


    /**
     *  setYear
     *  - checker det nye års klasse og finder udgangspunktet for de foranderlige helligdate (for de kristne kalendere påske søndag) 
     */

    public function setYear($newYear) : int
    {
        $this->year= $newYear;
        $this->yearClass($newYear);
        $this->_pascha();
        return $this->year;
    }

    public function getYearClass() : int
    {
        return $this->yearClass;
    }

    public function setYearClass($newYearClass) : int
    {
        return $this->yearClass= $newYearClass;
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
	*	static _jdag()
	*
	*	Beregner Juliansk dagtal for datoen i den givne kalender, gælder ubegrænset
	*	jdag= 0 svarer til -4712 jan 01, juliansk eller -4713 nov 24, gregoriansk
	*
	*/

	protected static function jdag($cal, $a, $m, $d) : int
	{
		$totdato;
		$nuldag;
		$cycklgd;
		$cykantal;
		$aaricyk;
		$aarhundicyk;


        switch($cal)
        {
            case self::JULIANSK:
                $nuldag= self::NULDAG_J;
                $cyklgd= self::CYKLGD_J;
                break;
            case self::GREGORIANSK:
                $nuldag= self::NULDAG_G;
                $cyklgd= self::CYKLGD_G;
                break;
            default:
                return 0;        
        }

		if ($m < 3)										/* Januar og februar henregnes til foregående år */
		{
			$m+= 12;
			$a-= 1;
		}

		$cykantal= intdiv($a, 400);					/* Antal cyklusser à 400 år */

		$aaricyk= $a - ($cykantal * 400);
		$aarhundicyk= intdiv($aaricyk, 100);
		$aarrest= $aaricyk % 100;

		return $nuldag + ($cykantal * $cyklgd) + intdiv(($aarhundicyk * $cyklgd), 4) + intdiv(($aarrest * 1461), 4) + intdiv(((153 * $m) - 457), 5) + $d;
	}


    

    	/**
	*	_pacha()
	*
	*	Beregner Påskedag for de kristne kalendere - dog kun for årene 600 -3199
	*/

	protected function __pascha($cal)
	{
		$aar= $this->year;
		$md;
		$dg;
		$gyldental;							/* Den 19-årige Metoncyklus */
		$hundredaar;
		$sun;
		$moon;
		$epakt;									/* Angiver månens alder, dvs hvor mange dage der er gået efter sidste nymåne */
		$pborder;

		$gyldental= ($aar % 19) + 1;
		$ugedagspos= intdiv(5 * $aar, 4);

        switch($cal)
        //{
		//if (1699 < $aar)									/* Gregoriansk Kalender */
		{
            case self::GREGORIANSK:
			    $hundredeaar= intdiv($aar, 100);
			    $sun= intdiv((3 * $hundredeaar) - 45, 4);
			    $moon= intdiv((8 * $hundredeaar) - 112, 25);
			    $ugedagspos-= (10 + $sun);
			    $epakt= ((11 * $gyldental + 19 + $moon - $sun) % 30) + 1;
			    if (($epakt == 25 && 11 < $gyldental) || $epakt == 24)
			    {
				    $epakt+= 1;
			    }
                break;
            case self::JULIANSK:
			    $epakt= ((11 * $gyldental - 4) % 30) + 1;
                break;
            default:
                $this->pjtal= 0;
                return; 
		}
		/* Restberegning - fælles for begge kalendere */

        $pborder= 44 - $epakt;
		if ($pborder < 21)
		$pborder+= 30;
		$dg= $pborder + 7 - (($ugedagspos + $pborder) % 7);
		if (31 < $dg)
		{
			$md= 4;
			$dg-= 31;
		}
		else
		$md= 3;

		//$this->pdg= $dg;
		//$this->pmd= $md;
		$this->pjtal= self::jdag($cal, $aar, $md, $dg);
	}



  	/**
	 *	getDateInfo()
	 *
     *  - Returnerer et array $info der indeholder følgende:
     *  $info['class']:
     *  $info['ugedag']:
     *  $info['description']:
     *  $info['tooltip']:
     *  $info['jdag']:
   **/

   public function _getDateInfo($d, $m, $y): array
   {
     $info['class']= 0;

   	 if ($this->setYear($y) == true && $this->setDato($d, $m) == true)
   	 {
   	 	 $info['class']= $this->getAdatoClass();
   	 	 $info['ugedag']= $this->getUgedag();
   	 	 $info['description']= $this->getDescription();
         $info['tooltip']= $this->getToolTip();
		 $info['jdag']= self::_jdag($y, $m, $d);
   	 }
   	 return $info;
   }



   


}