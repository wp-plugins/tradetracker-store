<?php
/*
Plugin Name: Tradetracker-Store
Plugin URI: http://wordpress.org/extend/plugins/tradetracker-store/
Version: 0.8
Description: A Plugin that will add the functions for a TradeTracker store based on the affiliate feeds. Show it by using  display_store_items funtion in your theme or [display_store] in a page.
Author: Robert Braam
Author URI: http://vannetti.nl
*/

/* 
..--==[ All items needed to activate the script ]==--..
 */

global $wpdb;
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
	currency VARCHAR(3) NOT NULL,
        description text,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
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
    $wpdb->query($structure);  
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

function store_items()
{
$Tradetracker_xml = get_option( Tradetracker_xml );
if ($Tradetracker_xml == null) {
	return "No store activated yet";
} else {
	$cache_time = 3600*24; // 24 hours

	$cache_file = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml';
	$timedif = @(time() - filemtime($cache_file));
		if (file_exists($cache_file) && $timedif < $cache_time) {
		} else {
    			$string = file_get_contents($Tradetracker_xml);
    			if ($f = @fopen($cache_file, 'w')) {
        			fwrite ($f, $string, strlen($string));
        			fclose($f);
    			}
			fill_database();
		}
	return show_items();
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

function show_items()
{
$Tradetracker_amount = get_option( Tradetracker_amount );
if ($Tradetracker_amount == null) {
$Tradetracker_amount_i = 12; 
} else {
$Tradetracker_amount_i = $Tradetracker_amount; 
}
global $wpdb; 
$table = PRO_TABLE_PREFIX."store";

$Tradetracker_productid = get_option( Tradetracker_productid );
if ($Tradetracker_productid == null) {
$visits=$wpdb->get_results("SELECT * FROM ".$table." ORDER BY RAND() LIMIT $Tradetracker_amount_i");
} else {
$productID = get_option( Tradetracker_productid );
$productID = str_replace(",", " or productID=", $productID);
$visits=$wpdb->get_results("SELECT * FROM ".$table." where productID=".$productID."");
}
$storeitems = "";
foreach ($visits as $product){

$storeitems .= "<div class=\"store-outerbox\">
	 	<div class=\"store-titel\">
			".$product->name."
		</div>			
	<div class=\"store-image\">
		<a href=\"".$product->imageURL."\" rel=\"lightbox[store]\">
	<img src=\"".$product->imageURL."\" alt=\"".$product->name."\" title=\"".$product->name."\" style=\"max-width:180px;max-height:180px;\" /></a>
</div>
<div class=\"store-footer\">
<div class=\"store-description\">
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
return $storeitems;
}


add_shortcode('display_store', 'display_store_items');
function display_store_items()
{
return store_items();
}

function adminstore_items()
{
$Tradetracker_xml = get_option( Tradetracker_xml );
if ($Tradetracker_xml == null) {
	echo "No XML filled in yet please change the settings first.";
} else {
	$cache_time = 3600*24; // 24 hours

	$cache_file = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml';
	$timedif = @(time() - filemtime($cache_file));

		if (file_exists($cache_file) && $timedif < $cache_time) {
		} else {
    			$string = file_get_contents($Tradetracker_xml);
    			if ($f = @fopen($cache_file, 'w')) {
        			fwrite ($f, $string, strlen($string));
        			fclose($f);
    			}
			fill_database();
		}
	return adminshow_items();
	}
}

function adminshow_items()
{
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }
    if( isset($_POST['posted']) && $_POST['posted'] == 'Y' ) {
        // Read their posted value

        $Tradetracker_items = $_POST['item'];
	$Tradetracker_items = implode(",", $Tradetracker_items);
        // Save the posted value in the database


 if ( get_option(Tradetracker_productid)  != $Tradetracker_items) {
        update_option(Tradetracker_productid, $Tradetracker_items );
  }
echo "<div class=\"updated\"><p><strong>Settings Saved</strong></p></div>";	
}
if($_GET['order']==null){
$order = "name";
} else {
$order = $_GET['order'];
}
global $wpdb; 
$limit = $_GET['limit'];
$currentpage = $_GET['currentpage'];
if (!($limit)){
$limit = 100;} // Default results per-page.
if (!($currentpage)){
$currentpage = 0;} // Default page value.
$table = PRO_TABLE_PREFIX."store";
$countquery=$wpdb->get_results("SELECT * FROM ".$table."");
$numrows = $wpdb->num_rows;
$pages = intval($numrows/$limit); // Number of results pages.
if ($numrows%$limit) {
$pages++;} 
$current = ($currentpage/$limit) + 1;
if (($pages < 1) || ($pages == 0)) {
$total = 1;}
else {
$total = $pages;} 
$first = $currentpage + 1;
if (!((($currentpage + $limit) / $limit) >= $pages) && $pages != 1) {
$last = $currentpage + $limit;}
else{
$last = $numrows;}
?>
<table width="700" border="0">
 <tr>
  <td width="50%" align="left">
Showing products <b><?=$first?></b> - <b><?=$last?></b> of <b><?=$numrows?></b>
  </td>
  <td width="50%" align="right">
Page <b><?=$current?></b> of <b><?=$total?></b>
  </td>
 </tr>
 <tr>
  <td colspan="2" align="right">
Results per-page: <a href="admin.php?page=tradetracker-shop-items&order=<?php echo $order; ?>&currentpage=<?=$currentpage?>&limit=100">100</a> | <a href="admin.php?page=tradetracker-shop-items&order=<?php echo $order; ?>&currentpage=<?=$currentpage?>&limit=200">200</a> | <a href="admin.php?page=tradetracker-shop-items&order=<?php echo $order; ?>&currentpage=<?=$currentpage?>&limit=500">500</a> | <a href="admin.php?page=tradetracker-shop-items&order=<?php echo $order; ?>&currentpage=<?=$currentpage?>&limit=1000">1000</a>
  </td>
 </tr>
</table>
<?php
$visits=$wpdb->get_results("SELECT * FROM ".$table." ORDER BY ".$order." ASC LIMIT ".$currentpage.", ".$limit."");
echo "<table width=\"700\">";
echo "<tr><td width=\"100\">";
echo "<b><a href=\"admin.php?page=tradetracker-shop-items&order=productID\">ProductID</a></b>";
echo "</td><td width=\"480\">";
echo "<b><a href=\"admin.php?page=tradetracker-shop-items&order=name\">Product name</a></b>";
echo "</td><td width=\"50\">";
echo "<b><a href=\"admin.php?page=tradetracker-shop-items&order=price\">Price</a></b>";
echo "</td><td width=\"70\">";
echo "<b>Currency</b>";
echo "</td></tr>";
echo "<form name=\"form2\" method=\"post\" action=\"\">";
echo "<input type=\"hidden\" name=\"posted\" value=\"Y\">";
foreach ($visits as $product){
echo "<tr><td>";
$productID = get_option( Tradetracker_productid );
$productID = explode(",",$productID);
if(in_array($product->productID, $productID, true))
{
echo "<input type=\"checkbox\" checked=\"yes\" name=\"item[]\" value=".$product->productID." />";
} else {
echo "<input type=\"checkbox\" name=\"item[]\" value=".$product->productID." />";
}
echo 		$product->productID;
echo "</td><td>";
echo 		$product->name;
echo "</td><td>";
echo 		$product->price;
echo "</td><td>";
echo 		$product->currency;
echo "</td></tr>";

}
echo "</table>";
echo "<p class=\"submit\">";
echo "<input type=\"submit\" name=\"Submit\" class=\"button-primary\" value=\"Save Changes\" />";
echo "</p>";

echo "</form>";
echo "<table width=\"700\"><tr><td>";
if ($currentpage != 0) { // Don't show back link if current page is first page.
$back_page = $currentpage - $limit;
echo("<a href=\"admin.php?page=tradetracker-shop-items&order=$order&currentpage=$back_page&limit=$limit\">back</a>    \n");}

for ($i=1; $i <= $pages; $i++) // loop through each page and give link to it.
{
 $ppage = $limit*($i - 1);
 if ($ppage == $currentpage){
 echo("<b>$i</b> \n");} // If current page don't give link, just text.
 else{
 echo("<a href=\"admin.php?page=tradetracker-shop-items&order=$order&currentpage=$ppage&limit=$limit\">$i</a> \n");}
}

if (!((($currentpage+$limit) / $limit) >= $pages) && $pages != 1) { // If last page don't give next link.
$next_page = $currentpage + $limit;
echo("    <a href=\"admin.php?page=tradetracker-shop-items&order=$order&currentpage=$next_page&limit=$limit\">next</a>\n");}
echo "</td></tr></table>";
}

add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {

  add_menu_page('Tradetracker Store', 'Tt Store', 'manage_options', 'tradetracker-shop', 'tradetracker_store_options');
  add_submenu_page('tradetracker-shop', 'Tradetracker Store settings', 'Tt Store Settings', 'manage_options', 'tradetracker-shop', 'tradetracker_store_options');
  add_submenu_page('tradetracker-shop', 'Tradetracker Store help', 'Tt Store Help', 'manage_options', 'tradetracker-shop-help', 'tradetracker_store_help');
add_submenu_page('tradetracker-shop', 'Tradetracker Store Items', 'Tt Store Items', 'manage_options', 'tradetracker-shop-items', 'adminstore_items');
add_submenu_page('tradetracker-shop', 'Tradetracker Store Feedback', 'Tt Store Feedback', 'manage_options', 'tradetracker-shop-feedback', 'tradetracker_store_feedback');
if(class_exists('SoapClient')){
add_submenu_page('tradetracker-shop', 'Tradetracker Store Stats', 'Tt Store Stats', 'manage_options', 'tradetracker-shop-stats', 'tradetracker_store_stats');
}

}


function tradetracker_store_options() {

  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

$file = WP_PLUGIN_DIR . '/tradetracker-store/store.css';
$file_directory = dirname($file);
if(!class_exists('SoapClient')){
echo "<div class=\"updated\"><p><strong><a href=\"http://www.electrictoolbox.com/class-soapclient-not-found/\" target=\"_blank\">SoapClient</a> is not enabled on your hosting. Stats are disabled.</strong></p></div>"; }
if(is_writable($file_directory)){
} else {
echo "<div class=\"updated\"><p><strong>Please make sure the directory ".$file_directory."/ is writable.</strong></p></div>";
}

    // variables for the field and option names 
    $hidden_field_name = 'mt_submit_hidden';

    $Tradetracker_xml_name = 'Tradetracker_xml';
    $Tradetracker_xml_field_name = 'Tradetracker_xml';
    
    $Tradetracker_amount_name = 'Tradetracker_amount';
    $Tradetracker_amount_field_name = 'Tradetracker_amount';

    $Tradetracker_productid_name = 'Tradetracker_productid';
    $Tradetracker_productid_field_name = 'Tradetracker_productid';

    $Tradetracker_customerid_name = 'Tradetracker_customerid';
    $Tradetracker_customerid_field_name = 'Tradetracker_customerid';

    $Tradetracker_access_code_name = 'Tradetracker_access_code';
    $Tradetracker_access_code_field_name = 'Tradetracker_access_code';

    $Tradetracker_siteid_name = 'Tradetracker_siteid';
    $Tradetracker_siteid_field_name = 'Tradetracker_siteid';



    // Read in existing option value from database
    $Tradetracker_xml_val = get_option( $Tradetracker_xml_name );
    $Tradetracker_amount_val = get_option( $Tradetracker_amount_name );
    $Tradetracker_productid_val = get_option( $Tradetracker_productid_name );
    $Tradetracker_customerid_val = get_option( $Tradetracker_customerid_name );
    $Tradetracker_access_code_val = get_option( $Tradetracker_access_code_name );
    $Tradetracker_siteid_val = get_option( $Tradetracker_siteid_name );


    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value

        $Tradetracker_xml_val = $_POST[ $Tradetracker_xml_field_name ];
 	$Tradetracker_amount_val = $_POST[ $Tradetracker_amount_field_name ];
 	$Tradetracker_productid_val = $_POST[ $Tradetracker_productid_field_name ];
 	$Tradetracker_customerid_val = $_POST[ $Tradetracker_customerid_field_name ];
 	$Tradetracker_access_code_val = $_POST[ $Tradetracker_access_code_field_name ];
 	$Tradetracker_siteid_val = $_POST[ $Tradetracker_siteid_field_name ];

        // Save the posted value in the database


 if ( get_option(Tradetracker_amount)  != $Tradetracker_amount_val) {
        update_option( $Tradetracker_amount_name, $Tradetracker_amount_val );
  }	
 if ( get_option(Tradetracker_xml)  != $Tradetracker_xml_val) {
	$myFile = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml';
	$fh = fopen($myFile, 'w') or die("can't open file");
	fclose($fh);
	unlink($myFile);
        update_option( $Tradetracker_xml_name, $Tradetracker_xml_val );
  }
 if ( get_option(Tradetracker_productid)  != $Tradetracker_productid_val) {
        update_option( $Tradetracker_productid_name, $Tradetracker_productid_val );
  }
 if ( get_option(Tradetracker_customerid)  != $Tradetracker_customerid_val) {
        update_option( $Tradetracker_customerid_name, $Tradetracker_customerid_val );
  }
 if ( get_option(Tradetracker_access_code)  != $Tradetracker_access_code_val) {
        update_option( $Tradetracker_access_code_name, $Tradetracker_access_code_val );
  }
 if ( get_option(Tradetracker_siteid)  != $Tradetracker_siteid_val) {
        update_option( $Tradetracker_siteid_name, $Tradetracker_siteid_val );
  }

        // Put an settings updated message on the screen

?>
<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Tradetracker Store Settings', 'menu-test' ) . "</h2>";

    // settings form
    
    ?>
<style type="text/css" media="screen">
.info {
		border-bottom: 1px dotted #666;
		cursor: help;
	}
</style>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<table>
<tr><td><label for="tradetrackerxml" title="Add the link to the XML file you've recieved from tradetracker here." class="info"><?php _e("Tradetracker XML:", 'tradetracker-xml' ); ?> </label> 
</td><td>
<input type="text" name="<?php echo $Tradetracker_xml_field_name; ?>" value="<?php echo $Tradetracker_xml_val; ?>" size="50"> <a href="admin.php?page=tradetracker-shop-help" target="_blank">Installation Instructions</a>
</td></tr>
<tr><td><label for="tradetrackerproductid" title="If you'd only like to show certain items fill in the productID here, seperated by a comma (for instance: 298,300,500 ). This will override the limit you've set below." class="info"><?php _e("Tradetracker productID:", 'tradetracker-xml' ); ?> </label> 
</td><td>
<input type="text" name="<?php echo $Tradetracker_productid_field_name; ?>" value="<?php echo $Tradetracker_productid_val; ?>" size="50"> <a href="admin.php?page=tradetracker-shop-items" target="_blank">Find productID</a>
</td></tr>
<tr><td>
<label for="tradetrackerxml" title="Choose the amount of items to show on the site. Default is 12." class="info"><?php _e("Amount of items to show:", 'tradetracker-amount' ); ?> </label>
</td><td>
<input type="text" name="<?php echo $Tradetracker_amount_field_name; ?>" value="<?php echo $Tradetracker_amount_val; ?>" size="5">
</td></tr>
<?php
if(class_exists('SoapClient')){
?>
<tr><td colspan="2">
<h2>Settings for Statistics</h2>
</td></tr>

<tr><td>
<label for="tradetrackercustomerid" title="Fill in your customer ID. You will need to enable webservices in Tradetracker" class="info"><?php _e("Customer ID:", 'tradetracker-customerid' ); ?> </label>
</td><td>
<input type="text" name="<?php echo $Tradetracker_customerid_field_name; ?>" value="<?php echo $Tradetracker_customerid_val; ?>" size="5"> <a href="https://affiliate.tradetracker.com/webService" target="_blank">Get Customer ID</a>
</td></tr>
<tr><td>
<label for="tradetrackeraccess_code" title="Fill in your access code." class="info"><?php _e("Access code:", 'tradetracker-access_code' ); ?> </label>
</td><td>
<input type="text" name="<?php echo $Tradetracker_access_code_field_name; ?>" value="<?php echo $Tradetracker_access_code_val; ?>" size="50">
</td></tr>
<tr><td>
<label for="tradetrackersiteid" title="Fill in your Site ID." class="info"><?php _e("Site ID:", 'tradetracker-siteid' ); ?> </label>
</td><td>
<input type="text" name="<?php echo $Tradetracker_siteid_field_name; ?>" value="<?php echo $Tradetracker_siteid_val; ?>" size="5"> <a href="https://affiliate.tradetracker.com/customerSite/list" target="_blank">Get Site ID</a>
</td></tr>
<?php
} ?>
</table>
<hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>
<?php 
 
}
?>
<?php
function tradetracker_store_help() {
?>

<h2>Tradetracker:</h2>
<p>
<ul>
<li>First you will need to register with <a href="http://tc.tradetracker.net/?c=1065&amp;m=64910&amp;a=66047&amp;r=&amp;u=" target="_blank">TradeTracker UK</a>
<li>When your site is accepted for their affiliate program you will receive an email. 
<li>Login to <a href="http://tc.tradetracker.net/?c=1065&amp;m=0&amp;a=66047&amp;r=login&amp;u=%2Fgb%2Fpublisher%2Flogin" target="_blank">Tradetracker</a>
<li>Within Tradetracker you go to "Affiliatemanagement" and then "Campagnes". Here you can find a campaign for you site
<li>When selecting a campaign make sure it has a productfeed.
<li>Sign up to one of the campaigns with a product feed and wait till you are accepted (some will manually approve you and some will do so automatically)
<li>When accepted go to <a href="http://tc.tradetracker.net/?c=1065&amp;m=0&amp;a=66047&amp;r=feed&amp;u=https%3A%2F%2Faffiliate.tradetracker.com%2FaffiliateMaterial%2FproductFeed" target="_blank">the product feed page</a>
<li>On the right side choose "create url"
<li>On the new screen you have the options for the product feed
<li><b>Product feed:</b> You can choose which site you want to place the product feed on
<li><b>Affiliatesite:</b> Select the campaign
<li><b>Output Type:</b> Make sure you select XML
<li><b>Coding:</b> Choose the iso-8859-1 option
<li>If you press "Generate" you will get a link. Use that at the Tradetracker XML option.
<li>If you can't find these steps this <a href=http://www.youtube.com/watch?v=c149cIEJFLk>movie</a> can help. Just remember you need a XML and not a CSV. 
</ul>
<h2>Installation everywhere in your theme:</h2>
<ul>
<li>You can now use &lt;?php display_store_items(); ?&gt; anywhere in your theme.
</ul>
<h2>Installation using template:</h2>
<ul>
<li>Copy <?php echo WP_PLUGIN_DIR . '/tradetracker-store/'; ?>theme/store.php into your own theme folder
<li>Create a new Page in your wordpress admin menu and make sure you choose your new template (Store Template) as the template for the page.
<li>You do not need to add text, just to fill in the title. Save the page and you will have your store ready.
</ul>
<h2>Installation using a shortcode in your post or page:</h2>
<ul>
<li>Use [display_store] in a page you created
</ul>
<h2>Extra Plugins:</h2>
<ul>
<li>Plugin also supports Lightbox. So if you don't have it yet i would advise to install <a href=http://wordpress.org/extend/plugins/wp-jquery-lightbox/>this plugin.</a>
</ul>
<?php 
 
}

function tradetracker_store_feedback() {
if (isset($_REQUEST['email']))
//if "email" is filled out, send email
  {
  //send email
  $name = $_REQUEST['name'] ;
  $email = $_REQUEST['email'] ;
  $subject = $_REQUEST['subject'] ;
  $message = $_REQUEST['message'] ;
  mail( "robert.braam@gmail.com", "$subject",
  $message, "From: $name <$email>" );
  echo "<div class=\"updated\"><p><strong>Feedback has been send</strong></p></div>";
  }
else
//if "email" is not filled out, display the form
  {
  echo "<h2>Ideas,comments or feedback?:</h2><p><table><form method='post' action=''>
<tr><td>Name: </td><td align=\"right\"><input name='name' type='text' /></td></tr>
  <tr><td>Email: </td><td align=\"right\"><input name='email' type='text' /></td></tr>
  <tr><td>Subject:</td><td align=\"right\"> <input name='subject' type='text' /></td></tr>
  <tr><td colspan=\"2\">Message:</td></tr>
  <tr><td colspan=\"2\"><textarea name='message' rows='15' cols='40'></textarea></td></tr>
<tr><td colspan=\"2\"><p class=\"submit\">
<input type=\"submit\" name=\"Submit\" class=\"button-primary\" value=\"Send feedback\" />
</p></td></tr>
  </form></table>";
  }
}

function tradetracker_store_stats() {
$siteid = get_option( Tradetracker_siteid );
$customerid = get_option( Tradetracker_customerid );
$acces_code = get_option( Tradetracker_access_code );
if (get_option( Tradetracker_siteid ) == null || get_option( Tradetracker_customerid ) == null || get_option( Tradetracker_access_code ) == null){
echo "Please adjust your stats settings";
} else {
$client = new SoapClient('http://ws.tradetracker.com/soap/affiliate?wsdl');
$client->authenticate($customerid, ''.$acces_code.'');

$affiliateSiteID = $siteid;
$registrationDateFrom = ''.\Date('Y-m-d', strtotime("-30 days")).'';
$registrationDateTo = ''.Date("Y-m-d").'';

$options = array(
	'dateFrom' => ''.Date('Y-m-d', strtotime("-30 days")).'',
	'dateTo' => ''.Date("Y-m-d").'',
);
echo "Statistics for ".Date('Y-M-d', strtotime('-30 days'))." till ".Date('Y-M-d')."";
echo "<table width=\"900\">";
echo "<tr><td><b>Campaign</b></td><td><b>Views</b></td><td><b>Commission</b></td><td><b>Clicks</b></td><td><b>Commission</b></td><td><b>Leads</b></td><td><b>Commission</b></td><td><b>Sales</b></td><td><b>Commission</b></td><td><b>Total</b></td></tr>";
foreach ($client->getReportCampaign($affiliateSiteID, $options) as $report) {
	echo '<tr><td>' . $report->campaign->name . '</td>';
	echo '<td>' . $report->reportData->uniqueImpressionCount . '</td>';
	echo '<td>' . round($report->reportData->impressionCommission,2) . '</td>';
	echo '<td>' . $report->reportData->uniqueClickCount . '</td>';
	echo '<td>' . round($report->reportData->clickCommission,2) . '</td>';
	echo '<td>' . $report->reportData->leadCount . '</td>';
	echo '<td>' . round($report->reportData->leadCommission,2) . '</td>';
	echo '<td>' . $report->reportData->saleCount . '</td>';
	echo '<td>' . round($report->reportData->saleCommission,2) . '</td>';
	echo '<td>' . round($report->reportData->totalCommission,2) . '</td></tr>';

}
$report = $client->getReportAffiliateSite($affiliateSiteID, $options);

	echo '<tr><td><b>Total</b></td>';
	echo '<td>' . $report->uniqueImpressionCount . '</td>';
	echo '<td>' . round($report->impressionCommission,2) . '</td>';
	echo '<td>' . $report->uniqueClickCount . '</td>';
	echo '<td>' . round($report->clickCommission,2) . '</td>';
	echo '<td>' . $report->leadCount . '</td>';
	echo '<td>' . round($report->leadCommission,2) . '</td>';
	echo '<td>' . $report->saleCount . '</td>';
	echo '<td>' . round($report->saleCommission,2) . '</td>';
	echo '<td>' . round($report->totalCommission,2) . '</td></tr>';
echo "</table>";
}
}
?>