<?php

namespace App\Utils;

/**
 *	interface iKalenderaar
 *
 *	Generisk klasse for �rs kalendere
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
		 * 	0: betyder ugyldigt �r, men i�vrigt defineres parameteren individuelt for hver kalender
		 * 		 For den Dansk/Norske kalender f.eks:
		 *  1: Almindeligt �r
     *  2: Almindeligt skud�r
     *  3: Overgangs�ret 1700
     *
		 **/

  public function getAarType() : int;


		/**
		 *	isValid();
		 *
		 *	TRUE hvis vi arbejder med et gyldigt �r
		 *
		 **/

	public function isValid() : bool;


	public function selectYear($y) : bool;
	public function selectDate($d, $m) : bool;
}