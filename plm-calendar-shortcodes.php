<?php
/*
 			This file contains all of the shortcode functions for the calendar class plugin.
*/

function single_month_sc($atts, $content)
{
	extract( shortcode_atts( array(
		'month' => '',
		'year' => '',
		'weekstyle' => 'long',
		'showyear' => 'yes'
		), $atts));

	$out = new plm_Month;

	$currentDate = new DateTime;

	if (!is_numeric($year) ) {
		$year = $currentDate->format('Y');
	}

	if (is_numeric($month) ) $month = intval($month);
	if (gettype($month) == 'string' ) $month = ucfirst(strtolower($month));

	if (!plm_month_checker($month))
	{
		$month = $currentDate->format('n');
	}

	$out->SetMonth($month, $year);

	$out->Set_Weekday_Option($weekstyle);
	$out->Set_Year_Display($showyear);

	return "\n" . '<div class="plm_single_month">' . "\n" . $out->Output() . "\n" . '</div>' . "\n" ;
}

function single_year_sc($atts, $content)
{
	extract( shortcode_atts( array(
		'year' => ''
		), $atts));
	$out = new plm_Year;

	if($content != '') {
		$year = $content;
	}

	if ($year != '')
	{
		$out->SetYear($year);
	}

	return $out->Output();
}

add_shortcode('single_month', 'single_month_sc');
add_shortcode('single_year', 'single_year_sc');

function holiday_sc($atts) {
	extract( shortcode_atts( array(
		'year' => ''
		), $atts));

	$tempDT = new DateTime('today');

	if(!is_numeric($year))
	{
		$year = $tempDT->format('Y');

	}

	$hlist = plm_holiday_list($year);

	$htable = '<div class="major_holidays"><h2>Major Holidays of ' . $year . '</h2>';

	$htable .= '<table class="holiday_table"><thead><tr><th>Date</th><th>Holiday</th></tr></thead><tbody>';
	foreach ($hlist as $MDD => $holiday) {
		$htable .= '<tr><td>';
		$the_month = substr($MDD, 0, 2);
		$the_date = substr($MDD, 2, 2);
		$tempDT->setDate($year, $the_month, $the_date);
		$htable .= $holiday . '</td><td>' . $tempDT->format('l, F j, Y') . '</td></tr>';
	}
	$htable .= '</tbody></table></div>';

	return $htable;

}

add_shortcode('holiday_list', 'holiday_sc');

function plm_holiday_diff_sc($atts)
{
	extract( shortcode_atts( array(
		'holiday' => 'newyears', 
		'para' => 'yes', 
		'spell' => ''
	), $atts ) );

	switch(strtolower( $holiday ) ) {
		case 'new years day':
		case 'nyd':
			$holiday = 'newyears';
			break;
		case 'xmas':
			$holiday = 'christmas';
			break;
		case 'independence day':
		case 'fourth of july':
			$holiday = 'july4';
			break;
	}

	switch( strtolower( $spell ) ) {
		case 'y':
			$spell = 'yes';
			break;
	}

	$returnArray = plm_holiday_diff($holiday);

	$days = $returnArray['days'];

	if(function_exists('plm_textualize_number') and ($spell == 'yes')) $days = ucfirst(plm_textualize_number($days));

	return '<p>' . $days . ' days until ' . $returnArray['holiday'] . '</p>';

}

add_shortcode('days_until', 'plm_holiday_diff_sc');
?>