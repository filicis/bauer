<?php

namespace App\Utils;

/**
 *	interface iKalender 
 *
 *	Grundl�ggende klasse for alle kalendrer der rod i den Julianske kalender
 */

interface iKalender
{
	public function jdag($d, $m, $y) : integer;  	
}