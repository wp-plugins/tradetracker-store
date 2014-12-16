<?php
if (get_option("TTstoreversion") == "4.5.54"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$tttable = $pro_table_prefix."store";
	$tttableextra = $pro_table_prefix."extra";
	$tttablecat = $pro_table_prefix."cat";
	$ttstoreitemtable = $pro_table_prefix."item";
	$res = $wpdb->get_results("SHOW INDEX FROM `$tttablecat` WHERE `Key_name` LIKE 'productID_%' or `Key_name` LIKE 'categorieid_%'");
	if ( $res ){
		foreach ( $res as $row ){
   			$wpdb->query("DROP INDEX `{$row->Key_name}` ON `{$row->Table}`");
		}
	}
	$res1 = $wpdb->get_results("SHOW INDEX FROM `$tttable` WHERE `Key_name` LIKE 'productID_%' or `Key_name` LIKE 'name_%'");
	if ( $res1 ){
		foreach ( $res1 as $row ){
   			$wpdb->query("DROP INDEX `{$row->Key_name}` ON `{$row->Table}`");
		}
	}
	$res2 = $wpdb->get_results("SHOW INDEX FROM `$tttableextra` WHERE `Key_name` LIKE 'productID_%'");
	if ( $res2 ){
		foreach ( $res2 as $row ){
   			$wpdb->query("DROP INDEX `{$row->Key_name}` ON `{$row->Table}`");
		}
	}
	$res3 = $wpdb->get_results("SHOW INDEX FROM `$ttstoreitemtable` WHERE `Key_name` LIKE 'productID_%'");
	if ( $res3 ){
		foreach ( $res3 as $row ){
   			$wpdb->query("DROP INDEX `{$row->Key_name}` ON `{$row->Table}`");
		}
	}
	update_option("TTstoreversion", "4.5.60" );
}


if (get_option("TTstoreversion") == "4.5.51"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$tttable = $pro_table_prefix."store";
	$wpdb->query("ALTER TABLE `".$tttable."` CHANGE `imageURL` `imageURL` VARCHAR(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL");
	update_option("TTstoreversion", "4.5.54" );
}
if (get_option("TTstoreversion") == "4.5.49"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstoremultitable = $pro_table_prefix."multi";
	$wpdb->query("ALTER TABLE `".$ttstoremultitable."` ADD `multiminprice` INT(6) NOT NULL DEFAULT '0'");
	update_option("TTstoreversion", "4.5.51" );
}
if (get_option("TTstoreversion") == "4.5.48"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstoremultitable = $pro_table_prefix."multi";
	$wpdb->query("ALTER TABLE `".$ttstoremultitable."` ADD `multicurrency` TEXT(8)");
	update_option("TTstoreversion", "4.5.49" );
}

if (get_option("TTstoreversion") == "4.5.29"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$tttable = $pro_table_prefix."store";
	$tttableextra = $pro_table_prefix."extra";
	$tttablecat = $pro_table_prefix."cat";
	delete_option("Tradetracker_importerror");
	delete_option("Tradetracker_memoryusage");	
	delete_option("Tradetracker_xml_extra");
	$wpdb->query("TRUNCATE TABLE `$tttable`");
	$wpdb->query("TRUNCATE TABLE `$tttableextra`");
	$wpdb->query("TRUNCATE TABLE `$tttablecat`");
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
	$wpdb->query("ALTER TABLE `".$tttablecat."` ADD INDEX(`productID`)");
	$wpdb->query("ALTER TABLE `".$tttablecat."` ADD INDEX(`categorieid`)");
	$wpdb->query("ALTER TABLE `".$tttable."` ADD INDEX(`productID`)");
	$wpdb->query("ALTER TABLE `".$tttableextra."` ADD INDEX(`productID`)");
	wp_clear_scheduled_hook('xmlscheduler');
	update_option("TTstoreversion", "4.5.48" );
}

