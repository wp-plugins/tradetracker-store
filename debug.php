<?php
function debug() {
global $wpdb;
global $foldersplits;
global $foldercache;
global $ttstoretable;
global $ttstoreextratable;
global $ttstorelayouttable;
global $ttstoremultitable;
?>
<?php $adminwidth = get_option("Tradetracker_adminwidth"); ?>
<?php $adminheight = get_option("Tradetracker_adminheight"); ?>

<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle"><?php _e('Debug', 'ttstore'); ?></div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">
<?php
	echo "<strong>";
	_e('Needed to import XML Feed', 'ttstore');
	echo "</strong><br>";

	if (get_option('Tradetracker_importtool')=="1"){
		if (ini_get('allow_url_fopen') == true) {
			_e('allow_url_fopen enabled:', 'ttstore'); 
			echo " ";
			_e('Yes', 'ttstore');
			echo "<br>";
		} else {
			echo "<font color=red>";
			_e('allow_url_fopen enabled:', 'ttstore'); 
			echo " ";
			_e('No, please change the import method <a href="admin.php?page=tt-store&option=pluginsettings">here</a>', 'ttstore');
			echo "</font><br>";
		}
	} else if (get_option('Tradetracker_importtool')=="2" || get_option('Tradetracker_importtool')=="3") {
		if (function_exists('curl_init')) {
			_e('Curl enabled:', 'ttstore'); 
			echo " ";
			_e('Yes', 'ttstore');
			echo "<br>";
		} else {
			echo "<font color=red>";
			_e('Curl enabled:', 'ttstore'); 
			echo " ";
			_e('No, please change the import method <a href="admin.php?page=tt-store&option=pluginsettings">here</a>', 'ttstore');
			echo "</font><br>";
		}
	} 
	echo "<p><strong>";
	_e('Needed to write XML file', 'ttstore');	
	echo "</strong><br>";
	if(is_writable($foldersplits)){
		printf(__('%s is writable.', 'ttstore'), $foldersplits);
		echo "<br>";
	} else {
		echo "<font color=red>";
		printf(__('%s is not writable. Please CHMOD 777 it.', 'ttstore'), $foldersplits);
		echo "</font><br>";
	}
	if(is_writable($foldercache)){
		printf(__('%s is writable.', 'ttstore'), $foldercache);
		echo "<br>";
	} else {
		echo "<font color=red>";
		printf(__('%s is not writable. Please CHMOD 777 it.', 'ttstore'), $foldercache);
		echo "</font><br>";
	}

	echo "<p><strong>";
	_e('Needed to write XML to database', 'ttstore');	
	echo "</strong><br>";
	if (!extension_loaded('simplexml')) {
		if (!dl('simplexml.so')) {
			echo "<font color=red>";
			_e('Simplexml installed:', 'ttstore'); 
			echo " ";
			_e('No', 'ttstore');
			echo "</font><br>";
		} else {
			_e('Simplexml installed:', 'ttstore'); 
			echo " ";
			_e('Yes', 'ttstore');
			echo "<br>";
		}
	} else {
		_e('Simplexml installed:', 'ttstore'); 
		echo " ";
		_e('Yes', 'ttstore');
		echo "<br>";
	}
	global $head_footer_errors;
	if(!empty($head_footer_errors)){
		echo "<p><strong>";
		_e('Your active theme', 'ttstore');	
		echo "</strong><br>";
		foreach ( $head_footer_errors as $error )
		echo '<font color=red>' . esc_html( $error ) . '</font><br>';
	} else {
		echo "<p><strong>";
		_e('Your active theme', 'ttstore');	
		echo "</strong><br>";
		_e('Has the wp_head in the header.php', 'ttstore');
	}
	echo "<p><strong>";
	_e('Last MySQL upgrade:', 'ttstore');
	echo "</strong><br>";
	echo get_option("TTstoreversion");
	$storetableoverview = $wpdb->get_results("SHOW COLUMNS FROM ".$ttstoretable."");
	echo "<p><strong>";
	_e('Database Table overview: Items', 'ttstore');	
	echo "</strong>";
	echo "<table>";
	echo "<tr><td width=\"200px\"><strong>";
	_e('Field', 'ttstore');
	echo "</strong></td><td width=\"200px\"><strong>";
	_e('Type', 'ttstore');
	echo "</strong></td></tr>";
	foreach ( $storetableoverview as $overview ) 
	{
		echo "<tr><td>".$overview->Field."</td>";
		echo "<td>".$overview->Type."</td></tr>";		
	}	
	echo "</table>";

	$multitableoverview = $wpdb->get_results("SHOW COLUMNS FROM ".$ttstoremultitable."");
	echo "<p><strong>";
	_e('Database Table overview: Store', 'ttstore');	
	echo "</strong>";
	echo "<table>";
	echo "<tr><td width=\"200px\"><strong>";
	_e('Field', 'ttstore');
	echo "</strong></td><td width=\"200px\"><strong>";
	_e('Type', 'ttstore');
	echo "</strong></td></tr>";
	foreach ( $multitableoverview as $overview ) 
	{
		echo "<tr><td>".$overview->Field."</td>";
		echo "<td>".$overview->Type."</td></tr>";		
	}	
	echo "</table>";

	$extratableoverview = $wpdb->get_results("SHOW COLUMNS FROM ".$ttstoreextratable."");
	echo "<p><strong>";
	_e('Database Table overview: Extra', 'ttstore');	
	echo "</strong>";
	echo "<table>";
	echo "<tr><td width=\"200px\"><strong>";
	_e('Field', 'ttstore');
	echo "</strong></td><td width=\"200px\"><strong>";
	_e('Type', 'ttstore');
	echo "</strong></td></tr>";
	foreach ( $extratableoverview as $overview ) 
	{
		echo "<tr><td>".$overview->Field."</td>";
		echo "<td>".$overview->Type."</td></tr>";		
	}	
	echo "</table>";

	$layouttableoverview = $wpdb->get_results("SHOW COLUMNS FROM ".$ttstorelayouttable."");
	echo "<p><strong>";
	_e('Database Table overview: Layout', 'ttstore');	
	echo "</strong>";
	echo "<table>";
	echo "<tr><td width=\"200px\"><strong>";
	_e('Field', 'ttstore');
	echo "</strong></td><td width=\"200px\"><strong>";
	_e('Type', 'ttstore');
	echo "</strong></td></tr>";
	foreach ( $layouttableoverview as $overview ) 
	{
		echo "<tr><td>".$overview->Field."</td>";
		echo "<td>".$overview->Type."</td></tr>";		
	}	
	echo "</table>";


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

	if (get_option('Tradetracker_importtool')=="1"){
		if (ini_get('allow_url_fopen') == false) {
			$tterror="yes";
		}
	} else if (get_option('Tradetracker_importtool')=="2" || get_option('Tradetracker_importtool')=="3") {
		if (!function_exists('curl_init')) {
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
		$warning = __('Error detected in TradeTracker Store plugin, please see <a href=admin.php?page=tt-store&option=debug>debug page</a>','ttstore');
		add_action('admin_notices', create_function( '', "echo \"<div class='error'><p>$warning</p></div>\";" ) );
		if($show=="yes"){
			return "yes";
		}
	}
}
function ttstoreheader() {
	$update = "";
	if(isset($_GET['update']) && $_GET['update']=="yes"){
		if(!isset($_GET['xmlfilecount'])){
			update_option("xmlfilecount", "0" );
		}
		xml_updater();
		$update = __('Update Finished:','ttstore');
	}
	if(isset($_GET['bgupdate']) && $_GET['bgupdate']=="yes"){
		wp_clear_scheduled_hook('xmlscheduler');
		update_option( 'Tradetracker_xml_update' , '' );
		$update = __('Update has started in the background:','ttstore');
	}
	if(isset($_GET['database']) && $_GET['database']=="yes"){
		if(!isset($_GET['xmldatabasecount'])){
			update_option("xmldatabasecount", "0" );
		}
		fill_database1($_GET['xmlfeedid'], "0");
	}
	$updatetext = __('Update now','ttstore');
	$updatebgtext = __('run update in background','ttstore');
	echo "<div class=\"updated\"><p><strong>".$update." ".get_option("Tradetracker_xml_update")." <a href=\"admin.php?page=tt-store&update=yes\">".$updatetext."</a> ".__('or', 'ttstore')." <a href=\"admin.php?page=tt-store&bgupdate=yes\">".$updatebgtext."</a></strong></p></div>";
	$errorfile = get_option("Tradetracker_importerror");
	if(!empty($errorfile)){
		$oldvalue = array("\n", "Feedname:", "Splitfile:");
		$newvalue = array("<br>", "<strong>Feedname:</strong>", "<strong>Splitfile:</strong>");
		$osmessage =  __('<strong>The following XML splits gave an error or were empty during the last import. So they are possibly not imported.</strong>','ttstore');
		$osmessage .= str_replace($oldvalue,$newvalue,$errorfile);
		echo "<div class='error'>".$osmessage."</div>";
	}
}
add_action( 'init', 'test_head_footer_init' );
function test_head_footer_init() {
	// Hook in at admin_init to perform the check for wp_head and wp_footer
	add_action( 'admin_init', 'check_head_footer' );
	//add_action( 'admin_init', 'ttstoreerrordetect' );
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
		if(!strstr( $html, 'basicstore' )) {
			$head_footer_errors['nohead'] = __('Is missing the call to <?php wp_head(); ?> which should appear directly before </head>','ttstore');
		}
		// Check to see if we found wp_head and if was located in the proper spot
		if ( ! empty( $head_footer_errors ) ){
			ttstoreerrordetect("no");
		} else {
			ttstoreerrordetect("no");
		}
	}
	}
}
//function test_head_footer_notices() {
//	$warning = __("Error detected in TradeTracker Store plugin, please see <a href=\"admin.php?page=tt-store&option=debug\">debug page</a>","ttstore");
//	add_action('admin_notices', create_function( '', "echo \"<div class='error'><p>$warning</p></div>\";" ) );
//}
?>