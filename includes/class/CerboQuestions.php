<?php

/* From https://www.usps.com/send/official-abbreviations.htm */

class CERBO_Questions {

	private $states = [
		'AL' => 'ALABAMA',
		'AK' => 'ALASKA',
		'AS' => 'AMERICAN SAMOA',
		'AZ' => 'ARIZONA',
		'AR' => 'ARKANSAS',
		'CA' => 'CALIFORNIA',
		'CO' => 'COLORADO',
		'CT' => 'CONNECTICUT',
		'DE' => 'DELAWARE',
		'DC' => 'DISTRICT OF COLUMBIA',
		'FM' => 'FEDERATED STATES OF MICRONESIA',
		'FL' => 'FLORIDA',
		'GA' => 'GEORGIA',
		'GU' => 'GUAM GU',
		'HI' => 'HAWAII',
		'ID' => 'IDAHO',
		'IL' => 'ILLINOIS',
		'IN' => 'INDIANA',
		'IA' => 'IOWA',
		'KS' => 'KANSAS',
		'KY' => 'KENTUCKY',
		'LA' => 'LOUISIANA',
		'ME' => 'MAINE',
		'MH' => 'MARSHALL ISLANDS',
		'MD' => 'MARYLAND',
		'MA' => 'MASSACHUSETTS',
		'MI' => 'MICHIGAN',
		'MN' => 'MINNESOTA',
		'MS' => 'MISSISSIPPI',
		'MO' => 'MISSOURI',
		'MT' => 'MONTANA',
		'NE' => 'NEBRASKA',
		'NV' => 'NEVADA',
		'NH' => 'NEW HAMPSHIRE',
		'NJ' => 'NEW JERSEY',
		'NM' => 'NEW MEXICO',
		'NY' => 'NEW YORK',
		'NC' => 'NORTH CAROLINA',
		'ND' => 'NORTH DAKOTA',
		'MP' => 'NORTHERN MARIANA ISLANDS',
		'OH' => 'OHIO',
		'OK' => 'OKLAHOMA',
		'OR' => 'OREGON',
		'PW' => 'PALAU',
		'PA' => 'PENNSYLVANIA',
		'PR' => 'PUERTO RICO',
		'RI' => 'RHODE ISLAND',
		'SC' => 'SOUTH CAROLINA',
		'SD' => 'SOUTH DAKOTA',
		'TN' => 'TENNESSEE',
		'TX' => 'TEXAS',
		'UT' => 'UTAH',
		'VT' => 'VERMONT',
		'VI' => 'VIRGIN ISLANDS',
		'VA' => 'VIRGINIA',
		'WA' => 'WASHINGTON',
		'WV' => 'WEST VIRGINIA',
		'WI' => 'WISCONSIN',
		'WY' => 'WYOMING',
		'AE' => 'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
		'AA' => 'ARMED FORCES AMERICA (EXCEPT CANADA)',
		'AP' => 'ARMED FORCES PACIFIC',
	];


	private $timezoneUSA = [
		'UTC -10 HST' => 'UTC -10 HST Hawaii Standard Time',
		'UTC -9 HDT'  => 'UTC -9 HDT Hawaii-Aleutian Daylight Time',
		'UTC -8 AKDT' => 'UTC -8 AKDT Alaska Daylight Time',
		'UTC -7 PDT'  => 'UTC -7 PDT Pacific Daylight Time',
		'UTC -7 MST'  => 'UTC -7 MST Mountain Standard Time',
		'UTC -6 MDT'  => 'UTC -6 MDT Mountain Daylight Time',
		'UTC -5 CDT'  => 'UTC -5 CDT Central Daylight Time',
		'UTC -4 EDT'  => 'UTC -4 EDT Eastern Daylight Time',


	];

	protected $med_prof_questions = [
		'med_prof_q1'  => 'Do you have or have you had any medical conditions?',
		'med_prof_q2'  => 'Which of the following medical conditions have you had or currently have?',
		'med_prof_q7'  => 'How physically active are you?',
		'med_prof_q8'  => 'Have you ever been told your kidneys are not working properly?',
		'med_prof_q9'  => 'Have you ever been told your liver is not working properly?',
		'med_prof_q10' => 'Do you currently smoke?',
		'med_prof_q11' => 'Do any of your immediate family members have a history of the following conditions?',
		'med_prof_q12' => 'Do you have a primary care provider?',
		'med_prof_q13' => 'Have you had a general health check-up or routine physical in the past three years?',
		'med_prof_q14' => 'Which medications are you currently taking?',
		'med_prof_q15' => 'Do you have any known allergies to the following?',

	];

	protected $auto_immu_questions = [
		'auto_immu_q6'  => 'Why are you interested in LDN?',
		'auto_immu_q7'  => 'What is your primary reason for requesting LDN?',
		'auto_immu_q8'  => 'Have you ever taken LDN before?',
		'auto_immu_q9'  => 'Have you ever been treated for any of the following medical conditions?(Select all that apply)',
		'auto_immu_q10' => 'Have you ever been hospitalized for mood related issues, such as depression, bipolar disorder, or schizophrenia?',
		'auto_immu_q11' => 'In the last 6 months OR in the coming 6 months, do you plan to take any of the following narcotic medications? (Select all that apply)',
		'auto_immu_q12' => 'Which of the following apply to your reproductive status?',
		'auto_immu_q13' => 'Is there anything else you want your prescriber to know about your condition or health?',
	];

	protected $height_pounds = [
		'height_foot'   => 'What is your height in foot?',
		'height_inches' => 'What is your height in inches?',
		'lbs'           => 'What is your weight in pounds?',
	];


	public function get_states() {
		return $this->states;
	}

	public function get_timezoneUSA() {
		return $this->timezoneUSA;
	}

	public function get_med_prof_question( $key ) {
		return $this->med_prof_questions[ $key ];
	}

	public function get_auto_immu_question( $key ) {
		return $this->auto_immu_questions[ $key ];
	}

	public function get_height_pounds( $key ) {
		return $this->height_pounds[ $key ];
	}

}
