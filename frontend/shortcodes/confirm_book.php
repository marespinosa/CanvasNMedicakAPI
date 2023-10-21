<?php


add_action( 'woocommerce_thankyou', 'custom_thankyou_page', 10, 1 );

function custom_thankyou_page( $order_id ) {
	$questions = new CERBO_Questions();

	$arrayptients = array(
		'post_type'      => 'patient',
		'posts_per_page' => - 1,
		'post_status'    => 'private'
	);

	$query = new WP_Query( $arrayptients );

	if ( $query->have_posts() ) {
		$counter             = 0;
		$patient_id          = '';
		$book_appointment    = '';
		$appointment_end_get = '';
		while ( $query->have_posts() ) {
			$query->the_post();
			$counter ++;
			if ( $counter == 1 ) {
				$post_patient_id       = get_the_ID();
				$appointment_confirmed = get_field( 'appointment_confirmed' );
				$patient_id            = get_field( 'patient_id' );
				$appointment_date      = get_field( 'appointment' );
				$appointment_end       = get_field( 'hourapptend' );
				$book_appointment      = $appointment_date;
				$appointment_end_get   = $appointment_end;
			}
		}


		wp_reset_postdata();
	}

	?>


    <div id="popup" style="display:none;">
        <h4>Appointment</h4>
        <?php if(empty($appointment_confirmed)){ ?>
            <form id="confirmBookAuto" method="POST">
                <input type="hidden" name="post_patient_id" value="<?php echo $post_patient_id; ?>"/>
                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>"/>

                <input type="text" name="appointment" placeholder="Start Time" value="<?php echo $book_appointment; ?>"/>
                <input type="text" name="hourapptend" placeholder="End Time" value="<?php echo $appointment_end_get; ?>"/>
                <input type="submit" name="confirm_appointment_book" id="confirmAppt" class="confirmbtm"  value="Confirm Appointment">
            </form>
        <?php }else{ ?>
            <p>Thank you for confirmation appointment!</p>
        <?php } ?>
    </div>
<?php } ?>
