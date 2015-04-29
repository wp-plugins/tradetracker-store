<?php
function xml_updater($xmlfilecount = "-1", $xmlfeedID = "0", $xmlcronjob = "0") {
	//load all needed variables
	global $wpdb;
	global $processed;
	global $filenum;
	global $ttstoretable;
	global $ttstoreextratable;
	global $ttstorexmltable;
	global $ttstorecattable;
	global $foldersplits;
	//update_option("xmldatabasecount", "0" );
	//prepare database 
	$xmlfilecount = get_option("xmlfilecount");
	if ($xmlfeedID == "0" && isset($_GET['xmlfeedID'])){
		$xmlfeedID = $_GET['xmlfeedID'];
	}
	if (($xmlfilecount == "-1" && !isset($_GET['xmlfilecount'])) || $xmlfilecount == "-2"){
		premium_updater();
		news_updater();
		update_option("xmlfilecount", "-2" );
		if ($xmlcronjob == "1"){
			$autoimport_count = $wpdb->get_var( "select count(id) from ".$ttstorexmltable." where autoimport = '0'" );
			if( $autoimport_count ) {
				$loadxmlfeed = $wpdb->get_results("select id from ".$ttstorexmltable." where autoimport = '1'");
				if ( $loadxmlfeed ){
					foreach ( $loadxmlfeed as $post ){
						$emptytable = "delete from ".$ttstoretable." where xmlfeed=".$post->id."";
						$wpdb->query($emptytable);
					}
					$emptyextratable = "DELETE FROM ".$ttstoreextratable." WHERE productID NOT IN (SELECT ".$ttstoretable.".productID FROM ".$ttstoretable.")";
					$wpdb->query($emptyextratable);
					$emptycattable = "DELETE FROM ".$ttstorecattable." WHERE productID NOT IN (SELECT ".$ttstoretable.".productID FROM ".$ttstoretable.")";
					$wpdb->query($emptycattable);
				}
			} else {
				$wpdb->query("TRUNCATE TABLE `$ttstoretable`");
				$wpdb->query("TRUNCATE TABLE `$ttstoreextratable`");
				$wpdb->query("TRUNCATE TABLE `$ttstorecattable`");
			}
		} else {
			$wpdb->query("TRUNCATE TABLE `$ttstoretable`");
			$wpdb->query("TRUNCATE TABLE `$ttstoreextratable`");
			$wpdb->query("TRUNCATE TABLE `$ttstorecattable`");
		}
		$xmlfilecount = "0";
		update_option("xmlfilecount", "0" );
		delete_option("Tradetracker_importerror");
		delete_option("Tradetracker_memoryusage");	
		delete_option("Tradetracker_xml_extra");
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
		echo "I cannot write to the folder. Please see the <a href=\"admin.php?page=tt-store&option=ttdebug\">debug settings</a> to find out more.";
		exit;
	}
	
	//get xml details from database
	$Tradetracker_xml = get_option("Tradetracker_xml");
	$Tradetracker_xmlname = get_option("Tradetracker_xmlname");
	if ($xmlcronjob == "1"){
		$loadxmlfeeds = $wpdb->get_results("select id, xmlfeed, xmlprovider from ".$ttstorexmltable." where autoimport = '1'", ARRAY_A);
	} else {
		$loadxmlfeeds = $wpdb->get_results("select id, xmlfeed, xmlprovider from ".$ttstorexmltable."", ARRAY_A);
	}
	//check if splits directory is empty else empty it
	$runs = "0";
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
		$directory = dir($foldersplits); 
		while ((FALSE !== ($item = $directory->read())) && ( ! isset($directory_not_empty))){  
			if ($item != '.' && $item != '..'){  
				$files = glob($foldersplits."/*xml");
				if(count($files) > 0){	
					if (is_array($files)) {
						foreach($files as $filedel){
							unlink($filedel); 
						}
					}
				}
			}  
		}
		$directory->close();
		update_option("xmlfilecount", $xmlfilecount );
		$value($xmlfeedid, $basefilename, $key,$filenum,$recordnum,$processed,'products', 'itemXMLtag');
		fill_database1($xmlfilecount, $xmlcronjob);
		if($xmlcronjob == "1"){
			$runs++;
			$feedperupdate = get_option("Tradetracker_xmlfeedsperupdate");
			if(isset($feedperupdate) && $feedperupdate > "0"){
				if($runs == $feedperupdate){
					exit();
				}
			}
		}
	} else {
		update_option("xmlfilecount", "-1" );
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