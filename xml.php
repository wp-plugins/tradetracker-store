<?php
// require( '../../../wp-load.php');
require('xmlsplit.php');
require('database.php');
function xml_updater() {
	global $wpdb;
	global $processed;
	global $filenum;
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
				foreach($files as $file){ 
					unlink($file); 
					// echo "Deleted ".$file."<br>";
				}
			}
		}  
	}  
      
	// Close the directory  
	$directory->close(); 
	$xmlfeedID = "0"; 
	$file = $Tradetracker_xml;
	foreach($file as $key => $value) {
		$recordnum = "0";
		$processed = "0";
		$filenum++;
		$value($xmlfeedID, $basefilename, $key,$filenum,$recordnum,$processed,'products', 'itemXMLtag');
		$xmlfeedID++;
	}
	// echo "<p>".$filenum." files created";
	unset($chunk);
	fill_database1();
}
?>