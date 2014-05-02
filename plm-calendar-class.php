<?php 
/*
Plugin Name: Paul's Classy Calendar
Plugin URI: http://www.paulmcelligott.com
Description: The title says it all.
Version: 0.1
Author: Paul McElligott
Author URI: http://www.paulmcelligott.com
License: GPL
*/
/**
* One Month 
*/



include(plugin_dir_path(__FILE__) . '/plm-calendar-activate.php');
include(plugin_dir_path(__FILE__) . '/plm-calendar-settings.php');
include(plugin_dir_path(__FILE__) . '/plm-calendar-functions.php');
include(plugin_dir_path(__FILE__) . '/plm-calendar-widgets.php');
include(plugin_dir_path(__FILE__) . '/plm-calendar-shortcodes.php');

class plm_Month 
{
	private $month;
	private $class = array('plm_calendar', 'month_table');
	private $longMonths = array(
		1 => 'January',
		2 => 'February',
		3 => 'March',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'August',
		9 => 'September',
		10 => 'October',
		11 => 'November',
		12 => 'December');

	private $shortMonths = array(
		1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
		5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
		9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
		);

	private $weekdays = array(
		1 => 'Sunday',
		2 => 'Monday',
		3 => 'Tuesday',
		4 => 'Wednesday',
		5 => 'Thursay',
		6 => 'Friday',
		7 => 'Saturday');

	private $dayformat = 'short';
	private $displayYear = 'yes';
	
	function __construct()
	{
		$this->month = new DateTime('first day of this month');
	}

	function SetMonth($newMonth, $newYear=NULL)
	{
		$useMonth = $this->month;

		if ($newYear == '')
		{
			$newYear = NULL;
		}

		if ((is_null($newYear)) | (!is_numeric($newYear))) 
		{
			$newYear = $useMonth->format('Y');
		}

		if ($newYear < 100) 
		{
			$newYear += 2000;
		} 
		elseif ($newYear < 1582) 
		{
			$newYear = 1582;
		} 
		elseif ($newYear > 9999) 
		{
			$newYear = 9999;
		}

		if (!is_numeric($newMonth)) {
			$checkLMon = array_search(ucfirst($newMonth), $this->longMonths);
			if ($checkLMon) {
				$newMonth = $checkLMon;
			} else {
				$checkSMon = array_search(ucfirst($newMonth), $this->shortMonths);
				if ($checkSMon) {
					$newMonth = $checkSMon;
				} else {
					$newMonth = $useMonth->format('Y');
				}
			}
		} elseif (($newMonth < 1) | ($newMonth > 12)) {
			$newMonth = $useMonth->format('n');
		}

		$this->month->setDate($newYear, $newMonth, 1);

		unset($checkSMon);
		unset($checkLMon);
		unset($useMonth);
	}

	function changeTableClass($newClass) {
		if($newClass != '') 
		{
			$this->class = explode(',', $newClass);
		}
	}

	function Set_Year_Display($newOption) 
	{
		switch (strtolower($newOption)) {
			case 'no':
			case 'n':
				$this->displayYear = 'no';
				break;
			default:
				$this->displayYear = 'yes';
				break;
		}
	}

	function Set_Weekday_Option($newOption)
	{
		switch(strtolower($newOption)) {
			case 'long':
			case 'l':
			case 'full':
			case 'f':
				$this->dayformat = 'long';
				break;
			case 'medium':
			case 'med':
			case 'm';
				$this->dayformat = 'medium';
				break;
			default:
				$this->dayformat = 'short';
				break;
		}
	}

	function Output() 
	{
		$newLine = "\n";
		$tabStop = "\t";
		$monthNum = $this->month->format('n');
		$dayNum = $this->month->format('j');
		$yearNum = $this->month->format('Y');
		$hilite = get_option('plm_calendar_hday');

		if($hilite=='1') {
			$hlist = plm_holiday_list($yearNum);
		}

		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $yearNum);

		$plm_calendar_out = $newLine . '<table class="' . implode(" ", $this->class) . '" id="plm-' . $this->month->format('Ym') . '" >' . $newLine . $tabStop;

		switch($this->displayYear) {
			case 'yes':
				$plm_calendar_out .= '<caption class="plm_calendar_caption">' . $this->month->format('F Y') . '</caption>';
				break;
			default:
				$plm_calendar_out .= '<caption class="plm_calendar_caption">' . $this->month->format('F') . '</caption>';
				break;
		}

