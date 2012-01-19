<?php
/*
Plugin Name: Tradetracker-Store
Plugin URI: http://wpaffiliatefeed.com
Version: 3.1.15
Description: A Plugin that will add a TradeTracker affiliate feed to your site with several options to choose from.
Author: Robert Braam
Author URI: http://wpaffiliatefeed.com
*/

/* 
..--==[ All items needed to activate the script ]==--..
 */

global $wpdb;
$pro_table_prefix=$wpdb->prefix.'tradetracker_';
define('PRO_TABLE_PREFIX', $pro_table_prefix);
if(!defined('WP_CONTENT_URL')){
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}
if(!defined('WP_PLUGIN_URL')){
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
}
include('admin/adminmenu.php');
include('admin/adminsetup.php');
include('admin/adminoptions.php');
include('premium.php');
include('debug.php');
include('xml.php');
if (get_option("Tradetracker_settings")=="1"){
include('admin/basic/adminitems.php');
include('admin/basic/adminoverview.php');
}

if (get_option("Tradetracker_settings")=="2"){
include('admin/advanced/adminlayout.php');
include('admin/advanced/adminstats.php');
include('admin/advanced/adminmulti.php');
include('admin/advanced/adminsearch.php');
include('admin/advanced/adminmultiitems.php');
include('admin/advanced/adminoverview.php');
}


register_activation_hook(__FILE__,'tradetracker_store_install');
register_deactivation_hook(__FILE__ ,'tradetracker_store_uninstall');
add_filter('plugin_action_links', 'TTstore_plugin_action_links', 10, 2);

function isTime($time){	
	return preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $time);
}


if (!wp_next_scheduled('xmlscheduler')) {
	$tijdschedule = get_option('Tradetracker_xmlupdate');
	if(isTime($tijdschedule)) {
		wp_schedule_event( strtotime(date('Y-m-d '.$tijdschedule.'', strtotime("+1 day"))), 'daily', 'xmlscheduler' );
	} else {
		wp_schedule_event( strtotime(date('Y-m-d 00:00:01', strtotime("+1 day"))), 'daily', 'xmlscheduler' );
	}
}
ttstoreerrordetect();
add_action( 'xmlscheduler', 'runxmlupdater' ); 
function runxmlupdater() {
	premium_updater();
	xml_updater();
	news_updater();
}
$store = PRO_TABLE_PREFIX."store";
$multi = PRO_TABLE_PREFIX."multi";
$layout = PRO_TABLE_PREFIX."layout";

if (get_option("TTstoreversion") == "3.1.11" || get_option("TTstoreversion") == "3.1.10"){
	$result=$wpdb->query("ALTER TABLE `".$multi."` ADD `multiproductpage` VARCHAR(1) NOT NULL");
	update_option("TTstoreversion", "3.1.14" );
}

if (get_option("TTstoreversion") == "3.1.7"){
	update_option("Tradetracker_debugemail", "1" );
	update_option("TTstoreversion", "3.1.11" );
}

if (get_option("TTstoreversion") == "3.1.6"){
	$result=$wpdb->query("ALTER TABLE `".$layout."` ADD `laycolorbuttonfont` VARCHAR(50) NOT NULL");
	update_option("TTstoreversion", "3.1.7" );
}

if (get_option("TTstoreversion") == "3.0.12"){
	$result=$wpdb->query("ALTER TABLE `".$store."` MODIFY `productID` VARCHAR(36)");
	update_option("TTstoreversion", "3.1.6" );
	xml_updater();
}

if (get_option("TTstoreversion") == "3.0.11"){
	$result=$wpdb->query("ALTER TABLE `".$layout."` ADD `laycolorborder` VARCHAR(7) NOT NULL");
	$result=$wpdb->query("ALTER TABLE `".$layout."` ADD `laycolorbutton` VARCHAR(7) NOT NULL");
	update_option("TTstoreversion", "3.0.12" );
	xml_updater();
}

