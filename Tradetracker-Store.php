<?php
/*
Plugin Name: Tradetracker-Store
Plugin URI: http://wpaffiliatefeed.com
Version: 4.5.33
Description: A Plugin that will add a TradeTracker affiliate feed to your site with several options to choose from.
Author: Robert Braam
Author URI: http://wpaffiliatefeed.com
*/
//all variables that will always stay the same
global $wpdb;
$pro_table_prefix=$wpdb->prefix.'tradetracker_';
define('PRO_TABLE_PREFIX', $pro_table_prefix);
$ttstoresubmit = 'mt_submit_hidden';
$ttstorehidden = "<input type=\"hidden\" name=\"".$ttstoresubmit."\" value=\"Y\">";
$ttstoretable = PRO_TABLE_PREFIX."store";
$ttstorelayouttable = PRO_TABLE_PREFIX."layout";
$ttstoremultitable = PRO_TABLE_PREFIX."multi";
$ttstoreitemtable = PRO_TABLE_PREFIX."item";
$ttstoreextratable = $pro_table_prefix."extra";
$ttstorexmltable = $pro_table_prefix."xml";
$ttstorecattable = $pro_table_prefix."cat";
$foldersplits = plugin_dir_path( __FILE__ )."splits/";
$foldercache = plugin_dir_path( __FILE__ )."cache/";
$folderhome = plugin_dir_path( __FILE__ );
load_plugin_textdomain( 'ttstore', false, dirname( plugin_basename( __FILE__ ) ) . '/translation/' );

//register activiation and deactivation
register_activation_hook(__FILE__,'tradetracker_store_install');
register_deactivation_hook(__FILE__ ,'tradetracker_store_uninstall');


if (file_exists(plugin_dir_path( __FILE__ )."functions.php")) {
include(plugin_dir_path( __FILE__ )."functions.php");
$manualpremium = get_option("Tradetracker_manualpremium");
if(empty($manualpremium) || $manualpremium != "1"){
	loadpremium();
}
}
include('front.php');
include('menu/xmlfeed.php');
include('menu/news.php');
include('menu/releaselog.php');
include('menu/xmloption.php');
include('menu/layout.php');
include('menu/store.php');
include('menu/menu.php');
include('menu/pluginsettings.php');
include('menu/premium.php');
include('menu/faq.php');
include('menu/search.php');
include('menu/itemselect.php');
include('widget/widget.php');
include('tinymce/tinymce.php');
include('tinymce/tinyTT.php');
$ttversion = get_option('TTstoreversion');
if (isset($ttversion)&&!empty($ttversion)){
	include('upgrading.php');
}
require('import/xml.php');
require('import/xmlsplit.php');
require('import/database.php');
include('debug.php');




if(isset($_GET['TTtinymce'])){
	TTtinymce();
	exit;
}
//create cronjon
if (!wp_next_scheduled('xmlscheduler')) {
	$tijdschedule = get_option('Tradetracker_xmlupdate');
	if(isTime($tijdschedule)) {
		wp_schedule_event( strtotime(date('Y-m-d '.$tijdschedule.'', strtotime("now"))), 'daily', 'xmlscheduler' );
	} else {
		wp_schedule_event( strtotime(date('Y-m-d 00:00:01', strtotime("now"))), 'daily', 'xmlscheduler' );
	}
}
add_action( 'xmlscheduler', 'runxmlupdater' ); 


function runxmlupdatercheck() {
	global $ttstorexmltable;
	global $wpdb;
	$Tradetracker_xml = $wpdb->get_results("select id, xmlfeed, xmlprovider from ".$ttstorexmltable."", ARRAY_A);
	$xmlfilecount = get_option("xmlfilecount");
	if ($xmlfilecount !="0" && $xmlfilecount < count($Tradetracker_xml)-1){
		wp_schedule_single_event(time()+700, 'xml_updater_check');
		xml_updater("0","0","1");
	} else {
		update_option("xmlfilecount", "0");
	}
}
add_action('xml_updater_check','runxmlupdatercheck');


function runxmlupdater() {
	update_option("xmlfilecount", "0");
	wp_schedule_single_event(time()+700, 'xml_updater_check');
	news_updater();
	xml_updater("0","0","1");
}

