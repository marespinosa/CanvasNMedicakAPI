<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class OpalPatient {

	const CANVAS_TOKEN = 'ZUzovI7Zy2HWtJCc1aj9eGWZmERIgmij';

	private function process_data( $data ) {
		$photo_param = [];
		if ( ! empty( $data['photo'] ) ) {
			$photo_tmp    = $data['photo']['tmp_name'];
			$photo_data   = file_get_contents( $photo_tmp );
			$photo_type   = pathinfo( $photo_tmp, PATHINFO_EXTENSION );
			$photo_base64 = base64_encode( $photo_data );
			$photo_param  = [ [ 'data' => $photo_base64 ] ];
		}

		$args = [
			'resourceType' => 'Patient',
			'extension'    =>
				[
					[
						'url'       => 'http://hl7.org/fhir/us/core/StructureDefinition/us-core-birthsex',
						'valueCode' => $data['sex'],
					],
					[
						'url'       => 'http://hl7.org/fhir/StructureDefinition/tz-code',
						'valueCode' => 'America/New_York',
					],
					[
						'url'         => 'http://schemas.canvasmedical.com/fhir/extensions/administrative-note',
						'valueString' => $data['questionnaire_answers'],
					],
				],
			'active'       => TRUE,
			'name'         =>
				[
					[
						'use'    => 'official',
						'family' => $data['last_name'],
						'given'  => [ $data['first_name'] ],
					],
				],
			'address'      =>
				[
					[
						'use'        => 'home',
						'type'       => 'both',
						'text'       => $data['state'],
						'line'       => [ '' ],
						'city'       => '',
						'state'      => $data['state'],
						'postalCode' => '',
					],
				],
			'telecom'      =>
				[
					[
						'system' => 'phone',
						'value'  => $data['phone'],
						'use'    => 'mobile',
						'rank'   => 1,
					],
					[
						'system' => 'email',
						'value'  => $data['email'],
						'use'    => 'work',
						'rank'   => 1,
					],
				],
			'gender'       => $data['sex'] == 'M' ? 'male' : 'female',
			'birthDate'    => $data['dob'],
			/*'photo'        => [
				'data' => 'R0lGODlhEwARAPcAAAAAAAAA/+9aAO+1AP/WAP/eAP/eCP/eEP/eGP/nAP/nCP/nEP/nIf/nKf/nUv/nWv/vAP/vCP/vEP/vGP/vIf/vKf/vMf/vOf/vWv/vY//va//vjP/3c//3lP/3nP//tf//vf///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////yH5BAEAAAEALAAAAAATABEAAAi+AAMIDDCgYMGBCBMSvMCQ4QCFCQcwDBGCA4cLDyEGECDxAoAQHjxwyKhQAMeGIUOSJJjRpIAGDS5wCDly4AALFlYOgHlBwwOSNydM0AmzwYGjBi8IHWoTgQYORg8QIGDAwAKhESI8HIDgwQaRDI1WXXAhK9MBBzZ8/XDxQoUFZC9IiCBh6wEHGz6IbNuwQoSpWxEgyLCXL8O/gAnylNlW6AUEBRIL7Og3KwQIiCXb9HsZQoIEUzUjNEiaNMKAAAA7'
			]*/
			//'photo'        => $photo_param,
		];


		return json_encode( $args );
	}


	public function canvas_create( $data ) {
		$client = new \GuzzleHttp\Client( [ 'verify' => FALSE ] );

		$body = $this->process_data( $data );


		$response = $client->request( 'POST',
			'http://fhir-mirapartners.preview.canvasmedical.com/Patient',
			[
				'body'    => $body,
				'headers' => [
					'Authorization' => 'Bearer ' . self::CANVAS_TOKEN,
					'accept'        => 'application/json',
					'content-type'  => 'application/json',
				],
			] );


		$status = $response->getStatusCode();


		if ( $status != 400 ) {
			$str  = $response->getHeader( 'Location' );
			$str2 = str_replace( 'http://fhir-mirapartners.preview.canvasmedical.com/Patient/', '', $str[0] );
			$str3 = str_replace( '/_history/1', '', $str2 );
			if ( $str3 && $data['patient_consent'] == 1 ) {
				$consentPatient         = new OpalConsentPatient();
				$responseConsentPatient = $consentPatient->create_consent( [ 'patient_id' => $str3 ] );
			}

			return $str3;
		}


		$data      = json_decode( $response->getBody(), TRUE );

		$patient_id = 0;
		if($data['id']){
			$patient_id = $data['id'];
		}


		return $patient_id;
	}


	public function create_wp_patient( $data ) {
		$opal_patient_id = $this->canvas_create( $data );


		$apptm    = $_POST['apppointmentTime'] . 'T' . $_POST['hourappt'];
		$endtAppt = $_POST['apppointmentTime'] . 'T' . $_POST['hourapptend'];

		$post_patient_id = wp_insert_post(
			[
				'post_type'   => 'patient',
				'post_title'  => $data['first_name'] . ' ' . $data['last_name'],
				'post_status' => 'private',
			]
		);
		update_post_meta( $post_patient_id, 'dob', $data['dob'] );
		update_post_meta( $post_patient_id, 'first_name', $data['first_name'] );
		update_post_meta( $post_patient_id, 'last_name', $data['last_name'] );
		update_post_meta( $post_patient_id, 'email', $data['email'] );
		update_post_meta( $post_patient_id, 'phone', $data['phone'] );
		update_post_meta( $post_patient_id, 'state', $data['state'] );
		update_post_meta( $post_patient_id, 'sex', $data['sex'] );
		update_post_meta( $post_patient_id, 'patient_id', $opal_patient_id );
		update_post_meta( $post_patient_id, 'timezoneUSA', $data['timezoneUSA'] );
		update_post_meta( $post_patient_id, 'appointment', $apptm );
		update_post_meta( $post_patient_id, 'hourapptend', $endtAppt );

		// this is for questionnaire

		update_post_meta( $post_patient_id, 'med_prof_answers', $data['med_prof_answers'] );
		update_post_meta( $post_patient_id, 'auto_immu_answers', $data['auto_immu_answers'] );
		update_post_meta( $post_patient_id, 'patient_data', $data );

		$response = [];
		if ( ! empty( $post_patient_id ) && ! empty( $opal_patient_id ) ) {
			$response = [
				'post_patient_id' => $post_patient_id,
				'opal_patient_id' => $opal_patient_id,
			];
		}


		return  $response;
	}


}


