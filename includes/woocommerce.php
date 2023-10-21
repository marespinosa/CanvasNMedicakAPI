<?php

function opal_add_to_cart_service() {
	$product_id = get_field( 'cerbo_product', 'option' );
	WC()->cart->add_to_cart( $product_id );
}


function opal_after_order_processed( $order_id, $posted_order, $order ) {
	$post_patient_id = $_COOKIE['post_patient_id'];

	if ( ! empty( $post_patient_id ) ) {
		update_post_meta( $order_id, 'patient_id', $post_patient_id );

		$user_id = $order->get_user_id();
		if ( $user_id ) {
			update_user_meta( $user_id, 'patient_id', $post_patient_id );
		}
	}
}

add_action( 'woocommerce_checkout_order_processed', 'opal_after_order_processed', 20, 3 );


function opal_wc_checkout_fields( $fields ) {
	if ( isset( $_COOKIE['post_patient_id'] ) ) {
		$post_patient_id = $_COOKIE['post_patient_id'];
		$fields['billing']['billing_first_name']['default'] = get_post_meta( $post_patient_id, 'first_name', TRUE );
		$fields['billing']['billing_last_name']['default']  = get_post_meta( $post_patient_id, 'last_name', TRUE );
		$fields['billing']['billing_last_name']['default']  = get_post_meta( $post_patient_id, 'last_name', TRUE );
		$fields['billing']['billing_email']['default']      = get_post_meta( $post_patient_id, 'email', TRUE );
		$fields['billing']['billing_phone']['default']      = str_replace( [ '(', ')', '-', ' ' ], [ '', '', '', '' ], get_post_meta( $post_patient_id, 'phone', TRUE ) );
	}

	return $fields;
}

add_filter( 'woocommerce_checkout_fields', 'opal_wc_checkout_fields', 99, 1 );


function opal_create_appointment_after_order_completed( $order_id ) {
	$post_patient_id = get_post_meta( $order_id, 'patient_id', TRUE );
	$order = wc_get_order( $order_id );
	if ( ! empty( $post_patient_id ) ) {
		$patient_data = [
			'post_patient_id' => $post_patient_id,
			'patient_id'      => get_post_meta( $post_patient_id, 'patient_id', TRUE ),
			'appointment'     => get_post_meta( $post_patient_id, 'appointment', TRUE ),
			'hourapptend'     => get_post_meta( $post_patient_id, 'hourapptend', TRUE ),
		];

		$book_appointment = new OpalAppointment();
		$response         = $book_appointment->create_appointment( $patient_data );


		if( isset( $response['appointment_id'] ) && $response['appointment_id'] > 0 ){
			$appointment_id = $response['appointment_id'];
			update_post_meta( $order_id, 'appointment_id', $appointment_id );
			update_post_meta( $post_patient_id, 'appointment_id', $appointment_id );
			$order->add_order_note( __( 'Your appointment has been successfully created.', 'opal' ) . PHP_EOL . __( 'Appointment ID', 'opal' ) . ' - ' . $appointment_id,  TRUE );
		}
		else{
			$order->add_order_note( 'Error! Appointment is not created.' );
		}
	}
}

add_action( 'woocommerce_order_status_completed', 'opal_create_appointment_after_order_completed', 999, 1 );


function opal_appointment_information( $order ){
?>
	<section class="woocommerce-appointment-details">
		<h2 class="woocommerce-column__title"><?php echo __( 'Appointment Details', 'opal' ); ?></h2>
        <?php
            if( is_int( $order ) ){
	            $order_id = $order;
            }
            else{
	            $order_id = $order->get_id();
            }
            $appointment_id = get_post_meta( $order_id, 'appointment_id', TRUE );

            if ( ! empty( $appointment_id ) ) :
                $appointment      = new OpalAppointment();
                $appointment_data = $appointment->get_appointment( $appointment_id );
            ?>
            <table class="woocommerce-table woocommerce-table--order-details shop_table appointment_details">
                <tfoot>
                   <!-- <tr>
                        <th scope="row"><?php /*echo __( 'Appointment ID', 'opal' ); */?>:</th>
                        <td><?php /*echo $appointment_data->id */?></td>
                    </tr>-->
                    <tr>
                        <th scope="row"><?php echo __( 'Start Time', 'opal' ); ?>:</th>
                        <td><?php echo date('m/d/Y H:i', strtotime( $appointment_data->start ) ); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __( 'End Time', 'opal' ); ?>:</th>
                        <td><?php echo date('m/d/Y H:i', strtotime( $appointment_data->end ) ); ?></td>
                    </tr>
                    <?php if( ! empty( $appointment_data->contained ) ): ?>
                        <?php $i = 1; foreach ( $appointment_data->contained as $meeting ): ?>
                        <tr>
                            <th scope="row"><?php echo __( 'Meeting Link', 'opal' ); ?> #<?php echo $i; ?> :</th>
                            <td> <a href="<?php echo $meeting->address; ?>" target="_blank"> <?php echo $meeting->address; ?></a></td>
                        </tr>
                        <?php $i++; endforeach; ?>
                    <?php endif; ?>
                </tfoot>
            </table>
            <?php endif; ?>
	</section>
<?php
}

add_action( 'woocommerce_order_details_after_order_table', 'opal_appointment_information', 99, 1 );

function opal_my_account_appointment_navigation( $items ) {
	$items['orders'] = __( 'Orders / Appointmens', 'opal' );

	return $items;
}

add_filter( 'woocommerce_account_menu_items', 'opal_my_account_appointment_navigation', 99, 1 );