if (get_option("TTstoreversion") == "3.0.7"){
	$result=$wpdb->query("ALTER TABLE `".$store."` ADD `categorieid` VARCHAR(32) NOT NULL");
	update_option("TTstoreversion", "3.0.11" );
	xml_updater();
}

if (get_option("TTstoreversion") == "3.0.5"){
	if(function_exists('curl_init')) {
		$us = $_SERVER['HTTP_HOST'];
		$url = "http://wpaffiliatefeed.com/premium/site.php?where=".$us."";
		$ch = curl_init();
		$timeout = 5; // set to zero for no timeout
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$content = curl_exec($ch);
		curl_close($ch);
	}
	update_option("TTstoreversion", "3.0.7" );	
}


if (get_option("TTstoreversion") == "3.0.0"){
	$result=$wpdb->query("ALTER TABLE `".$store."` ADD `categorie` longtext NOT NULL");
	$result=$wpdb->query("ALTER TABLE `".$multi."` ADD `categories` longtext NOT NULL");
	update_option("TTstoreversion", "3.0.5" );
	xml_updater();	
}

if (get_option("TTstoreversion") < "3.0.0"){
	$result=$wpdb->query("ALTER TABLE `".$store."` ADD `extrafield` TEXT NOT NULL");
	$result=$wpdb->query("ALTER TABLE `".$store."` ADD `extravalue` TEXT NOT NULL");
	$result=$wpdb->query("ALTER TABLE `".$store."` ADD `xmlfeed` VARCHAR(10) NOT NULL");
	$result=$wpdb->query("ALTER TABLE `".$store."` MODIFY `price` decimal(10,2)");
	$result=$wpdb->query("ALTER TABLE `".$multi."` ADD `multixmlfeed` VARCHAR(10) NOT NULL");
	delete_option("Tradetracker_xml");
	update_option("TTstoreversion", "3.0.0" );
	xml_updater();
	// Don't forget to adjust this also at the installation query below.
}

function TTstore_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        // The "page" query string value must be equal to the slug
        // of the Settings admin page we defined earlier, which in
        // this case equals "myplugin-settings".
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=tradetracker-shop">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}