class OpalAppointment {

	const CANVAS_TOKEN = OpalPatient::CANVAS_TOKEN;

	private function appointment_data_request( $patient_data ) {
		// Define constants
		define( 'REASON_CODE_SYSTEM', 'Internal/Custom' );
		define( 'REASON_CODE_DISPLAY', 'Visit' );
		define( 'APPOINTMENT_TYPE_SYSTEM', 'http://snomed.info/sct' );
		define( 'APPOINTMENT_TYPE_DISPLAY', 'Telemedicine' );

		$reason_code = [
			'coding' => [
				'system'  => REASON_CODE_SYSTEM,
				'code'    => '448337001',
				'display' => REASON_CODE_DISPLAY,
			],
			'text'   => 'Weekly check-in',
		];

		$participant_patient = [
			'actor'  => [
				'reference' => 'Patient/' . $patient_data['patient_id'],
			],
			'status' => 'accepted',
		];

		$participant_practitioner = [
			'actor'  => [
				'reference' => 'Practitioner/6d70e3af871c4b419ccb94ba129515a4',
			],
			'status' => 'accepted',
		];


		$contained = [
			'resourceType' => 'Endpoint',
			'id' => 'appointment-meeting-endpoint',
			'status' => 'active',
			'connectionType' => [
				'code' => 'https'
			],
			'payloadType' => [
				'coding' => [
					'code' => 'video-call'
				]
			],
			'address' => 'https://url-for-video-chat.example.com?meetingi=abc123',
		];




		$appointment_type = [
			'coding' => [
				'system'  => APPOINTMENT_TYPE_SYSTEM,
				'code'    => '448337001',
				'display' => APPOINTMENT_TYPE_DISPLAY,
			],
		];

		$appointment_args = [
			"resourceType"          => "Appointment",
			"reasonCode"            => [
				[
					"coding" => [
						[
							"system"  => "Internal/Custom",
							"code"    => "448337001",
							"display" => "Visit",
						],
					],
					"text"   => "Weekly check-in",
				],
			],
			"description"           => "Weekly check-in.",
			"participant"           => [
				[
					"actor"  => [
						"reference" => "Patient/" . $patient_data['patient_id'],
					],
					"status" => "accepted",
				],
				[
					"actor"  => [
						"reference" => "Practitioner/6d70e3af871c4b419ccb94ba129515a4",
					],
					"status" => "accepted",
				],
			],
			"appointmentType"       => $appointment_type,
			"start"                 => $patient_data['appointment'],
			"end"                   => $patient_data['hourapptend'],
			"supportingInformation" => [
				[
					"reference" => "Location/1",
				]
			],
			"status"  => "proposed",
		];

		return json_encode( $appointment_args );
	}


