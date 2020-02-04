<?php

namespace App\Utils;

use Symfony\Contracts\Translation\TranslatorInterface;


class Bauer
{
	const MONTHS_DA= ["bauer.januar", "bauer.februar", "bauer.marts", "bauer.april", "bauer.maj", "bauer.juni", "bauer.juli", "bauer.august", "bauer.september", "bauer.oktober", "bauer.november", "bauer.december"];
	const UGEDAG_DA= ["bauer.mandag", "bauer.tirsdag", "bauer.onsdag", "bauer.torsdag", "bauer.fredag", "bauer.lørdag", "bauer.søndag"];

	const MINAAR= 600;
	const MAXAAR= 3199;




	/**
	*	Variable der knytter sig til iCalendarView snitfladen
	*
	**/

	protected $year= 0;
	protected $yearClass= 0;

	protected $jtal= 0;


	public $pmd= 3;
	public $pdg= 12;
	public $pjtal;

	/* Variable der relaterer sig til aktuelle dato */

	private $adg;
	private $amd;
	private $ajtal;
	private $dagiaar;
	private $description= "Midsommer";
	private $augedag;


	private $adatoClass;


		/**
		 *	_construct()
		 *
		 *	- anvendes ikke umiddelbart
		 *
		 **/

	public function __construct()
	{
	}



	/**
	*	static _jdag()
	*
	*	Beregner Juliansk dagtal for datoen
	*	- gælder for dansk-norsk kalender 600-3199
	*	jdag= 0 svarer til -4712 jan 01, juliansk eller -4713 nov 24, gregoriansk
	*
	*/

