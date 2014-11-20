<?php
function xml_updater($xmlfilecount = "0", $xmlfeedID = "0", $xmlcronjob = "0") {
	//load all needed variables
	global $wpdb;
	global $processed;
	global $filenum;
	global $ttstoretable;
	global $ttstoreextratable;
	global $ttstorexmltable;
	global $ttstorecattable;
	global $foldersplits;
	update_option("xmldatabasecount", "0" );
	//prepare database 
	$xmlfilecount = get_option("xmlfilecount");
	if ($xmlfeedID == "0" && isset($_GET['xmlfeedID'])){
		$xmlfeedID = $_GET['xmlfeedID'];
	}
	if ($xmlfilecount == "0" && !isset($_GET['xmlfilecount'])){
		premium_updater();
		news_updater();
		delete_option("Tradetracker_importerror");
		delete_option("Tradetracker_memoryusage");	
		delete_option("Tradetracker_xml_extra");
		$emptytable = "TRUNCATE TABLE `$ttstoretable`";
		$emptyextratable = "TRUNCATE TABLE `$ttstoreextratable`";
		$emptycattable = "TRUNCATE TABLE `$ttstorecattable`";
		$wpdb->query($emptytable);
		$wpdb->query($emptyextratable);
		$wpdb->query($emptycattable);
		$item_count = $wpdb->get_var("SELECT COUNT(*) FROM $ttstoretable;");
		$currentupdate = date('Y-m-d H:i:s');
		$option_name = 'Tradetracker_xml_update' ;
		$newvalue = sprintf(__('Database filled with %1$s new items on %2$s','ttstore'), $item_count, $currentupdate);

		if ( get_option( $option_name ) != $newvalue ) {
			update_option( $option_name, $newvalue );
		} else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option( $option_name, $newvalue, $deprecated, $autoload );
		}
		$directory = dir($foldersplits); 
		while ((FALSE !== ($item = $directory->read())) && ( ! isset($directory_not_empty)))
		{  
			if ($item != '.' && $item != '..')
       			{  
				$files = glob($foldersplits."/*xml");
				if(count($files) > 0)
				{
					if (is_array($files)) {
						foreach($files as $file){
							unlink($file); 
						}
					}
				}
			}  
		}
		// Close the directory  
		$directory->close(); 
	} 


	//prepare cache file
	$basefilename = "TTStoreXML";
	//$filenum = "0"; // start chunk file number at 1
	if(!is_writable($foldersplits)){
		exit;
	}
	
	//get xml details from database
	$Tradetracker_xml = get_option("Tradetracker_xml");
	$Tradetracker_xmlname = get_option("Tradetracker_xmlname");
	$loadxmlfeeds = $wpdb->get_results("select id, xmlfeed, xmlprovider from ".$ttstorexmltable."", ARRAY_A);
	//check if splits directory is empty else empty it
	if ($xmlfilecount <= count($loadxmlfeeds)-1){
		echo "<br /><strong>Memory Usage before import:</strong>".convert(memory_get_peak_usage());
		$ttmemoryusage = get_option("Tradetracker_memoryusage");
		$ttmemoryusage .= "<br/><strong>Memory Usage before import:</strong>".convert(memory_get_peak_usage());
		update_option( "Tradetracker_memoryusage", $ttmemoryusage );
		$key = $loadxmlfeeds[$xmlfilecount]['xmlfeed'];
		$value = $loadxmlfeeds[$xmlfilecount]['xmlprovider'];
		$xmlfeedid = $loadxmlfeeds[$xmlfilecount]['id'];
		$recordnum = "0";
		$processed = "0";
		$filenum = "0";
		$value($xmlfeedid, $basefilename, $key,$filenum,$recordnum,$processed,'products', 'itemXMLtag');
		fill_database1($xmlfilecount, $xmlcronjob);

	} else {
		update_option("xmlfilecount", "0" );
		$errorfile = get_option("Tradetracker_importerror");
		if(!empty($errorfile)){
			if(get_option("Tradetracker_debugemail")==1){
				$message = "Hi,". "\r\n" ."";
				$message .= "". "\r\n" ."You receive this message because you are using the TradeTracker-Store plugin. It just tried to import the XML feed(s) but encountered an error.". "\r\n" ."";
				$message .= "". "\r\n" ."More information about this can be found here: http://wpaffiliatefeed.com/624/frequently-asked-questions/my-import-gives-an-error/.". "\r\n" ."";
				$message .= "". "\r\n" ."The following XML splits are giving an error or are empty. So it could be there are no items imported from that feed.". "\r\n" ."";
				$message .= $errorfile;
				$message .= "". "\r\n" ."". "\r\n" ."To disable this email go to ".get_bloginfo('url')."/wp-admin/admin.php?page=tt-store&option=pluginsettings and select no for the option Get email when import fails:";
				$to      = ''.get_bloginfo('admin_email').'';
				$subject = 'There was an issue with importing the XML Feed';
				$headers = 'From: '.get_bloginfo('admin_email').'' . "\r\n" . 'Reply-To: '.get_bloginfo('admin_email').'' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $message, $headers);
			}
		}
	}
}
?>