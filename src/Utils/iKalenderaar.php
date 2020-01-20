<?php

namespace App\Utils;

/**
 *	interface iKalenderaar
 *
 *	Generisk klasse for års kalendere
 *
 */

interface iKalenderaar
{
	public function getDayMax() : int;
	public function getMonthMax() : int;

	public function getDagType() : int;
	public function getDagBetegnelse() : String;
	public function getDagTooltip() : String;
	public function getUgedag() : String;


		/**
		 *	getAarType()
		 *
		 * 	0: betyder ugyldigt år, men iøvrigt defineres parameteren individuelt for hver kalender
		 * 		 For den Dansk/Norske kalender f.eks:
		 *  1: Almindeligt år
     *  2: Almindeligt skudår
     *  3: Overgangsåret 1700
     *
		 **/

  public function getAarType() : int;


		/**
		 *	isValid();
		 *
		 *	TRUE hvis vi arbejder med et gyldigt år
		 *
		 **/

	public function isValid() : bool;


	public function selectYear($y) : bool;
	public function selectDate($d, $m) : bool;
}