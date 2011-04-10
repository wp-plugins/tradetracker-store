<?php
/*
Plugin Name: Tradetracker-Store
Plugin URI: http://wordpress.org/extend/plugins/tradetracker-store/
Version: 2.0.1
Description: A Plugin that will add a TradeTracker affiliate feed to your site with several options to choose from.
Author: Robert Braam
Author URI: http://vannetti.nl
*/

/* 
..--==[ All items needed to activate the script ]==--..
 */

global $wpdb;
include('admin/adminmenu.php');
include('admin/adminsetup.php');
include('admin/adminoptions.php');
include('admin/adminitems.php');
include('admin/adminlayout.php');
include('admin/adminoverview.php');
include('admin/adminstats.php');
include('admin/adminmulti.php');
include('admin/adminmultiitems.php');

$pro_table_prefix=$wpdb->prefix.'tradetracker_';
define('PRO_TABLE_PREFIX', $pro_table_prefix);
register_activation_hook(__FILE__,'tradetracker_store_install');
register_deactivation_hook(__FILE__ ,'tradetracker_store_uninstall');
if(!defined('WP_CONTENT_URL')){
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}
if(!defined('WP_PLUGIN_URL')){
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
}
add_action('wp_print_styles', 'add_my_stylesheet');


/* 
..--==[ Function to add a database table for this script ]==--..
*/

function tradetracker_store_install()
{
    global $wpdb;
$table = PRO_TABLE_PREFIX."store";
if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {

    
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
	productID INT(10) NOT NULL,
        name VARCHAR(80) NOT NULL,
        imageURL VARCHAR(200) NOT NULL,
	productURL VARCHAR(1000) NOT NULL,
	price DECIMAL(5,2) NOT NULL,
	currency VARCHAR(5) NOT NULL,
        description text,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);

    update_option( Tradetracker_width, "250" );
    update_option( Tradetracker_colortitle, "#ececed" );
    update_option( Tradetracker_colorfooter, "#ececed" );
    update_option( Tradetracker_colorimagebg, "#FFFFFF" );
    update_option( Tradetracker_colorfont, "#000000" );
	  // Populate table
}
}

/* 
..--==[ Function to delete a database table for this script ]==--.. 
*/

function tradetracker_store_uninstall()
{
    global $wpdb;
    $table = PRO_TABLE_PREFIX."store";
    $structure = "drop table if exists $table";
    $table2 = PRO_TABLE_PREFIX."layout";
    $structure2 = "drop table if exists $table2";
    $table3 = PRO_TABLE_PREFIX."multi";
    $structure3 = "drop table if exists $table3";
    $wpdb->query($structure); 
    $wpdb->query($structure2);  
    $wpdb->query($structure3);   
	$myFile = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml';
	$fh = fopen($myFile, 'w') or die("can't open file");
	fclose($fh);
	unlink($myFile);
	
}


/* 
..--==[ Function to add the stylesheet for the store ]==--.. 
*/
function add_my_stylesheet() {
	$myStyleUrl = WP_PLUGIN_URL . '/tradetracker-store/store.css';
	$myStyleFile = WP_PLUGIN_DIR . '/tradetracker-store/store.css';
	if ( file_exists($myStyleFile) ) {
		wp_register_style('myStyleSheets', $myStyleUrl);
		wp_enqueue_style( 'myStyleSheets');
        }
}

/* 
..--==[ Function to see if XML is loaded already and cached. ]==--.. 
*/

