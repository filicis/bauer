<?php

namespace App\Utils;

/**
 *	interface iKalenderaar 
 *
 *	Generisk klasse for �rs kalendere 
 */

interface iKalenderaar
{
    public function getDayMax() : int; 
    public function getMonthMax() : int;
    
    public function getDagType() : int;
    public function getDagBetegnelse() : String;
    public function getDagTooltip() : String;
    public function getUgedag() : String;
    
	public function selectYear($y) : bool;
	public function selectDate($d, $m) : bool;
}