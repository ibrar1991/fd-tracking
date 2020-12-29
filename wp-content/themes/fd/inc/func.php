<?php

function get_order_by_tracking_id( $post_title, $post_type ){

	global $wpdb;

	$q1 = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_title = '{$post_title}' AND post_type = '{$post_type}' AND post_status = 'publish'";

	$results1 = $wpdb->get_results( $q1, OBJECT );

	$order_id = $results1[0]->ID;

	if($order_id){



		$order_current_status 	= get_post_meta( $order_id, 'imp-order-current_status', true );
		$order_date_time 		= get_post_meta( $order_id, 'imp-order-status_date_time', true );
		$order_location 		= get_post_meta( $order_id, 'imp-order-location', true );
		$order_status 			= get_post_meta( $order_id, 'imp-order-status', true );
		$order_order_id 		= get_post_meta( $order_id, 'imp-order-order_id', true );
		$order_destination 		= get_post_meta( $order_id, 'imp-order-destination', true );
		$order_customer_name 	= get_post_meta( $order_id, 'imp-order-customer_name', true );
		$order_customer_email 	= get_post_meta( $order_id, 'imp-order-customer_email', true );
		$order_customer_mobile_number = get_post_meta( $order_id, 'imp-order-customer_mobile_number', true );

		$result_array[] = [
			'date_time' 	=> $order_date_time,
			'location' 		=> $order_location,
			'status' 		=> $order_status,
			'order_id' 		=> $order_order_id,
			'destination' 	=> $order_destination,
			'customer_name' => $order_customer_name,
			'customer_email' => $order_customer_email,
			'customer_number' => $order_customer_mobile_number,
			'current_status' => $order_current_status,
		];

	}else{

		return false;	
	}



	// return $result_array;



	//======================= now get statuses

	$q2 = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_title = '{$post_title}' AND post_type = 'order-status' AND post_status = 'publish'";

	$results2 = $wpdb->get_results( $q2, OBJECT );

	if($results2){

		foreach ($results2 as $key => $value) {

			$status_id = $value->ID;

			$order_current_status 	= get_post_meta( $status_id, 'order-status-current_status', true );
			$domestic_tn			= get_post_meta( $status_id, 'order-status-domestic_tn', true );
			$order_date_time 		= get_post_meta( $status_id, 'order-status-status_date_time', true );
			$order_location 		= get_post_meta( $status_id, 'order-status-location', true );
			$order_status 			= get_post_meta( $status_id, 'order-status-status', true );

			$result_array[] = [
				'date_time' 	=> $order_date_time,
				'location' 		=> $order_location,
				'status' 		=> $order_status,
				'current_status'=> $order_current_status,
				'domestic_tn' 	=> $domestic_tn,
			];

		}

	}

	return array_reverse($result_array);














}
