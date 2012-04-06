<?php
if (get_option("TTstoreversion") == "4.0.17"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstoremultitable = $pro_table_prefix."multi";
	$ttstoretable = $pro_table_prefix."store";
	$itemedit=$wpdb->get_results("SELECT id, multiitems FROM ".$ttstoremultitable."");
	foreach ($itemedit as $layout_val){
		if($layout_val->multiitems != "" ) {
			$productID = $layout_val->multiitems;
			$productID = explode(",",$productID);
			$amountitems = count($productID);
			$i="0";
			foreach ($productID as $productID_val){
				$itemproducturl=$wpdb->get_row("SELECT productURL FROM ".$ttstoretable." where productID ='".$productID_val."'");
				if($i=="0"){
					$item = md5($itemproducturl->productURL);
				} else {
					$item .= ",".md5($itemproducturl->productURL);
				}
				$i++;
			}
			$query = $wpdb->update( $ttstoremultitable, array( 'multiitems' => $item), array( 'id' => $layout_val->id), array( '%s'), array( '%s'), array( '%d' ) );
		}
	}
	wp_clear_scheduled_hook('xmlscheduler');
	update_option("TTstoreversion", "4.0.22" );
}

if (get_option("TTstoreversion") == "4.0.11"){
	update_option("Tradetracker_loadextra", "1");
	update_option("TTstoreversion", "4.0.17" );
}
if (get_option("TTstoreversion") == "4.0.10"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstoremultitable = $pro_table_prefix."multi";
	$multi_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ".$ttstoremultitable." where id = '1';" ) );
	if($multi_count=="0"){
		$currentpagemulti["id"]="1";
		$currentpagemulti["multiname"]="basic";
		$currentpagemulti["multisorting"]="rand()";
		$currentpagemulti["multiorder"]="asc";
		$currentpagemulti["multilayout"]="1";
		$currentpagemulti["multiitems"]="";
		$currentpagemulti["multiamount"]="18";
		$currentpagemulti["multilightbox"]="0";
		$currentpagemulti["multixmlfeed"]="*";
		$currentpagemulti["multiproductpage"]="";
		$currentpagemulti["categories"]="";
		$currentpagemulti["buynow"]="Buy Now";
		$wpdb->insert( $ttstoremultitable, $currentpagemulti);
	} else {
		$sql = "UPDATE `".$ttstoremultitable."` SET `multiname` = 'basic', `multisorting` = 'rand()', `multiorder` = 'asc', `multilayout` = '1', `multiitems` = '', `multiamount` = '10', `multilightbox` = '0', `multixmlfeed` = '*', `categories` = '', `buynow` = 'Buy Now'  WHERE `id` = '1'";
		$wpdb->query($sql);
	}
	$result=$wpdb->query("ALTER TABLE `".$ttstoremultitable."` ADD `multiproductpage` VARCHAR(1) NOT NULL");
	update_option("TTstoreversion", "4.0.11" );
}

if (get_option("TTstoreversion") == "4.0.8"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstorelayouttable = $pro_table_prefix."layout";
	$ttstoremultitable = $pro_table_prefix."multi";
	$layout_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $ttstorelayouttable;" ) );
	if($layout_count=="0"){
		$currentpagelayout["laywidth"]="250";
		$currentpagelayout["layname"]="basic";
		$currentpagelayout["layfont"]="verdana";
		$currentpagelayout["laycolortitle"]="#ececed";
		$currentpagelayout["laycolorfooter"]="#ececed";
		$currentpagelayout["laycolorimagebg"]="#FFFFFF";
		$currentpagelayout["laycolorfont"]="#000000";
		$currentpagelayout["laycolorborder"]="#65B9C1";
		$currentpagelayout["laycolorbutton"]="#65B9C1";
		$currentpagelayout["laycolorbuttonfont"]="#000000";
		$currentpagelayout["layfontsize"]="10";
		$wpdb->insert( $ttstorelayouttable, $currentpagelayout);
	} else {
		$query = $wpdb->update( $ttstorelayouttable, array( 'laywidth' => "250", 'layname' => "basic", 'layfont' => "verdana", 'laycolortitle' => "#ececed", 'laycolorfooter' => "#ececed",'laycolorimagebg' => "#FFFFFF",'laycolorfont' => "#000000", 'laycolorborder' => "#65B9C1", 'laycolorbutton' => "#65B9C1", 'laycolorbuttonfont' => "#000000", 'layfontsize' => "10"), array( 'id' => "1"), array( '%d', '%s','%s','%s','%s','%s','%s','%s','%s','%s','%d' ), array( '%d' ) );
	}
	$multi_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ".$ttstoremultitable." where id = '1';" ) );
	if($multi_count=="0"){
		$currentpagemulti["id"]="1";
		$currentpagemulti["multiname"]="basic";
		$currentpagemulti["multisorting"]="rand()";
		$currentpagemulti["multiorder"]="asc";
		$currentpagemulti["multilayout"]="1";
		$currentpagemulti["multiitems"]="";
		$currentpagemulti["multiamount"]="18";
		$currentpagemulti["multilightbox"]="0";
		$currentpagemulti["multixmlfeed"]="*";
		$currentpagemulti["multiproductpage"]="";
		$currentpagemulti["categories"]="";
		$currentpagemulti["buynow"]="Buy Now";
		$wpdb->insert( $ttstoremultitable, $currentpagemulti);
	} else {
		$sql = "UPDATE `".$ttstoremultitable."` SET `multiname` = 'basic', `multisorting` = 'rand()', `multiorder` = 'asc', `multilayout` = '1', `multiitems` = '', `multiamount` = '10', `multilightbox` = '0', `multixmlfeed` = '*', `categories` = '', `buynow` = 'Buy Now'  WHERE `id` = '1'";
		$wpdb->query($sql);
	}
	update_option("TTstoreversion", "4.0.10" );
}