	public static function _jdag($a, $m, $d) : int
	{
		$totdato;
		$nuldag;
		$cycklgd;
		$cykantal;
		$aaricyk;
		$aarhundicyk;

		/* Beregn nuldag og cyklisk længde for begge kalendere, skæringsdatoer for Danmark-Norge: 1700 feb 18 - 1700 mar 01 */

		$totdato= ($a * 100) + $m;
		if ($totdato > 170002)				/* Gregoriansk kalender */
		{
			$nuldag= 1721119;
			$cyklgd= 146097;
		}
		else													/* Juliansk Kalender */
		{
			$nuldag= 1721117;
			$cyklgd= 146100;
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
	*	Beregner Påskedag, gældende for Dansk-Norsk kalender 600 -3199
	*/

	protected function _pascha()
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

		if (1699 < $aar)									/* Gregoriansk Kalender */
		{
			$hundredeaar= intdiv($aar, 100);
			$sun= intdiv((3 * $hundredeaar) - 45, 4);
			$moon= intdiv((8 * $hundredeaar) - 112, 25);
			$ugedagspos-= (10 + $sun);
			$epakt= ((11 * $gyldental + 19 + $moon - $sun) % 30) + 1;
			if (($epakt == 25 && 11 < $gyldental) || $epakt == 24)
			{
				$epakt+= 1;
			}
		}
		else																	/* Juliansk Kalender */
		{
			$epakt= ((11 * $gyldental - 4) % 30) + 1;
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

		if ($aar == 1744)
		{
			$md= 3;
			$dg= 29;
		}
		$this->pdg= $dg;
		$this->pmd= $md;
		$this->pjtal= self::_jdag($aar, $md, $dg);
	}


	/**
	*	private _yearClass()											** Parallel til Algoritme 1 **
	*
	*	0: Ugyldigt år
	*	1: Almindeligt år med 365 dage
	*	2: Skudår med 366 dage
	*	3: Overgangsåret 1700 med 355 dage
	*/

	private function _yearClass($year) : int
	{
		$this->yearClass= 0;

		if (self::MINAAR <= $year && $year <= self::MAXAAR)
		{
			if (1700 < $year)
			{
				$this->yearClass= ($year % 4) ? 1 : ($year % 100) ? 2 : ($year % 400) ? 1 : 2;

			}
			else
			{
				if ($year < 1700)
				{
					$this->yearClass= ($year % 4) ? 1 : 2;
				}
				else
				{
					$this->yearClass= 3;
				}
			}
		}

		return $this->yearClass;
	}


	/**
	*	_validDato()														** Parallel til Algorime 2 **
	*
	*	Forudsætter at vi har et gyldigt år
	*/

	public function _validDato($d, $mmm) : bool
	{
		if ($this->yearClass != 0)
		{
			if (0 < $mmm && $mmm < 13)
			{
				if (0 < $d && $d < 32)
				{
					switch($mmm)
					{
						case 2: switch ($this->yearClass)
						{
							case 1: if (28 < $d) return false;
							break;

							case 2: if (29 < $d) return false;
							break;

							case 3: if (18 < $d) return false;
						}
						break;

						case 4:
						case 6:
						case 9:
						case 11: if (30 < $d) return false;

					}
					return true;

				}
			}

		}
		return false;
	}


	/**
	*	_dagiaar																	** Parallel til Algorime 3 **
	*
	*	- Beregner dagens nummer i året ud fra datoen. Gælder for Dansk/Norsk kalender 600-3199
	*
	*	- Forudsætter at vi har et gyldigt år
	*/

	public function _dagiaar($d, $m) : int
	{
		$dagiaar;

		/* Beregner først korrektionen til dagnummer */

		$korr= ($this->yearClass == 3) ? 12 : (3 - $this->yearClass);

		$dagiaar= intdiv(214 * $m - 211, 7) + $d;

		return (($dagiaar + $korr) > 61) ? $dagiaar - $korr : $dagiaar;

	}


	/**
	* getMonths()
	*
	* - del af iCalendarView snitfladen
	*
	**/

	public function getMonths() : array
	{
		return self::MONTHS_DA;
	}


	/**
	* 	getYear()
	*
	* - del af iCalendarView snitfladen
	*
	**/

	public function getYear() : int
	{
		return $this->year;
	}


	public function getAdatoClass() : int
	{
		return $this->adatoClass;
	}


	public function getDagiaar() : int
	{
		return $this->dagiaar;
	}



	/**
	*	getDescription()
	*
	*  Dansk eller Latinsk betegnelse afhængig af variablen isLatin;
	*/

	public function getDescription() : String
	{
		return $this->description[0];
	}

	public function getTooltip() : String
	{
		return $this->description[2];
	}

	public function _getUgedag() : int
	{
		return $this->ajtal % 7;
	}


	public function getUgedag() : String
	{
		return self::UGEDAG_DA[$this->_getUgedag()];

	}



	public function isValid() : bool
	{
		return ($this->yearClass != 0);
	}




	/**
	*	setYear()
	*
	*	- Checker at året ligger i intervalle 600-3200
	*	- Finder datoen for påskesøndag (som bestemmer de øvrige helligdage i  kirkeåret
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
	* setDato()
	*
	*/

	public function setDato($d, $m) : bool
	{
		$this->adg= 			$d;
		$this->amd= 			$m;
		$this->ajtal= 		self::_jdag($this->year, $m, $d);
		$this->augedag=   $this->getUgedag();
		$this->description= ["", "", ""];

		$this->dagiaar= $this->_dagiaar($d, $m);

		// this->index();

		if (self::_validDato($d, $m))
		{
			switch($this->_getUgedag())
			{
				case 5:
				$this->adatoClass=2;
				break;

				case 6:
				$this->adatoClass=3;
				break;

				default:
				$this->adatoClass=1;
				break;
			}
		}
		else $this->adatoClass= 0;

		/* Søndagene efter Hellig 3 Konger */

		if ($this->adatoClass == 3 && 5 < $this->dagiaar )
		{
			/* Søndagene efter Hellig 3 Konger */

			switch (intdiv(($this->dagiaar - 5), 7))
			{
				case 0: $this->description= ["bauer.h3k1", "", ""];
				break;

				case 1: $this->description= ["bauer.h3k2", "", ""];
				break;

				case 2: $this->description= ["bauer.h3k3", "", ""];
				break;

				case 3: $this->description= ["bauer.h3k4", "", ""];
				break;

				case 4: $this->description= ["bauer.h3k5", "", ""];
				break;

				case 5: $this->description= ["bauer.h3k6", "", ""];
				break;

			}

		}

		/* Check for forskydelige dage i forhold til Påskedag */

		switch($this->ajtal - $this->pjtal)
		{
			case	-63: $this->description = ["bauer.septuagesima", "", ""];
			$gamme= '_NYTAAR';
			break;

			case	-56: $this->description = ["bauer.sexagesima", "",  ""];
			break;

			case  -49: $this->description = ["bauer.fastelavn", "", "bauer.fastelavnTT"];
			break;

			case  -46: $this->description = [ "bauer.askeonsdag", "", "bauer.askeonsdagTT"];
			break;

			case  -42: $this->description = ["bauer.invocavit", "", "bauer.invocavitTT"];
			break;

			case  -35: $this->description = ["bauer.reminiscere", "", "bauer.reminiscereTT"];
			break;

			case  -28: $this->description = ["bauer.oculi", "", "bauer.oculiTT"];
			break;

			case  -21: $this->description = ["bauer.laetare", "", "bauer.laetareTT"];
			break;

			case  -18: $this->description = ["bauer.magniscrut", "", ""];
			break;

			case  -14: $this->description = ["bauer.judica", "", "bauer.judicaTT"];
			break;

			case   -7: $this->description = ["bauer.palmesøndag", "", "bauer.palmesøndagTT"];
			break;


			case   -3: $this->description = ["bauer.skærtorsdag", "", "bauer.skærtorsdagTT"];
			$this->adatoClass=3;
			break;

			case   -2: $this->description = ["bauer.langfredag", "", "bauer.langfredagTT"];
			$this->adatoClass=3;
			break;

			case    0: $this->description = ["bauer.påskedag", "", "bauer.påskedagTT"];
			break;

			case    1: $this->description = ["bauer.påskedag2", "", ""];
			$this->adatoClass=3;
			break;

			case    2: if ($this->year < 1771)
			{
				$this->description = ["bauer.påskedag3", "", "bauer.påskedag3"];
				$this->adatoClass=3;
			}
			break;

			case    7: $this->description = ["bauer.quasimodogeniti", "", "bauer.quasimodogenitiTT"];
			break;

			case   14: $this->description = ["bauer.misericordia", "", "bauer.misericordiaTT"];
			break;

			case   21: $this->description = ["bauer.jubilate", "Dom Jubilate", ""];
			break;

			case   26: if (1685 < $this->year)		// Store Bededag indføres 27/3 1686
			{
				$this->description = ["bauer.storebededag", "", "bauer.storebededagTT"];
				$this->adatoClass=3;
			}
			break;

			case   28: $this->description = ["bauer.cantate", "", ""];
			break;

			case   35: $this->description = ["bauer.rogate", "", "bauer.rogateTT"];
			break;

			case   39: $this->description = ["bauer.kristihimmelfart", "", ""];
			$this->adatoClass=3;
			break;

			case   42: $this->description = ["bauer.exaudi", "", ""];
			break;

			case   49: $this->description = ["bauer.pinsedag", "", "bauer.pinsedagTT"];
			break;

			case   50: $this->description = ["bauer.pinsedag2", "", "bauer.pinsedag2TT"];
			$this->adatoClass=3;
			break;

			case   51: if ($this->year < 1771)
			{
				$this->description = ["bauer.pinsedag3", "", "bauer.pinsedag3TT"];
				$this->adatoClass=3;
			}
			break;

			case   56: $this->description = ["bauer.trinitatis", "", "bauer.trinitatisTT"];
			break;

			case   63: $this->description = ["bauer.trinitatis01", "", ""];
			break;

			case   67: if ($this->year < 1771)
			{
				$this->description = ["bauer.kristilegemsfest", "", ""];
			}
			break;

			case   70: $this->description = ["bauer.trinitatis02", "", ""];
			break;

			case   77: $this->description = ["bauer.trinitatis03", "", ""];
			break;

			case   84: $this->description = ["bauer.trinitatis04", "", ""];
			break;

			case   91: $this->description = ["bauer.trinitatis05", "", ""];
			break;

			case   98: $this->description = ["bauer.trinitatis06", "", ""];
			break;

			case  105: $this->description = ["bauer.trinitatis07", "", ""];
			break;

			case  112: $this->description = ["bauer.trinitatis08", "", ""];
			break;

			case  119: $this->description = ["bauer.trinitatis09", "", ""];
			break;

			case  126: $this->description = ["bauer.trinitatis10", "", ""];
			break;

			case  133: $this->description = ["bauer.trinitatis11", "", ""];
			break;

			case  140: $this->description = ["bauer.trinitatis12", "", ""];
			break;

			case  147: $this->description = ["bauer.trinitatis13", "", ""];
			break;

			case  154: $this->description = ["bauer.trinitatis14", "", ""];
			break;

			case  161: $this->description = ["bauer.trinitatis15", "", ""];
			break;

			case  168: $this->description = ["bauer.trinitatis16", "", ""];
			break;

			case  175: $this->description = ["bauer.trinitatis17", "", ""];
			break;

			case  182: $this->description = ["bauer.trinitatis18", "", ""];
			break;

			case  189: $this->description = ["bauer.trinitatis19", "", ""];
			break;

			case  196: $this->description = ["bauer.trinitatis20", "", ""];
			break;

			case  203: $this->description = ["bauer.trinitatis21", "", ""];
			break;

			case  210: $this->description = ["bauer.trinitatis22", "", ""];
			break;

			case  217: $this->description = ["bauer.trinitatis23", "", ""];
			break;

			case  224: $this->description = ["bauer.trinitatis24", "", ""];
			break;

			case  231: $this->description = ["bauer.trinitatis25", "", ""];
			break;

			case  238: $this->description = ["bauer.trinitatis26", "", ""];
			break;

			case  245: $this->description = ["bauer.trinitatis27", "", ""];
			break;


		}

		/* Check for faste datoer */

		switch ($d * 100 + $m )
		{
			case  101: $this->description = ["bauer.nytår", "", "bauer.nytårTT"];
			$this->adatoClass=3;
			break;

			case  501: $this->description = ["bauer.h3k", "", "bauer.h3kTT"];
			if ($this->year < 1771)
			$this->adatoClass=3;
			break;

			case 2501: $this->description = ["bauer.paulus", "", ""];
			break;

			case  202: $this->description = ["bauer.kyndelmisse", "", "bauer.kyndelmisseTT"];
			if ($this->year < 1771)
			$this->adatoClass=3;
			break;

			case  105: if (1890 < $this->year)
			{
				$this->description = ["bauer.1maj", "", "bauer.1majTT"];
			}
			break;

			case 2503: if ($this->year < 1771)
			$this->description = ["bauer.mariæbebud", "", "bauer.mariæbebudTT"];
			$this->adatoClass=3;
			break;

			case  506: if (1848 < $this->year)
			{
				$this->description = ["bauer.grundlovsdag", "", ""];
			}
			break;

			case 2406: $this->description = ["bauer.sankthans", "", "bauer.sankthansTT"];
			if ($this->year < 1771)
			$this->adatoClass=3;
			break;

			case 2706: $this->description = ["bauer.syvsoverdag", "", ""];
			break;

			case  207: if ($this->year < 1771)
			{
				$this->description = ["bauer.mariæbesøg", "",  "bauer.mariæbesøgTT"];
				$this->adatoClass=3;
			}
			break;

			case  408: $this->description = ["bauer.dominicus", "", ""];
			break;

			case  708: $this->description = ["bauer.donatus", "", ""];
			break;

			case  1508: $this->description = ["bauer.assumptio", "", ""];
			break;


			case 2909: if ($this->year < 1771)
			{
				$this->description = ["bauer.mikkelsdag", "", "bauer.mikkelsdagTT"];
				$this->adatoClass=3;
			}
			break;

			case 2310: if (1728 < $this->year && $this->year < 1771)
			{
				$this->description = ["bauer.taksigelse", "bauer.taksigelse", "bauer.taksigelseTT"];
				$this->adatoClass=3;
			}
			break;

			case  111: $this->description = ["bauer.allehelgen", "", "bauer.allehelgenTT"];
			if ($this->year < 1771)
			{
				$this->adatoClass=3;
			}
			break;

			case  211: if ($this->_getUgedag() != 6)
			{
				$this->description = ["bauer.allesjæle", "", "bauer.allesjæleTT"];
			}
			break;

			case  311: if ($this->_getUgedag() == 0)
			{
				$this->description = ["bauer.allesjæle", "", "bauer.allesjæleTT"];
			}
			break;

			case 1111: $this->description = ["bauer.mortensdag", "", "bauer.mortensdagTT"];
			if ($this->year < 1771)
			{
				$this->adatoClass=3;
			}
			break;

			case 1312: $this->description = ["bauer.lucia", "", ""];
			break;


			case 2412: $this->description = ["bauer.juleaften", "", ""];
			break;

			case 2512: $this->description = ["bauer.juledag", "", ""];
			$this->adatoClass=3;
			break;

			case 2612: $this->description = ["bauer.2juledag", "", ""];
			$this->adatoClass=3;
			break;

			case 2712: if ($this->year < 1771)
			{
				$this->description = ["bauer.3juledag", "", "bauer.3juledagTT"];
				$this->adatoClass=3;
			}
			break;


			default:   break;
		}

		if ($this->_getUgedag() == 6)
		{
			if (0 < $dummy= ($this->_dagiaar(25,12) - $this->dagiaar))
			{
				switch(intdiv($dummy, 7))
				{

					case 0: $this->description= ["bauer.advent4", "", ""];
					break;

					case 1: $this->description= ["bauer.advent3", "", ""];
					break;

					case 2: $this->description= ["bauer.advent2", "", ""];
					break;

					case 3: $this->description= ["bauer.advent1", "", "bauer.advent1TT"];
					break;
				}
			}
		}

		return $this->_validDato($d, $m);
	}


	//
	//	Diverse debugging og test functioner
	//
	//

	public function testDay() : int
	{
		$x= new KalenderGrg;
		$z= $x->pascha($this->year);

		return $z->getDay();
		//return 12;
	}

	public function testMonth() : int
	{
		$x= new KalenderGrg;
		$z= $x->pascha($this->year);

		return $z->getMonth();
		// return 12;
	}

	public function getPjtal() : int
	{
		return $this->pjtal;
	}


	public function jtaltest() : int
	{
		$x= new KalenderDA;

		$y= $x->jday(new Dato($this->pdg, $this->pmd, $this->year));
		return $y->getJd();
		//return 252525;
	}

	/**
	*	getCalendarName()
	*
	* - del af iCalendarView snitfladen
	*/

	public function getCalendarName() : String
	{
		return 'Danmark';
	}


}
