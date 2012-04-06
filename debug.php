<?php
function debug() {
global $foldersplits;
global $foldercache;
?>
<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: 1000px;">
	<div id="ttstorebox">
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle">Debug</div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		<?php $adminheight = get_option("Tradetracker_adminheight"); ?>
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">
<?php
	echo "<b>Needed to import XML Feed</b><br>";
	if (function_exists('curl_init')) {
		echo "Curl enabled: Yes<br>";
	} else {
		if (ini_get('allow_url_fopen') == true) {
			echo "allow_url_fopen enabled: Yes<br>";
		} else {
			echo "<font color=red>Curl enabled: No<br>allow_url_fopen enabled: No</font><br>";
		}
	}
	echo "<p><b>Needed to write XML file</b><br>";
	if(is_writable($foldersplits)){
		echo $foldersplits." is writable<br>";
	} else {
		echo "<font color=red>".$foldersplits." is not writable. Please CHMOD 777 it</font><br>";
	}
	if(is_writable($foldercache)){
		echo $foldercache." is writable<br>";
	} else {
		echo "<font color=red>".$foldercache." is not writable. Please CHMOD 777 it</font><br>";
	}

	echo "<p><b>Needed to write XML to database</b><br>";
	if (!extension_loaded('simplexml')) {
		if (!dl('simplexml.so')) {
			echo "<font color=red>Simplexml installed: No</font><br>";
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
		echo '<br><font color=red>' . esc_html( $error ) . '</font><br>';
	} else {
		echo '<p><strong>Your active theme:</strong>';
		echo "<br>Has the wp_head in the header.php";
	}
?>
		</div>
		<div id="ttstoreboxbottom">
			<INPUT type="button" name="Close" class="button-secondary" value="<?php esc_attr_e('Close') ?>" onclick="location.href='admin.php?page=tt-store'"> 
		</div>
	</div>
</div>
<?php
}
function ttstoreerrordetect($show) {
	global $head_footer_errors;
	$foldersplits = plugin_dir_path( __FILE__ )."splits/";
	$foldercache = plugin_dir_path( __FILE__ )."cache/";

	$tterror = "no";
	if (!function_exists('curl_init')) {
		if (ini_get('allow_url_fopen') == false) {
			$tterror="yes";
		}
	} 
		if(!is_writable($foldersplits)){
			$tterror="yes";
		}
		if(!is_writable($foldercache)){
			$tterror="yes";
		} 	

	if (!extension_loaded('simplexml')) {
		if (!dl('simplexml.so')) {
			$tterror="yes";
		}
	}
	if(!empty($head_footer_errors)){
		$tterror="yes";
	}	
	if ($tterror == "yes"){
		$warning = __('Error detected in TradeTracker Store plugin, please see <a href=\"admin.php?page=tt-store&option=debug\">debug page</a>','ttstore' );
		add_action('admin_notices', create_function( '', "echo \"<div class='error'><p>$warning</p></div>\";" ) );
		if($show=="yes"){
			return "yes";
		}
	}
}
function ttstoreheader() {
	$update = "";
	if(isset($_GET['update']) && $_GET['update']=="yes"){
		xml_updater();
		$update = "Update Finished:";
	}
	echo "<div class=\"updated\"><p><strong>".$update." ".get_option("Tradetracker_xml_update")." <a href=\"admin.php?page=tt-store&update=yes\">Update now</a></strong></p></div>";
	$errorfile = get_option("Tradetracker_importerror");
	if(!empty($errorfile)){
		$oldvalue = array("\n", "Feedname:", "Splitfile:");
		$newvalue = array("<br>", "<strong>Feedname:</strong>", "<strong>Splitfile:</strong>");
		$osmessage = "<strong>The following XML splits gave an error or were empty during the last import. So they are possibly not imported.</strong>";
		$osmessage .= str_replace($oldvalue,$newvalue,$errorfile);
		echo "<div class='error'>".$osmessage."</div>";
	}
}
add_action( 'init', 'test_head_footer_init' );
function test_head_footer_init() {
	// Hook in at admin_init to perform the check for wp_head and wp_footer
	add_action( 'admin_init', 'check_head_footer' );
	add_action( 'admin_init', 'ttstoreerrordetect' );
}
 
function check_head_footer() {
	// Build the url to call, NOTE: uses home_url and thus requires WordPress 3.0
	if(get_option("Tradetracker_usecss") != "1"){
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
			ttstoreerrordetect("no");
	}
	}
}
function test_head_footer_notices() {
	$warning = __('Error detected in TradeTracker Store plugin, please see <a href=\"admin.php?page=tt-store&option=debug\">debug page</a>','ttstore' );
	add_action('admin_notices', create_function( '', "echo \"<div class='error'><p>$warning</p></div>\";" ) );
}
?>