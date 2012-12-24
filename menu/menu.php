<?php
add_action('admin_menu', 'ttstore_menu');

function ttstore_menu() {
	add_menu_page('Tradetracker Store', 'TT Store', 'manage_options', 'tt-store', 'ttstore_options');
	$ttstoreadmincss = add_submenu_page('tt-store', 'Tradetracker Store setup', 'Setup', 'manage_options', 'tt-store', 'ttstore_options');
	add_submenu_page('tt-store', 'Tradetracker Store FAQ', 'FAQ', 'manage_options', 'tt-store-faq', 'ttstore_faq');
	add_submenu_page('tt-store', 'Tradetracker Store Releaselog', 'Releaselog', 'manage_options', 'tt-store-rllog', 'ttstore_rllog');
	add_submenu_page('tt-store', 'Tradetracker Store News', 'News', 'manage_options', 'tt-store-news', 'ttstore_news');
	add_action( "admin_print_scripts-".$ttstoreadmincss, 'ttstore_admin_css' );
}
function ttstore_admin_css() {
	echo "<link rel='stylesheet' href='".plugins_url( 'menu.css' , __FILE__ )."' type='text/css' />\n";
	wp_enqueue_script('loadjsttadmin', plugins_url( 'expand.js' , __FILE__ ));
	wp_enqueue_script('jquery');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
}

