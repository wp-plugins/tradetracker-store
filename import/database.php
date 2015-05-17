<?php
function parse_recursive(SimpleXMLElement $element, $level = 0)
{	
	global $wpdb;
	GLOBAL $extrafield;
	GLOBAL $extravalue;
	GLOBAL $counterxml;
	global $ttstoreextratable;
	$indent = str_repeat("\t", $level); // determine how much we'll indent
	$value = trim((string) $element); // get the value and trim any whitespace from the start and end
	$attributes = $element->attributes(); // get all attributes
	$children = $element->children(); // get all children
        if(count($children) == 0 && !empty($value)) 
	{       
		if($element->getName()=="field"){
			$wpdb->insert($ttstoreextratable ,array('productID' => $productID,'extrafield' => $attributes,'extravalue' => $element ),array('%s','%s','%s'));
			$wpdb->flush();
		}      
	}
	
	if(count($children))
	{
		foreach($children as $child)
		{
			parse_recursive($child, $level+1); // recursion :)
		}
	}
}
function str2bytes($value) {
// only string
$unit_byte = preg_replace('/[^a-zA-Z]/', '', $value);
$unit_byte = strtolower($unit_byte);
// only number (allow decimal point)
$num_val = preg_replace('/\D\.\D/', '', $value);
switch ($unit_byte) {
case 'p': // petabyte
case 'pb':
$num_val *= 1024;
case 't': // terabyte
case 'tb':
$num_val *= 1024;
case 'g': // gigabyte
case 'gb':
$num_val *= 1024;
case 'm': // megabyte
case 'mb':
$num_val *= 1024;
case 'k': // kilobyte
case 'kb':
$num_val *= 1024;
case 'b': // byte
return $num_val *= 1;
break; // make sure
default:
return FALSE;
}
return FALSE;
} 
function convert($size)
 {
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
 }

