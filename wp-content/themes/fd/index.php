<?php

$tracking_id = $_GET['tracking-id'];
$tracking_id = strtolower($tracking_id);


if( preg_match("/^imp/", $tracking_id) )
	$post_type = 'imp-order';
elseif( preg_match("/^dmp/", $tracking_id) )
	$post_type = 'dmp-order';
else
	$post_type = false;


if( $post_type )
	$order = get_order_by_tracking_id($tracking_id, $post_type);
else
	$order = false;


?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <link rel="shortcut icon" href="https://www.cart2gulf.com/favicon.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />

		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/style.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/main.css" />

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />

		<title>Track Order - Track</title>
        
	</head>

<body>


<style type="text/css">
	/*.submit-btn{
		margin-top: 25px;
	}*/
</style>

<div class="container-fluid" style="border-bottom: 1px solid lightgray">
	<div class="row">
		<div class="col-md-8 col-sm-8 col-xs-12" style="padding: 20px 15px">
			<a href="http://www.importkar.com/" target="_blank">
				<img src="<?php echo get_home_url(); ?>/wp-content/uploads/2019/04/fa74ce3a-33af-4a13-9b01-57c3212cd12a.png" class="img-fluid" style="width: 200px;" />
			</a>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 20px 15px">
			<div class="phone floatR">
				<span class="a_phone" style="font-weight: bold">+919653274480</span><br>
		        <span class="a_time"><small>12am-8pm (Mon-Sat)</small></span>
		    </div>
		</div>
	</div>
</div>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 col-sm-6 col-xs-12 text-center" style="padding: 20px 15px 0 15px;">
			<h1>Track Order</h1>
		</div>
		<div class="col-md-6 offset-md-2 col-sm-12 col-xs-12" style="padding: 20px 15px 0 15px;">
			<form action="<?php echo get_home_url(); ?>" method="get" id="trackOrder" style="margin-bottom: 0;">
				<div class="row">
					<div class="col-md-5 col-sm-12 col-xs-12 form-group">
						<!-- <label>Select: </label>
						<select name="search-type" required="required" class="form-control">
							<option value="">Select Option</option>
							<option value="tracking-id">Order ID</option>
							<option value="shipment-no">Shipment No</option>
						</select> -->
					</div>
					<div class="col-md-5 col-sm-12 col-xs-12 form-group">
						<!-- <label>Select: </label> -->
						<input type="text" name="tracking-id" required="required" class="form-control" placeholder="Tracking ID" value="<?= $_GET['tracking-id']; ?>" />
					</div>
					<!-- <label>&nbsp;</label> -->
					<div class="col-md-2 col-sm-12 col-xs-12 form-group">
						<input type="submit" value="Search" class="btn btn-primary submit-btn" />
					</div>
				</div>
			</form>
		</div>

		<div class="container"><hr /></div>

	</div>
</div>

<?php