/* 
..--==[ Function to add a database table for this script ]==--..
*/
function news()
{
?>
<style type="text/css" media="screen">
.plugin_news
{
	float:left;
	font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
	font-size:12px;	
	width:200px;
	min-height:1050px;
}

.plugin_news h3
{
	font-size:15px;
	margin:0 0 10px;
	color:#E41B17;
}

.plugin_news ul
{
	margin:0;
	padding:0 0 0 15px;
}

.plugin_news_section
{
margin-bottom:5px;
margin-top:5px;
margin-left:0px;
background: #FDFFEE;
border: 1px solid #DDDDDD;
padding:10px;
}

.plugin_news li
{
	font-size:11px;
	line-height:16px;
	list-style:disc;
	margin:0px;
}
#slider ul,#slider li
{
	list-style:none;
	margin:0;
	padding:0
}

#slider,#slider li
{
	width:180px;
	overflow:hidden
}

</style>
	<div class="plugin_news">
	<div class="plugin_news_section">
	<h3>Donate</h3>
	<div id="slider">
	<ul>
		<li>This plugin is made in my spare time. If you really like this plugin and it helped to improve the income of your site and you are willing to show me some of the gratitude:
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="J3UBRGHKXSAWC">
<input type="image" src="https://www.paypalobjects.com/nl_NL/NL/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal, de veilige en complete manier van online betalen.">
<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
</form>
	</ul>
	</div>
	</div>
	<div class="plugin_news_section">
	<h3>Like this on facebook</h3>
	<iframe src="http://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FWpaffiliatefeed%2F243951359002776&amp;width=180&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=false&amp;appId=126016140831179" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:180px; height:258px;"></iframe>
	</div>

	<div class="plugin_news_section">
	<h3>Sites using this plugin</h3>
	<ul>
<?php
	$site_dir = WP_PLUGIN_DIR.'/tradetracker-store/cache/sites.xml';
	if (!file_exists($site_dir)) {
		   $site_dir = 'http://wpaffiliatefeed.com/tradetracker-store/sites.xml'; 
	} 
	$sites = file_get_contents($site_dir);
	$sites = simplexml_load_string($sites);
	foreach($sites as $site) // loop through our items
	{
echo "<li><a href=\"".$site->siteadres."\" target=\"_blank\">".$site->sitenaam."</a>";		
	}
?>
	</ul>
	</div>
	<div class="plugin_news_section">
	<h3>Your Site here?</h3>
	<div id="slider">
	<ul>
		<li>if you want Your site here please use Tt Store Feedback and let me know
	</ul>
	</div>
	</div>
	<div class="plugin_news_section">
	<h3>Rate the plugin</h3>
	<div id="slider">
	<ul>
		<li>if you like this plugin please go to <a href="http://wordpress.org/extend/plugins/tradetracker-store/" target="_blank">here</a> and give it a rating and vote for yes if the plugin works.
	</ul>
	</div>
	</div>
	<div class="plugin_news_section">
	<h3>News</h3>
	<div id="slider">
	<ul>
<?php
	$news_dir = WP_PLUGIN_DIR.'/tradetracker-store/cache/news.xml';
	if (!file_exists($news_dir)) {
		   $news_dir = 'http://wpaffiliatefeed.com/category/news/feed/'; 
	} 
	$news = file_get_contents($news_dir);
	$news = simplexml_load_string($news);
	foreach($news as $newsmsg) // loop through our items
	{
echo "<li><strong><a href=\"".$newsmsg->item->link."\">".$newsmsg->item->title."</a></strong><br><strong>Posted: ".date("d M Y",strtotime($newsmsg->item->pubDate))."</strong><br>".$newsmsg->item->description."</li>";
	}
?>
	</ul>
	</div>
	</div>
	</div>

<?php
}
function news_updater(){
			$dir =  WP_PLUGIN_DIR . "/tradetracker-store";
			$site_file = 'http://wpaffiliatefeed.com/tradetracker-store/sites.xml';
			if (function_exists('curl_init')) {
				$ch = curl_init($site_file);
				$fp = fopen($dir."/cache/sites.xml", "w");
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);
			}
			$news_file = 'http://wpaffiliatefeed.com/feed/';
			if (function_exists('curl_init')) {
				$ch = curl_init($news_file);
				$fp = fopen($dir."/cache/news.xml", "w");
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);
			}

}
function tradetracker_store_install()
{
    global $wpdb;
$table = PRO_TABLE_PREFIX."store";
if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
	productID VARCHAR(36) NOT NULL,
        name VARCHAR(80) NOT NULL,
        imageURL VARCHAR(200) NOT NULL,
	productURL VARCHAR(1000) NOT NULL,
	price DECIMAL(10,2) NOT NULL,
	currency VARCHAR(10) NOT NULL,
	xmlfeed VARCHAR(10) NOT NULL,
	categorieid VARCHAR(32) NOT NULL,
	categorie longtext NOT NULL,
	description text,
	extrafield text,
	extravalue text,
	UNIQUE KEY id (id)
    );";
	if(function_exists('curl_init')) {
		$us = $_SERVER['HTTP_HOST'];
		$url = "http://wpaffiliatefeed.com/premium/site.php?where=".$us."";
		$ch = curl_init();
		$timeout = 5; // set to zero for no timeout
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$content = curl_exec($ch);
		curl_close($ch);
	}
    $wpdb->query($structure)  or die(mysql_error());
	update_option("TTstoreversion", "3.1.14" );
	update_option("Tradetracker_width", "250" );
	update_option("Tradetracker_debugemail", "1" );
	update_option("Tradetracker_colortitle", "#ececed" );
	update_option("Tradetracker_colorfooter", "#ececed" );
	update_option("Tradetracker_colorimagebg", "#FFFFFF" );
	update_option("Tradetracker_colorfont", "#000000" );
	update_option("Tradetracker_colorborder", "#65B9C1" );
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
	wp_clear_scheduled_hook('xml_update');  
}


