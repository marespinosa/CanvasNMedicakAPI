<?php

add_action( 'acf/init', 'cerbo_acf_op_init' );
function cerbo_acf_op_init() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' => __( 'Cerbo Options' ),
			'menu_title' => __( 'Cerbo Options' ),
			'menu_slug'  => 'cerbo-options',
			'capability' => 'edit_posts',
			'redirect'   => false
		) );
	}

}
