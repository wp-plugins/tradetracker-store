<?php
/*
Plugin Name: Tradetracker-Store
Plugin URI: http://www.problogdesign.com/tag/wordpress/
Version: 0.1
Description: A Plugin that will add the functions for a TradeTracker store based on the affiliate feeds. Show it by using  display_store_items funtion in your theme.
Author: Robert Braam
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
        $myStyleUrl = WP_PLUGIN_URL . '/Tradetracker-Store/store.css';
        $myStyleFile = WP_PLUGIN_DIR . '/Tradetracker-Store/store.css';
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
	echo "No store active yet";
} else {
	$cache_time = 3600*24; // 24 hours

	$cache_file = WP_PLUGIN_DIR . '/Tradetracker-Store/cache.xml';
	$timedif = @(time() - filemtime($cache_file));

		if (file_exists($cache_file) && $timedif < $cache_time) {
    			$string = file_get_contents($cache_file);
			$products = simplexml_load_string($string);
			global $wpdb; 
			$table = PRO_TABLE_PREFIX."store";
			$visits=$wpdb->get_results("SELECT * FROM ".$table."");
			if(count($products) != $wpdb->num_rows){
			fill_database();
			}
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
			$cache_file = WP_PLUGIN_DIR . '/Tradetracker-Store/cache.xml';
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

foreach ($visits as $product){


echo "<div class=\"store-outerbox\">";
echo " 	<div class=\"store-titel\">";
echo 		$product->name;
echo "	</div>";			
echo "	<div class=\"store-image\">";
echo "		<a href=\"".$product->imageURL."\" rel=\"lightbox[store]\">";
echo "			<img src=\"".$product->imageURL."\" alt=\"".$product->name."\" title=\"".$product->name."\" style=\"max-width:180px;max-height:180px;\" />";
echo "		</a>";
echo "	</div>";
echo "	<div class=\"store-footer\">";
echo "		<div class=\"store-description\">";
echo 			$product->description;
echo "		</div>";
echo "		<div class=\"buttons\">";
echo "			<a href=\"".$product->productURL."\" class=\"regular\">";
echo "				Buy Item";
echo "			</a>";
echo "		</div>";
echo "		<div class=\"store-price\">";
echo "			<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
echo "				<tr>";
echo "					<td style=\"height:23px;\" class=\"euros\">";
echo $product->price." ".$product->currency;
echo "					</td>";
echo "				</tr>";
echo "			</table>";
echo "		</div>";
echo "	</div>";
echo "</div>";
}
}


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

	$cache_file = WP_PLUGIN_DIR . '/Tradetracker-Store/cache.xml';
	$timedif = @(time() - filemtime($cache_file));

		if (file_exists($cache_file) && $timedif < $cache_time) {
    			$string = file_get_contents($cache_file);
			$products = simplexml_load_string($string);
			global $wpdb; 
			$table = PRO_TABLE_PREFIX."store";
			$visits=$wpdb->get_results("SELECT * FROM ".$table."");
			if(count($products) != $wpdb->num_rows){
			fill_database();
			}
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
global $wpdb; 
$table = PRO_TABLE_PREFIX."store";

$visits=$wpdb->get_results("SELECT * FROM ".$table."");
echo "<table>";
echo "<tr><td>";
echo "<b>ProductID</b>";
echo "</td><td>";
echo "<b>Product name</b>";
echo "</td><td>";
echo "<b>Price</b>";
echo "</td><td>";
echo "<b>Currency</b>";
echo "</td></tr>";

foreach ($visits as $product){

echo "<tr><td>";
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
}

add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {

  add_menu_page('Tradetracker Store', 'Tt Store', 'manage_options', 'tradetracker-shop', 'tradetracker_store_options');
  add_submenu_page('tradetracker-shop', 'Tradetracker Store settings', 'Tt Store Settings', 'manage_options', 'tradetracker-shop', 'tradetracker_store_options');
  add_submenu_page('tradetracker-shop', 'Tradetracker Store help', 'Tt Store Help', 'manage_options', 'tradetracker-shop-help', 'tradetracker_store_help');
add_submenu_page('tradetracker-shop', 'Tradetracker Store Items', 'Tt Store Items', 'manage_options', 'tradetracker-shop-items', 'adminstore_items');

}

function tradetracker_store_options() {

  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

    // variables for the field and option names 
    $hidden_field_name = 'mt_submit_hidden';

    $Tradetracker_xml_name = 'Tradetracker_xml';
    $Tradetracker_xml_field_name = 'Tradetracker_xml';
    
    $Tradetracker_amount_name = 'Tradetracker_amount';
    $Tradetracker_amount_field_name = 'Tradetracker_amount';

    $Tradetracker_productid_name = 'Tradetracker_productid';
    $Tradetracker_productid_field_name = 'Tradetracker_productid';

    // Read in existing option value from database
    $Tradetracker_xml_val = get_option( $Tradetracker_xml_name );
    $Tradetracker_amount_val = get_option( $Tradetracker_amount_name );
    $Tradetracker_productid_val = get_option( $Tradetracker_productid_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value

        $Tradetracker_xml_val = $_POST[ $Tradetracker_xml_field_name ];
 	$Tradetracker_amount_val = $_POST[ $Tradetracker_amount_field_name ];
 	$Tradetracker_productid_val = $_POST[ $Tradetracker_productid_field_name ];

        // Save the posted value in the database


 if ( get_option(Tradetracker_amount)  != $Tradetracker_amount_val) {
        update_option( $Tradetracker_amount_name, $Tradetracker_amount_val );
  }	
 if ( get_option(Tradetracker_xml)  != $Tradetracker_xml_val) {
        update_option( $Tradetracker_xml_name, $Tradetracker_xml_val );
  }
 if ( get_option(Tradetracker_productid)  != $Tradetracker_productid_val) {
        update_option( $Tradetracker_productid_name, $Tradetracker_productid_val );
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
<tr><td><label for="tradetrackerxml" title="Add the link to the XML file you got from tradetracker here." class="info"><?php _e("Tradetracker XML:", 'tradetracker-xml' ); ?> </label> 
</td><td>
<input type="text" name="<?php echo $Tradetracker_xml_field_name; ?>" value="<?php echo $Tradetracker_xml_val; ?>" size="50"> <a href="admin.php?page=tradetracker-shop-help" target="_blank">Installation Instructions</a>
</td></tr>
<tr><td><label for="tradetrackerproductid" title="If you want to show only certain items fill the productID here seperated by a comma (for instance: 298,300,500 ). This will ignore the limit you set below." class="info"><?php _e("Tradetracker productID:", 'tradetracker-xml' ); ?> </label> 
</td><td>
<input type="text" name="<?php echo $Tradetracker_productid_field_name; ?>" value="<?php echo $Tradetracker_productid_val; ?>" size="50"> <a href="admin.php?page=tradetracker-shop-items" target="_blank">Find productID</a>
</td></tr>
<tr><td>
<label for="tradetrackerxml" title="Choose amount of items to show on the site. Default is 12." class="info"><?php _e("Amount of items to show:", 'tradetracker-amount' ); ?> </label>
</td><td>
<input type="text" name="<?php echo $Tradetracker_amount_field_name; ?>" value="<?php echo $Tradetracker_amount_val; ?>" size="5">
</td></tr>

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
<li>First you will need to register with <a href="http://tc.tradetracker.net/?c=27&amp;m=0&amp;a=48684&amp;r=wp-pluginregister&amp;u=%2Fgb%2Fpublisher%2Fregister" target="_blank">Tradetracker</a>
<li>When your site is accepted for their affiliate program you will receive an email. 
<li>Login to <a href="http://tc.tradetracker.net/?c=27&amp;m=0&amp;a=48684&amp;r=wp-pluginlogin&amp;u=%2Fgb%2Fpublisher%2Flogin" target="_blank">Tradetracker</a>
<li>Within Tradetracker you go to "Affiliatemanagement" and then "Campagnes". Here you can find a campaign for you site
<li>When selecting a campaign make sure it has a productfeed.
<li>Sign up to one of the campaigns with a product feed and wait till you are accepted (some will manually approve you and some will do so automatically)
<li>When accepted go to <a href="http://tc.tradetracker.net/?c=27&amp;m=0&amp;a=48684&amp;r=wp-pluginfeed&amp;u=https%3A%2F%2Faffiliate.tradetracker.com%2FaffiliateMaterial%2FproductFeed" target="_blank">the product feed page</a>
<li>On the right side choose "create url"
<li>On the new screen you have the options for the product feed
<li><b>Product feed:</b> You can choose which site you want to place the product feed on
<li><b>Affiliatesite:</b> Select the campaign
<li><b>Output Type:</b> Make sure you select XML
<li><b>Coding:</b> Choose the iso-8859-1 option
<li>If you press "Generate" you will get a link. Use that at the Tradetracker XML option.
</ul>
<h2>Installation everywhere in your theme:</h2>
<ul>
<li>You can now use &lt;?php display_store_items(); ?&gt; anywhere in your theme.
</ul>
<h2>Installation using template:</h2>
<ul>
<li>Copy <?php echo WP_PLUGIN_DIR . '/Tradetracker-Store/'; ?>theme/store.php into your own theme folder
<li>Create a new Page in your wordpress admin menu and make sure you choose your new template (Store Template) as the template for the page.
<li>You do not need to add text, just to fill in the title. Save the page and you will have your store ready.
</ul>
<?php 
 
}
?>