<?php

namespace App\Utils;

/**
 *	interface iKalenderView
 *
 *	Generisk klasse for �rs kalendere ifb udskrivning
 *
 *  Oplyser om
 *  - Kalenderens navn (Dansk, Norsk, Svensk, Juliansk, Gregoriansk, etc...)
 *  - Kalenderens logo (Typisk et landeflag)
 *  - �rstallet
 *	- Antallet af m�neder i �ret og deres navne
 *	- Antal dage i en given m�ned
 *	- For hver enkelt dag dens type (normal, l�rdag, helligdag), evt beskrivelse og evt Tooltip
 *
 * 	S�vel dage som m�neder nummereres fra 1..nn
 */

interface iKalenderView
{
	public function getCalendarName(): String;

  public function setYear(): bool;
  public function getYear(): int;

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