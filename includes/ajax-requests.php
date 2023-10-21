<?php

add_action( 'wp_ajax_cerbo_register', 'cerbo_register_ajax' );
add_action( 'wp_ajax_nopriv_cerbo_register', 'cerbo_register_ajax' );

function cerbo_register_ajax() {


	$dob      = sanitize_text_field( $_POST['year'] ) . '-' . sprintf( '%02d', sanitize_text_field( $_POST['month'] ) ) . '-' . sprintf( '%02d', sanitize_text_field( $_POST['day'] ) );
	$timezone = $_POST['timezoneUSA'];
	$apptm    = $_POST['apppointmentTime'] . 'T' . $_POST['hourappt'];
	$endtAppt = $_POST['apppointmentTime'] . 'T' . $_POST['hourapptend'];

	$data = [
		'first_name'  => sanitize_text_field( $_POST['first_name'] ),
		'last_name'   => sanitize_text_field( $_POST['last_name'] ),
		'sex'         => sanitize_text_field( $_POST['sex'] ),
		'dob'         => $dob,
		'email'       => sanitize_email( $_POST['email'] ),
		'phone'       => sanitize_text_field( $_POST['phone'] ),
		'state'       => sanitize_text_field( $_POST['state'] ),
		'appointment' => $apptm,
		'hourapptend' => $endtAppt,
		'timezoneUSA' => $timezone,
	];

	$questions_title = new CERBO_Questions();

	$post_data = [
		'height_foot'   => $_POST['height_foot'],
		'height_inches' => $_POST['height_inches'],
		'lbs'           => $_POST['lbs'],
		'med_prof_q1'   => $_POST['med_prof_q1'],
		'med_prof_q2'   => $_POST['med_prof_q2'],
		'med_prof_q7'   => $_POST['med_prof_q7'],
		'med_prof_q8'   => $_POST['med_prof_q8'],
		'med_prof_q9'   => $_POST['med_prof_q9'],
		'med_prof_q10'  => $_POST['med_prof_q10'],
		'med_prof_q11'  => $_POST['med_prof_q11'],
		'med_prof_q12'  => $_POST['med_prof_q12'],
		'med_prof_q13'  => $_POST['med_prof_q13'],
		'med_prof_q14'  => $_POST['med_prof_q14'],
		'med_prof_q15'  => $_POST['med_prof_q15'],
		'auto_immu_q6'  => $_POST['auto_immu_q6'],
		'auto_immu_q7'  => $_POST['auto_immu_q7'],
		'auto_immu_q8'  => $_POST['auto_immu_q8'],
		'auto_immu_q9'  => $_POST['auto_immu_q9'],
		'auto_immu_q10' => $_POST['auto_immu_q10'],
		'auto_immu_q11' => $_POST['auto_immu_q11'],
		'auto_immu_q12' => $_POST['auto_immu_q12'],
		'auto_immu_q13' => $_POST['auto_immu_q13'],
	];


	$answers_data = [];

	foreach ( $post_data as $key => $val ) {
		if ( strstr( $key, 'height_foot' ) || strstr( $key, 'height_inches' ) || strstr( $key, 'lbs' ) ) {
			$question_name = $questions_title->get_height_pounds( $key );
		}


		if ( strstr( $key, 'med_prof' ) ) {
			$question_name                    = $questions_title->get_med_prof_question( $key );
			$data['med_prof_answers'][ $key ] = $post_data[ $key ];
		}


		if ( strstr( $key, 'auto_immu' ) ) {
			$question_name                     = $questions_title->get_auto_immu_question( $key );
			$data['auto_immu_answers'][ $key ] = $post_data[ $key ];
		}


		$answers_data[ $question_name ] =  ( is_array( $val ) ) ? array_filter( $val ) : $val;
	}


	$data['questionnaire_answers'] = '';
	if ( ! empty( $answers_data ) ) {
		$text = '';
		$i    = 1;
		foreach ( $answers_data as $question => $answer ) {
			$text .= $i . '. ' . $question . PHP_EOL;
			if ( ! empty( $answer ) ) {
				foreach ( $answer as $value ) {
					$text .= '- ' . $value . PHP_EOL;
				}
			} else {
				$text .= '- no answer...' . PHP_EOL;
			}

			$text .= PHP_EOL;
			$i ++;
		}

		$data['questionnaire_answers'] = $text;
	}

	$data['patient_consent'] = $_POST['patient_consent'];
	$data['photo']           = ($_FILES['photo']['name']) ? $_FILES['photo'] : '';
	$api                     = new OpalPatient();
	$patient_response        = $api->create_wp_patient( $data );


	if(  !empty($patient_response) && $patient_response != 0 ){
		$response = [
			'post_patient_id' => $patient_response['post_patient_id'],
			'opal_patient_id' => $patient_response['opal_patient_id']
		];
	}
	else{
		$response = ['message' => 'Error! No patient created. '];
	}

	opal_add_to_cart_service();

	wp_send_json_success( $response );
}
