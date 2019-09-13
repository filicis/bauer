<?php

namespace App\Utils;


class Bauer
{
	const MONTHS_DA= ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"];

	const UGEDAG_DA= ["Ma", "Ti", "On", "To", "Fr", "Lø", "SØ"];
	
	const MINAAR= 600;
	const MAXAAR= 3199;
	
	
	const _NYTAAR=    0;
	const _H3KONG=    1;
	

	
	public $nytarray = array (
	  '_NYTAAR' => ["Nytår", "", "" ],
	  '_H3KONG' => ["Hellig 3 Konger", "", ""],
	);
	
	
	protected $latin= False;
		    

  protected $aar= 0;
  protected $aartype= 0;
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
	
	  /**
	    *	adgClass
	    *
	    *  
	    */
	
	private $adatoClass;
	

  public function __construct($nytaar, $latin)
	{
		$this->setAar($nytaar);
		$this->latin= $latin;
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
  	$aar= $this->aar;
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
   *	static _aartype()											** Parallel til Algoritme 1 **
   *
   *	0: Ugyldigt år
   *	1: Almindeligt år med 365 dage
   *	2: Skudår med 366 dage
   *	3: Overgangsåret 1700 med 355 dage
   */  
    
  public static function _aartype($aar) : int
  {
  	$aartype= 0;
  	
  	if (self::MINAAR <= $aar && $aar <= self::MAXAAR)
  	{
  		if (1700 < $aar)
  		{
  			$aartype= ($aar % 4) ? 1 : ($aar % 100) ? 2 : ($aar % 400) ? 1 : 2;
  			
  		}
  		else
  		{
  			if ($aar < 1700)
  			{
  				$aartype= ($aar % 4) ? 1 : 2;
  			}
  			else
  			{
  			  $aartype= 3;
  			}
  		}
  	}

  	return $aartype;
  }
  
  
  /**
   *	_validDato()														** Parallel til Algorime 2 **
   *
   *	Forudsætter at vi har et gyldigt år
   */
   
   public function _validDato($d, $mmm) : bool
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

	  $korr= ($this->aartype == 3) ? 12 : (3 - $this->aartype);
	  
	  $dagiaar= intdiv(214 * $m - 211, 7) + $d;
	  
	  return (($dagiaar + $korr) > 61) ? $dagiaar - $korr : $dagiaar;

	}    
    



