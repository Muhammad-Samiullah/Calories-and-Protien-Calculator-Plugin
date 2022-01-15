<?php
/*
Plugin Name: Ingredients Calories Calculator
Description: This plugin calculates calories
*/

add_shortcode('ingredients_calories_calculator_admin', 'Ingredients_Calories_Calculator_Admin');
function Ingredients_Calories_Calculator_Admin($atts) {
	$user = wp_get_current_user();
	$roles = ( array ) $user->roles;
	if(count($roles) > 0) {
		$role = $roles[0];	
	}
	else {
		$role = "";
	}
	$content = "";
	if($role == "administrator") {
		global $wpdb;
		$table = $wpdb->prefix . "Ingredients";
		$rows = $wpdb->get_results('SELECT * FROM '.$table);
		$content .= "<center><button id='add-btn' type='button' data-toggle='modal' data-target='#addModal' class='btn btn-primary'>Add Ingredient</button></center>";
		$content .= "<div class='table-responsive'>";
		$content .= "<table class='table table striped table-bordered'>";
		$content .= "<tr class='thead-light'>";
		$content .= "<th>Ingredient</th>";
		$content .= "<th>Protien</th>";
		$content .= "<th>Calories</th>";
		$content .= "<th>Image</th>";
		$content .= "<th>Options</th>";
		$content .= "</tr>";
		foreach($rows as $row) {
			$image_name = $row->image_name;
			$content .= "<tr>";
			$content .= "<td>" . $row->ingredient . "</td>";
			$content .= "<td>" . $row->protien . "</td>";
			$content .= "<td>" . $row->calories . "</td>";
			$image_src = get_site_url() . '/wp-content/uploads/' . $image_name;
			$content .= "<td><img src='" . $image_src . "' /></td>";
			$content .= "<td><div style='display: flex'><button type='button' data-toggle='modal' data-target='#exampleModal' data-id='".$row->id."' data-protien='".$row->protien."' data-calories='".$row->calories."' data-ingredient='".$row->ingredient."' class='btn btn-secondary edit-btn'>Edit</button><button data-id='".$row->id."' class='btn btn-danger delete-btn'>Delete</button></div></td>";
			$content .= "</tr>";
		}
		$content .= "</table>";
		$content .= "</div>";
	}
	else {
		$content = "Admin Access Required";	
	}
    $content .= file_get_contents (plugin_dir_path( __FILE__ ) . "page.txt");
    return $content;
	die();
}


add_action('wp_enqueue_scripts', 'pwwp_enqueue_bootstrap4');
function pwwp_enqueue_bootstrap4() {
    wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' );
    wp_enqueue_script( 'popper','//cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js', array( 'jquery' ),'',true );
    wp_enqueue_script( 'boot3','https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', array( 'jquery' ),'',true );
    wp_enqueue_style('main-styles', plugins_url( 'css/style.css' , __FILE__ ), array(), rand(), false);
}