function fill_database1($xmlfeedid, $xmlcronjob)
{
	global $wpdb;
	global $ttstorexmltable;
	if(!isset($wpdb) || empty($wpdb)){
		tt_log_me("TT Database: wpdb not loaded");
	}
	global $errorfile;
	if(!isset($errorfile) || empty($errorfile)){
		tt_log_me("TT Database: errorfile not loaded");
	}
	GLOBAL $extrafield;
	if(!isset($extrafield) || empty($extrafield)){
		tt_log_me("TT Database: extrafield not loaded");
	}
	GLOBAL $extravalue;
	if(!isset($extravalue) || empty($extravalue)){
		tt_log_me("TT Database: extravalue not loaded");
	}
	GLOBAL $counterxml;
	if(!isset($counterxml) || empty($counterxml)){
		tt_log_me("TT Database: counterxml not loaded");
	}
	global $ttstoretable;
	if(!isset($ttstoretable) || empty($ttstoretable)){
		tt_log_me("TT Database: ttstoretable not loaded");
	}
	global $ttstoreextratable;
	if(!isset($ttstoreextratable) || empty($ttstoreextratable)){
		tt_log_me("TT Database: ttstoreextratable not loaded");
	}
	global $ttstorexmltable;
	if(!isset($ttstorexmltable) || empty($ttstorexmltable)){
		tt_log_me("TT Database: ttstorexmltable not loaded");
	}
	global $ttstorecattable;
	if(!isset($ttstorecattable) || empty($ttstorecattable)){
		tt_log_me("TT Database: ttstorecattable not loaded");
	}
	global $foldersplits;
	if(!isset($foldersplits) || empty($foldersplits)){
		tt_log_me("TT Database: foldersplits not loaded");
	}
	$xmlfilecount = get_option("xmlfilecount");
	$xmlfeedid = get_option("xmlfilecount");
	$newcategory = get_option("TTnewcategory");
	$xmldatabasecount = get_option("xmldatabasecount");
	if($xmldatabasecount == ""){
		$xmldatabasecount = "0";
	}
	$extrafieldarray = get_option('Tradetracker_xml_extra');

	$files = glob($foldersplits."*xml");
	if(isset($xmlfeedid)){
		$xmlfeednumber = $xmlfeedid;
	} else {
		$xmlfeednumber = 1;
	}
	if ($xmlcronjob == "1"){
		$xmlfeed = $wpdb->get_results("select xmlname FROM ".$ttstorexmltable." where autoimport = '1'" , ARRAY_N);
	} else {
		$xmlfeed = $wpdb->get_results("select xmlname FROM ".$ttstorexmltable."" , ARRAY_N);
	}
	//$xmlfeed = get_option("Tradetracker_xmlname");	
	//$keys = array_keys($xmlfeed);
	//$key = $keys[$xmlfeedid];
	//$xmlfeed = $xmlfeed[$key];
	if ($xmlcronjob == "0"){
		$feednumercount = $xmlfeednumber;
		echo "<br /><strong>Feeds Completed: </strong> ".$feednumercount."/".count($xmlfeed)."";
		$feedsimported = sprintf(__('<strong>Feeds Completed: </strong> %1$s / %2$s','ttstore'), $feednumercount, count($xmlfeed));
		update_option( "Tradetracker_feedsimported", $feedsimported );
		$scale = "4";
		$percent = (100/count($xmlfeed))*$feednumercount;
		echo "<style>.percentbar { background:#CCCCCC; border:1px solid #666666; height:10px; }.percentbar div { background: #28B8C0; height: 10px; }</style>";	
		echo "<div class=\"percentbar\" style=\"width:".round(100 * $scale)."px;\">";
		echo "<div style=\"width:".round($percent * $scale)."px;\"></div>";
		echo "</div>".round($percent,'2')."%"; 
		echo "<br /><strong>Currently Importing: </strong>".$xmlfeed[$xmlfeednumber][0];
		//echo "<br /><strong>File: </strong>".$files[$xmldatabasecount];
	}
		$ttmemoryusage = get_option("Tradetracker_memoryusage");
		$ttmemoryusage .= "<br/><strong>Importing:</strong>".$xmlfeed[$xmlfeednumber][0];
		update_option( "Tradetracker_memoryusage", $ttmemoryusage );
		tt_log_me("TT Database: After memory usage");
	if (is_array($files)) {
		tt_log_me("TT Database: Files is array");
		for ( $i="0"; ($i <= 2 && $i <count($files)); $i++) {
			if ($xmlcronjob == "0"){
				$scale = "4";
				$percent = (100/count($files))*$xmldatabasecount;
				echo "<style>.percentbar { background:#CCCCCC; border:1px solid #666666; height:10px; }.percentbar div { background: #28B8C0; height: 10px; }</style>";	
				echo "<div class=\"percentbar\" style=\"width:".round(100 * $scale)."px;\">";
				echo "<div style=\"width:".round($percent * $scale)."px;\"></div>";
				echo "</div>".round($percent,'2')."%";
			} 
			$filename = $files[$xmldatabasecount];
			if($filename != ""){
				$products = simplexml_load_file($filename);
				//$string = file_get_contents($filename, FILE_TEXT);
				//$products = @simplexml_load_string($string);
				if($products === false)
				{
					$errorxml = libxml_get_last_error();
					if($errorxml->message != 'failed to load external entity ""'){
						$errorfile = get_option("Tradetracker_importerror");
						$errorfile .= "". "\n" ."Feedname: ".$xmlfeed[$xmlfeednumber][0];
						$errorfile .= "". "\n" ."Splitfile: ".$filename;
						$errorfile .= "". "\n" ."Error: ".$errorxml->message;
						update_option( "Tradetracker_importerror", $errorfile );
					}
					libxml_clear_errors();
					tt_log_me("TT Database: Error 1");
				}else if ($products->body->p == "The requested product feed could not be generated:"){
					$errorxml = libxml_get_last_error();
					$errorfile = get_option("Tradetracker_importerror");
					$errorfile .= "". "\n" ."Feedname: ".$xmlfeed[$xmlfeednumber][0];
					$errorfile .= "". "\n" ."Error: Tradetracker cannot create the productfeed. The feed itself is empty";
					libxml_clear_errors();
					update_option( "Tradetracker_importerror", $errorfile );
					tt_log_me("TT Database: Error 2");
				} else {
					tt_log_me("TT Database: No error");
					foreach($products as $product) // loop through our items
					{
						$counterxml = "1";
						$extrafield = "";
						$extravalue = "";
						$productID = $product->productURL;
						$productID = md5($productID);
						$currentpage["productID"]=$productID;
						$currentpage["xmlfeed"]=$product->xmlfile;		
						$currentpage["name"]=$product->name;
						$currentpage["imageURL"]=$product->imageURL;
						$currentpage["imageURL"]=$product->imageURL;
						$currentpage["productURL"]=$product->productURL;
						$currentpage["description"]=strip_tags(htmlspecialchars_decode($product->description));
						$currentpage["price"]=$product->price;
						$currentpage["currency"]=$product->price['currency'];
						$wpdb->insert( $ttstoretable, $currentpage);//insert the captured values
						$wpdb->flush();
						if($product->categories->category==""){
							$wpdb->insert($ttstorecattable ,array('productID' => $productID,'categorie' => "empty category",'categorieid' => md5($xmlfeed[$xmlfeednumber][0]."empty category") ),array('%s','%s','%s'));
						} else {
							$querycat = "";
							$i="1";
							$totalcat = count($product->categories->children());
							foreach($product->categories->children() as $catchild){
								$categories = $catchild['path'];
								$categories = str_replace(array('(',')'), '', $categories);
								if($catchild['path']!=""){
									if($i == $totalcat){
										if (isset($newcategory) && $newcategory == "1"){ 
											$querycat .= "('".$productID."', '".str_replace("'","''", $categories)."', '".md5($xmlfeed[$xmlfeednumber][0]."".$categories)."')";
										} else {
											$querycat .= "('".$productID."', '".str_replace("'","''", $categories)."', '".md5($categories)."')";
										}
									} else {
										if (isset($newcategory) && $newcategory == "1"){ 
											$querycat .= "('".$productID."', '".str_replace("'","''", $categories)."', '".md5($xmlfeed[$xmlfeednumber][0]."".$categories)."'),";
										} else {
											$querycat .= "('".$productID."', '".str_replace("'","''", $categories)."', '".md5($categories)."'),";
										}
									}
									//$wpdb->insert($ttstorecattable ,array('productID' => $productID,'categorie' => $categories,'categorieid' => md5($categories) ),array('%s','%s','%s'));
								}
								$i++;
							}
							$wpdb->query("INSERT INTO ".$ttstorecattable." (productID, categorie, categorieid) VALUES ".$querycat.";");
							$wpdb->flush();
						}	
						if(get_option("Tradetracker_loadextra")=="1" && $product->additional && ($product->additional != '')) {
							$queryextra = "";
							$i="1";
							$totalextra = count($product->additional->children());
							foreach($product->additional->children() as $datachild){
								if($datachild['name']!=""){
									if($i == $totalextra){
										$queryextra .= "('".$productID."', '".$datachild['name']."', '".str_replace("'","''", $datachild)."')";
									} else {
										$queryextra .= "('".$productID."', '".$datachild['name']."', '".str_replace("'","''", $datachild)."'),";
									}
									//$wpdb->insert($ttstoreextratable ,array('productID' => $productID,'extrafield' => $datachild['name'],'extravalue' => $datachild ),array('%s','%s','%s'));
								}
								$i++;
							}
							$wpdb->query("INSERT INTO ".$ttstoreextratable." (productID, extrafield, extravalue) VALUES ".$queryextra.";");
							$wpdb->flush();
						}
						if(get_option("Tradetracker_loadextra")=="1" && $product->properties && ($product->properties != '')) {
							$queryextra = "";
							$i="1";
							$totalextra = count($product->properties->children());
							foreach($product->properties->children() as $datachild){
								if($datachild['name']!=""){
									if($i == $totalextra){
										$queryextra .= "('".$productID."', '".$datachild['name']."', '".str_replace("'","''", $datachild->value)."')";
									} else {
										$queryextra .= "('".$productID."', '".$datachild['name']."', '".str_replace("'","''", $datachild->value)."'),";
									}
									//$wpdb->insert($ttstoreextratable ,array('productID' => $productID,'extrafield' => $datachild['name'],'extravalue' => $datachild->value ),array('%s','%s','%s'));
								}
								$i++;
							}
							$wpdb->query("INSERT INTO ".$ttstoreextratable." (productID, extrafield, extravalue) VALUES ".$queryextra.";");
							$wpdb->flush();
						}
					}
				} 
				$xmldatabasecount++;
				update_option("xmldatabasecount", $xmldatabasecount );
			}
		}
	}
	//$extrafieldarray = array_unique(explode(",",$extrafieldarray));
	//$remove_null_number = true;
	//$extrafieldarray = remove_array_empty_values($extrafieldarray, $remove_null_number);
	
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
	if ($xmlcronjob == "0"){
		echo "<br /><strong>Memory Usage:</strong>";
		$scale = "4";
		$percent = (100/str2bytes(ini_get('memory_limit')))*memory_get_peak_usage();
		if ($percent > 90){
			echo "<style>.amountbar { background:#CCCCCC; border:1px solid #666666; height:10px; }.amountbar div { background: #c03028; height: 10px; }</style>";	
		} elseif ($percent <= 90 && $percent > 50) {
			echo "<style>.amountbar { background:#CCCCCC; border:1px solid #666666; height:10px; }.amountbar div { background: #c07c28; height: 10px; }</style>";	
		} else {
			echo "<style>.amountbar { background:#CCCCCC; border:1px solid #666666; height:10px; }.amountbar div { background: #28B8C0; height: 10px; }</style>";	
		}
		echo "<div class=\"amountbar\" style=\"width:".round(100 * $scale)."px;\">";
		echo "<div style=\"width:".round($percent * $scale)."px;\"></div>";
		echo "</div>"; 
		echo convert(memory_get_peak_usage());
		echo "/".convert(str2bytes(ini_get('memory_limit')));
		echo "<br /><strong>Items imported:</strong><br />".$item_count;
	}
	$ttmemoryusage = get_option("Tradetracker_memoryusage");
	$ttmemoryusage .= "<br/><strong>Memory Usage before import:</strong>".convert(memory_get_peak_usage())."<p>";
	update_option( "Tradetracker_memoryusage", $ttmemoryusage );
	wp_clear_scheduled_hook('xml_updater_check');
	if (!wp_next_scheduled('xml_updater_check')) {
		wp_schedule_single_event(time()+700, 'xml_updater_check');
	}
	if ($xmldatabasecount < count($files)){
		update_option("xmldatabasecount", $xmldatabasecount );
		if($xmlcronjob=="0"){
?>
<script type="text/javascript">
window.location.href='<?php echo "admin.php?page=tt-store&database=yes&xmldatabasecount=$xmldatabasecount&xmlfeedID=$xmlfilecount"; ?>';
</script>
<?php
		} else {
			fill_database1($xmlfeedid, $xmlcronjob);
		}
	} else {
		$xmlfilecount = get_option("xmlfilecount");
		$xmlfilecount++;
		update_option("xmlfilecount", $xmlfilecount );
		update_option("xmldatabasecount", "0" );

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
		if($xmlcronjob=="0"){
?>
<script type="text/javascript">
window.location.href='<?php echo "admin.php?page=tt-store&update=yes&xmlfilecount=$xmlfilecount&xmlfeedID=$xmlfilecount"; ?>';
</script>
<?php
		} else {
			xml_updater($xmlfilecount, $xmlfilecount, "1");
		}
	}
}
?>