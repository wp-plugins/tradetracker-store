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


function fill_database1()
{
	global $wpdb; 
	global $itemsadded;
	GLOBAL $extrafield;
	GLOBAL $extravalue;
	GLOBAL $counterxml;
	$table = PRO_TABLE_PREFIX."store";
	$folder =  WP_PLUGIN_DIR . "/tradetracker-store/splits";
	// echo "<p>Deleted ".$wpdb->rows_affected." items in database";
	foreach(glob($folder."/*xml") as $filename) {
		$string = file_get_contents($filename, FILE_TEXT);
		$products = simplexml_load_string($string);
			foreach($products as $product) // loop through our items
			{
				$counterxml = "1";
				$extrafield = "";
				$extravalue = "";
				$productID = (string)$product->productID;
				if(!is_numeric($productID)){
					$productID = md5($productID);
				}
				$currentpage["productID"]=$product->xmlfile."-".$productID;
				$currentpage["xmlfeed"]=$product->xmlfile;		
				$currentpage["name"]=$product->name;
				$currentpage["imageURL"]=$product->imageURL;
				if($product->categories->category==""){
					$currentpage["categorie"]="empty category";
					$currentpage["categorieid"]=md5("empty category");
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
				parse_recursive($product);
				$currentpage["extrafield"]=$extrafield;
				$currentpage["extravalue"]=$extravalue;
				$wpdb->insert( $table, $currentpage);//insert the captured values
				$extrafieldarray .= ",".$extrafield;
				$itemsadded++;
			}
	}
	$extrafieldarray = array_unique(explode(",",$extrafieldarray));
	$remove_null_number = true;
	$extrafieldarray = remove_array_empty_values($extrafieldarray, $remove_null_number);

	$option_name = 'Tradetracker_xml_extra' ;
	$newvalue = $extrafieldarray;

	if ( get_option( $option_name ) != $newvalue ) {
		update_option( $option_name, $newvalue );
	} else {
		$deprecated = ' ';
		$autoload = 'no';
		add_option( $option_name, $newvalue, $deprecated, $autoload );
	}
	$currentupdate = date('Y-m-d H:i:s');
	// echo "<br>Database filled with ".$itemsadded." new items on ".$currentupdate;
	$option_name = 'Tradetracker_xml_update' ;
	$newvalue = "Database filled with ".$itemsadded." new items on ".$currentupdate;

	if ( get_option( $option_name ) != $newvalue ) {
		update_option( $option_name, $newvalue );
	} else {
		$deprecated = ' ';
		$autoload = 'no';
		add_option( $option_name, $newvalue, $deprecated, $autoload );
	}
}


// $xml = new SimpleXMLElement('cache.xml', null, true);
// parse_recursive($xml);
?>