		$plm_calendar_out .= $newLine . $tabStop . '<thead>' . $newLine . str_repeat($tabStop, 2)  . '<tr>';

		foreach ($this->weekdays as $i => $dayToken) {
			$plm_calendar_out .= $newLine . str_repeat($tabStop, 3) . '<th>';

			switch ($this->dayformat) {
				case 'long':
					$plm_calendar_out .= $dayToken;
					break;
				case 'medium':
					$plm_calendar_out .= substr($dayToken, 0, 3);
					break;
				default:
					$plm_calendar_out .= substr($dayToken, 0, 1);
					break;
			}

			$plm_calendar_out .= '</th>';
		}

		$plm_calendar_out .= $newLine . str_repeat($tabStop, 2) . '</tr>' . $newLine . $tabStop . '</thead>';

		$plm_calendar_out .= $newLine . $tabStop . '<tbody>';
		$plm_calendar_out .= $newLine . str_repeat($tabStop, 2) . '<tr>' . $newLine . str_repeat($tabStop, 3);

		$firstWeekPad = $this->month->format('w');
		$weekday = $firstWeekPad + 1;

		if ($firstWeekPad != 0)
		{
			$plm_calendar_out .= '<td colspan="' . $firstWeekPad . '" class="first plm_calendar_pad">&nbsp;</td>';
		}

		for ($i=1; $i <= $daysInMonth; $i++) {
			$plm_calendar_out .= $newLine . str_repeat($tabStop, 3);
			if ($hilite != '1') {
				$plm_calendar_out .= '<td>' . $i . '</td>';
			} else {
				$dkey = sprintf('%02d%02d', $monthNum, $i);
				if (!array_key_exists($dkey, $hlist)) {
					$plm_calendar_out .= '<td>' . $i . '</td>';
				} else {
					$plm_calendar_out .= '<td class="holiday">';
					$plm_calendar_out .= '<span title="' . $hlist[$dkey] . '">' . $i . '</a></td>';
				}
			}

			if ($weekday == 7) 
			{
				$plm_calendar_out .= $newLine . str_repeat($tabStop, 2) . '</tr>';
				$plm_calendar_out .= $newLine . str_repeat($tabStop, 2) . '<tr>';
				$weekday = 1;
			} else {
				$weekday += 1;
			}
		}

		$lastweekpad = 8 - $weekday;

		if (($lastweekpad != 0) & ($weekday != 1)) {

			$plm_calendar_out .= $newLine . str_repeat($tabStop, 3) . '<td colspan="' . $lastweekpad . '" class="plm_calendar_pad last">&nbsp;</td>';
		}

		$plm_calendar_out .= $newLine . str_repeat($tabStop, 2) . '</tr>';

		$plm_calendar_out .= $newLine . $tabStop . '</tbody>';

		$plm_calendar_out .= $newLine . '</table>' . $newLine;

		return $plm_calendar_out;
	}
}

class plm_Year 
{
	private $thisYear;


	private $shortMonths = array(
		1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
		5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
		9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
		);

	function __construct()
	{
		$temp = new DateTime('first day of this year');
		$this->thisYear = $temp->format('Y');
		unset($temp);
	}

	function SetYear($newYear)
	{
		if (is_numeric($newYear)) 
		{
			if ($newYear < 100) {
				$this->thisYear = $newYear + 2000;
			} elseif ($newYear < 1582) {
				$this->thisYear = 1582;
			} elseif ($newYear > 9999) {
				$this->thisYear = 9999;
			} else {
				$this->thisYear = $newYear;
			}
		}
	}

	function Output()
	{
		$newLine = "\n";
		$tabStop = "\t";

		$plm_calendar_out = '<section id="plm_calendar_' . $this->thisYear . '" class="plm_calendar_container">' . $newLine;
		$plm_calendar_out .= '<h3 class="plm_calendar_month">' . $this->thisYear . '</h3>' . $newLine;

		for ($i=1; $i <= 12; $i++) {
			$outYear = new plm_Month;
			$outYear->SetMonth($i, $this->thisYear);
			$outYear->Set_Year_Display("no");

			$plm_calendar_out .= '<div class="plm_calendar_month ' . $this->shortMonths[$i] . '_' . $this->thisYear . '">' . $newLine;
			$plm_calendar_out .= $outYear->Output() . $newLine;
			$plm_calendar_out .= '</div>' . $newLine;

			unset($outYear);
		}

		$plm_calendar_out .= '</section>';

		return $plm_calendar_out;
	}
}



?>