//installation and creation of database
function tradetracker_store_install()
{
global $wpdb;
update_option("TTstoreversion", "4.5.27" );
$ttstoretable = PRO_TABLE_PREFIX."store";
$ttstorelayouttable = PRO_TABLE_PREFIX."layout";
$ttstoremultitable = PRO_TABLE_PREFIX."multi";
$ttstoreitemtable = PRO_TABLE_PREFIX."item";
$ttstorexmltable = PRO_TABLE_PREFIX."xml";
$ttstorecattable = PRO_TABLE_PREFIX."cat";
$ttstoreextratable = PRO_TABLE_PREFIX."extra";
if($wpdb->get_var("SHOW TABLES LIKE '$ttstoretable'") != $ttstoretable) {
    $structure = "CREATE TABLE IF NOT EXISTS ".$ttstoretable." (
	id INT(9) NOT NULL AUTO_INCREMENT,
	productID VARCHAR(36) NOT NULL,
	name VARCHAR(80) NOT NULL,
	imageURL VARCHAR(200) NOT NULL,
	productURL VARCHAR(1000) NOT NULL,
	price DECIMAL(10,2) NOT NULL,
	currency VARCHAR(10) NOT NULL,
	xmlfeed VARCHAR(10) NOT NULL,
	description text,
	extrafield text,
	extravalue text,
	UNIQUE KEY id (id),
	INDEX (productID)
    );";
	$wpdb->query("ALTER TABLE `".$ttstoretable."` ENGINE = MYISAM");
	$wpdb->query("ALTER TABLE `".$ttstoretable."` ADD FULLTEXT (`name` ,`description`)");
	
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
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structurelay);
	$currentpagelayout["laywidth"]="250";
	$currentpagelayout["layname"]="basic";
	$currentpagelayout["layfont"]="verdana";
	$currentpagelayout["layfontsize"]="10";
	$currentpagelayout["laycolortitle"]="#ececed";
	$currentpagelayout["laycolorfooter"]="#ececed";
	$currentpagelayout["laycolorimagebg"]="#FFFFFF";
	$currentpagelayout["laycolorfont"]="#000000";
	$currentpagelayout["laycolorborder"]="#65B9C1";
	$currentpagelayout["laycolorbutton"]="#65B9C1";
	$currentpagelayout["laycolorbuttonfont"]="#000000";
	$wpdb->insert( $ttstorelayouttable, $currentpagelayout);

    $structuremulti = "CREATE TABLE IF NOT EXISTS $ttstoremultitable (
	id INT(9) NOT NULL AUTO_INCREMENT,
	multiname VARCHAR(100) NOT NULL,
	multisorting VARCHAR(100) NOT NULL DEFAULT 'rand()',
	multiorder VARCHAR(4) NOT NULL DEFAULT 'asc',
	multilayout INT(10) NOT NULL,
	multiitems VARCHAR(10000) NOT NULL,
	multipageamount int(3) NOT NULL DEFAULT '0',
	multiamount int(3) NOT NULL,
	multilightbox VARCHAR(1) NOT NULL,
	multixmlfeed VARCHAR(10) NOT NULL,
	multiproductpage VARCHAR(1) NOT NULL,
	multimaxprice int(6) NOT NULL DEFAULT '0',
	categories longtext NOT NULL,
	buynow TEXT NOT NULL,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structuremulti);
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

	$wpdb->query($structure);
	update_option("TTnewcategory", "1" );
	update_option("Tradetracker_debugemail", "1" );
	update_option("Tradetracker_xmlupdate", "00:00:01");
	update_option("Tradetracker_currency", "0");
	update_option("Tradetracker_currencyloc", "0");
	update_option("Tradetracker_removelayout", "1");
	update_option("Tradetracker_removestores", "1");
	update_option("Tradetracker_removeproducts", "1");
	update_option("Tradetracker_removexml", "1");
	update_option("Tradetracker_removeother", "1");
	update_option("Tradetracker_adminheight", "460" );
	update_option("Tradetracker_adminwidth", "1000" );
	update_option("Tradetracker_showurl", "1" );
	update_option("Tradetracker_loadextra", "1");
	update_option("Tradetracker_slidertheme", "base");
	if (ini_get('allow_url_fopen') == true) {
		update_option("Tradetracker_importtool", "1");
	} else {
		update_option("Tradetracker_importtool", "3");
	}
	  // Populate table
	}
	$structureitem = "CREATE TABLE IF NOT EXISTS ".$ttstoreitemtable." (
		id INT(9) NOT NULL AUTO_INCREMENT,
		storeID INT(100),
		productID VARCHAR(36) NOT NULL,
		UNIQUE KEY id (id)
	);";
	$wpdb->query($structureitem);

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

	$structurecat = "CREATE TABLE IF NOT EXISTS ".$ttstorecattable." (
		catid INT(9) NOT NULL AUTO_INCREMENT,
		productID VARCHAR(100) NOT NULL,
		categorieid VARCHAR(100) NOT NULL,
		categorie VARCHAR(100) NOT NULL,
		UNIQUE KEY catid (catid),
		INDEX (productID)
	);";
	$wpdb->query($structurecat);

	$structureextra = "CREATE TABLE IF NOT EXISTS ".$ttstoreextratable." (
	id INT(9) NOT NULL AUTO_INCREMENT,
	productID VARCHAR(36) NOT NULL,
	extrafield TEXT NOT NULL,
	extravalue TEXT NOT NULL,
	UNIQUE KEY id (id), 
	INDEX (productID));";
	$wpdb->query($structureextra);
}

