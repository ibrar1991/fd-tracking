<?php



add_action( 'init', 'fd_imp_order_post_type' );
/**
 * Register a book post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function fd_imp_order_post_type() {
	$labels = array(
		'name'               => _x( 'IMP Orders', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'IMP Order', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'IMP Orders', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'IMP Order', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'IMP Order', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New IMP Order', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New IMP Order', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit IMP Order', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View IMP Order', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All IMP Orders', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search IMP Orders', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent IMP Orders:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No IMP Orders found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No IMP Orders found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'imp-order' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title' )
	);

	register_post_type( 'imp-order', $args );
}


add_action( 'init', 'fd_imp_status_post_type' );
/**
 * Register a book post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function fd_imp_status_post_type() {
	$labels = array(
		'name'               => _x( 'Order Status', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Order Status', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Order Status', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Order Status', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'Order Status', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Order Status', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Order Status', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Order Status', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Order Status', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Order Status', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Order Status', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Order Status:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No Order Status found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No Order Status found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'order-status' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title' )
	);

	register_post_type( 'order-status', $args );
}




function my_title_place_holder($title , $post){

    if( $post->post_type == 'imp-order' || $post->post_type == 'order-status'){
        $my_title = "Tracking ID";
        return $my_title;
    }

    return $title;
}
add_filter('enter_title_here', 'my_title_place_holder' , 20 , 2 );



include 'inc/func.php';



//================
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Email Settings',
		'menu_title'	=> 'Email Settings',
		'menu_slug' 	=> 'email-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}


//=============
function my_project_updated_send_email_45484( $post_id ) {

	if ( wp_is_post_revision( $post_id ) ) { return; }

    if( get_post_type( $post_id ) == 'imp-order' ){ 
    	//=== do nothing
    }else{
    	return;
    }

	$ee = get_field('enable_email_on_order_update', 'option');

	if( $ee == false ){ return; }


	$status_title = strtolower( get_the_title($post_id) );


	if( preg_match("/^imp/", $status_title) )
		$post_type = 'imp-order';
	elseif( preg_match("/^dmp/", $status_title) )
		$post_type = 'dmp-order';
	else
		$post_type = false;


	if($post_type)
		$main_order_id = get_page_by_title( $status_title, OBJECT, $post_type )->ID;
	else
		return;

	$to = get_post_meta( $main_order_id, 'imp-order-customer_email', true );

	if( $to == '' ){ return; }

	
    $post_title = get_the_title( $post_id );
    $status_link = get_home_url().'?tracking-id='.$post_title;

    $find = array('{{id}}', '{{link}}');
	$replace = array($post_title, $status_link);

	$email_text = get_field('email_content_for_order_update', 'option');
	$email_sub = get_field('email_subject_for_order_update', 'option');

	$email_sub = str_replace( $find, $replace, $email_sub );
	$email_text = str_replace( $find, $replace, $email_text );

	$headers[] = 'From: Your Order Tracking <noreply@'.$_SERVER['SERVER_NAME'].'>';

	wp_mail( $to, $email_sub, $email_text, $headers );
}
add_action( 'save_post', 'my_project_updated_send_email_45484' );


function wpse27856_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );




function your_prefix_get_meta_box48435498541876( $meta_boxes ) {
	$prefix = 'imp-order-';

	$meta_boxes[] = array(
		'id' => 'untitled',
		'title' => esc_html__( 'Details', 'metabox-online-generator' ),
		'post_types' => array('imp-order'),
		'context' => 'advanced',
		'priority' => 'default',
		'autosave' => 'false',
		'fields' => array(
			array(
				'id' => $prefix . 'order_id',
				'type' => 'text',
				'name' => esc_html__( 'Order ID', 'metabox-online-generator' ),
			),
			array(
				'id' => $prefix . 'current_status',
				'name' => esc_html__( 'Current Status', 'metabox-online-generator' ),
				'type' => 'select',
				'placeholder' => esc_html__( 'Select Current Status', 'metabox-online-generator' ),
				'options' => array(
					1 => 'Processing your order at USA office',
					2 => 'Shipped to India',
					3 => 'In Custom Clearance',
					4 => 'Shipment received in Mumbai office',
					5 => 'Connected via domestic courier for delivery',
				),
			),
			array(
				'id' => $prefix . 'status_date_time',
				'type' => 'datetime',
				'name' => esc_html__( 'Status date Time', 'metabox-online-generator' ),
				// 'timestamp' => 'true',
				'js_options' => array(
			        'dateFormat'      => 'dd-mm-yy',
			        'showTimepicker'  => true,
			    ),
			    'save_format' => 'Y-m-d H:i:s',
			),
			array(
				'id' => $prefix . 'location',
				'type' => 'text',
				'name' => esc_html__( 'Location', 'metabox-online-generator' ),
			),
			array(
				'id' => $prefix . 'status',
				'type' => 'textarea',
				'name' => esc_html__( 'Status', 'metabox-online-generator' ),
			),
			array(
				'id' => $prefix . 'destination',
				'type' => 'text',
				'name' => esc_html__( 'Destination', 'metabox-online-generator' ),
			),
			array(
				'id' => $prefix . 'customer_name',
				'type' => 'text',
				'name' => esc_html__( 'Customer Name', 'metabox-online-generator' ),
			),
			array(
				'id' => $prefix . 'customer_email',
				'name' => esc_html__( 'Customer Email', 'metabox-online-generator' ),
				'type' => 'email',
			),
			array(
				'id' => $prefix . 'customer_mobile_number',
				'type' => 'text',
				'name' => esc_html__( 'Customer Mobile Number', 'metabox-online-generator' ),
			),
		),
	);

	return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'your_prefix_get_meta_box48435498541876' );



function your_prefix_get_meta_box7845612359554238( $meta_boxes ) {
	$prefix = 'order-status-';

	$meta_boxes[] = array(
		'id' => 'untitled',
		'title' => esc_html__( 'Untitled Metabox', 'metabox-online-generator' ),
		'post_types' => array('order-status'),
		'context' => 'advanced',
		'priority' => 'default',
		'autosave' => 'false',
		'fields' => array(
			array(
				'id' => $prefix . 'current_status',
				'name' => esc_html__( 'Current Status', 'metabox-online-generator' ),
				'type' => 'select',
				'placeholder' => esc_html__( 'Select Current Status', 'metabox-online-generator' ),
				'options' => array(
					1 => 'Processing your order at USA office',
					2 => 'Shipped to India',
					3 => 'In Custom Clearance',
					4 => 'Shipment received in Mumbai office',
					5 => 'Connected via domestic courier for delivery',
				),
			),
			array(
				'id' => $prefix . 'domestic_tn',
				'type' => 'text',
				'name' => esc_html__( 'Domestic Tracking Number', 'metabox-online-generator' ),
			),
			array(
				'id' => $prefix . 'status_date_time',
				'type' => 'datetime',
				'name' => esc_html__( 'Status date Time', 'metabox-online-generator' ),
				// 'timestamp' => 'true',
				'js_options' => array(
			        'dateFormat'      => 'dd-mm-yy',
			        'showTimepicker'  => true,
			    ),
			    'save_format' => 'Y-m-d H:i:s',
			),
			array(
				'id' => $prefix . 'location',
				'type' => 'text',
				'name' => esc_html__( 'Location', 'metabox-online-generator' ),
			),
			array(
				'id' => $prefix . 'status',
				'type' => 'textarea',
				'name' => esc_html__( 'Status', 'metabox-online-generator' ),
			),
		),
	);

	return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'your_prefix_get_meta_box7845612359554238' );



function remove_menus457845() {
	remove_menu_page( 'index.php' );                  //Dashboard
	remove_menu_page( 'jetpack' );                    //Jetpack* 
	remove_menu_page( 'edit.php' );                   //Posts
	remove_menu_page( 'upload.php' );                 //Media
	// remove_menu_page( 'edit.php?post_type=page' );    //Pages
	remove_menu_page( 'edit.php?post_type=acf-field-group' );    //Pages
	remove_menu_page( 'edit-comments.php' );          //Comments
	remove_menu_page( 'themes.php' );                 //Appearance
	remove_menu_page( 'plugins.php' );                //Plugins
	remove_menu_page( 'users.php' );                  //Users
	remove_menu_page( 'tools.php' );                  //Tools
	remove_menu_page( 'options-general.php' );        //Settings
}
add_action( 'admin_menu', 'remove_menus457845' );




