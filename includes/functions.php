<?php
function is_user_canvas_patient( $user_id ) {
	$id = get_user_meta( $user_id, 'patient_id', true );

	return $id ? true : false;
}
