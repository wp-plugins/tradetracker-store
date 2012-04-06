<?php
function xml_updater($xmlfilecount = "0", $xmlfeedID = "0") {
	//load all needed variables
	global $wpdb;
	global $processed;
	global $filenum;
	global $ttstoretable;
	global $foldersplits;

	//prepare database 

	if ( $xmlfilecount == "0" && isset($_GET['xmlfilecount'])){
		$xmlfilecount = $_GET['xmlfilecount'];
	}
	if (isset($_GET['xmlfeedID'])){
		$xmlfeedID = $_GET['xmlfeedID'];
	}
	if ($xmlfilecount == "0" && !isset($_GET['xmlfilecount'])){
		premium_updater();
		news_updater();
		delete_option("Tradetracker_importerror");
		$emptytable = "TRUNCATE TABLE `$ttstoretable`";
		$wpdb->query($emptytable);
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


	//check if splits directory is empty else empty it

	$keys = array_keys($Tradetracker_xml);
	$key = $keys[$xmlfilecount];
	$value = $Tradetracker_xml[$key];


	 
	$file = $Tradetracker_xml;
	$recordnum = "0";
	$processed = "0";
	$filenum++;
	$value($xmlfilecount, $basefilename, $key,$filenum,$recordnum,$processed,'products', 'itemXMLtag');
	fill_database1($xmlfilecount);
	$xmlfeedID++;
	$directory = dir($foldersplits); 
	while ((FALSE !== ($item = $directory->read())) && ( ! isset($directory_not_empty)))  
		{  
		if ($item != '.' && $item != '..')
   		{  
			$files = glob($foldersplits."/*xml");
			if(count($files) > 0)
				{	
				if (is_array($files)) {
					foreach($files as $filedel){
						unlink($filedel); 
					}
				}
			}
		}  
	}
	$directory->close();
	if ($xmlfilecount < count($Tradetracker_xml)-1){
		$xmlfilecount++;
		$runs = array("5", "10", "15", "20", "25", "30", "35", "40", "45", "50", "55", "60", "65", "70", "75", "80");
		if (in_array($xmlfilecount, $runs)) {
?>
<script type="text/javascript">
window.location.href='<?php echo "admin.php?page=tt-store&update=yes&xmlfilecount=$xmlfilecount&xmlfeedID=$xmlfeedID"; ?>';
</script>
<?php
		} else {
			xml_updater($xmlfilecount, $filenum, $xmlfeedID); 
		}
	} else {
		$errorfile = get_option("Tradetracker_importerror");
		if(!empty($errorfile)){
			if(get_option("Tradetracker_debugemail")==1){
				$message = "Hi,". "\r\n" ."";
				$message .= "". "\r\n" ."You receive this message because you are using the TradeTracker-Store plugin. It just tried to import the XML feed(s) but encountered an error.". "\r\n" ."";
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