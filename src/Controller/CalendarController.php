<?php

namespace App\Controller;

use App\Form\AarstalType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\Bauer;

class CalendarController extends AbstractController
{
    private $session;
	  public $aarstal= 1850;
	  

	public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    
    
    /**
     *  about()
     *
     * @Route("/about", name="about")
     *
     */
          
    public function about(Request $request)
    {

        $test= ['message' => 'Entest'];
        $form= $this->createFormBuilder($test /*, ['attr' => ['class' => 'form-inline']] */ )
          ->add('year', TextType::class /*, ['attr' => ['class' => 'form-control mr-sm-2', 'type' => 'number', 'placeholder' => '�rstal', 'aria-label' => 'Search']]*/ )
          ->add('send', SubmitType::class , ['attr' => ['label' => 'V�lg']] )
          ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $data= $form->getData();
            $this->session->set('Bauer', $data['year']); 
            
             return $this->redirectToRoute('calendar');
            
            //foreach($data as $x => $x_value)
            //{
                  $this->addFlash('notice', $data['year']);
            //    }

        }

    	
        return $this->render('about/index.html.twig', [
            'controller_name' => 'CalendarController', 'our_form' => $form, 'our_form' => $form->createView(),
        ]);
        
    }
  
	
	
    /**
     * @Route("/calendar/{aarstal}", name="calendar1")
     */
    public function index($aarstal)
    {
        $this->session->set('Bauer', $aarstal); 
    	$bauer= new Bauer($aarstal);
   	
    	
    	
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
            'bauer' => $bauer,
        ]);
    }


    /**
     * @Route("/", name="calendar")
     */
     
    public function index1(Request $request)
    {
    	$bauer= new Bauer($this->session->get('Bauer', 1960));

        $test= ['message' => 'Entest'];
        $form= $this->createFormBuilder($test /*, ['attr' => ['class' => 'form-inline']] */ )
          ->add('year', TextType::class /*, ['attr' => ['class' => 'form-control mr-sm-2', 'type' => 'number', 'placeholder' => '�rstal', 'aria-label' => 'Search']]*/ )
          ->add('send', SubmitType::class , ['attr' => ['label' => 'V�lg']] )
          ->getForm();
   	
    	$form->handleRequest($request);
    	
    	
    	
    	if($form->isSubmitted() /* && $form->isValid() */ )
    	{
            $data= $form->getData();
            $this->session->set('Bauer', $data['year']); 

             return $this->redirectToRoute('calendar');

    	}
    	
  	
    	
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
            'bauer' => $bauer, 
            'our_form' => $form, 
            'our_form' => $form->createView(),
        ]);
    }
    
    
    public function getAarstal() : integer
    {
    	return $this->aarstal;
    	
    }
    
    /**
     *	aartype
     *
     *	Returnerer
     *	0: fejl, �ret ligger uden for intervallet 600 - 3199
     *  1: almindeligt �r med 365 dage
     *	2: skud�r med 366 dage
     *	3: overgangs�ret 1700 med 355 dage	
     */
    
    public function aartype($aar) : integer
    {
    	if (599 < $aar and $aar < 3200)
    	{
    		if (1700 < $aar) /* Gregoriansk kalender */
    		{
    			if (($aar % 4) == 0)
    			{
    				if (($aar % 100) == 0)
    				{
    					if (($aar % 400) != 0) 
    					{
    					  return 1; 
    					}
    				}
    				return 2;
    			}
    			return 1; /* Almindeligt �r med 365 dage */
    		}
    		if ($aar < 1700) /* Juliansk kalender */
    		{
    			return (($aar % 4) != 0) ? 1 : 2;
    		}
    		return 3;
    	}
    	return 0;
    }
    
    
    /**
     *	jdag
     *
     *	Beregner Juliansk dagtal for datoen
     *	- g�lder for dansk-norsk kalender 600-3199
     *	jdag= 0 svarer til -4712 jan 01, juliansk eller -4713 nov 24, gregoriansk
     *
     */
    
    public function jdag($a, $m, $d) : integer
    {
    	$totdato; 
    	$nuldag; 
    	$cycklgd; 
    	$cykantal; 
    	$aaricyk; 
    	$aarhundicyk;
    	
    	/* Beregn nuldag og cyklisk l�ngde for begge kalendere, sk�ringsdatoer for Danmark-Norge: 1700 feb 18 - 1700 mar 01 */
    	
    	$totdato= (a * 100) + m;
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
   		
   		if (m < 3)										/* Januar og februar henregnes til foreg�ende �r */
    	{
    	  $m+= 12;
    	  $a-= 1;
    	}
    	
    	$cykantal= intdiv($a, 400);					/* Antal cyklusser � 400 �r */
    	
    	$aaricyk= $a - ($cykantal * 400);
    	$aarhundicyk= intdiv($aaricyk, 100);
    	$aarrest= $aaricyk % 100;
    	
    	return $nuldag + ($cykantal * $cyklgd) + intdiv(($aarhundicyk * $cyklgd), 4) + intdiv(($aarrest * 1461), 4) + intdiv(((153 * $m) - 457), 5) + $d;
    } 
    
    
    /*
     *
     *
     */
     
	public function months() : iterable 
	{
		return ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"];
	}   
}