	public function create_appointment( $appointment_data ) {
		$client = new \GuzzleHttp\Client( [ 'verify' => FALSE ] );

		$body = $this->appointment_data_request( $appointment_data );

		$response = $client->request( 'POST',
			'http://fhir-mirapartners.preview.canvasmedical.com/Appointment/',
			[
				'body'    => $body,
				'headers' => [
					'Authorization' => 'Bearer ' . self::CANVAS_TOKEN,
					'accept'        => 'application/json',
					'content-type'  => 'application/json',
				],
			] );


		$status = $response->getStatusCode();
		if ( $status != 400 ) {
			$str  = $response->getHeader( 'Location' );
			$str2 = str_replace( 'http://fhir-mirapartners.preview.canvasmedical.com/Appointment/', '', $str[0] );
			$str3 = str_replace( '/_history/1', '', $str2 );
			if ( $str3 ) {
				update_post_meta( $appointment_data['post_patient_id'], 'appointment_confirmed', 'Yes' );
				update_post_meta( $appointment_data['post_patient_id'], 'appointment_id', $str3 );
				return ['appointment_id', $str3];
			}
			else{
				return ['appointment_id', 0];
			}


		}

		return $status;
	}

}


class OpalFormatQuestionnaire {


	const CANVAS_TOKEN = OpalPatient::CANVAS_TOKEN;

	public function create_task( $patient_id ) {
		$data = [
			'resourceType' => 'Task',
			'status'       => 'requested',
			'requester'    =>
				[
					'reference' => 'Practitioner/6d70e3af871c4b419ccb94ba129515a4',
				],
			'description'  => 'New Patient, Follow up Today',
			'for'          =>
				[
					'reference' => 'Patient/' . $patient_id['patient_id'],
				],
			'owner'        =>
				[
					'reference' => 'Practitioner/6d70e3af871c4b419ccb94ba129515a4',
				],
			'restriction'  =>
				[
					'period' =>
						[
							'end' => date( "c", strtotime( '+2 days' ) ),
						],
				],
			'input'        =>
				[
					0 =>
						[
							'type'        =>
								[
									'text' => 'label',
								],
							'valueString' => 'Urgent',
						],
				],
		];

		$client = new \GuzzleHttp\Client( [ 'verify' => FALSE ] );

		$response = $client->request( 'POST',
			'http://fhir-mirapartners.preview.canvasmedical.com/Task',
			[
				'body'    => json_encode( $data ),
				'headers' => [
					'Authorization' => 'Bearer ' . self::CANVAS_TOKEN,
					'accept'        => 'application/json',
					'content-type'  => 'application/json',
				],
			] );
		$status   = $response->getStatusCode();
		if ( $status != 400 ) {
			$str = $response->getHeader( 'Location' );
			ob_start();
			var_dump( $str );
			$log = ob_get_contents();
			ob_end_clean();
			error_log( $log );

			return TRUE;
		}

		return FALSE;
	}


	public function format_med_prof_questionnaire( $answers ) {
		$questions = new CERBO_Questions();
		$items     = [];

		foreach ( $answers as $key => $answer ) {
			$raw_data[ $key ] = $answer;
			$items[]          = [
				[
					'linkId' => $key,
					'text'   => $questions->get_med_prof_question( $key ),
					'answer' =>
						[
							[
								'valueCoding' =>
									[
										'system'  => 'http://schemas.training.canvasmedical.com/fhir/systems/internal',
										'code'    => $key,
										'display' => $answer,
									],
							],
						],
				],
			];
		}

		return $items;
	}