if (get_option("TTstoreversion") == "4.5.27"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$tttable = $pro_table_prefix."store";
	$wpdb->query("ALTER TABLE `".$tttable."` ENGINE = MYISAM");
	$wpdb->query("ALTER TABLE `".$tttable."` ADD FULLTEXT (`name` ,`description`)");
	update_option("TTstoreversion", "4.5.29" );
}
if (get_option("TTstoreversion") == "4.5.26"){
	if ( get_option("Tradetracker_usecss") != "1" ){
		update_option("Tradetracker_showurl", "1" );
	}
	update_option("Tradetracker_slidertheme", "base");
	update_option("TTstoreversion", "4.5.27" );
}
if (get_option("TTstoreversion") == "4.5.25"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstoremultitable = $pro_table_prefix."multi";
	$wpdb->query("ALTER TABLE `".$ttstoremultitable."` ADD `multimaxprice` INT(6) NOT NULL DEFAULT '0'");
	update_option("TTstoreversion", "4.5.26" );
}

if (get_option("TTstoreversion") == "4.5.23"){
	$TTnewcategory = get_option( 'TTnewcategory', '1' );
	update_option("TTnewcategory", $TTnewcategory );
	update_option("TTstoreversion", "4.5.25" );
}

if (get_option("TTstoreversion") == "4.5.21"){
	update_option("TTnewcategory", "0" );
	update_option("TTstoreversion", "4.5.23" );
}


if (get_option("TTstoreversion") == "4.5.15"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$tttable = $pro_table_prefix."store";
	$wpdb->query("ALTER TABLE `".$tttable."` ADD FULLTEXT (`name` ,`description`)");
	update_option("TTstoreversion", "4.5.21" );
}

if (get_option("TTstoreversion") == "4.5.7"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttxmltable = $pro_table_prefix."xml";
	$wpdb->query("ALTER TABLE `".$ttxmltable."` CHANGE `xmlfeed` `xmlfeed` VARCHAR( 10000 ) NOT NULL");
	update_option("TTstoreversion", "4.5.15" );
}

if (get_option("TTstoreversion") == "4.5.6"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstorecattable = $pro_table_prefix."cat";
	$ttstoretable = $pro_table_prefix."store";
	$ttstoreitemtable = $pro_table_prefix."item";
	$ttstoreextratable = $pro_table_prefix."extra";
	$wpdb->query("ALTER TABLE `".$ttstorecattable."` ADD INDEX ( `productID` ) ");
	$wpdb->query("ALTER TABLE `".$ttstoretable."` ADD INDEX ( `productID` ) ");
	$wpdb->query("ALTER TABLE `".$ttstoreitemtable."` ADD INDEX ( `productID` ) ");
	$wpdb->query("ALTER TABLE `".$ttstoreextratable."` ADD INDEX ( `productID` ) ");
	update_option("TTstoreversion", "4.5.7" );
}
if (get_option("TTstoreversion") == "4.5.4"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstorecattable = $pro_table_prefix."cat";
	$ttstoretable = $pro_table_prefix."store";
	$structurecat = "CREATE TABLE IF NOT EXISTS ".$ttstorecattable." (
		catid INT(9) NOT NULL AUTO_INCREMENT,
		productID VARCHAR(100) NOT NULL,
		categorieid VARCHAR(100) NOT NULL,
		categorie VARCHAR(100) NOT NULL,
		UNIQUE KEY catid (catid)
	);";
	$wpdb->query($structurecat);
	wp_clear_scheduled_hook('xmlscheduler');
	update_option("TTstoreversion", "4.5.6" );
}