function store_items($used, $winkel)
{
	global $wpdb;
	if( get_option( Tradetracker_update ) == "" ){
		$update= "24";
	} else {
		$update= get_option( Tradetracker_update );
	}

$Tradetracker_xml = get_option( Tradetracker_xml );
if ($Tradetracker_xml == null) {
	return "No store activated yet";
} else {
	$cache_time = 3600*$update; // 24 hours
$context = stream_context_create(array(
    'http' => array(
        'timeout' => 3      // Timeout in seconds
    )
));
	$cache_file = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml';
	$timedif = @(time() - filemtime($cache_file));
		if (file_exists($cache_file) && $timedif < $cache_time) {
			if ('' == file_get_contents($cache_file))
				{
		     			$string = file_get_contents(''.$Tradetracker_xml.'', 0, $context);
		    			if ($f = @fopen($cache_file, 'w')) {
        					fwrite ($f, $string, strlen($string));
        					fclose($f);
    					}
					fill_database();
				}  
		} else {
    			$string = file_get_contents(''.$Tradetracker_xml.'', 0, $context);
    			if ($f = @fopen($cache_file, 'w')) {
        			fwrite ($f, $string, strlen($string));
        			fclose($f);
    			}
			fill_database();
		}
	return show_items($used, $winkel);
	}
}
/* 
..--==[ Function to fill the database from the xml. ]==--.. 
*/

function fill_database()
{
	global $wpdb; 
	$table = PRO_TABLE_PREFIX."store";
	$emptytable = "DELETE FROM $table;;";
	$wpdb->query($emptytable);
	$cache_file = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml';
	$string = file_get_contents($cache_file);
	$products = simplexml_load_string($string);
	foreach($products as $product) // loop through our items
	{
	    	global $wpdb; 
	    	$currentpage["productID"]=$product->productID;		
		$currentpage["name"]=$product->name;
		$currentpage["imageURL"]=$product->imageURL;
		$currentpage["productURL"]=$product->productURL;
		$currentpage["description"]=$product->description;
		$currentpage["price"]=$product->price;
		$currentpage["currency"]=$product->price['currency'];
		$wpdb->insert( $table, $currentpage);//insert the captured values
	}
}
/* 
..--==[ Function to show the items. ]==--.. 
*/

