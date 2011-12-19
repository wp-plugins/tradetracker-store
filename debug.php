<?php
function debug_ttstore() {
	echo "<b>Needed to import XML Feed</b><br>";
	if (function_exists('curl_init')) {
		echo "Curl enabled: Yes<br>";
	} else {
		if (ini_get('allow_url_fopen') == true) {
			echo "allow_url_fopen enabled: Yes<br>";
		} else {
			echo "Curl enabled: No<br>allow_url_fopen enabled: No<br>";
		}
	}
	echo "<p><b>Needed to write XML file</b><br>";
	$folder =  WP_PLUGIN_DIR . "/tradetracker-store/splits";
	if(is_writable($folder)){
		echo $folder." is writable<br>";
	} else {
		echo $folder." is not writable. Please CHMOD 777 it<br>";
	}
	$folder2 =  WP_PLUGIN_DIR . "/tradetracker-store/cache";
	if(is_writable($folder2)){
		echo $folder2." is writable<br>";
	} else {
		echo $folder2." is not writable. Please CHMOD 777 it<br>";
	}

	echo "<p><b>Needed to write XML to database</b><br>";
	if (!extension_loaded('simplexml')) {
		if (!dl('simplexml.so')) {
			echo "Simplexml installed: No<br>";
		} else {
			echo "Simplexml installed: Yes<br>";
		}
	} else {
		echo "Simplexml installed: Yes<br>";
	}
	global $head_footer_errors;
	if(!empty($head_footer_errors)){
		echo '<p><strong>Your active theme:</strong>';
		foreach ( $head_footer_errors as $error )
		echo '<br>' . esc_html( $error ) . '<br>';
	} else {
		echo '<p><strong>Your active theme:</strong>';
		echo "<br>Has the wp_head in the header.php";
	}
}
function ttstoreerrordetect() {
	$settingsselected = get_option("Tradetracker_settings");
	if (!empty($settingsselected)) { 
		$tterror = "no";
		if (!function_exists('curl_init')) {
			if (ini_get('allow_url_fopen') == false) {
				$tterror="yes";
			}
		} 
		$folder =  WP_PLUGIN_DIR . "/tradetracker-store/splits";
	
		if(!is_writable($folder)){
			$tterror="yes";
		}
		$folder2 =  WP_PLUGIN_DIR . "/tradetracker-store/cache";
		if(!is_writable($folder2)){
			$tterror="yes";
		} 	
	
		if (!extension_loaded('simplexml')) {
			if (!dl('simplexml.so')) {
				$tterror="yes";
			}
		}
		if ($tterror == "yes"){
			$warning = __('Error detected in TradeTracker Store plugin, please see <a href=\"admin.php?page=tradetracker-shop-debug\">debug page</a>','ttstore' );
			add_action('admin_notices', create_function( '', "echo \"<div class='error'><p>$warning</p></div>\";" ) );
		}
	}
}
function ttstoreheader() {
	$update = "";
	if($_GET['update']=="yes"){
		xml_updater();
		$update = "Update Finished:";
	}
	echo "<div class=\"updated\"><p><strong>".$update." ".get_option("Tradetracker_xml_update")." <a href=\"admin.php?page=tradetracker-shop-settings&update=yes\">Update now</a></strong></p></div>";
	premiumcheck();
}
add_action( 'init', 'test_head_footer_init' );
function test_head_footer_init() {
	// Hook in at admin_init to perform the check for wp_head and wp_footer
	add_action( 'admin_init', 'check_head_footer' );
}
 
function check_head_footer() {
	// Build the url to call, NOTE: uses home_url and thus requires WordPress 3.0
	$url = home_url();
	// Perform the HTTP GET ignoring SSL errors
	$response = wp_remote_get( $url );
	// Grab the response code and make sure the request was sucessful
	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( $code == 200 ) {
		global $head_footer_errors;
		$head_footer_errors = array();	
		// Strip all tabs, line feeds, carriage returns and spaces
		$html = preg_replace( '/[
]/', '', wp_remote_retrieve_body( $response ) );

		// Check to see if we found the existence of wp_head
		if ( ! strstr( $html, 'basicstore' ) )
			$head_footer_errors['nohead'] = 'Is missing the call to <?php wp_head(); ?> which should appear directly before </head>';
		// Check to see if we found wp_head and if was located in the proper spot
		if ( ! empty( $head_footer_errors ) )
			test_head_footer_notices();
	}
}
 

function test_head_footer_notices() {
		$warning = __('Error detected in TradeTracker Store plugin, please see <a href=\"admin.php?page=tradetracker-shop-debug\">debug page</a>','ttstore' );
		add_action('admin_notices', create_function( '', "echo \"<div class='error'><p>$warning</p></div>\";" ) );
}
?>