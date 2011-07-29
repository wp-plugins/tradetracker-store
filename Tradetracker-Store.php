<?php
/*
Plugin Name: Tradetracker-Store
Plugin URI: http://wordpress.org/extend/plugins/tradetracker-store/
Version: 2.1.9
Description: A Plugin that will add a TradeTracker affiliate feed to your site with several options to choose from.
Author: Robert Braam
Author URI: http://vannetti.nl
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

if (get_option(Tradetracker_settings)=="1"){
include('admin/basic/adminitems.php');
include('admin/basic/adminoverview.php');
}

if (get_option(Tradetracker_settings)=="2"){
include('admin/advanced/adminlayout.php');
include('admin/advanced/adminstats.php');
include('admin/advanced/adminmulti.php');
include('admin/advanced/adminmultiitems.php');
include('admin/advanced/adminoverview.php');
}
	$file = WP_PLUGIN_DIR . '/tradetracker-store/store.css';
	$file_directory = dirname($file);
	if(!is_writable($file_directory)){
		$warning = __('Please make sure the directory '.$file_directory.'/ is writable else Tradetracker-Store will not function','ttstore' );
		add_action('admin_notices', create_function( '', "echo \"<div class='error'><p>$warning</p></div>\";" ) );
	}

register_activation_hook(__FILE__,'tradetracker_store_install');
register_deactivation_hook(__FILE__ ,'tradetracker_store_uninstall');
add_action('wp_print_styles', 'add_my_stylesheet');
add_filter('plugin_action_links', 'TTstore_plugin_action_links', 10, 2);

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
		<li>if you really like this plugin and it helped to improve the income of your site please show the creator your graditude:
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
	<h3>Sites using this plugin</h3>
	<ul>
<?php
	$site_file = 'http://debestekleurplaten.nl/tradetracker-store/sites.xml';
	$sites = file_get_contents($site_file);
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
	$news_file = 'http://debestekleurplaten.nl/tradetracker-store/news.xml';
	$news = file_get_contents($news_file);
	$news = simplexml_load_string($news);
	foreach($news as $newsmsg) // loop through our items
	{
echo "<p><li><b>".$newsmsg->newsdate."</b><br>".$newsmsg->newsmessage."";
	}
?>
	</ul>
	</div>
	</div>
	</div>

<?php
}

function tradetracker_store_install()
{
    global $wpdb;
$table = PRO_TABLE_PREFIX."store";
if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {

    
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
	productID VARCHAR(25) NOT NULL,
        name VARCHAR(80) NOT NULL,
        imageURL VARCHAR(200) NOT NULL,
	productURL VARCHAR(1000) NOT NULL,
	price DECIMAL(5,2) NOT NULL,
	currency VARCHAR(10) NOT NULL,
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
	if ($Tradetracker_xml == null) 
	{
		echo "No XML filled in yet please change the settings first.";
	} else {
	$context = stream_context_create(array(
    'http' => array(
        'timeout' => 3      // Timeout in seconds
    )
	));
		$cache_time = 3600*$update; // 24 hours
		$cache_file = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml';
		$timedif = @(time() - filemtime($cache_file));
		if (file_exists($cache_file) && $timedif < $cache_time) 
		{
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
$tablestore = PRO_TABLE_PREFIX."store";
$storecount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".$tablestore.""));
if($storecount=="0"){
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
	if (get_option(versionbuynow) != "1"){
	$result=$wpdb->query("ALTER TABLE `".$tablemulti."` ADD `buynow` TEXT NOT NULL");
		update_option( versionbuynow, "1" );
	}

$tablelayout = PRO_TABLE_PREFIX."layout";
define('PRO_TABLE_PREFIX', $pro_table_prefix);
	if ($winkelvol=="0"){
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
		if( get_option( Tradetracker_buynow ) == "" ){
			$buynow= "Buy Item";
		} else {
			$buynow= get_option( Tradetracker_buynow );
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
		$multi=$wpdb->get_results("SELECT buynow, multiname, laywidth, layfont, laycolortitle, laycolorfooter, laycolorimagebg, laycolorfont, multiitems, multiamount, multilightbox FROM ".$tablemulti.",".$tablelayout." where ".$tablemulti.".multilayout=".$tablelayout.".id and ".$tablemulti.".id=".$winkelvol."");
		foreach ($multi as $multi_val){
			$Tradetracker_amount = $multi_val->multiamount;
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
			$storename = create_slug($multi_val->multiname);
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
		$productID = str_replace(",", "' or productID='", $productID);
		$visits=$wpdb->get_results("SELECT * FROM ".$table." where productID='".$productID."' ORDER BY RAND() ".$Tradetracker_amount_i."");
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
							".$buynow."
						</a>
					</div>
					<div class=\"store-price\">
						<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
							<tr>
								<td style=\"width:55px;height:20px;\" class=\"euros\">
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
	return store_items(1, 0);
}

function display_store_items()
{
	return store_items(2, 0);
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