  public function months() : iterable
  {
  	return self::MONTHS_DA;
  }
  
  
  public function getAar() : int
  {
  	return $this->aar;
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
  	return $this->isLatin() ? $this->description[1] : $this->description[0];
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
  
  
  public function isLatin(): bool
  {
  	return $this->latin;
  }
  
  
  public function isValid() : boolean
  {
  	return true;
  }
  

  /**
   *	setAar()
   *
   *	- Checker at året ligger i intervalle 600-3200
   *	- Finder datoen for påskesøndag (som bestemmer de øvrige helligdage i  kirkeåret
   */

  public function setAar($nytaar) : int
  {
  	$this->aartype= self::_aartype($nytaar);
  	
  	$this->aar= (($this->aartype != 0) ? $nytaar : 0);
  	$this->_pascha();
  	return $this->aar;
  }
  
  /**
   * setDato()
   *
   */

  public function setDato($d, $m) : bool
  {
  	$this->adg= 			$d;
  	$this->amd= 			$m;
  	$this->ajtal= 		self::_jdag($this->aar, $m, $d);
  	$this->augedag=   $this->getUgedag(); 
  	$this->description= ["", "", ""];
  	
  	$this->dagiaar= $this->_dagiaar($d, $m);
  	
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
  	
  	/* Check for forskydelige dage i forhold til Påskedag */
  	
  	switch($this->ajtal - $this->pjtal)
  	{
  		case	-63: $this->description = ["2. S f Fastelavn", "Septuagesima", ""];
  								$gamme= '_NYTAAR';
  		           break;
  		
  		case	-56: $this->description = ["1. S f Fastelavn", "Sexagesima",  ""];
  							 break;
  		
  		case  -49: $this->description = ["Fastelavn", "Dom Esto mihi", ""];
  							 break;
  							 
  		case  -46: $this->description = [ "Askeonsdag", "Dies Pulveris", "Askeonsdag, Dies Ater, Dies Cinerum, Dies Pulveris"];
  							 break;
  							 
  		case  -42: $this->description = ["", "Dom Invocavit", ""];
  							 break;
  							 
  		case   -7: $this->description = ["Palmesøndag", "Dom Palmarum", ""];
  							 break;

  		case   -3: $this->description = ["Skærtorsdag", "Dom Viridium", ""];
  		           $this->adatoClass=3;
  							 break;

  		case   -2: $this->description = ["Langfredag", "", ""];
  		           $this->adatoClass=3;
  							 break;
  							 
  	  case    0: $this->description = ["Påskedag", "Dom Pasces", ""];
  	  					 break;
  	  					
  	  case    1: $this->description = ["2. Påskedag", "", ""];
  		           $this->adatoClass=3;
  	  					 break;
  	  					 
  	  case    7: $this->description = ["1. S. e Påske", "Quasimodogeniti", ""];
  	  					 break;
  	  					 
  	  case   14: $this->description = ["2. S. e Påske", "Dom Misericordia", ""];
  	  					 break;
  	  					 
  	  case   21: $this->description = ["3. S. e Påske", "Dom Jubilate", ""];
  	  					 break;
  	  					 
  	  case   26: $this->description = ["Store bededag", "", ""];
  		           $this->adatoClass=3;
  	  					 break;
  	  						  	  					 
  	  case   28: $this->description = ["4. S. e Påske", "Dom Cantate", ""];
  	  					 break;
  	  					 
  	  case   35: $this->description = ["5. S e Påske", "Dom Rogate", ""];
  	  					 break;
  	  						
  	  case   39: $this->description = ["Kristi Himmelfart", "Fest Ascensio Christi", ""];
  		           $this->adatoClass=3;
  	  					 break;

  	  case   42: $this->description = ["6. S e Påske", "Dom Exaudi", ""];
  	  					 break;
  	  						
  	  case   49: $this->description = ["Pinsedag", "Pentecoste", ""];
  	  					 break;

  	  case   50: $this->description = ["2. Pinsedag", "", ""];
  		           $this->adatoClass=3;
  	  					 break;

  	  case   56: $this->description = ["Trinitatis", "Trinitatis", ""];
  	             break;

  	  case   63: $this->description = ["1. Søn Trin", "Dom 1 p Trinit", ""];
  	             break;
  	             
  	  case   70: $this->description = ["2- Søn Trin", "Dom 2 p Trinit", ""];
  	             break;
  	             
  	  case   77: $this->description = ["3. Søn Trin", "Dom 3 p Trinit", ""];
  	             break;

  	  case   84: $this->description = ["4. Søn Trin", "Dom 4 p Trinit", ""];
  	             break;

  	  case   91: $this->description = ["5. Søn Trin", "Dom 5 p Trinit", ""];
  	             break;

  	  case   98: $this->description = ["6. Søn Trin", "Dom 6 p Trinit", ""];
  	             break;

  	  case  105: $this->description = ["7. Søn Trin", "Dom 7 p Trinit", ""];
  	             break;

  	  case  112: $this->description = ["8. Søn Trin", "Dom 8 p Trinit", ""];
  	             break;

  	  case  119: $this->description = ["9. Søn Trin", "Dom 9 p Trinit", ""];
  	             break;

  	  case  126: $this->description = ["10. Søn Trin", "Dom 10 p Trinit", ""];
  	             break;

  	  case  161: $this->description = ["15. Søn Trin", "Dom 15 p Trinit", ""];
  	             break;

  	  case  196: $this->description = ["20. Søn Trin", "Dom 20 p Trinit", ""];
  	             break;

  	  case  231: $this->description = ["25. Søn Trin", "Dom 25 p Trinit", ""];
  	             break;
  	             

  	}
  	
  	/* Check for faste datoer */
  	
  	switch ($d * 100 + $m )
  	{
  		case  101: $this->description = ["Nytår", "Circumcisio Christi", ""];
  		           $this->adatoClass=3;
  		           break;
  		          
  		case  202: $this->description = ["Kyndelmisse", "Purificatio Mariae", ""];
  		           break;

  		case  501: $this->description = ["Hellig 3 konger", "Epiphanias", ""];
  		           break;
  		          
  		case  105: $this->description = ["1. Maj", "", ""];
  		           break; 
  		           
 // 		case 2503: $this->description = ["Maria bebudelse", "", ""];
 // 		           break;	
  		          
  		case  506: $this->description = ["Grundlovsdag", "", ""];
  		           break; 
  		          
  		case 2406: $this->description = ["Sct Hans", "Fest Nat St Johs Baptistae", ""];
  		           break; 
  		          
  		case 2412: $this->description = ["Juleaftensdag", "", ""];
  		           break;
  		           
  		case 2512: $this->description = ["Juledag", "Natalis Domini", ""];
  		           $this->adatoClass=3;
  		           break;
  		           
  		case 2612: $this->description = ["2. Juledag", "Fest St Stephanus Promartyris", ""];
  		           $this->adatoClass=3;
  		           break;
  		          
  		default:   break;
  	}
  	
  	return $this->_validDato($d, $m);
  }

}