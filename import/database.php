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
function convert($size)
 {
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
 }

function fill_database1($xmlfeedid, $xmlcronjob)
{
	global $wpdb; 
	global $errorfile;
	GLOBAL $extrafield;
	GLOBAL $extravalue;
	GLOBAL $counterxml;
	global $ttstoretable;
	global $ttstoreextratable;
	global $ttstorexmltable;
	global $ttstorecattable;
	global $foldersplits;
	$xmlfilecount = get_option("xmlfilecount");
	$xmlfeedid = get_option("xmlfilecount");
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
	$xmlfeed = $wpdb->get_results("select xmlname FROM ".$ttstorexmltable."", ARRAY_N);
	//$xmlfeed = get_option("Tradetracker_xmlname");	
	//$keys = array_keys($xmlfeed);
	//$key = $keys[$xmlfeedid];
	//$xmlfeed = $xmlfeed[$key];
	echo "<br /><strong>Importing:</strong>".$xmlfeed[$xmlfeednumber][0];
		$ttmemoryusage = get_option("Tradetracker_memoryusage");
		$ttmemoryusage .= "<br/><strong>Importing:</strong>".$xmlfeed[$xmlfeednumber][0];
		update_option( "Tradetracker_memoryusage", $ttmemoryusage );
	if (is_array($files)) {
		for ( $i="0"; ($i <= 10 && $i <count($files)); $i++) {
			$filename = $files[$xmldatabasecount];
			echo ".";
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
				}else if ($products->body->p == "The requested product feed could not be generated:"){
					$errorxml = libxml_get_last_error();
					$errorfile = get_option("Tradetracker_importerror");
					$errorfile .= "". "\n" ."Feedname: ".$xmlfeed[$xmlfeednumber][0];
					$errorfile .= "". "\n" ."Error: Tradetracker cannot create the productfeed. The feed itself is empty";
					libxml_clear_errors();
					update_option( "Tradetracker_importerror", $errorfile );
				} else {
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
						$currentpage["description"]=strip_tags($product->description);
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
										$querycat .= "('".$productID."', '".str_replace("'","''", $categories)."', '".md5($xmlfeed[$xmlfeednumber][0]."".$categories)."')";
									} else {
										$querycat .= "('".$productID."', '".str_replace("'","''", $categories)."', '".md5($xmlfeed[$xmlfeednumber][0]."".$categories)."'),";
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
	echo "<br /><strong>Memory Usage after database import:</strong>".convert(memory_get_peak_usage());
	$ttmemoryusage = get_option("Tradetracker_memoryusage");
	$ttmemoryusage .= "<br/><strong>Memory Usage before import:</strong>".convert(memory_get_peak_usage())."<p>";
	update_option( "Tradetracker_memoryusage", $ttmemoryusage );
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