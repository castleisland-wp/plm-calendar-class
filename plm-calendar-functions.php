<?php
/*
	Herein find the assorted functions.
*/

function plm_holiday_diff($holiday) 
{
		$todaysDate = new DateTime('now');

		$thisyear = $todaysDate->format('Y');
		$nextyear = $thisyear + 1;

		$holidayName = '';
		$returnArray = array();

		switch ($holiday) {
			case 'newyears':
				$dateThisYear = new DateTime($thisyear . '-1-1');
				$dateNextYear = new DateTime($nextyear . '-1-1');
				$holidayName = "New Year's Day";
				break;
			
			case 'valentine':
				$dateThisYear = new DateTime($thisyear . '-2-14');
				$dateNextYear = new DateTime($nextyear . '-2-14');
				$holidayName = "Valentine's Day";
				break;

			case 'stpaddy':
				$dateThisYear = new DateTime($thisyear . '-3-17');
				$dateNextYear = new DateTime($nextyear . '-3-17');
				$holidayName = "St. Patrick's Day";
				break;

			case 'easter':
				$dateThisYear = new DateTime($thisyear . '-3-21');
				$dateThisYear->add(new DateInterval('P'. easter_days($thisyear) .'D'));
				$dateNextYear = new DateTime($nextyear . '-3-21');
				$dateNextYear->add(new DateInterval('P' . easter_days($nextyear) . 'D'));
				$holidayName = 'Easter';
				break;

			case 'mother':
				$dateThisYear = new DateTime('second sunday of May ' . $thisyear);
				$dateNextYear = new DateTime('second sunday of May ' . $nextyear);
				$holidayName = "Mother's Day";
				break;

			case 'memorial':
				$dateThisYear = new DateTime('last Monday of May ' . $thisyear);
				$dateNextYear = new DateTime('last Monday of May ' . $nextyear);
				$holidayName = "Memorial Day";
				break;

			case 'father':
				$dateThisYear = new DateTime('third Sunday of June ' . $thisyear);
				$dateNextYear = new DateTime('third Sunday of June ' . $nextyear);
				$holidayName = "Father's Day";
				break;

			case 'july4':
				$dateThisYear = new DateTime($thisyear . '-7-4');
				$dateNextYear = new DateTime($nextyear . '-7-4');
				$holidayName = "the 4th of July";
				break;

			case 'labor':
				$dateThisYear = new DateTime('first Monday of September ' . $thisyear);
				$dateNextYear = new DateTime('first Monday of September ' . $nextyear);
				$holidayName = "Labor Day";
				break;

			case 'columbus':
				$dateThisYear = new DateTime('second Monday of October ' . $thisyear);
				$dateNextYear = new DateTime('second Monday of October ' . $nextyear);
				$holidayName = "Columbus Day";
				break;

			case 'halloween':
				$dateThisYear = new DateTime($thisyear . '-10-31');
				$dateNextYear = new DateTime($nextyear . '-10-31');
				$holidayName = "Halloween";
				break;

			case 'veteran':
				$dateThisYear = new DateTime($thisyear . '-11-11');
				$dateNextYear = new DateTime($nextyear . '-11-11');
				$holidayName = "Veterans Day";
				break;

			case 'thanksgiving':
				$dateThisYear = new DateTime('fourth thursday of November ' . $thisyear);
				$dateNextYear = new DateTime('fourth thursday of November ' . $nextyear);
				$holidayName = "Thanksgiving";
				break;

			case 'christmas':
				$dateThisYear = new DateTime($thisyear . '-12-25');
				$dateNextYear = new DateTime($nextyear . '-12-25');
				$holidayName = "Christmas";
				break;
		}

		if($dateThisYear >= $todaysDate)
		{
			$daysuntil = $todaysDate->diff($dateThisYear);
		} else {
			$daysuntil = $todaysDate->diff($dateNextYear);
		}

		$returnArray['days'] = $daysuntil->days;
		$returnArray['holiday'] = $holidayName;

		return $returnArray;
}



function plm_holiday_list($year) 
{
	if(!is_numeric($year))
	{
		$tempDT = new DateTime('today');
		$year = $tempDT->format('Y');

	}

	if($year < 1582) $year = 1582;
	if($year > 9999) $year = 9999;

	$holidayList = array('0101' => "New Year's Day");
	unset($tempDT);
	$tempDT = new DateTime('third Monday January ' . $year);
	$holidayList[$tempDT->format('md')] = 'Martin Luther King, Jr. Day';

	$holidayList['0214'] = "Valentine's Day";

	unset($tempDT);
	$tempDT = new DateTime('third monday of February ' . $year);
	$holidayList[$tempDT->format('md')] = "President's Day";

	$holidayList['0317'] = "St. Patrick's Day";

	$tempDT->setDate($year, 3, 21);
	$easter = easter_days($year);
	$tempDT->add(new DateInterval('P' . $easter . 'D'));
	$holidayList[$tempDT->format('md')] = 'Easter';

	unset($tempDT);
	$tempDT = new DateTime('second sunday of may ' . $year);
	$holidayList[$tempDT->format('md')] = "Mother's Day";

	unset($tempDT);
	$tempDT = new DateTime('last monday of may ' . $year);
	$holidayList[$tempDT->format('md')] = "Memorial Day";

	unset($tempDT);
	$tempDT = new DateTime('third sunday of june ' . $year);
	$holidayList[$tempDT->format('md')] = "Father's Day";

	$holidayList['0704'] = "Independence Day";

	unset($tempDT);
	$tempDT = new DateTime('first monday of september ' . $year);
	$holidayList[$tempDT->format('md')] = "Labor Day";

	unset($tempDT);
	$tempDT = new DateTime('second monday of october ' . $year);
	$holidayList[$tempDT->format('md')] = "Columbus Day";

	$holidayList['1031'] = "Halloween";

	unset($tempDT);
	$tempDT = new DateTime('first monday of november ' . $year);
	$tempDT->add(new DateInterval('P1D'));
	$holidayList[$tempDT->format('md')] = 'Election Day';

	$holidayList['1111'] = 'Veterans Day';

	unset($tempDT);
	$tempDT = new DateTime('fourth thursday of november ' . $year);
	$holidayList[$tempDT->format('md')] = "Thanksgiving";

	$holidayList['1225'] = "Christmas";
	$holidayList['1231'] = "New Year's Eve";
	unset($tempDT);
	return $holidayList;
}

//This function builds a list of valid month name or number values and compares the value to the array.
function plm_month_checker($month)
{
	$monthList = array(
		'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
		'Jan', 'Feb', 'Mar', 'Apr', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
		);

	return array_search($month, $monthList);
}


function px_to_rem( $pixels )
{
	if ( is_numeric( $pixels ) ) 
	{
		$rem = ( ( $pixels * 10^8 ) / 14 ) / 10^8;
		return $rem;
	} else {
		return FALSE;
	}
}

function rem_to_px( $rem ) 
{
	if ( is_numeric( $rem ) ) 
	{
		$pixels = ( ( $rem * 10^8 ) * 14 ) / 10^8;
		return $pixels;
	} else {
		return FALSE;
	}
}

?>