function ttstore_options(){
global $wpdb;
global $ttstorelayouttable;
global $ttstoremultitable;
global $menuarray;
global $foldercache;
global $ttstorexmltable;
?>
<?php $adminwidth = get_option("Tradetracker_adminwidth"); ?>

<style type="text/css" media="screen">
#ttstorebox { width:<?php echo $adminwidth; ?>px; }
#ttstoreboxtop {width: <?php echo $adminwidth-10; ?>px;}
#ttstoreboxoptions {width:<?php echo $adminwidth-10; ?>px;}
#ttstoreboxbottom {width: <?php echo $adminwidth-10; ?>px;}
</style>
<?php



if(isset($_GET['option'])){
	$option = $_GET['option'];
	if(!empty($option)){
		$option();
	}
}
$tradetracker_xml = $wpdb->get_var(  "SELECT COUNT(*) FROM $ttstorexmltable;"  ); if($tradetracker_xml>0) {$tradetracker_xml_done = "<div class=\"ttstore-filled\"><img src=\"".plugins_url( 'images/vinkje.png' , __FILE__ )."\" title=\"".__("Productfeed has been entered","ttstore")."\" alt=\"".__("Productfeed has been entered","ttstore")."\"></div>";} else {$tradetracker_xml_done = "<div class=\"ttstore-filled\"><img src=\"".plugins_url( 'images/kruisje.png' , __FILE__ )."\" title=\"".__("No productfeed has been entered","ttstore")."\" alt=\"".__("No productfeed has been entered","ttstore")."\"></div>";} 
$tradetracker_layoutcount = $wpdb->get_var(  "SELECT COUNT(*) FROM $ttstorelayouttable;"  ); if($tradetracker_layoutcount>="2") {$layout_done = "<div class=\"ttstore-filled\"><img src=\"".plugins_url( 'images/vinkje.png' , __FILE__ )."\" title=\"".__("You created at least one layout","ttstore")."\" alt=\"".__("You created at least one layout","ttstore")."\"></div>";} else {$layout_done = "<div class=\"ttstore-filled\"><img src=\"".plugins_url( 'images/kruisje.png' , __FILE__ )."\" title=\"".__("You did not create a layout yet. You can only use the basic layout now","ttstore")."\" alt=\"".__("You did not create a layout yet. You can only use the basic layout now","ttstore")."\"></div>";} 
$tradetracker_storecount = $wpdb->get_var(  "SELECT COUNT(*) FROM $ttstoremultitable;"  ); if($tradetracker_storecount>="2") {$store_done = "<div class=\"ttstore-filled\"><img src=\"".plugins_url( 'images/vinkje.png' , __FILE__ )."\" title=\"".__("You created at least one store","ttstore")."\" alt=\"".__("You created at least one store","ttstore")."\"></div>";} else {$store_done = "<div class=\"ttstore-filled\"><img src=\"".plugins_url( 'images/kruisje.png' , __FILE__ )."\" title=\"".__("You did not create a store yet. You can only use the basic store now","ttstore")."\" alt=\"".__("You did not create a store yet. You can only use the basic store now","ttstore")."\"></div>";} 
if(ttstoreerrordetect("yes")=="yes"){
	$debug_done = "<div class=\"ttstore-filled\"><img src=\"".plugins_url( 'images/kruisje.png' , __FILE__ )."\" title=\"".__("Errors in the plugin","ttstore")."\" alt=\"".__("Errors in the plugin","ttstore")."\"></div>";
} else {
	$debug_done = "<div class=\"ttstore-filled\"><img src=\"".plugins_url( 'images/vinkje.png' , __FILE__ )."\" title=\"".__("No errors in the plugin","ttstore")."\" alt=\"".__("No errors in the plugin","ttstore")."\"></div>";
}
if(get_option("Tradetracker_premiumupdate")==""){
	$premium_done = "<div class=\"ttstore-filled\"><img src=\"".plugins_url( 'images/kruisje.png' , __FILE__ )."\" title=\"".__("No premium content enabled","ttstore")."\" alt=\"".__("No premium content enabled","ttstore")."\"></div>";
} else {
	$premium_done = "<div class=\"ttstore-filled\"><img src=\"".plugins_url( 'images/vinkje.png' , __FILE__ )."\" title=\"".__("Premium content has been enabled","ttstore")."\" alt=\"".__("Premium content has been enabled","ttstore")."\"></div>";
}

$readmore = "";
$menuarray = array( array( 'Title' => __("Add/Edit XMLFeeds","ttstore"), 
                      'Image' => plugins_url( 'images/enhanced-distribution.png' , __FILE__ ),
                      'Pricecheck' => "Free", 
                      'Price' => __("Free","ttstore"), 
                      'Shortdesc' => __("Add new or edit existing XML feeds here.","ttstore"), 
                      'Longdesc' => __("<h3>Adding or editing XMLFeeds</h3><p>This will give you the following options:<br>- Adding several XML feeds. With <a href=\"admin.php?page=tt-store&option=premium\">premium content</a> you are even able to add XML feeds from other affiliatenetworks than TradeTracker<br>- Giving your XML Feed a name so you can recognize it<br>You need to register on <a href=\"http://tc.tradetracker.net/?c=1065&amp;m=64910&amp;a=66047&amp;r=register&amp;u=\" target=\"_blank\">TradeTracker UK</a> or <a href=\"http://tc.tradetracker.net/?c=27&amp;m=39673&amp;a=48684&amp;r=&amp;u=%2Fnl%2Fpublisher%2Fregister\" target=\"_blank\">Tradetracker NL</a></p>","ttstore"), 
                      'Name' => "xmlfeedmore",
                      'Link' => "xmlfeed",		
                      'Vink' => $tradetracker_xml_done
                    ),
               array( 'Title' => __("Change XMLFeed options","ttstore"), 
                      'Image' => plugins_url( 'images/xml-feed-options.png' , __FILE__ ),
                      'Pricecheck' => "Free", 
                      'Price' => __("Free","ttstore"), 
                      'Shortdesc' => __("Adjust XMLFeed options like import time and extra field selection.","ttstore"), 
                      'Longdesc' => __("<h3>Changing XML Options</h3><p>This will give you the following options:<br>- Select the extra fields you like to use<br>- What time the XML feed should update<br>- Adjust currency symbol<br>- Location of the currency</p>","ttstore"), 
                      'Name' => "xmloptionmore",
                      'Link' => "xmloption",		
                      'Vink' => ""
                    ),
               array( 'Title' => __("Add/Edit Layouts","ttstore"), 
                      'Image' => plugins_url( 'images/layout-settings.png' , __FILE__ ),
                      'Pricecheck' => "Free", 
                      'Price' => __("Free","ttstore"), 
                      'Shortdesc' => __("This will give you the abillity to add and edit the layout.","ttstore"), 
                      'Longdesc' => __("<h3>Adding or Editing Layouts</h3><p>You can adjust the following layout settings:<br>- Adjust the width of an item box<br>- Adjust the font<br>- Adjust the fontsize<br>- Adjust the background of the image, footer and title<br>- Adjust the border color<br>- Adjust the font color<br>- Adjust the button font color<br><strong>Important: The basic layout can never be editted</strong></p>","ttstore"), 
                      'Name' => "layout",
                      'Link' => "layout",		
                      'Vink' => $layout_done
                    ),
               array( 'Title' => __("Add/Edit Stores","ttstore"), 
                      'Image' => plugins_url( 'images/add-edit-stores.png' , __FILE__ ),
                      'Pricecheck' => "Free", 
                      'Price' => __("Free","ttstore"), 
                      'Shortdesc' => __("Here you can create different stores. This will give you the abbility to use different settings for every store.","ttstore"), 
                      'Longdesc' => __("<h3>Adding or Editing Stores</h3><p>You can adjust the following store settings:<br>- Adjust on which fields the items are sorted<br>- Select the layout to use for a store<br>- Select which feed you want to use for a store<br>- Select which categories you would like to show in the store<br>- Adjust the text on the button<br>- Choose the amount of items that should be shown<br>- Choose to use lightbox<br><strong>Important: The basic store can never be editted</strong></p>","ttstore"), 
                      'Name' => "store",
                      'Link' => "store",		
                      'Vink' => $store_done
                    ),
               array( 'Title' => __("Item Selection","ttstore"), 
                      'Image' => plugins_url( 'images/item-selection.png' , __FILE__ ),
                      'Pricecheck' => "Free", 
                      'Price' => __("Free","ttstore"), 
                      'Shortdesc' => __("Here you can select the items you would like to show.","ttstore"), 
                      'Longdesc' => __("<h3>Item selection</h3><p>This will give you the following options:<br>- Select all items you would like to show on the site.<br>- <strong>If you don't select any item it will display all items in the selected feed/category</strong></p>","ttstore"), 
                      'Name' => "itemselect",
                      'Link' => "itemselect",		
                      'Vink' => ""
                    ),
               array( 'Title' => __("Search Settings","ttstore"), 
                      'Image' => plugins_url( 'images/xml-feed-options.png' , __FILE__ ),
                      'Pricecheck' => "Free", 
                      'Price' => __("Free","ttstore"), 
                      'Shortdesc' => __("Here you can set the settings for the search results.","ttstore"), 
                      'Longdesc' => __("<h3>Search Settings</h3><p>This will show options on you wordpress search results page</p>","ttstore"), 
                      'Name' => "ttsearch",
                      'Link' => "ttsearch",		
                      'Vink' => ""
                    ),
               array( 'Title' => __("Plugin Settings","ttstore"), 
                      'Image' => plugins_url( 'images/xml-feed-options.png' , __FILE__ ),
                      'Pricecheck' => "Free", 
                      'Price' => __("Free","ttstore"), 
                      'Shortdesc' => __("Here you can set the settings for the plugin itself.","ttstore"), 
                      'Longdesc' => __("<h3>Plugin Settings</h3><p>This will give you the following options:<br>- Get an email when import fails<br>- Decide if you want to import extra fields. If you don't use extra fields it is better to disable them.<br>- Decide how high the admin menu can be<br>- Select which options should be removed when plugin is deactivated. (when you remove the plugin all settings will be also removed)</p>","ttstore"), 
                      'Name' => "pluginsettings",
                      'Link' => "pluginsettings",		
                      'Vink' => ""
                    ),
               array( 'Title' => __("Premium Addons","ttstore"), 
                      'Image' => plugins_url( 'images/Premium-addons.png' , __FILE__ ),
                      'Price' => __("4,99 each","ttstore"), 
                      'Shortdesc' => __("Here you can see what addons are available.","ttstore"), 
                      'Longdesc' => __("<h3>Premium Addons</h3><p>You can buy the following productfeed addons:<br>- Daisycon productfeed<br>- Zanox productfeed<br>- Cleafs productfeed<br>- Tradedoubler productfeed<br>- Paidonresult productfeed<br>- M4n productfeed<br>- Bol productfeed<br>- Belboon productfeed<br>- Affiliatewindow productfeed<br>- Avangate productfeed</p><br><p>You can buy the following function addons:<br>- Add a productpage</p>","ttstore"), 
                      'Name' => "premium",
                      'Link' => "premium",		
                      'Vink' => $premium_done
                    ),
               array( 'Title' => __("Debug","ttstore"), 
                      'Image' => plugins_url( 'images/debug.png' , __FILE__ ),
                      'Pricecheck' => "Free", 
                      'Price' => __("Free","ttstore"), 
                      'Shortdesc' => __("This will tell you if there are any errors in the plugin.","ttstore"), 
                      'Longdesc' => __("<h3>Debugging</h3><p>You can see if the following is working properly:<br>- Splits and Cache folder is writable<br>- Curl function enabled<br>- Simplexml function enabled<br>- wp_head enabled in your theme</p>","ttstore"), 
                      'Name' => "ttdebug",
                      'Link' => "ttdebug",		
                      'Vink' => $debug_done
                    )
             );
	$provider = get_option('tt_premium_function');
	if(!empty($provider)){
		foreach($provider as $providers) {
			if($providers == "productpage"){ 
				newmenupremium();
			}
		}
	}
$menuoptioncount = count($menuarray);
ttstoreheader();
echo "<div class=\"wrap\">";
echo "<div class=\"ttstore-adminmenu\">";
$i=0;
for ($row = 0; $row < $menuoptioncount; $row++) {
if(isset($menuarray[$row]["Pricecheck"]) && $menuarray[$row]["Pricecheck"]=="Free"){
			$price = "<div class=\"module-image\">
				<img align=\"right\" width=\"71\" height=\"45\" src=\"".$menuarray[$row]["Image"]."\">
				<p><span class=\"module-image-badge\">".$menuarray[$row]["Price"]."</span></p>
			</div>";

} else {
			$price = "<div class=\"module-image\" style=\"background-color: #e0b5a1 !important;\">
				<img align=\"right\" width=\"71\" height=\"45\" src=\"".$menuarray[$row]["Image"]."\">
				<p style=\"background-color: #d29576 !important;\"><span class=\"module-image-badge\">".$menuarray[$row]["Price"]."</span></p>
			</div>";
}
$learnmoretext = __("Learn More","ttstore");
$settingstext = __("Settings","ttstore");
echo "<div class=\"ttstore-module\">
		<h3>".$menuarray[$row]["Title"]."</h3>

		<div class=\"ttstore-module-description\">
			".$price."
			<p>".$menuarray[$row]["Shortdesc"]."</p>
		</div>
		<div class=\"ttstore-module-actions\">
			<INPUT type=\"button\" name=\"Learn More\" class=\"button-secondary\" value=\"".$learnmoretext."\" onclick=\"shoh('".$menuarray[$row]["Name"]."');\"> 
			<INPUT type=\"button\" name=\"Settings\" class=\"button-secondary\" value=\"".$settingstext."\" onclick=\"location.href='admin.php?page=tt-store&option=".$menuarray[$row]["Link"]."'\">
		</div>
		".$menuarray[$row]["Vink"]."
	</div>";
$readmore .= "<img src=\"".plugins_url( 'more.png' , __FILE__ )."\" style=\"border:0;\" border=\"0\" name=\"img".$menuarray[$row]["Name"]."\" width=\"0\" height=\"0\">
	<div style=\"display: none;\" id=\"".$menuarray[$row]["Name"]."\" class=\"ttstore-moreinfo\">
		".$menuarray[$row]["Longdesc"]."
	</div>";
	$i++;
	if($i=="3"){
		echo $readmore;
		$i=0;
		$readmore="";
	}
	
}
echo $readmore;
echo "</div>";


?>
<div class="plugin_news">
	<h3><?php _e("Donate","ttstore"); ?></h3>
	<p>
		<?php _e("This plugin is made in my spare time. If you really like this plugin and it helped to improve the income of your site and you are willing to show me some of the gratitude:","ttstore"); ?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="97K9JBA7Z2K7Q">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</p>
	<h3><?php _e("Like this on facebook","ttstore"); ?></h3>
	<p>
	<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FWpaffiliatefeed%2F203825303083826&amp;width=180&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:180px; height:258px;" ></iframe>
	</p>
	<h3><?php _e("Sites using this plugin","ttstore"); ?></h3>

	<ul>
<?php
	$site_dir = $foldercache.'sites.xml';
	if (!file_exists($site_dir)) {
		$site_dir = 'http://wpaffiliatefeed.com/tradetracker-store/sites.xml'; 
	} 
	$sites = file_get_contents($site_dir);
	$sites = simplexml_load_string($sites);
	if($sites != ""){
		foreach($sites as $site) // loop through our items
		{
		echo "<li><a href=\"".$site->siteadres."\" target=\"_blank\">".$site->sitenaam."</a>";		
		}
	}
?>
	</ul>

	<h3><?php _e("Your Site here?","ttstore"); ?></h3>
	<p>
		<?php _e("if you want Your site here please use Tt Store Feedback and let me know","ttstore"); ?>
	</p>
	<h3><?php _e("Rate the plugin","ttstore"); ?></h3>
	<p>
		<?php _e("if you like this plugin please go to <a href=\"http://wordpress.org/extend/plugins/tradetracker-store/\" target=\"_blank\">here</a> and give it a rating and vote for yes if the plugin works.","ttstore"); ?>
	</p>
	<h3><?php _e("News","ttstore"); ?></h3>
	<p>
<?php
	$news_dir = $foldercache.'news.xml';
	if (!file_exists($news_dir)) {
		$news_dir = 'http://wpaffiliatefeed.com/category/news/feed/'; 
	}
	$news = file_get_contents($news_dir);
	$news = simplexml_load_string($news);
	if($news != ""){
		foreach($news as $newsmsg) // loop through our items
		{
			echo "<strong><a href=\"".$newsmsg->item->link."\">".$newsmsg->item->title."</a></strong><br><strong>Posted: ".date("d M Y",strtotime($newsmsg->item->pubDate))."</strong><br>".$newsmsg->item->description."";
		}
	}
?>
	</p>
</div>
</div>
<?php
}
?>