if (get_option("TTstoreversion") == "4.0.4"){
	update_option("Tradetracker_showurl", "0" );
	update_option("TTstoreversion", "4.0.8" );
}
if (get_option("TTstoreversion") == "4.0"){
	update_option("Tradetracker_adminheight", "460" );
	update_option("TTstoreversion", "4.0.4" );
}
if (get_option("TTstoreversion") < "4.0" || get_option("TTstoreversion") == ""){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstorelayouttable = $pro_table_prefix."layout";
	$ttstoremultitable = $pro_table_prefix."multi";
	$structurelay = "CREATE TABLE IF NOT EXISTS ".$ttstorelayouttable." (
	id INT(9) NOT NULL AUTO_INCREMENT,
	layname VARCHAR(100) NOT NULL,
	laywidth INT(10) NOT NULL,
	laycolortitle VARCHAR(7) NOT NULL,
	laycolorfooter VARCHAR(7) NOT NULL,
	laycolorimagebg VARCHAR(7) NOT NULL,
	laycolorfont VARCHAR(7) NOT NULL,
	laycolorborder VARCHAR(7) NOT NULL,
	laycolorbutton VARCHAR(7) NOT NULL,
	laycolorbuttonfont VARCHAR(50) NOT NULL,
	layfont VARCHAR(50) NOT NULL,
	layfontsize INT(3) NOT NULL,
	UNIQUE KEY id (id));";
	$wpdb->query($structurelay);
	$result=$wpdb->query("ALTER TABLE `".$ttstorelayouttable."` ADD `layfontsize` INT(3) NOT NULL");
	$layout_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $ttstorelayouttable;" ) );
	if($layout_count=="0"){
		$currentpagelayout["laywidth"]="250";
		$currentpagelayout["layname"]="basic";
		$currentpagelayout["layfont"]="verdana";
		$currentpagelayout["laycolortitle"]="#ececed";
		$currentpagelayout["laycolorfooter"]="#ececed";
		$currentpagelayout["laycolorimagebg"]="#FFFFFF";
		$currentpagelayout["laycolorfont"]="#000000";
		$currentpagelayout["laycolorborder"]="#65B9C1";
		$currentpagelayout["laycolorbutton"]="#65B9C1";
		$currentpagelayout["laycolorbuttonfont"]="#000000";
		$currentpagelayout["layfontsize"]="10";
		$wpdb->insert( $ttstorelayouttable, $currentpagelayout);
	} else {
		$query = $wpdb->update( $ttstorelayouttable, array( 'laywidth' => "250", 'layname' => "basic", 'layfont' => "verdana", 'laycolortitle' => "#ececed", 'laycolorfooter' => "#ececed",'laycolorimagebg' => "#FFFFFF",'laycolorfont' => "#000000", 'laycolorborder' => "#65B9C1", 'laycolorbutton' => "#65B9C1", 'laycolorbuttonfont' => "#000000", 'layfontsize' => "10"), array( 'id' => "1"), array( '%d', '%s','%s','%s','%s','%s','%s','%s','%s','%s','%d' ), array( '%d' ) );
	}
	$structuremulti = "CREATE TABLE IF NOT EXISTS $ttstoremultitable (
        id INT(9) NOT NULL AUTO_INCREMENT,
	multiname VARCHAR(100) NOT NULL,
	multisorting VARCHAR(100) NOT NULL,
	multiorder VARCHAR(4) NOT NULL DEFAULT 'rand()',
	multilayout INT(10) NOT NULL DEFAULT 'asc',
        multiitems VARCHAR(10000) NOT NULL,
        multiamount int(3) NOT NULL,
	multilightbox VARCHAR(1) NOT NULL,
	multixmlfeed VARCHAR(10) NOT NULL,
	multiproductpage VARCHAR(1) NOT NULL,
	categories longtext NOT NULL,
	buynow TEXT NOT NULL,
	UNIQUE KEY id (id));";
	$wpdb->query($structuremulti);
	$result=$wpdb->query("ALTER TABLE `".$ttstoremultitable."` ADD `multisorting` VARCHAR(100) NOT NULL DEFAULT 'rand()'");
	$result=$wpdb->query("ALTER TABLE `".$ttstoremultitable."` ADD `multiorder` VARCHAR(4) NOT NULL DEFAULT 'asc'");
	$multi_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ".$ttstoremultitable." where id = '1';" ) );
	if($multi_count=="0"){
		$currentpagemulti["id"]="1";
		$currentpagemulti["multiname"]="basic";
		$currentpagemulti["multisorting"]="rand()";
		$currentpagemulti["multiorder"]="asc";
		$currentpagemulti["multilayout"]="1";
		$currentpagemulti["multiitems"]="";
		$currentpagemulti["multiamount"]="18";
		$currentpagemulti["multilightbox"]="0";
		$currentpagemulti["multixmlfeed"]="*";
		$currentpagemulti["multiproductpage"]="";
		$currentpagemulti["categories"]="";
		$currentpagemulti["buynow"]="Buy Now";
		$wpdb->insert( $ttstoremultitable, $currentpagemulti);
	} else {
		$sql = "UPDATE `".$ttstoremultitable."` SET `multiname` = 'basic', `multisorting` = 'rand()', `multiorder` = 'asc', `multilayout` = '1', `multiitems` = '', `multiamount` = '10', `multilightbox` = '0', `multixmlfeed` = '*', `categories` = '', `buynow` = 'Buy Now'  WHERE `id` = '1'";
		$wpdb->query($sql);
	}

	$debugemail = get_option("Tradetracker_debugemail");
	if(!isset($debugemail) || $debugemail == ""){
		update_option("Tradetracker_debugemail", "1" );
	}

	$xmlupdate = get_option("Tradetracker_xmlupdate");
	if(!isset($xmlupdate) || $xmlupdate == ""){
		update_option("Tradetracker_xmlupdate", "00:00:01");
	}

	$debugemail = get_option("Tradetracker_debugemail");
	if(!isset($debugemail) || $debugemail == ""){
		update_option("Tradetracker_debugemail", "1");
	}

	$currency = get_option("Tradetracker_currency");
	if(!isset($currency) || $currency == ""){
		update_option("Tradetracker_currency", "0");
	}

	$currencyloc = get_option("Tradetracker_currencyloc");
	if(!isset($currencyloc) || $currencyloc == ""){
		update_option("Tradetracker_currencyloc", "0");
	}

	$removelayout = get_option("Tradetracker_removelayout");
	if(!isset($removelayout) || $removelayout == ""){
		update_option("Tradetracker_removelayout", "1");
	}

	$removestores = get_option("Tradetracker_removestores");
	if(!isset($removestores) || $removestores == ""){
		update_option("Tradetracker_removestores", "1");
	}

	$removeproducts = get_option("Tradetracker_removeproducts");
	if(!isset($removeproducts) || $removeproducts == ""){
		update_option("Tradetracker_removeproducts", "1");
	}

	$removexml = get_option("Tradetracker_removexml");
	if(!isset($removexml) || $removexml == ""){
		update_option("Tradetracker_removexml", "1");
	}

	$removeother = get_option("Tradetracker_removeother");
	if(!isset($removeother) || $removeother == ""){
		update_option("Tradetracker_removeother", "1");
	}

	$importtool = get_option("Tradetracker_importtool");
	if(!isset($importtool) || $importtool==""){
		if (ini_get('allow_url_fopen') == true) {
			update_option("Tradetracker_importtool", "1");
		} else {
			update_option("Tradetracker_importtool", "3");
		}
	}
	update_option("TTstoreversion", "4.0" );
	news_updater();
}

?>