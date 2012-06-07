<?php
function parse_recursive(SimpleXMLElement $element, $level = 0)
{	
	GLOBAL $extrafield;
	GLOBAL $extravalue;
	GLOBAL $counterxml;
	$indent = str_repeat("\t", $level); // determine how much we'll indent
	$value = trim((string) $element); // get the value and trim any whitespace from the start and end
	$attributes = $element->attributes(); // get all attributes
	$children = $element->children(); // get all children
        if(count($children) == 0 && !empty($value)) 
	{       
		if($element->getName()=="field"){
			if($counterxml=="1"){
				$extrafield = str_replace(",", "&#44;", $attributes);
				$extravalue = str_replace(",", "&#44;", $element);	
				$counterxml++;			
			} else {
				$extrafield .= ",".str_replace(",", "&#44;", $attributes);
				$extravalue .= ",".str_replace(",", "&#44;", $element);
				$counterxml++;
			}

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


function fill_database1($xmlfeedid)
{
	global $wpdb; 
	global $errorfile;
	GLOBAL $extrafield;
	GLOBAL $extravalue;
	GLOBAL $counterxml;
	global $ttstoretable;
	global $foldersplits;
	$extrafieldarray = get_option('Tradetracker_xml_extra');
	$files = glob($foldersplits."*xml");
	if (is_array($files)) {
		foreach($files as $filename) {
			$products = simplexml_load_file($filename);
				//$string = file_get_contents($filename, FILE_TEXT);
				//$products = @simplexml_load_string($string);

				if($products === false)
				{
					$xmlfeed = get_option("Tradetracker_xmlname");	
					$keys = array_keys($xmlfeed);
					$key = $keys[$xmlfeedid];
					$xmlfeed = $xmlfeed[$key];
					$errorxml = libxml_get_last_error();
					$errorfile = get_option("Tradetracker_importerror");
					$errorfile .= "". "\n" ."Feedname: ".$xmlfeed;
					$errorfile .= "". "\n" ."Splitfile: ".$errorxml->file;
					$errorfile .= "". "\n" ."Error: ".$errorxml->message;
					libxml_clear_errors();
					update_option( "Tradetracker_importerror", $errorfile );
				}else if ($products->body->p == "The requested product feed could not be generated:"){
					$xmlfeed = get_option("Tradetracker_xmlname");	
					$keys = array_keys($xmlfeed);
					$key = $keys[$xmlfeedid];
					$xmlfeed = $xmlfeed[$key];
					$errorxml = libxml_get_last_error();
					$errorfile = get_option("Tradetracker_importerror");
					$errorfile .= "". "\n" ."Feedname: ".$xmlfeed;
					$errorfile .= "". "\n" ."Error: Tradetracker cannot create the productfeed. The feed itself is empty";
					libxml_clear_errors();
					update_option( "Tradetracker_importerror", $errorfile );

				} else {
					$xmlfeed = get_option("Tradetracker_xmlname");	
					$keys = array_keys($xmlfeed);
					$key = $keys[$xmlfeedid];
					$xmlfeed = $xmlfeed[$key];
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
						if($product->categories->category==""){
							$currentpage["categorie"]="empty category";
							$currentpage["categorieid"]=md5($xmlfeed."empty category");
						} else {
							$categories = $product->categories->category;
							$categories = str_replace(array('(',')'), '', $categories);
							$currentpage["categorie"]=$categories;
							$currentpage["categorieid"]=md5($categories);
						}				
						$currentpage["imageURL"]=$product->imageURL;
						$currentpage["productURL"]=$product->productURL;
						$currentpage["description"]=strip_tags($product->description);
						$currentpage["price"]=$product->price;
						$currentpage["currency"]=$product->price['currency'];
						//parse_recursive($product);
						if(get_option("Tradetracker_loadextra")=="1") {
							foreach($product->children() as $car => $data){
								if($data->field['name']!=""){
									if($counterxml=="1"){
										$extrafield = str_replace(",", "&#44;", $data->field['name']);
										$extravalue = str_replace(",", "&#44;", $data->field);	
										$counterxml++;			
									} else {
										$extrafield .= ",".str_replace(",", "&#44;", $data->field['name']);
										$extravalue .= ",".str_replace(",", "&#44;", $data->field);
										$counterxml++;
									}
								}
							} 
						} else {
							$extrafield = "";
							$extravalue = "";
						}
						$currentpage["extrafield"]=$extrafield;
						$currentpage["extravalue"]=$extravalue;
						$wpdb->insert( $ttstoretable, $currentpage);//insert the captured values
						$wpdb->flush();
						if(isset($extrafieldarray)){
							if(is_array($extrafieldarray)){
								if (!in_array($extrafield, $extrafieldarray)) {
    									array_push($extrafieldarray, $extrafield);
								}
							} else {
								$extrafieldarray = array($extrafield);
							}
						} else {
							$extrafieldarray = array($extrafield);
						}
					}
				} 
		}
	}
	//$extrafieldarray = array_unique(explode(",",$extrafieldarray));
	//$remove_null_number = true;
	//$extrafieldarray = remove_array_empty_values($extrafieldarray, $remove_null_number);

	$option_name = 'Tradetracker_xml_extra' ;
	$newvalue = $extrafieldarray;

	if ( get_option( $option_name ) != $newvalue ) {
		update_option( $option_name, $newvalue );
	} else {
		$deprecated = '';
		$autoload = 'no';
		add_option( $option_name, $newvalue, $deprecated, $autoload );
	}
	$item_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $ttstoretable;" ) );
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
}
?>