if (get_option("TTstoreversion") == "4.5.1"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstorecattable = $pro_table_prefix."cat";
	$ttstoretable = $pro_table_prefix."store";
	$wpdb->query("ALTER TABLE `".$ttstoretable."` DROP `categorieid`");
	$wpdb->query("ALTER TABLE `".$ttstoretable."` DROP `categorie`");
	wp_clear_scheduled_hook('xmlscheduler');
	update_option("TTstoreversion", "4.5.4" );
}
if (get_option("TTstoreversion") <= "4.5.0" && get_option("TTstoreversion") >= "4.1.11"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstoreitemtable = $pro_table_prefix."item";
	$ttstorexmltable = $pro_table_prefix."xml";
	$ttstoremultitable = $pro_table_prefix."multi";
	$structureitem = "CREATE TABLE IF NOT EXISTS ".$ttstoreitemtable." (
		id INT(9) NOT NULL AUTO_INCREMENT,
		storeID INT(100),
		productID VARCHAR(36) NOT NULL,
		UNIQUE KEY id (id)
	);";
	$wpdb->query($structureitem);
	$itemlist = $wpdb->get_results('SELECT id, multiitems FROM `'.$ttstoremultitable.'` where multiitems <> ""');
	foreach ($itemlist as $items){
		$item = explode(',',$items->multiitems);		
		foreach ($item as $itemoverview){
			$wpdb->insert( 
				$ttstoreitemtable, 
				array( 
					storeID => $items->id, 
					productID => $itemoverview 
				), 
				array( 
					'%d', 
					'%s' 
				) 
			);
		}
	}
	$structurexml = "CREATE TABLE IF NOT EXISTS ".$ttstorexmltable." (
		id INT(9) NOT NULL AUTO_INCREMENT,
		xmlfeed VARCHAR(10000) NOT NULL,
		xmlname VARCHAR(100) NOT NULL,
		xmlprovider VARCHAR(100) NOT NULL,
		xmltitle VARCHAR(100) NOT NULL,
		xmlimage VARCHAR(100) NOT NULL,
		xmldescription VARCHAR(100) NOT NULL,
		xmlprice VARCHAR(100) NOT NULL,
		UNIQUE KEY id (id)
	);";
	$wpdb->query($structurexml);
	//variables for this function
	$Tradetracker_xml_name = 'Tradetracker_xml';
	$Tradetracker_xmlname_name = 'Tradetracker_xmlname';

	//filling variables from database
	$Tradetracker_xml_val = get_option( $Tradetracker_xml_name );
	$Tradetracker_xmlname_val = get_option( $Tradetracker_xmlname_name );
	$i="0";
	if($Tradetracker_xml_val != ""){
		$file = $Tradetracker_xml_val;
		foreach($file as $key => $value) {
			echo "<tr><td>";
			if($key !=""){
				$wpdb->insert( 
					$ttstorexmltable, 
					array( 
						id => $i,
						xmlfeed => $key, 
						xmlname => $Tradetracker_xmlname_val[$i],
						xmlprovider => $value
					), 
					array( 
						'%s',
						'%s',
						'%s',
						'%s' 
					) 
				);
				$i++;
			}
		}
	}

	update_option("TTstoreversion", "4.5.1" );
}

if (get_option("TTstoreversion") == "4.1.9"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstoremultitable = $pro_table_prefix."multi";
	$wpdb->query("ALTER TABLE `".$ttstoremultitable."` ADD `multipageamount` INT(3) NOT NULL DEFAULT '0'");
	update_option("TTstoreversion", "4.1.11" );
}

if (get_option("TTstoreversion") == "4.1.2"){
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$ttstoreextratable = $pro_table_prefix."extra";
	$structureextra = "CREATE TABLE IF NOT EXISTS ".$ttstoreextratable." (
	id INT(9) NOT NULL AUTO_INCREMENT,
	productID VARCHAR(36) NOT NULL,
	extrafield TEXT NOT NULL,
	extravalue TEXT NOT NULL,
	UNIQUE KEY id (id));";
	$wpdb->query($structureextra);
	update_option("TTstoreversion", "4.1.9" );
}
if (get_option("TTstoreversion") == "4.0.22"){
	update_option("Tradetracker_adminwidth", "1000" );
	update_option("TTstoreversion", "4.1.2" );
}
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
if (get_option("TTstoreversion") < "4.0"){
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
	multisorting VARCHAR(100) NOT NULL DEFAULT 'rand()',
	multiorder VARCHAR(4) NOT NULL DEFAULT 'asc',
	multilayout INT(10) NOT NULL,
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