function show_items($usedhow, $winkelvol)
{
	global $wpdb;
$pro_table_prefix=$wpdb->prefix.'tradetracker_';
$tablemulti = PRO_TABLE_PREFIX."multi";
$tablelayout = PRO_TABLE_PREFIX."layout";
define('PRO_TABLE_PREFIX', $pro_table_prefix);
	if (empty($winkelvol)){
		$Tradetracker_amount = get_option( Tradetracker_amount );
		$Tradetracker_productid = get_option( Tradetracker_productid );
		if ($Tradetracker_productid == null) {
			if ($Tradetracker_amount == null) 
			{
				$Tradetracker_amount_i = "LIMIT 12"; 
			} else {
				$Tradetracker_amount_i = "LIMIT ".$Tradetracker_amount.""; 
			}
		}
			$width= "250";
			$font= "Verdana";
			$widthtitle = $width-6;
			$colortitle = "#ececed";
			$colorfooter = "#ececed";
			$colorimagebg = "#ffffff";
			$colorfont = "#000000";
			$storename = "basic";

	} else {
		$multi=$wpdb->get_results("SELECT multiname, laywidth, layfont, laycolortitle, laycolorfooter, laycolorimagebg, laycolorfont, multiitems, multiamount, multilightbox FROM ".$tablemulti.",".$tablelayout." where ".$tablemulti.".multilayout=".$tablelayout.".id and ".$tablemulti.".id=".$winkelvol."");
		foreach ($multi as $multi_val){
			$Tradetracker_amount = $multi_val->multiamount;
			if ($multi_val->multiamount == "") 
			{
				$Tradetracker_amount_i = "LIMIT 12"; 
			} else {
				$Tradetracker_amount_i = "LIMIT ".$multi_val->multiamount.""; 
			}
			
			if( $multi_val->laywidth == "" ){
				$width= "250";
			} else {
				$width= $multi_val->laywidth;
			}
			if( $multi_val->layfont == "" ){
				$font= "Verdana";
			} else {
				$font= $multi_val->layfont;
			}
			$widthtitle = $width-6;
			if( $multi_val->laycolortitle == "" ){
				$colortitle = "#ececed";
			} else {
				$colortitle = $multi_val->laycolortitle;
			}
			if( $multi_val->laycolorfooter == "" ){
				$colorfooter = "#ececed";
			} else {
				$colorfooter = $multi_val->laycolorfooter;
			}
			if( $multi_val->laycolorimagebg == "" ){
				$colorimagebg = "#ffffff";
			} else {
				$colorimagebg = $multi_val->laycolorimagebg;
			}
			if( $multi_val->laycolorfont == "" ){
				$colorfont = "#000000";
			} else {
				$colorfont = $multi_val->laycolorfont;
			}
			$Tradetracker_productid = $multi_val->multiitems;
			$storename = str_replace(" ", "_", $multi_val->multiname);
		}
	}
	echo "<style type=\"text/css\" media=\"screen\">";
	echo ".".$storename."store-outerbox{width:".$width."px;color:".$colorfont.";font-family:".$font.";float:left;margin:0px 15px 15px 0;height:353px;border:solid 1px #999999;position:relative;}";
	echo ".".$storename."store-titel{width:".$widthtitle."px;background-color:".$colortitle.";color:".$colorfont.";float:left;position:relative;height:30px;line-height:15px;font-size:11px;padding:3px;font-weight:bold;text-align:center;}";
	echo ".".$storename."store-image{width:".$width."px;height:180px;padding:0px;overflow:hidden;margin: auto;background-color:".$colorimagebg.";}";
	echo ".".$storename."store-image img{display: block;border:0px;margin: auto;}";
	echo ".".$storename."store-footer{width:".$width."px;background-color:".$colorfooter.";float:left;position:relative;height:137px;}";
	echo ".".$storename."store-description{width:".$widthtitle."px;color:".$colorfont.";position:absolute;top:5px;left:5px;height:90px;line-height:14px;font-size:10px;overflow:auto;}";
	echo "</style>";

	$table = PRO_TABLE_PREFIX."store";

	if ($Tradetracker_productid == null) 
	{
		$visits=$wpdb->get_results("SELECT * FROM ".$table." ORDER BY RAND() $Tradetracker_amount_i");
	} else {
		$productID = $Tradetracker_productid;
		$productID = str_replace(",", " or productID=", $productID);
		$visits=$wpdb->get_results("SELECT * FROM ".$table." where productID=".$productID." ORDER BY RAND() ".$Tradetracker_amount_i."");
	}
	$storeitems = "";
	foreach ($visits as $product){
		if(get_option(Tradetracker_lightbox)==1){
			$image = $product->imageURL;
			$target = "";	
			$rel = "rel=\"lightbox[store]\"";
		} else {
			$image = $product->productURL;
			$target = "target=\"_blank\"";
			$rel = "";
		}
		$storeitems .= "
			<div class=\"".$storename."store-outerbox\">
				<div class=\"".$storename."store-titel\">
					".$product->name."
				</div>			
				<div class=\"".$storename."store-image\">
					<a href=\"".$image."\" ".$rel." ".$target.">
						<img src=\"".$product->imageURL."\" alt=\"".$product->name."\" title=\"".$product->name."\" style=\"max-width:".$width."px;max-height:180px;\" />
					</a>
				</div>
				<div class=\"".$storename."store-footer\">
					<div class=\"".$storename."store-description\">
						".$product->description."
					</div>
					<div class=\"buttons\">
						<a href=\"".$product->productURL."\" class=\"regular\">
							Buy Item
						</a>
					</div>
					<div class=\"store-price\">
						<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
							<tr>
								<td style=\"height:23px;\" class=\"euros\">
									".$product->price." ".$product->currency."
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>";
	}
	if ($usedhow == 1){
	return $storeitems;
	}
	if ($usedhow == 2){
	echo $storeitems;
	}
}

add_shortcode('display_store', 'display_store_items_short');

function display_store_items_short()
{
	return store_items(1);
}

function display_store_items()
{
	return store_items(2);
}


add_shortcode('display_multi', 'display_multi_items_short');

function display_multi_items_short($store)
{
	extract(shortcode_atts(array("store" => '0'), $store));
	return store_items(1, $store);
}

function display_multi_items($store)
{
	return store_items(2, $store);
}


?>