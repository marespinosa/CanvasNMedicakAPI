<?php
/*
 
Plugin Name: Opal
Description: Opal custom features, developed by danestan913@gmail.com and upgraded by mariconespinosa.info@gmail.com
Version: 1.0.0
Author: danestan913@gmail.com & mariconespinosa.info@gmail.com
Text Domain: opal
 
*/


define( 'OPAL_DIR', plugin_dir_path( __FILE__ ) );
define( 'CERBO_URL', plugin_dir_url( __FILE__ ) );

add_action( 'init', 'cerbo_init' );

function cerbo_init() {
	require_once OPAL_DIR . 'vendor/autoload.php';
	require_once OPAL_DIR . 'includes/functions.php';
	require_once OPAL_DIR . 'includes/class/CerboQuestions.php';
	require_once OPAL_DIR . 'includes/class/OpalCanvasApi.php';


	require_once OPAL_DIR . 'includes/ajax-requests.php';
	require_once OPAL_DIR . 'includes/woocommerce.php';
	require_once OPAL_DIR . 'includes/options-page.php';

	require_once OPAL_DIR . 'frontend/shortcodes/register-form.php';
	//require_once OPAL_DIR . 'frontend/shortcodes/confirm_book.php';
	//require_once OPAL_DIR . 'includes/acf.php';
}



add_action( 'init', 'cerbo_post_types' );

function cerbo_post_types() {
	$args = [
		'public'            => FALSE,
		'show_in_nav_menus' => TRUE,
		'show_ui'           => TRUE,
		'label'             => __( 'Patients', 'cerbo' ),
		'menu_icon'         => 'dashicons-book',
	];
	register_post_type( 'patient', $args );
}

function cerbo_stylesand_scripts( $hook ) {
	wp_enqueue_script( 'opal-scripts-ui',
		plugins_url( 'assets/js/jquery-3.6.0.js', __FILE__ ),
		[ 'jquery' ],
		date( 'Ymdhis' ) );
	wp_enqueue_script( 'opal-js-scripts-ui',
		plugins_url( 'assets/js/jquery-ui.js', __FILE__ ),
		[ 'jquery' ],
		date( 'Ymdhis' ) );

	wp_enqueue_script( 'opal-js-add-on',
		plugins_url( 'assets/js/jquery-ui-timepicker-addon.min', __FILE__ ),
		[ 'jquery' ],
		date( 'Ymdhis' ) );
	wp_enqueue_script( 'opal-js-add-on-ui',
		plugins_url( 'assets/js/jquery-ui.min', __FILE__ ),
		[ 'jquery' ],
		date( 'Ymdhis' ) );

	wp_register_style( 'opal-style',
		plugins_url( 'assets/css/style.css', __FILE__ ),
		[],
		date( 'Ymdhis' ) );
	wp_enqueue_style( 'opal-style' );

	wp_register_style( 'opal-custom-style',
		plugins_url( 'assets/css/custom.css', __FILE__ ),
		[],
		date( 'Ymdhis' ) );
	wp_enqueue_style( 'opal-custom-style' );

	wp_enqueue_script( 'opal-cookie-scripts',
		plugins_url( 'assets/js/js.cookie.min.js', __FILE__ ),
		[ 'jquery' ],
		date( 'Ymdhis' ) );
	wp_enqueue_script( 'opal-scripts',
		plugins_url( 'assets/js/scripts.js', __FILE__ ),
		[ 'jquery' ],
		date( 'Ymdhis' ) );
}

add_action( 'wp_enqueue_scripts', 'cerbo_stylesand_scripts' );

function submit_questions() {
	if ( isset( $_POST['submit_form'] ) ) {
		$med_prof_answers = [
			'med_prof_q1'  => $_POST['med_prof_q1'],
			'med_prof_q2'  => $_POST['med_prof_q2'],
			'med_prof_q7'  => $_POST['med_prof_q7'],
			'med_prof_q8'  => $_POST['med_prof_q8'],
			'med_prof_q9'  => $_POST['med_prof_q9'],
			'med_prof_q10' => $_POST['med_prof_q10'],
			'med_prof_q11' => $_POST['med_prof_q11'],
			'med_prof_q12' => $_POST['med_prof_q12'],
			'med_prof_q13' => $_POST['med_prof_q13'],
			'med_prof_q14' => $_POST['med_prof_q14'],
			'med_prof_q15' => $_POST['med_prof_q15'],

		];


		$data['med_prof_answers'] = $med_prof_answers;

		$auto_immu_data = [
			'auto_immu_q6'  => $_POST['auto_immu_q6'],
			'auto_immu_q7'  => $_POST['auto_immu_q7'],
			'auto_immu_q8'  => $_POST['auto_immu_q8'],
			'auto_immu_q9'  => $_POST['auto_immu_q9'],
			'auto_immu_q10' => $_POST['auto_immu_q10'],
			'auto_immu_q11' => $_POST['auto_immu_q11'],
			'auto_immu_q12' => $_POST['auto_immu_q12'],
			'auto_immu_q13' => $_POST['auto_immu_q13'],
		];

		$auto_immu_answers = [];

		foreach ( $auto_immu_data as $key => $val ) {
			$auto_immu_answers[ $key ] = [];
			foreach ( $val as $x ) {
				array_push( $auto_immu_answers[ $key ], sanitize_text_field( $x ) );
			}
		}

		$data['auto_immu_answers'] = $auto_immu_answers;

		$send_appt = new OpalFormatQuestionnaire();
		$send_appt->send_questionnaire( $data );
	}
}