/* 
..--==[ Function to add the stylesheet for the store ]==--.. 
*/

function TTstore_scripts() {   
	wp_enqueue_script( 'ttstoreexpand-script', WP_PLUGIN_URL . '/tradetracker-store/js/expand.js');
}       
	
add_action('init', 'TTstore_scripts'); 





/* 
..--==[ Function to see if XML is loaded already and cached. ]==--.. 
*/
function store_items($used, $winkel, $searching)
{
	$Tradetracker_xml = get_option("Tradetracker_xml");
	if ($Tradetracker_xml == null)
	{
		echo "No XML filled in yet please change the settings first.";
	} else {
		return show_items($used, $winkel, $searching);
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
add_action('wp_head', 'header_css_style');

function header_css_style() {
	global $wpdb;
	$style="";
	if (get_option("Tradetracker_settings")==2){
	$tablelayout = PRO_TABLE_PREFIX."layout";
	$tablemulti = PRO_TABLE_PREFIX."multi";
	$multi=$wpdb->get_results("SELECT multiname, laywidth, layfont, laycolortitle, laycolorbuttonfont, laycolorbutton, laycolorborder, laycolorfooter, laycolorimagebg, laycolorfont FROM ".$tablemulti.",".$tablelayout." where ".$tablemulti.".multilayout=".$tablelayout.".id");
		foreach ($multi as $multi_val){
			$i="1";
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
			$widthmore = $width-10;
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
			if( $multi_val->laycolorborder == "" ){
				$colorborder = "#65B9C1";
			} else {
				$colorborder = $multi_val->laycolorborder;
			}
			if( $multi_val->laycolorbutton == "" ){
				$colorbutton = "#65B9C1";
			} else {
				$colorbutton = $multi_val->laycolorbutton;
			}
			if( $multi_val->laycolorbuttonfont == "" ){
				$colorbuttonfont = "#ffffff";
			} else {
				$colorbuttonfont = $multi_val->laycolorbuttonfont;
			}
			$storename = create_slug($multi_val->multiname);
			$style .= "<style type=\"text/css\" media=\"screen\">";
			$style .= ".".$storename."store-outerbox{width:".$width."px;color:".$colorfont.";font-family:".$font.";float:left;margin:0px 15px 15px 0;min-height:353px;border:solid 1px ".$colorborder.";position:relative;}";
			$style .= ".".$storename."store-titel{width:".$widthtitle."px;background-color:".$colortitle.";color:".$colorfont.";float:left;position:relative;height:30px;line-height:15px;font-size:11px;padding:3px;font-weight:bold;text-align:center;}";
			$style .= ".".$storename."store-image{width:".$width."px;height:180px;padding:0px;overflow:hidden;margin: auto;background-color:".$colorimagebg.";}";
			$style .= ".".$storename."store-image img{display: block;border:0px;margin: auto;}";
			$style .= ".".$storename."store-footer{width:".$width."px;background-color:".$colorfooter.";float:left;position:relative;min-height:137px;}";
			$style .= ".".$storename."store-footer table tr td{padding:4px;}";
			$style .= ".".$storename."store-description{width:".$widthtitle."px;color:".$colorfont.";position:relative;top:5px;left:5px;height:90px;line-height:14px;font-size:10px;overflow:auto;}";
			$style .= ".".$storename."store-more{min-height:20px; width:".$widthtitle."px;position: relative;float: left;margin-top:10px;margin-left:5px;margin-bottom: 5px;}";
			$style .= ".".$storename."store-more img{margin:0px !important;}";
			$style .= ".".$storename."store-price {border: 0 solid #65B9C1;color: #4E4E4E !important;float: right;font-size: 12px !important;font-weight: bold !important;height: 30px !important;position: relative;text-align: center !important;width: 80px !important;}";
			$style .= ".".$storename."store-price table {height:29px;width:79px;background-color: ".$colorfooter." !important; border: 1px none !important;border-collapse: inherit !important;float: right;margin: 1px 0 1px 1px;text-align: center !important;}";
			$style .= ".".$storename."store-price table tr {padding: 1px !important;}";
			$style .= ".".$storename."store-price table tr td {padding: 1px !important;}";
			$style .= ".".$storename."store-price table td, table th, table tr {border: 1px solid #CCCCCC;padding: 0 !important;}";
			$style .= ".".$storename."store-price table td.euros {font-size: 12px !important;letter-spacing: -1px !important; }";
			$style .= ".".$storename."store-price {background-color: ".$colorborder." !important;}";
			$style .= ".".$storename."buttons a, .".$storename."buttons button {background-color: ".$colorbutton.";border: 1px solid ".$colorbutton.";bottom: 0;color: ".$colorbuttonfont.";cursor: pointer;display: block;float: left;font-size: 12px;font-weight: bold;margin-top: 0;padding: 5px 10px 5px 7px;position: relative;text-decoration: none;width: 100px;}";
			$style .= ".".$storename."buttons button {overflow: visible;padding: 4px 10px 3px 7px;width: auto;}";
			$style .= ".".$storename."buttons button[type] {line-height: 17px;padding: 5px 10px 5px 7px;}";
			$style .= ".".$storename.":first-child + html button[type] {padding: 4px 10px 3px 7px;}";
			$style .= ".".$storename."buttons button img, .".$storename."buttons a img {border: medium none;margin: 0 3px -3px 0 !important;padding: 0;}";
			$style .= ".".$storename."button.regular, .".$storename."buttons a.regular {color: ".$colorbuttonfont.";}";
			$style .= ".".$storename."buttons a.regular:hover, button.regular:hover {background-color: #4E4E4E;border: 1px solid #4E4E4E;color: ".$colorbuttonfont.";}";
			$style .= ".".$storename."buttons a.regular:active {background-color: #FFFFFF;border: 1px solid ".$colorbutton.";color: ".$colorbuttonfont.";}";
			$style .= "</style>";
		}
	}
		$width= "250";
		$font= "Verdana";
		$widthtitle = $width-6;
		$widthmore = $width-10;
		$colortitle = "#ececed";
		$colorfooter = "#ececed";
		$colorimagebg = "#ffffff";
		$colorfont = "#000000";
		$colorborder = "#65B9C1";
		$storename = "basic";
		$style .= "<style type=\"text/css\" media=\"screen\">";
		$style .= ".".$storename."store-outerbox{width:".$width."px;color:".$colorfont.";font-family:".$font.";float:left;margin:0px 15px 15px 0;min-height:353px;border:solid 1px ".$colorborder.";position:relative;}";
		$style .= ".".$storename."store-titel{width:".$widthtitle."px;background-color:".$colortitle.";color:".$colorfont.";float:left;position:relative;height:30px;line-height:15px;font-size:11px;padding:3px;font-weight:bold;text-align:center;}";
		$style .= ".".$storename."store-image{width:".$width."px;height:180px;padding:0px;overflow:hidden;margin: auto;background-color:".$colorimagebg.";float:left;}";
		$style .= ".".$storename."store-image img{display: block;border:0px;margin: auto;}";
		$style .= ".".$storename."store-footer{width:".$width."px;background-color:".$colorfooter.";float:left;position:relative;min-height:137px;}";
		$style .= ".".$storename."store-description{width:".$widthtitle."px;color:".$colorfont.";position:relative;top:5px;left:5px;height:90px;line-height:14px;font-size:10px;overflow:auto;}";
		$style .= ".".$storename."store-more{min-height:20px; width:".$widthtitle."px;position: relative;float: left;margin-top:10px;margin-left:5px;margin-bottom: 5px;}";
		$style .= ".".$storename."store-more img{margin:0px !important;}";
		$style .= ".".$storename."store-price {border: 0 solid #65B9C1;color: #4E4E4E !important;float: right;font-size: 12px !important;font-weight: bold !important;height: 30px !important;position: relative;text-align: center !important;width: 80px !important;}";
		$style .= ".".$storename."store-price table {height:29px;width:79px;background-color: ".$colorfooter." !important; border: 1px none !important;border-collapse: inherit !important;float: right;margin: 1px 0 1px 1px;text-align: center !important;}";
		$style .= ".".$storename."store-price table tr {padding: 1px !important;}";
		$style .= ".".$storename."store-price table tr td {padding: 1px !important;}";
		$style .= ".".$storename."store-price table td, table th, table tr {border: 1px solid #CCCCCC;padding: 0 !important;}";
		$style .= ".".$storename."store-price table td.euros {font-size: 12px !important;letter-spacing: -1px !important; }";
		$style .= ".".$storename."store-price {background-color: ".$colorborder." !important;}";
		$style .= ".".$storename."buttons a, .".$storename."buttons button {background-color: ".$colorbutton.";border: 1px solid ".$colorbutton.";bottom: 0;color: #FFFFFF;cursor: pointer;display: block;float: left;font-size: 12px;font-weight: bold;margin-top: 0;padding: 5px 10px 5px 7px;position: relative;text-decoration: none;width: 100px;}";
		$style .= ".".$storename."buttons button {overflow: visible;padding: 4px 10px 3px 7px;width: auto;}";
		$style .= ".".$storename."buttons button[type] {line-height: 17px;padding: 5px 10px 5px 7px;}";
		$style .= ".".$storename.":first-child + html button[type] {padding: 4px 10px 3px 7px;}";
		$style .= ".".$storename."buttons button img, .".$storename."buttons a img {border: medium none;margin: 0 3px -3px 0 !important;padding: 0;}";
		$style .= ".".$storename."button.regular, .".$storename."buttons a.regular {color: #FFFFFF;}";
		$style .= ".".$storename."buttons a.regular:hover, button.regular:hover {background-color: #4E4E4E;border: 1px solid #4E4E4E;color: #FFFFFF;}";
		$style .= ".".$storename."buttons a.regular:active {background-color: #FFFFFF;border: 1px solid ".$colorbutton.";color: #FFFFFF;}";
		$style .= ".ttstorelink a { display:none; }";
		$style .= "</style>";
		echo $style;
}

function imageSize($size) {
	$width = $size[0];
	$height = $size[1];
	return 'width="'.$width.'" height="'.$height.'"';
}
function show_items($usedhow, $winkelvol, $searching)
{
	global $wpdb;
	$tablemulti = PRO_TABLE_PREFIX."multi";
	$tablelayout = PRO_TABLE_PREFIX."layout";
	if ($winkelvol=="0"){
		$Tradetracker_amount = get_option("Tradetracker_amount");
		$Tradetracker_productid = get_option("Tradetracker_productid");
		if ($Tradetracker_productid == null) {
			if ($Tradetracker_amount == null) 
			{
				$Tradetracker_amount_i = "LIMIT 12"; 
			} else {
				$Tradetracker_amount_i = "LIMIT ".$Tradetracker_amount.""; 
			}
		}
		$multixmlfeed = "";
		if( get_option("Tradetracker_buynow") == "" ){
			$buynow= "Buy Item";
		} else {
			$buynow= get_option("Tradetracker_buynow");
		}
			$width= "250";
			$font= "Verdana";
			$widthtitle = $width-6;
			$widthmore = $width-10;
			$colortitle = "#ececed";
			$colorfooter = "#ececed";
			$colorimagebg = "#ffffff";
			$colorfont = "#000000";
			$storename = "basic";
			$colorborder = "#65B9C1";
			$colorbutton = "#65B9C1";
		if(get_option("Tradetracker_lightbox")==1){
			$uselightbox = "1";
		}
	} else {
		if ($searching == "1") {
			$multi=$wpdb->get_results("SELECT buynow, categories, multixmlfeed, multiname, laywidth, multiitems, multiamount, multilightbox FROM ".$tablemulti.",".$tablelayout." where ".$tablemulti.".multilayout=".$tablelayout.".id and ".$tablemulti.".id=".get_option("Tradetracker_searchlayout")."");
		} else {
			$multi=$wpdb->get_results("SELECT buynow, categories, multixmlfeed, multiproductpage, multiname, laywidth, multiitems, multiamount, multilightbox FROM ".$tablemulti.",".$tablelayout." where ".$tablemulti.".multilayout=".$tablelayout.".id and ".$tablemulti.".id=".$winkelvol."");
		}
		foreach ($multi as $multi_val){	
			$Tradetracker_amount = $multi_val->multiamount;
			if($multi_val->multixmlfeed == "*" ){
				$multixmlfeed = "";
			} else {
				$multixmlfeed = "where xmlfeed = ".$multi_val->multixmlfeed." ";
			}
			if($multi_val->multiproductpage == "1" ){
				$multiproductpage = "1";
			} else {
				$multiproductpage = "0";
			}
			$i="1";
			$categories = unserialize($multi_val->categories);
			if(!empty($categories)){
				foreach ($categories as $categories){
					if($i == "1" ) {
						if($multixmlfeed == ""){
							$categorieselect = " where (categorieid = \"".$categories."\"";
						}else {
							$categorieselect = " and (categorieid = \"".$categories."\"";
						}
					$i = "2";
					} else {
							$categorieselect .= " or categorieid = \"".$categories."\"";
					}
				}
				$categorieselect .= ") ";
			} else {
				$categorieselect = "";
			}
			if( $multi_val->buynow == "" ){
				$buynow= "Buy Item";
			} else {
				$buynow= $multi_val->buynow;
			}
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
			$widthtitle = $width-6;
			$widthmore = $width-10;
			$Tradetracker_productid = $multi_val->multiitems;
			$storename = create_slug($multi_val->multiname);
			if($multi_val->multilightbox==1){
				$uselightbox = "1";
			}
		}
	}
	$table = PRO_TABLE_PREFIX."store";
	if ($searching == "1") {
		$term = mysql_real_escape_string(get_search_query());
		$visits=$wpdb->get_results("SELECT * FROM ".$table." WHERE `name` LIKE '%$term%' or `description` LIKE '%$term%' ORDER BY RAND() $Tradetracker_amount_i");
	} else {
		if ($Tradetracker_productid == null) 
		{
			$visits=$wpdb->get_results("SELECT * FROM ".$table." ".$multixmlfeed." ".$categorieselect." ORDER BY RAND() $Tradetracker_amount_i");
		} else {
			$productID = $Tradetracker_productid;
			$productID = str_replace(",", "' or productID='", $productID);
			$visits=$wpdb->get_results("SELECT * FROM ".$table." where productID='".$productID."' ORDER BY RAND() ".$Tradetracker_amount_i."");
		}
	}
	$storeitems = "";
	$i="1";
	foreach ($visits as $product){
		$Tradetracker_extra_val = get_option("Tradetracker_extra");
		if(!empty($Tradetracker_extra_val)){
			$extrafield = explode(",",$product->extrafield);
			$extravalue = explode(",",$product->extravalue);
			$extras = array_combine($extrafield, $extravalue);
			$extraname = "";
			$extravar = "";
			foreach ($extras as $key => $value) {
				$Tradetracker_extra_val = get_option("Tradetracker_extra");
				if(!empty($Tradetracker_extra_val)){
					if(in_array($key, $Tradetracker_extra_val, true)) {
						$value = str_replace("&#44;", "&#44; ", $value);
						$extraname .= "<tr><td width=\"50\"><b>".$key."</b></td><td>".$value."</td></tr>";
					}
				}
			}
			if($extraname != ""){
				$more = "<div class=\"".$storename."store-more\">
						<img src=\"".WP_PLUGIN_URL."/tradetracker-store/images/more.png\" style=\"border:0;\" border=\"0\" name=\"img".$i."\" width=\"11\" height=\"13\" border=\"0\" >
						<a href=\"#TTreadmore\" onClick=\"shoh('".$i."');\" >More info</a> 
						<div style=\"display: none;\" id=\"".$i."\" > 
							<table style=\"width:".$widthmore."px;\" width=\"".$widthmore."\">".$extraname."</table>
						</div>
					</div>";
			} else {
				$more = "<div class=\"".$storename."store-more\"></div>";
			}
		} else {
			$more = "<div class=\"".$storename."store-more\"></div>";
		}
		if($multiproductpage == "1" ){
			$producturl = "".get_option("Tradetracker_productpageURL")."?ttproductid=".$product->productID."";
			$urltarget ="";
		} else {
			$producturl = $product->productURL;
			$urltarget ="target=\"_blank\"";
		}
		if($uselightbox==1){
			$image = $product->imageURL;
			$target = "";	
			$rel = "rel=\"lightbox[store]\"";
		} else {
			$image = $producturl;
			$target = $urltarget;
			$rel = "";
		}
		$productname = str_replace("&", "&amp;", $product->name);
		$productdescription = str_replace("&", "&amp;", $product->description);
		if(get_option("Tradetracker_currency")=="1") {
			$array = get_option("Tradetracker_newcur");
			$key = $product->currency; 
			$currency = $array[$key]; 
		} else {
			$currency = $product->currency;
		}
		if(get_option("Tradetracker_currencyloc")=="1") {
			$price = $product->price." ".$currency;
		}else {
			$price = $currency." ".$product->price;
		}
		if($product->imageURL==""){
			$imageURL = WP_PLUGIN_URL."/tradetracker-store/images/No_image.png";
		} else {
			$imageURL = $product->imageURL;
		}
		if (ini_get('allow_url_fopen') == true) {
			//$size = @getimagesize($imageURL);
			//$sizes = imageSize($size);
		} else {
			$sizes = "";
		}
		$storeitems .= "<div class=\"".$storename."store-outerbox\">
				<div class=\"".$storename."store-titel\">
					".$productname."
				</div>			
				<div class=\"".$storename."store-image\">
					<a href=\"".$image."\" ".$rel." ".$target.">
						<img src=\"".$imageURL."\" alt=\"".$productname."\" title=\"".$productname."\" ".$sizes." style=\"max-width:".$width."px;max-height:180px;width:auto;height:auto;\"/>
					</a>
				</div>
				<div class=\"".$storename."store-footer\">
					<div class=\"".$storename."store-description\">
						".$productdescription."
					</div>
					".$more."
					<div class=\"".$storename."buttons\">
						<a href=\"".$producturl."\" class=\"regular\" ".$urltarget." title=\"".$productname."\">
							".$buynow."
						</a>
					</div>
					<div class=\"".$storename."store-price\">
						<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
							<tr>
								<td style=\"border: 1px none;\" class=\"euros\">
									".$price."
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>";
	$i++;
	}
	$storeitems .= "<div class=\"cleared\"></div>";
	$storeitems .= "<div class=\"ttstorelink\"><a target=\"_blank\" href=\"http://wpaffiliatefeed.com\">TradeTracker wordpress plugin</a></div>";
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
	return store_items(1, 0, 0);
}
	
function display_store_items()
{
	return store_items(2, 0, 0);
}
add_shortcode('display_multi', 'display_multi_items_short');
function display_multi_items_short($store)
{
	extract(shortcode_atts(array("store" => '0'), $store));
	return store_items(1, $store, 0);
}
function display_multi_items($store)
{
	return store_items(2, $store, 0);
}
add_shortcode('display_search', 'display_search_items_short');
function display_search_items_short()
{
	return store_items(1, 1, 1);
}
function display_search_items()
{
	return store_items(2, 1, 1);
} 
?>