	public function format_auto_immu_questionnaire( $answers ) {
		$html      = '';
		$questions = new CERBO_Questions();
		$raw_data  = [];

		foreach ( $answers as $key => $answer ) {
			$html             .= '<div><strong>' . $questions->get_auto_immu_question( $key ) . '</strong><br><em>' . implode( ', ',
					$answer ) . '</em></div>';
			$raw_data[ $key ] = $answer;
		}

		return [
			'questionnaire_type' => 'autoimmune_disorder',
			'questionnaire_name' => 'Auto Immune Disorder',
			'html_content'       => $html,
			'raw_data'           => $raw_data,
		];
	}


	public function send_questionnaire( $patient_id ) {
		$args = [
			'item' => [
				'linkId' => 'c79da8bd-d20e-4f56-909f-f3dabae7f64f',
				'text'   => 'This is question #1',
				'answer' => [
					'valueString' => 'Answer #1',
				],
			],
		];

		$data = [
			'resourceType'  => 'QuestionnaireResponse',
			'questionnaire' => 'Questionnaire/med_prof_q7',
			'subject'       => [ 'reference' => 'Patient/' . $patient_id ],
			//			'author'        => ['reference' => 'Practitioner/6d70e3af871c4b419ccb94ba129515a4'],
			'item'          => $args,
		];


		$client = new \GuzzleHttp\Client( [ 'verify' => FALSE ] );

		$response = $client->request( 'POST',
			'http://fhir-mirapartners.preview.canvasmedical.com/QuestionnaireResponse',
			[
				'body'    => json_encode( $data ),
				'headers' => [
					'Authorization' => 'Bearer ' . self::CANVAS_TOKEN,
					'accept'        => 'application/json',
					'content-type'  => 'application/json',
				],
			]
		);

		$status = $response->getStatusCode();
		if ( $status != 400 ) {
			$str  = $response->getHeader( 'Location' );
			$str2 = str_replace( 'http://fhir-mirapartners.preview.canvasmedical.com/QuestionnaireResponse',
				'',
				$str[0] );

			return $str2;
		}
	}

}


class OpalConsentPatient {

	const CANVAS_TOKEN = OpalPatient::CANVAS_TOKEN;

	private function process_data( $data ) {
		$args = [
			'resourceType' => 'Consent',
			'status'       => 'active',
			'category'     => [
				[
					'coding' => [
						[
							'system'  => 'LOINC',
							'code'    => '64292-6',
							'display' => 'Approved by the patient',
						],
					],
				],
			],
			'patient'      => [
				'reference' => 'Patient/' . $data['patient_id'],
			],
			'provision'    => [
				'period' => [
					'start' => date( 'Y-m-d' ),
				],
			],
		];

		return json_encode( $args );
	}

	public function create_consent( $data ) {
		$client = new \GuzzleHttp\Client( [ 'verify' => FALSE ] );

		$body = $this->process_data( $data );

		//$body = '{"resourceType":"Consent","status":"active","scope":{"coding":[{"system":"http://terminology.hl7.org/CodeSystem/consentscope","code":"patient-privacy"}]},"category":[{"coding":[{"system":"ConsentCoding_System_ConfigureInAdmin","code":"ConsentCoding_Code_ConfigureInAdmin","display":"ConsentCoding_Display_ConfigureInAdmin"}]}],"patient":{"reference":"Patient/'.$data['patient_id'].'"},"provision":{"period":{"start":"2022-05-15","end":"2022-10-10"}}}';

		$response = $client->request( 'POST', 'http://fhir-mirapartners.preview.canvasmedical.com/Consent',
			[
				'body'    => $body,
				'headers' => [
					'Authorization' => 'Bearer ' . self::CANVAS_TOKEN,
					'accept'        => 'application/json',
					'content-type'  => 'application/json',
				],
			]
		);

		$status = $response->getStatusCode();

		return $status;
	}

}