//delete databases when uninstalling
function tradetracker_store_uninstall()
{
	global $wpdb;
	global $ttstoretable;
	global $ttstorelayouttable;
	global $ttstoremultitable;
	global $ttstoreitemtable;
	global $ttstorexmltable;
	global $ttstorecattable;
	global $ttstoreextratable;
	wp_clear_scheduled_hook('xml_update');
	if(get_option("Tradetracker_removeproducts")=="1"){
		$structure = "drop table if exists $ttstoretable";
		$wpdb->query($structure);
	}
	if(get_option("Tradetracker_removelayout")=="1"){
		$structure2 = "drop table if exists $ttstorelayouttable";
		$wpdb->query($structure2); 
	}
	if(get_option("Tradetracker_removestores")=="1"){
		$structure3 = "drop table if exists $ttstoremultitable";
		$wpdb->query($structure3); 
		$structure4 = "drop table if exists $ttstoreitemtable";
		$wpdb->query($structure4); 

	}
	if(get_option("Tradetracker_removexml")=="1"){
		$structure5 = "drop table if exists $ttstorexmltable";
		$wpdb->query($structure5);
		$structure6 = "drop table if exists $ttstorecattable";
		$wpdb->query($structure6); 
		$structure7 = "drop table if exists $ttstoreextratable";
		$wpdb->query($structure7); 
		$ttstorestatstable = PRO_TABLE_PREFIX."stats";
		$structure8 = "drop table if exists $ttstorestatstable";
		$wpdb->query($structure8);
		delete_option("TTnewcategory");
		delete_option("Tradetracker_xml");
		delete_option("Tradetracker_xmlname");
		delete_option("Tradetracker_xmlupdate");
		delete_option("Tradetracker_currency");
		delete_option("Tradetracker_currencyloc");
		delete_option("Tradetracker_newcur");
		delete_option("Tradetracker_extra");
		delete_option("Tradetracker_xml_extra");

	}
	if(get_option("Tradetracker_removeother")=="1"){
		delete_option("Tradetracker_importtool");
		delete_option("TTstoreversion");
		delete_option("Tradetracker_width");
		delete_option("Tradetracker_debugemail");
		delete_option("Tradetracker_removelayout");
		delete_option("Tradetracker_removestores");
		delete_option("Tradetracker_removeproducts");
		delete_option("Tradetracker_removexml");
		delete_option("Tradetracker_removeother");
		delete_option("tt_premium_provider");
		delete_option("tt_premium_function");
		delete_option("Tradetracker_premiumupdate");
		delete_option("Tradetracker_premiumaccepted");
		delete_option("Tradetracker_premiumapi");
		delete_option("Tradetracker_adminheight");
		delete_option("Tradetracker_adminwidth");
		delete_option("Tradetracker_showurl");
		delete_option("Tradetracker_searchlayout");
		delete_option("Tradetracker_loadextra");
	}
}
?>