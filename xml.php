<?php
// require( '../../../wp-load.php');
require('xmlsplit.php');
require('database.php');
function xml_updater() {
	global $wpdb;
	global $processed;
	global $filenum;
	GLOBAL $errorfile;
	GLOBAL $oserrorfile;
	$table = PRO_TABLE_PREFIX."store";
	//$emptytable = "DELETE FROM $table;;";
	$emptytable = "TRUNCATE TABLE `$table`";
	$wpdb->query($emptytable);
	$basefilename = "TTStoreXML";
	$filenum = "0"; // start chunk file number at 1
	$folder =  WP_PLUGIN_DIR . "/tradetracker-store/splits";
	if(is_writable($folder)){
		// echo $folder." is writable<br>";
	} else {
		// echo $folder." is not writable. Please CHMOD 777 it";
		exit;
	}
	if (!extension_loaded('simplexml')) {
		if (!dl('simplexml.so')) {
			// echo "Simplexml installed: no";
			exit;
		} else {
			// echo "Simplexml installed: yes<br>";
		}
	} else {
		// echo "Simplexml installed: yes<br>";
	}
	$Tradetracker_xml = get_option("Tradetracker_xml");
	$Tradetracker_xmlname = get_option("Tradetracker_xmlname");
	if ($Tradetracker_xml == null) 
	{
		// echo "No XML filled in yet please change the settings first.";
	}

	$directory = dir(WP_PLUGIN_DIR."/tradetracker-store/splits"); 
	while ((FALSE !== ($item = $directory->read())) && ( ! isset($directory_not_empty)))  
	{  
		if ($item != '.' && $item != '..')  
       		{  
			$files = glob($folder."/*xml");
			if(count($files) > 0)
			{
				if (is_array($files)) {
					foreach($files as $file){ 
						unlink($file); 
						// echo "Deleted ".$file."<br>";
					}
				}
			}
		}  
	}  
      
	// Close the directory  
	$directory->close(); 
	$xmlfeedID = "0"; 
	$file = $Tradetracker_xml;
	$i="0";
	foreach($file as $key => $value) {
		if($i<=5){
			$recordnum = "0";
			$processed = "0";
			$filenum++;
			$value($xmlfeedID, $basefilename, $key,$filenum,$recordnum,$processed,'products', 'itemXMLtag');
			$xmlfeedID++;
		$i++;
		} else {
			$recordnum = "0";
			$processed = "0";
			$filenum++;
			$value($xmlfeedID, $basefilename, $key,$filenum,$recordnum,$processed,'products', 'itemXMLtag');
			$xmlfeedID++;
			fill_database1();
			$directory = dir(WP_PLUGIN_DIR."/tradetracker-store/splits"); 
			while ((FALSE !== ($item = $directory->read())) && ( ! isset($directory_not_empty)))  
			{  
				if ($item != '.' && $item != '..')  
    		   		{  
					$files = glob($folder."/*xml");
					if(count($files) > 0)
					{	
						if (is_array($files)) {
							foreach($files as $filedel){ 
								unlink($filedel); 
								// echo "Deleted ".$file."<br>";
							}
						}
					}
				}  
			}
			$i="0";
		}
	}
	fill_database1();
	if(!empty($errorfile)){
		if(get_option("Tradetracker_debugemail")==1){
			$message = "Hi,". "\r\n" ."";
			$message .= "". "\r\n" ."You receive this message because you are using the TradeTracker-Store plugin. It just tried to import the XML feed(s) but encountered an error.". "\r\n" ."";
			$message .= "". "\r\n" ."The following XML feeds are giving an error or are empty. So they are not imported.". "\r\n" ."";
			$message .= $errorfile;
			$message .= "". "\r\n" ."". "\r\n" ."Please contact TradeTracker and ask them why the feeds are emtpy". "\r\n" ."";
			$message .= "". "\r\n" ."To disable this email go to ".get_bloginfo(siteurl)."/wp-admin/admin.php?page=tradetracker-shop-settings and select no for the option Get email when import fails:";
			$to      = ''.get_bloginfo('admin_email').'';
			$subject = 'There was an issue with importing the XML Feed';
			$headers = 'From: '.get_bloginfo('admin_email').'' . "\r\n" . 'Reply-To: '.get_bloginfo('admin_email').'' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $message, $headers);
		}
		$osmessage = "<strong>The following XML feeds are giving an error or are empty. So they are not imported.</strong>";
		$osmessage .= $oserrorfile;
		$osmessage .= "<br>Please contact TradeTracker and ask them why the feeds are emtpy";
		echo "<div class='error'>".$osmessage."</div>";
	}
	// echo "<p>".$filenum." files created";
}
?>