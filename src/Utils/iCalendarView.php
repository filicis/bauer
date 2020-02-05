<?php

namespace App\Utils;

/**
 *	interface iCalendarView
 *
 *	Generisk klasse for års kalendere ifb udskrivning
 *
 *  Oplyser om
 *  - Kalenderens navn (Dansk, Norsk, Svensk, Juliansk, Gregoriansk, etc...)
 *  - Kalenderens logo (Typisk et landeflag)
 *  - Årstallet
 *	- Antallet af måneder i året og deres navne
 *	- Antal dage i en given måned
 *	- For hver enkelt dag dens type (normal, lørdag, helligdag), evt beskrivelse og evt Tooltip
 *
 * 	Såvel dage som måneder nummereres fra 1..nn
 */

interface iCalendarView
{
	public function getCalendarName(): String;

  public function setYear($year): bool;
  public function getYear(): int;

		/**
		 *	getAarClass()
		 *
		 * 	0: betyder ugyldigt år, men iøvrigt defineres parameteren individuelt for hver kalender
		 * 		 For den Dansk/Norske kalender f.eks:
		 *  1: Almindeligt år
     *  2: Almindeligt skudår
     *  3: Overgangsåret 1700
     *
		 **/

  public function getYearClass() : int;
  public function getMonths(): array;

		/**
		 *	isValid();
		 *
		 *	TRUE hvis vi arbejder med et gyldigt år
		 *
		 **/

	public function isValid() : bool;




	public function getDayMax() : int;
	public function getMonthMax() : int;

	public function getDagType() : int;
	public function getDagBetegnelse() : String;
	public function getDagTooltip() : String;
	public function getUgedag() : String;


	public function selectDate($d, $m) : bool;

	public function getDateInfo($d, $m, $y) : array;
}