if( isset($_GET['tracking-id']) ) //== if  isset($_GET['tracking-id'])
{

	if($order == false){ //========= in valid tracing id

		echo '<div class="container">';
			echo '<h3 class="text-center">You have invalid Tracking ID please enter correct Tracking ID to track your order.</h3>';
		echo '</div>';

	}else{ //=========== valid tracking id


		$last = end($order); 
		$first = $order[0]; 
		$status_count = count($order);

		$customer_name 	= $last['customer_name'] ? $last['customer_name'] : 'N/A' ;
		$order_id 		= $last['order_id'] ? $last['order_id'] : 'N/A' ;
		$destination 	= $last['destination'] ? $last['destination'] : 'N/A' ;


		echo '<div class="container" style="margin-bottom: 50px; font-size: 20px;">';
			echo '<div class="row">';
				echo '<div class="col-md-4 col-sm-6 text-center" style="margin-bottom: 25px;"><strong style="display: inline-block; margin-bottom: 10px;">Customer Name:</strong><br />'.$customer_name.'</div>';
				echo '<div class="col-md-4 col-sm-6 text-center" style="margin-bottom: 25px;"><strong style="display: inline-block; margin-bottom: 10px;">Order ID:</strong><br />'.$order_id.'</div>';
				echo '<div class="col-md-4 col-sm-6 text-center" style="margin-bottom: 25px;"><strong style="display: inline-block; margin-bottom: 10px;">Destination:</strong><br />'.$destination.'</div>';
				echo '<div class="col-md-12 col-sm-12 col-xs-12 text-center" style="font-size: 18px; margin-top: 50px;"><em style="line-height: 30px;">Kindly send your kyc (Aadhar Card and Pan Card Copy) for faster custom clearance on <a href="mailto:kyc@importkar.com?subject=kyc for '.$_GET['tracking-id'].'">kyc@importkar.com</a></em><hr /></div>';
			echo '</div>';
		echo '</div>';


		switch ($first['current_status']) {
			case 1:
				$class_for_pin = '.status-box-1::before';
				$class_for_checked = '';
				break;

			case 2:
				$class_for_pin = '.status-box-2::before';
				$class_for_checked = '.status-box-1::before';
				break;

			case 3:
				$class_for_pin = '.status-box-3::before';
				$class_for_checked = '.status-box-1::before, .status-box-2::before';
				break;

			case 4:
				$class_for_pin = '.status-box-4::before';
				$class_for_checked = '.status-box-1::before, .status-box-2::before, .status-box-3::before';
				break;

			case 5:
				$class_for_pin = '.status-box-5::before';
				$class_for_checked = '.status-box-1::before, .status-box-2::before, .status-box-3::before, .status-box-4::before';
				break;

			default:
				# code...
				break;
		}

		?>

		<style type="text/css">


			.status-box::before{
				font-family: "Font Awesome 5 Free";
				font-weight: 900;
				content: "\f111";
				font-size: 36px;
				display: block;
				text-align: center;
				margin-bottom: 25px;
				color: #0000ff;
				visibility: hidden;
			}

			<?php echo $class_for_pin; ?>{
				font-family: "Font Awesome 5 Free";
				font-weight: 900;
				content: "\f3c5";
				font-size: 36px;
				display: block;
				text-align: center;
				margin-bottom: 25px;
				color: #0000ff;
				visibility: visible;
			}

			<?php echo $class_for_checked; ?>{
				font-family: "Font Awesome 5 Free";
				font-weight: 900;
				content: "\f00c";
				font-size: 36px;
				display: block;
				text-align: center;
				margin-bottom: 25px;
				color: #00b700;
				visibility: visible;
			}

		</style>


		<?php


		echo '<div class="container" style="margin-bottom: 50px;">';
			echo '<div class="row seven-cols">';
				echo '<div class="col text-center status-box status-box-1">(1)<br />Processing your order at USA office</div>';
				echo '<div class="col text-center status-box status-box-2">(2)<br />Shipped to India</div>';
				echo '<div class="col text-center status-box status-box-3">(3)<br />In Custom Clearance</div>';
				echo '<div class="col text-center status-box status-box-4">(4)<br />Shipment received in Mumbai office</div>';

				if( $first['domestic_tn'] && $first['current_status'] == 5 )
					echo '<div class="col text-center status-box status-box-5"><a href="https://importkar.shiprocket.co/tracking/'.$first['domestic_tn'].'" style="display:block; " target="_blank">(5)<br />Connected via domestic courier for delivery<br />Click here<br />(Tracking no '.$first['domestic_tn'].')</a></div>';
				else
					echo '<div class="col text-center status-box status-box-5">(5)<br />Connected via domestic courier for delivery</div>';

			echo '</div>';
		echo '</div>';



		echo '<div class="container" style="margin-bottom: 100px;">';
		echo '<div class="row">';
			echo '<table class="table">';

					echo '<thead class="thead-light">';
						echo '<tr>';
							echo '<th scope="col">#</th>';
							echo '<th scope="col">Date</th>';
							echo '<th scope="col">Time</th>';
							echo '<th scope="col">Location</th>';
							echo '<th scope="col">Status</th>';
						echo '</tr>';
					echo '</thead>';

				foreach ($order as $key => $value) {

					$dateTime = strtotime($value['date_time']);

					$date = date('d M, Y', $dateTime);
					$time = date('h:i a', $dateTime);

					echo '<tbody>';

						echo '<tr>';
							echo '<td scope="col">'.$status_count.')</td>';
							echo '<td scope="col">'.$date.'</td>';
							echo '<td scope="col">'.$time.'</td>';
							echo '<td scope="col">'.$value['location'].'</td>';
							echo '<td scope="col">'.$value['status'].'</td>';
						echo '</tr>';

					echo '</tbody>';  $status_count--;
				}

			echo '</table>';
		echo '</div>';
		echo '</div>';

	}

}
else //== if  !isset($_GET['tracking-id'])
{ 

	echo '<div class="container">';
		echo '<h3 class="text-center">Please enter your Tracking ID in search bar to track your package.</h3>';
	echo '</div>';

}

?>





<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
