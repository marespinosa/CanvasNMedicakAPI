<?php


function opal_settings_link( $links_array, $plugin_file_name ) {
	$links_array['settings'] = '<a href="' . esc_url( '/wp-admin/options-general.php?page=opal' ) . '" aria-label="' . esc_attr__( "Settings", "opal" ) . '">' . esc_html__( "Settings", "opal" ) . '</a>';

	return $links_array;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'opal_settings_link', 10, 2 );


function opal_register_settings() {
	add_option( 'opal_api_url', '' );
	add_option( 'opal_api_token', '' );


	register_setting( 'opal_options_group', 'opal_api_url', 'opal_callback' );
	register_setting( 'opal_options_group', 'opal_api_token', 'opal_callback' );
}

add_action( 'admin_init', 'opal_register_settings' );


function opal_register_options_page() {
	add_options_page( esc_html__( "Opal Api Settings", "opal" ),
		esc_html__( "Opal Api Settings", "opal" ),
		'manage_options',
		'opal',
		'opal_options_page'
    );
}

add_action( 'admin_menu', 'opal_register_options_page' );


function opal_options_page(){
	$opal_api_url = get_option('opal_api_url');
	$opal_api_token = get_option('opal_api_token');

	?>
	<div>
		<h1><?php echo esc_html__( "Opal Api Settings", "tell-friend" ); ?></h1>

		<form method="post" action="options.php">
			<?php settings_fields( 'opal_options_group' ); ?>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="opal_api_url"><?php echo esc_html__( "API URL", "tell-friend" ); ?></label></th>
                        <td><input class="regular-text" type="text" id="opal_api_url" name="opal_api_url" value="<?php echo $opal_api_url; ?>"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="opal_api_url"><?php	echo esc_html__( "API TOKEN", "tell-friend" ); ?></label></th>
                        <td><input class="regular-text" type="text" id="opal_api_token" name="opal_api_token" value="<?php echo $opal_api_token; ?>"/></td>
                    </tr>
                </table>

		    <?php submit_button(); ?>
		</form>

	</div>
	<?php
}