function love_calculator_scripts() {
    wp_enqueue_script( 'frontend-ajax', plugins_url( 'js/demo.js?x=' . rand(), __FILE__ ), array('jquery'), null, true );
    wp_localize_script( 'frontend-ajax', 'frontend_ajax_object',
        array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))
    	);
	wp_enqueue_script('bootstrap-js', 'https://code.jquery.com/jquery-3.2.1.slim.min.js');
	wp_enqueue_script('bootstrap-js2', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js');
	wp_enqueue_script('bootstrap-js3', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js');
	wp_enqueue_script('jquery1', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');
}
add_action( 'wp_enqueue_scripts', 'love_calculator_scripts' );





add_shortcode('ingredients_calories_calculator', 'Ingredients_Calories_Calculator');
function Ingredients_Calories_Calculator($atts) {
	$content = "";
		global $wpdb;
		$table = $wpdb->prefix . "Ingredients";
		$rows = $wpdb->get_results('SELECT * FROM '.$table);
		$content .= "<h2>poke<b>shop</b></h2>";
		$content .= "<div style='display: flex; justify-content: space-between'><h5>Calories: <span id='calories-sum'>0</span>g</h5>";
		$content .= "<h5>Protiens: <span id='protiens-sum'>0</span>g</h5></div>";
		$content .= "<table class='table table-striped table-bordered'>";
		$content .= "<tr class='thead-light'>";
		$content .= "<th>Ingredient</th>";
		$content .= "<th>Image</th>";
		$content .= "<th>Protien</th>";
		$content .= "<th>Calories</th>";
		$content .= "</tr>";
		foreach($rows as $row) {
			$image_name = $row->image_name;
			$content .= "<tr class='trs'>";
			$content .= "<td>" . $row->ingredient . "</td>";
			$image_src = get_site_url() . '/wp-content/uploads/' . $image_name;
			$content .= "<td><img src='" . $image_src . "' /></td>";
			$content .= "<td>" . $row->protien . "</td>";
			$content .= "<td>" . $row->calories . "</td>";
			$content .= "</tr>";
	}
	$content .= "</table>";
	$content .= "<center><button class='btn btn=primary' id='clear-btn'>Clear Selection</button></center>";
    return $content;
	die();
}







add_action( 'wp_ajax_delete_ingredient', 'delete_ingredient' );
function delete_ingredient(){
	$current_user = wp_get_current_user();
    global $wpdb;
	$table = $wpdb->prefix . "Ingredients";
	$id = $_POST['id'];
	
	$query = 'DELETE FROM '.$table.' WHERE id='.$id;

	$success = $wpdb->query($query);

	if($success){
		$content .= 'Ingredient Deleted Successfully. ' ; 
	} else {
		$content .= 'Error Deleting Ingredient. ';
	}
	echo $list;
	die();
	
}

add_action( 'wp_ajax_update_ingredient', 'update_ingredient' );
function update_ingredient(){
	$current_user = wp_get_current_user();
    global $wpdb;
	$table = $wpdb->prefix . "Ingredients";
	$id = $_POST['id'];
	$ingredient = $_POST['ingredient'];
	$protien = $_POST['protien'];
	$calories = $_POST['calories'];
	
	$query = 'UPDATE '.$table.' SET ingredient="'.$ingredient.'", protien="'.$protien.'", calories="'.$calories.'" WHERE id='.$id;
	

	$success = $wpdb->query($query);

	if($success){
		$content .= 'Ingredient Updated Successfully. ' ; 
	} else {
		$content .= 'Error Updating Ingredient. ';
	}
	echo $content;
	die();
	
}




add_action( 'wp_ajax_insert_ingredient', 'insert_ingredient');
function insert_ingredient(){
	$current_user = wp_get_current_user();
    global $wpdb;
	$table = $wpdb->prefix . "Ingredients";
	$ingredient = $_POST['ingredient'];
	$protien = $_POST['protien'];
	$calories = $_POST['calories'];
	$tmp = $_FILES['file']['tmp_name'];
	$year = date("Y");
	$month = date("m");
	
	$image_url = $tmp;
	$upload_dir = wp_upload_dir();
	$image_data = file_get_contents( $image_url );
	$filename = basename( $image_url );
	if ( wp_mkdir_p( $upload_dir['path'] ) ) {
  		$file = $upload_dir['path'] . '/' . $filename;
	}
	else {
	  $file = $upload_dir['basedir'] . '/' . $filename;
	}
	
	file_put_contents( $file, $image_data );

	$wp_filetype = wp_check_filetype( $filename, null );

	$attachment = array(
	  'post_mime_type' => $wp_filetype['type'],
	  'post_title' => sanitize_file_name( $filename ),
	  'post_content' => '',
	  'post_status' => 'inherit'
	);

	$attach_id = wp_insert_attachment( $attachment, $file );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	
	$filepath = $year . '/' . $month . '/' . $filename;
	
// 	echo get_site_url() . '/wp-content/uploads/2022/01/phpDE99.tmp';
	
	$query = "INSERT INTO ". $table. " (`ingredient`, `protien`, `calories`, `image_name`) VALUES ('$ingredient', '$protien', '$calories', '$filepath');";
	echo $query;
	

	$success = $wpdb->query($query);

	if($success){
		$content .= 'Ingredient Added Successfully. ' ; 
	} else {
		$content .= 'Error Adding Ingredient. ';
	}
	echo $content;
	die();
	
}




function Ingredients_Table_Check(){
        global $wpdb;
		
		$my_products_db_version = '1.0.0';
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . "Ingredients";
		if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {
			$sql = "CREATE TABLE  $table_name ( 
				`id`  int NOT NULL AUTO_INCREMENT,
				`ingredient`  varchar(256),
				`protien`  varchar(256),
				`calories`  varchar(256),
				`image_name` varchar(256),
				PRIMARY KEY  (id)
				) $charset_collate;";
    		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    		dbDelta($sql);
    		add_option('my_db_version', $my_products_db_version);
			}
}

register_activation_hook( __FILE__, 'Ingredients_Table_Check' );


?>
