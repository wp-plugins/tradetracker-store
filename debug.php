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
}
function ttstoreerrordetect() {
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
function ttstoreheader() {
	echo "<div class=\"updated\"><p><strong>".get_option("Tradetracker_xml_update")."</strong></p></div>";
	premiumcheck();
}
?>