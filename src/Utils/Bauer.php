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
  	  
  	  /* Søndagene efter Hellig 3 Konger */
  	
  	if ($this->adatoClass == 3)
  	{
  	    /* Søndagene efter Hellig 3 Konger */
  	    
  	  switch (intdiv(($this->dagiaar - 6), 7))
  	  {
  	    case 0: $this->description= ["1. S e H3K", "Dom 1 p Epiph", ""];
  	            break;
  	            
  	    case 1: $this->description= ["2. S e H3K", "Dom 2 p Epiph", ""];
  	            break;

  	    case 2: $this->description= ["3. S e H3K", "Dom 3 p Epiph", ""];
  	            break;

  	    case 3: $this->description= ["4. S e H3K", "Dom 4 p Epiph", ""];
  	            break;

  	    case 4: $this->description= ["5. S e H3K", "Dom 5 p Epiph", ""];
  	            break;

  	    case 5: $this->description= ["6. S e H3K", "Dom 6 p Epiph", ""];
  	            break;
    
  	  }
  	  
  	}  
  	
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
  							 
  		case  -42: $this->description = ["1. S i Fasten", "Dom Invocavit", ""];
  							 break;
  							 
  		case  -35: $this->description = ["2. S i Fasten", "Dom Reminiscere", ""];
  							 break;
  							 
  		case  -28: $this->description = ["3. S i Fasten", "Dom Oculi", ""];
  							 break;
  							 
  		case  -21: $this->description = ["4. S i Fasten", "Dom Laetare", ""];
  							 break;
  							 
  		case  -14: $this->description = ["5. S i Fasten", "Dom Judica", ""];
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
  	  					 
  	  case    2: if ($this->aar < 1771)
  	             { 
  	  						 $this->description = ["3. Påskedag", "", "Helligdag frem til 1770"];
  		             $this->adatoClass=3;
  		           }  
  	  					 break;
  	  					 
  	  case    7: $this->description = ["1. S. e Påske", "Quasimodogeniti", "Dagen kaldes også for Hvide søndag. Hvide søndag er en gammel betegnelse for søndagen efter Påske (lat. Dominica in albis depositis = de aflagte hvide klæders søndag). I Oldkirken blev dåben ofte henlagt til påskenat, hvorefter de nydøbte bar deres hvide dåbsklædning hele påskeugen, og derefter, nemlig til hvide søndag, aflagde den."];
  	  					 break;
  	  					 
  	  case   14: $this->description = ["2. S. e Påske", "Dom Misericordia", ""];
  	  					 break;
  	  					 
  	  case   21: $this->description = ["3. S. e Påske", "Dom Jubilate", ""];
  	  					 break;
  	  					 
  	  case   26: if (1685 < $this->aar)		// Store Bededag indføres 27/3 1686 
  	  					 {	
  	  					 	 $this->description = ["Store Bededag", "Feria Precat Extraord", ""];
  		             $this->adatoClass=3;
  		           }
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

  	  case   51: if ($this->aar < 1771)
  	             {
  	               $this->description = ["3. Pinsedag", "", "Helligdag frem til 1770"];
  		             $this->adatoClass=3;
  		           }
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

  	  case  133: $this->description = ["11. Søn Trin", "Dom 11 p Trinit", ""];
  	             break;

  	  case  140: $this->description = ["12. Søn Trin", "Dom 12 p Trinit", ""];
  	             break;

  	  case  147: $this->description = ["13. Søn Trin", "Dom 13 p Trinit", ""];
  	             break;

  	  case  154: $this->description = ["14. Søn Trin", "Dom 14 p Trinit", ""];
  	             break;

  	  case  161: $this->description = ["15. Søn Trin", "Dom 15 p Trinit", ""];
  	             break;

  	  case  168: $this->description = ["16. Søn Trin", "Dom 16 p Trinit", ""];
  	             break;

  	  case  175: $this->description = ["17. Søn Trin", "Dom 17 p Trinit", ""];
  	             break;

  	  case  182: $this->description = ["18. Søn Trin", "Dom 18 p Trinit", ""];
  	             break;

  	  case  189: $this->description = ["19. Søn Trin", "Dom 19 p Trinit", ""];
  	             break;

  	  case  196: $this->description = ["20. Søn Trin", "Dom 20 p Trinit", ""];
  	             break;

  	  case  203: $this->description = ["21. Søn Trin", "Dom 21 p Trinit", ""];
  	             break;

  	  case  210: $this->description = ["22. Søn Trin", "Dom 22 p Trinit", ""];
  	             break;

  	  case  217: $this->description = ["23. Søn Trin", "Dom 23 p Trinit", ""];
  	             break;

  	  case  224: $this->description = ["24. Søn Trin", "Dom 24 p Trinit", ""];
  	             break;

  	  case  231: $this->description = ["25. Søn Trin", "Dom 25 p Trinit", ""];
  	             break;

  	  case  238: $this->description = ["26. Søn Trin", "Dom 26 p Trinit", ""];
  	             break;

  	  case  245: $this->description = ["27. Søn Trin", "Dom 27 p Trinit", ""];
  	             break;
  	             

  	}
  	
  	/* Check for faste datoer */
  	
  	switch ($d * 100 + $m )
  	{
  		case  101: $this->description = ["Nytår", "Circumcisio Christi", ""];
  		           $this->adatoClass=3;
  		           break;
  		          
  		case  501: $this->description = ["Hellig 3 konger", "Epiphanias", "Helligdag frem til 1770"];
  							 if ($this->aar < 1771)
  		             $this->adatoClass=3;
  		           break;

  		case  202: $this->description = ["Kyndelmisse", "Purificatio Mariae", ""];
  		           if ($this->aar < 1771)
  		             $this->adatoClass=3;
  		           break;
  		          
  		case  105: $this->description = ["1. Maj", "", ""];
  		           break; 
  		           
 // 		case 2503: $this->description = ["Maria bebudelse", "", ""];
 // 		           break;	
  		          
  		case  506: $this->description = ["Grundlovsdag", "", ""];
  		           break; 
  		          
  		case  207: if ($this->aar < 1771)
  		           { 
  								 $this->description = ["Mariæ besøgelsesdag", "Visitationes Mariae", ""];
  		             $this->adatoClass=3;
  		           }
  		           break; 
  		          
  		case 2406: $this->description = ["Sct Hans", "Fest Nat St Johs Baptistae", "Helligdag frem til 1770"];
  							 if ($this->aar < 1771)
  		             $this->adatoClass=3;
  		           break; 

  		case 2310: if (1728 < $this->aar && $this->aar < 1771)
  		           { 
  								 $this->description = ["Taksigelsesdag", "Festum Gratiarum 1725", "Taksigelse for afslutningen af Københavns brand 1728"];
  		             $this->adatoClass=3;
  		           }
  		           break; 
 	          
  		          
  		case 2412: $this->description = ["Juleaftensdag", "", ""];
  		           break;
  		           
  		case 2512: $this->description = ["Juledag", "Natalis Domini", ""];
  		           $this->adatoClass=3;
  		           break;
  		           
  		case 2612: $this->description = ["2. Juledag", "Fest St Stephanus Promartyris", ""];
  		           $this->adatoClass=3;
  		           break;
  		           
  		case 2712: if ($this->aar < 1771)
  		           { 
  								 $this->description = ["3. Juledag", "Fest St. Johannis Evangelistae", "Helligdag frem til 1770"];
  		             $this->adatoClass=3;
  		           }
  		           break;
  		           
  		          
  		default:   break;
  	}
  	
  	if ($this->_getUgedag() == 6)
  	{
  		$dummy= ($this->_dagiaar(25,12) - $this->dagiaar);
  		switch(intdiv($dummy, 7))
  		{
  			
  			case 0: $this->description= ["4. S i Advent", "Dom 4 Advent", ""];
  			        break;

  			case 1: $this->description= ["3. S i Advent", "Dom 3 Advent", ""];
  			        break;

  			case 2: $this->description= ["2. S i Advent", "Dom 2 Advent", ""];
  			        break;

  			case 3: $this->description= ["1. S i Advent", "Dom 1 Advent", ""];
  			        break;
  		}
  	}
  	  
  	
  	return $this->_validDato($d, $m);
  }

}