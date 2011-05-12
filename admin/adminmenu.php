<?php
add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
	global $wpdb;
	add_menu_page('Tradetracker Store', 'Tt Store', 'manage_options', 'tradetracker-shop', 'tradetracker_store_setup');
	$tabs = add_submenu_page('tradetracker-shop', 'Tradetracker Store setup', 'Tt Store Setup', 'manage_options', 'tradetracker-shop', 'tradetracker_store_setup');
	if (get_option( Tradetracker_settings )==1){
		$tabs1 = add_submenu_page('tradetracker-shop', 'Tradetracker Store settings', 'Tt Store Settings', 'manage_options', 'tradetracker-shop-settings', 'tradetracker_store_options');
		$mypage = add_submenu_page('tradetracker-shop', 'Tradetracker Store Items', 'Tt Store Items', 'manage_options', 'tradetracker-shop-items', 'adminstore_items');
		$tabs2 = add_submenu_page('tradetracker-shop', 'Tradetracker Store Overview', 'Tt Store Overview', 'manage_options', 'tradetracker-shop-overview', 'tradetracker_store_overview');
		$tabs3 = add_submenu_page('tradetracker-shop', 'Tradetracker Store Feedback', 'Tt Store Feedback', 'manage_options', 'tradetracker-shop-feedback', 'tradetracker_store_feedback');
		$tabs4 = add_submenu_page('tradetracker-shop', 'Tradetracker Store help', 'Tt Store Help', 'manage_options', 'tradetracker-shop-help', 'tradetracker_store_help');
	}
	if (get_option( Tradetracker_settings )==2){
		$tabs5 = add_submenu_page('tradetracker-shop', 'Tradetracker Store settings', 'Tt Store Settings', 'manage_options', 'tradetracker-shop-settings', 'tradetracker_store_options');
		if(class_exists('SoapClient')){
			if (get_option( Tradetracker_statsdash )==1){
				$tabs6 = add_submenu_page('tradetracker-shop', 'Tradetracker Store Stats', 'Tt Store Stats', 'manage_options', 'tradetracker-shop-stats', 'tradetracker_store_stats');
			}
		}
		$mylayout =  add_submenu_page('tradetracker-shop', 'Tradetracker Store layout', 'Tt Store Layout', 'manage_options', 'tradetracker-shop-layout', 'tradetracker_store_layout');
		$tabs7 = add_submenu_page('tradetracker-shop', 'Tradetracker Store Multi', 'Tt Store Multi', 'manage_options', 'tradetracker-shop-multi', 'tradetracker_store_multi');
		$mypage = add_submenu_page('tradetracker-shop', 'Tradetracker Store Items', 'Tt Store Items', 'manage_options', 'tradetracker-shop-multiitems', 'adminstore_multiitems');
		$tabs8 = add_submenu_page('tradetracker-shop', 'Tradetracker Store Overview', 'Tt Store Overview', 'manage_options', 'tradetracker-shop-overview', 'tradetracker_store_overview');
		$tabs9 = add_submenu_page('tradetracker-shop', 'Tradetracker Store Feedback', 'Tt Store Feedback', 'manage_options', 'tradetracker-shop-feedback', 'tradetracker_store_feedback');
		$tabs10 = add_submenu_page('tradetracker-shop', 'Tradetracker Store help', 'Tt Store Help', 'manage_options', 'tradetracker-shop-help', 'tradetracker_store_help');
		if (get_option( Tradetracker_stores )>1){
			add_submenu_page('tradetracker-shop', 'Tradetracker Multi', 'Tt Store Multi', 'manage_options', 'tradetracker-shop-multi', 'tradetracker_store_multi');
		}
	}
	add_action( "admin_print_scripts-$mypage", 'ozh_loadjs_admin_head' );
	add_action( "admin_print_scripts-$mylayout", 'ozh_loadcss_admin_head' );
	add_action( "admin_print_scripts-$tabs", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$tabs1", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$mypage", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$tabs2", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$tabs3", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$tabs4", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$tabs5", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$mylayout", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$tabs6", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$tabs7", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$tabs8", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$tabs9", 'tabs_admin_head' );
	add_action( "admin_print_scripts-$tabs10", 'tabs_admin_head' );
}

function ozh_loadjs_admin_head() {
	wp_enqueue_script('loadjs', WP_PLUGIN_URL .'/tradetracker-store/js/jquery.js');
	wp_enqueue_script('loadjs1', WP_PLUGIN_URL .'/tradetracker-store/js/main.js');
}
function tabs_admin_head() {
	wp_enqueue_script('loadjstab', WP_PLUGIN_URL .'/tradetracker-store/js/addclasskillclass.js');
	wp_enqueue_script('loadjstab1', WP_PLUGIN_URL .'/tradetracker-store/js/attachevent.js');
	wp_enqueue_script('loadjstab2', WP_PLUGIN_URL .'/tradetracker-store/js/addcss.js');
	wp_enqueue_script('loadjstab3', WP_PLUGIN_URL .'/tradetracker-store/js/tabtastic.js');
	echo "<link rel='stylesheet' href='".WP_PLUGIN_URL ."/tradetracker-store/js/tabtastic.css' type='text/css' />\n";
}
function ozh_loadcss_admin_head() {
	echo "<link rel='stylesheet' href='".WP_PLUGIN_URL ."/tradetracker-store/store.css' type='text/css' />\n";
}

function tradetracker_store_help() {
?>

	<?php if (get_option(Tradetracker_settings)==1){ ?>
<h2>Basic Settings help:</h2>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
   <li><a href="admin.php?page=tradetracker-shop-items#tab3">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab4">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab5">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab6" class="active">Help</a></li>
</ul>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<iframe width=200 height=800 frameborder="0" src="http://debestekleurplaten.nl/tradetracker-store/news.php"></iframe>
 	</div>
<div id="tab6" class="tabset_content">
   <h2 class="tabset_label">Help</h2>
<?php } if (get_option(Tradetracker_settings)==2){ ?>
<h2>Advanced Settings help:</h2>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
		<?php if ( get_option( Tradetracker_statsdash ) == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab7">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab8">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab9" class="active">Help</a></li>
</ul>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<iframe width=200 height=800 frameborder="0" src="http://debestekleurplaten.nl/tradetracker-store/news.php"></iframe>
 	</div>
<div id="tab9" class="tabset_content">
   <h2 class="tabset_label">Help</h2>
	<?php } ?>


<h2>Select the tab you would like to get help on below:</h2>
<?php if (get_option(Tradetracker_settings)==1){ ?>
<ul class="tabset_tabs">
   <li><a href="#help1" class="active">Setup</a></li>
   <li><a href="#help2">Registration</a></li>
   <li><a href="#help3">Settings</a></li>
   <li><a href="#help4">Items</a></li>
   <li><a href="#help5">Overview</a></li>
   <li><a href="#help6">Feedback</a></li>
</ul>
<?php } ?>
<?php if (get_option(Tradetracker_settings)==2){ ?>
<ul class="tabset_tabs">
   <li><a href="#help1" class="active">Setup</a></li>
   <li><a href="#help2">Registration</a></li>
   <li><a href="#help3">Settings</a></li>
   <li><a href="#help4">Stats</a></li>
   <li><a href="#help5">Layout</a></li>
   <li><a href="#help6">Store</a></li>
   <li><a href="#help7">Items</a></li>
   <li><a href="#help8">Overview</a></li>
   <li><a href="#help9">Feedback</a></li>
</ul>

<?php } ?>
<div id="help1" class="tabset_content1">
   <h2 class="tabset_label">Setup</h2>
<h2>Setup:</h2>
<p>
Select what setup you want. Do you want to use the <b>Basic</b> version or the <b>Advanced</b> version
<p><b>Basic Version</b>:
<br>As the name says this is the Basic version of the plugin with only the basic settings to put a product feed on your site.
<br>These features are possible in the Basic version:
<ul>
<li>- Add 1 XML feed to your site
<li>- Select how many items the plugin should randomly select from the feed
<li>- Select which specific items you would like to show on the site
<li>- Overview of the selected options
</ul>
<p>
<b>Advanced Version</b>:
<br>In the Advanced version there are a lot more possibilities then in the Basic version. This is to give the website owner more control over the plugin.
<br>These features are possible in the Advanced version:
<ul>
<li>- All the features from the basic version
<li>- Change how the items are shown on the site
<li>- Create several stores using 1 XML file with each having different layout settings
</ul>
</div>

<div id="help2" class="tabset_content1">
   <h2 class="tabset_label">Registration</h2>
<h2>Registration:</h2>
<p>First you will need to register with <a href="http://tc.tradetracker.net/?c=1065&amp;m=64910&amp;a=66047&amp;r=register&amp;u=" target="_blank">TradeTracker UK</a> or <a href=http://tc.tradetracker.net/?c=27&m=0&a=48684&r=register&u=%2Fnl%2Fpublisher%2Fregister target="_blank">Tradetracker NL</a>
<br>When your site is accepted for their affiliate program you will receive an email. Login to <a href="http://tc.tradetracker.net/?c=1065&amp;m=0&amp;a=66047&amp;r=login&amp;u=%2Fgb%2Fpublisher%2Flogin" target="_blank">Tradetracker</a>
<p>Within Tradetracker you go to "Affiliatemanagement" and then "Campagnes". Here you can find a campaign for your site. When selecting a campaign make sure it has a product feed. Sign up to one of the campaigns with a product feed and wait till you are accepted (some will manually approve you and some will do so automatically so sometimes it can take a while)
<p>When accepted go to <a href="http://tc.tradetracker.net/?c=1065&amp;m=0&amp;a=66047&amp;r=feed&amp;u=https%3A%2F%2Faffiliate.tradetracker.com%2FaffiliateMaterial%2FproductFeed" target="_blank">the product feed page</a> and make sure you select these settings:
<ul>
<li> - On the right side choose "create url"
<li> - On the new screen you have the options for the product feed
<li> - <b>Product feed:</b> You can choose which site you want to place the product feed on
<li> - <b>Affiliatesite:</b> Select the campaign
<li> - <b>Output Type:</b> Make sure you select XML
<li> - <b>Coding:</b> Choose the iso-8859-1 option
</ul>
If you press "Generate" you will get a link. Use that at the Tradetracker XML option. If you can't find these steps, this <a href=http://www.youtube.com/watch?v=c149cIEJFLk>movie</a> can help. Just remember you need a XML and not a CSV. 
</div>
<div id="help3" class="tabset_content1">
   <h2 class="tabset_label">Settings:</h2>

<h2>Settings:</h2>
<p>This is the main settings screen for the plugin. Basically this is the place where you input your XML file and select how the items are shown.

<p><b>Tradetracker XML:</b><br> This is where you select which productfeed you are going to use. You will get the link to the XML file from Tradetracker. To find out how to get the XML file go to <a href="admin.php?page=tradetracker-shop-help#help2">here</a>.
<?php if (get_option(Tradetracker_settings)==2){ ?>
<p><b>Tradetracker update:</b><br> Here you can choose how often the plugin should update the XML feed in to the database. In most cases 24 hours would be ok. But if the affiliate program updates their XML feed every 10 days you can may as well use 240 hours to save bandwidth.
<p><b>Income stats on your Dashboard?:</b><br>If you want to see the income stats in your wordpress dashboard select this option. It will also give you an extra tab called <b>Stats</b> on your setup page where you can fill in all needed details for the stats.
<?php } ?>
<?php if (get_option(Tradetracker_settings)==1){ ?>
<p><b>Tradetracker productID:</b><br> Here you will see all the ID's from the items you selected on the Item Selection screen. You can use this field to delete all items you selected. If this field is filled the next option will be ignored.
<p><b>Amount of items to show:</b><br> Instead of selecting which items you want to show on the site you can show them randomly. If you fill in an amount here the plugin will show that amount of items randomly on the site. (this option will not be here when you selected products to show on the site)
<p><b>Use Lightbox:</b><br> If you want to use lightbox, this means when users click on the image of the product the image will be shows with a nice black border around it, you can enable it here. If it is disabled users will go the the product page instead.
<?php } ?>
</div>
<?php if (get_option(Tradetracker_settings)==1){ ?>
<div id="help4" class="tabset_content1">
   <h2 class="tabset_label">Items</h2>
<?php } ?>
<?php if (get_option(Tradetracker_settings)==2){ ?>
<div id="help7" class="tabset_content1">
   <h2 class="tabset_label">Items</h2>
<?php } ?>
	<h2>Item Selection:</h2>
<?php if (get_option(Tradetracker_settings)==2){ ?>
	<p>If you just go to the Items tab you will see all the created stores which you can select items for. You can do this by clicking on <b>Select Items</b> at the store you want to adjust.
<?php } ?>
	<p>In this screen you can select which items you want to show on your site. If you don't select any items it will randomly show items from this list. The amount of items it will randomly show is based on the amount you filled in in the settings screen. When you select items here it will only show those items. So if you select 30 items. It will show 30 items on 1 page.
	<p>On top you can select how many items you see on this selection page. That setting is only for this selection page and is not the amount of items shown on your actual site
	<p>If you hover your mouse over the product name you will see the image belonging to the item
	<p>You can sort the list by clicking on <b>ProductID</b>, <b>ProductName</b> and <b>Price</b>.
	<p>At the bottom you can browse between different pages by clicking on that page number. Dont forget to press Save Changes first before going to another page, otherwise you might loose your selection.
</div>

<?php if (get_option(Tradetracker_settings)==2){ ?>
<div id="help4" class="tabset_content1">
   <h2 class="tabset_label">Stats</h2>
	<h2>Statistics Settings:</h2>
	<p>This tab only shows up when you have selected to show the Stats on your wordpress dashboard on the <b>Settings</b> page. To be able to show the stats in your dashboard you need to fill out these details:
	<p><b>Customer ID:</b><br> You need to fill in the Customer ID you get from TradeTracker. You can find your customerID by clicking on the link next to this option called <b>Get Customer ID</b>>. When it is the first time you will need to enable webservices on that page.
	<p><b>Access Code:</b><br> You will find this code just below your customerid in Tradetracker
	<p><b>Site ID:</b><br> When you click on <b>Get SiteID</b> you will go to a page on Tradetracker showing all your registered sites. In front of the site you like to use you will find the ID number. Fill in that number in this field.
	<p><b>Timeframe of stats:</b><br>Here you can choose what timeframe you want to show on your dashboard. Most people will be using weekly or monthly stats. But if you have a lot of sales on a daily basis you can use the Day setting.
</div>
<?php } ?>
<?php if (get_option(Tradetracker_settings)==1){ ?>
<div id="help5" class="tabset_content1">
   <h2 class="tabset_label">Overview</h2>
<?php } ?>
<?php if (get_option(Tradetracker_settings)==2){ ?>
<div id="help8" class="tabset_content1">
   <h2 class="tabset_label">Overview</h2>
<?php } ?>
	<h2>Settings Overview:</h2>
	<p>On this page you will see the overview of the settings you have chosen. 
	<p><b>Selected XML File:</b><br>Here you can open the XML you provided. You can look in to the XML and see how it is built and also see if there are issues with the link you provided.
	<p><b>File size: </b><br>This will tell you how big the XML file is you are using. The bigger the file the more bandwidth it will use.
<?php if (get_option(Tradetracker_settings)==2){ ?>
	<p><b>Update Frequency: </b><br>Here you will see how often the XML file will be redownloaded to your hosting. Most companies on Tradetracker will tell you in there productfeed page how often they will update there productfeed.
	<p><b>Monthly bandwidth: </b><br>This gives you an estimate on the bandwidth the XML file import will use on a monthtly basis. This is basically the file size x (720/hours you filled in in the update frequency settings)
	<p><b>Stats in Dashboard: </b><br>If this says yes it will mean you can see the income statistics in your wordpress dashboard.
	<p><b>CustomerID:</b><br>The customer ID you have filled in in your statistic settings.
	<p><b>Access code:</b><br>The access code you have filled in in your statistic settings.
	<p><b>Site ID:</b><br>The Site ID you have filled in in your statistic settings.
	<p><b>Timeframe:</b><br>The Timeframe you selected in your statistic settings.
 	<p>Below this you will see the overview of all your created stores.
	<p><b>Store Name:</b><br>This is the name of the store you created.
	<p><b>Layout Name:</b><br>This is the name of the layout you will use in the store you created.
	<p><b>Amount of items visible at same time:</b><br> This is the amount of items that will be shown at a single time. Even if you have 50 items selected to show and you put this on 10 it will only show 10 random items from the 50 you selected.
	<p><b>Use Lightbox:</b><br>If you have selected to use the lighbox options you will see that here.
	<p><b>Items to show:</b><br>This is the list of items you selected to be shown on the website.
	<p>You will also see on this page how to use the store in your website. You have 2 possibilities to show the items on your site:
	<br><b>On a Page/Post</b>
	<br>When you create a page/post you can put the following on that page/post to show the store: <b>[display_multi store="x"]</b>
	<br>This will show the items from your productfeed on that page/post
	<p><b>In your theme</b>
	<br>To show the items in your theme you can use the following <b><?php echo htmlentities('<?php display_multi_items($store="x") ?>'); ?></b> anywhere in your theme.
<?php } ?>
<?php if (get_option(Tradetracker_settings)==1){ ?>
	<p><b>Monthly bandwidth: </b><br>This gives you an estimate on the bandwidth the XML file import will use on a monthtly basis. This is basicly the file size x 30 days. Cause the plugin collects the data from the XML on a 24 hours basis.
	<p><b>Amount of items:</b><br>This shows how many items will be shown on the site randomly. This will not be visible when you have selected which items needs to be showed on the site.
	<p><b>Items being shown:</b><br>This is the list of items you selected to be shown on the website.
	<p><b>Use Lightbox:</b><br>If you have selected to use the lighbox options you will see that here.
	<p>You will also see on this page how to use the store in your website. You have 2 possibility's to show the items on your site:
	<br><b>On a Page/Post</b>
	<br>When you create a page/post you can put the following on that page/post to show the store: <b>[display_store]</b>
	<br>This will show the items from your productfeed on that page/post
	<p><b>In your theme</b>
	<br>To show the items in your theme you can use the following <b><?php echo htmlentities('<?php display_store_items() ?>'); ?></b> anywhere in your theme.
<?php } ?>
</div>

<?php if (get_option(Tradetracker_settings)==2){ ?>
<div id="help5" class="tabset_content1">
   <h2 class="tabset_label">Layout</h2>
	<h2>Layout Settings:</h2>
	<p>This tab gives you the ability to adjust the way the items are shown on your site. You can create new layouts here or adjust old ones.
	<p>This tab consists of 2 parts. The top part gives you the abillity to fill in the settings and next to it, it will show what it would look like after you've saved the settings. On the bottom part you see already created layouts which you can adjust.
	<p><b>Name for Layout:</b><br>This is the name for the layout you are creating. You will also use this layoutname when creating the store and when you are selecting which layout you would like to use for your store.
	<p><b>Store Width:</b><br>Here you can choose how wide 1 item will be. Standard it will be 250 width.
	<p><b>Font:</b><br>Here you can select which font you would like to use for your store. Make sure you use <b>WebSafe Fonts</b> because then you know for sure everybody will be able to view it. The default font is <b>Verdana</b>
	<p><b>Title background color:</b><br>Here you can select which color you would like to use in the title background. You will always have to fill in a hex code with the # sign in front of it.
	<p><b>Image background color:</b><br>Here you can select which color you would like to use in the image background. You will always have to fill in a hex code with the # sign in front of it.
	<p><b>Footer background color:</b><br>Here you can select which color you would like to use in the footer background. You will always have to fill in a hex code with the # sign in front of it.
	<p><b>Font color:</b><br>Here you can select which color the font should be. You will always have to fill in a hex code with the # sign in front of it.
	<p>After you have pressed on the Create or Save button (depending on whether you have created a new one or are editing an existing one) you will see the changes on the right side in the example view.
	<p>You can press the <b>New</b> button if you want to get a new blank form.
	<p>Below the buttons you will see all the layouts you created with the chosen settings for them. Here you can press the <b>Edit</b> button if you want to edit them.
</div>

<?php } ?>
<?php if (get_option(Tradetracker_settings)==1){ ?>
<div id="help6" class="tabset_content1">
   <h2 class="tabset_label">Feedback</h2>
<?php } ?>
<?php if (get_option(Tradetracker_settings)==2){ ?>
<div id="help9" class="tabset_content1">
   <h2 class="tabset_label">Feedback</h2>
<?php } ?>
	<h2>Feedback:</h2>
	<p>You can use this form to give me feedback about the plugin. You can tell me if you miss certain features or when something is not working. You can also request to be added to the plugin news part with your site. Just remember the more information you give the easier it is for me to help you.
</div>

<?php if (get_option(Tradetracker_settings)==2){ ?>
<div id="help6" class="tabset_content1">
   <h2 class="tabset_label">Store</h2>
	<h2>Store Settings:</h2>
	<p>This tab gives you the ability to create different stores to show different items on different pages of your site.
	<p>This tab consists of 2 parts. The top part gives you the abillity to fill in the settings. On the bottom part you see already created stores which you can adjust.
	<p><b>Name for Store:</b><br>This is the name for the store you are creating. This is so you can easily recognise which store is which when you are selecting items for it.
	<p><b>Layout:</b><br>Here you can choose which layout settings you would like to use.
	<p><b>Amount of items:</b><br>This is the amount of items the plugin will show. It will select this amount of items from the product feed (or the items you selected) and show them randomly.
	<p><b>Selected Items:</b><br> Here you will see all the ID's from the items you selected on the Item Selection screen. You can use this field to delete all items you selected.
	<p><b>Use Lightbox:</b><br> If you want to use lightbox, (This enlarges the image once it has been clicked by a user and shows it with a nice black border around it.) you can enable it here. If it is disabled users will go to the product page instead.
	<p>You can press the <b>New</b> button if you want to get a new blank form
	<p>Below the buttons you will see all the stores you created. Here you can press the <b>Edit</b> button if you want to edit them or press <b>Select Items</b> to select items for this specific store.
</div>
<?php } ?>

</div>
<?php 
 
}

function tradetracker_store_feedback() {

	echo '<div class="wrap">';


if (isset($_REQUEST['email']))
//if "email" is filled out, send email
  {
  //send email
  $name = $_REQUEST['name'] ;
if($name == "") {
	$name = "TTStore user";
	}
  $email = $_REQUEST['email'] ;
if($email == "") {
	$email = get_option( admin_email );
	}
  $subject = $_REQUEST['subject'] ;
if($subject == "") {
	$subject = "Tradetracker Store Feedback";
	}
  $message = $_REQUEST['message']." 
message sent using TT-Store on: ".get_option( siteurl )."";
  mail( "robert.braam@gmail.com", "$subject",
  $message, "From: $name <$email>" );
  echo "<div class=\"updated\"><p><strong>Feedback has been send</strong></p></div>";
  }
else
//if "email" is not filled out, display the form
  {
?>
<h2>Ideas, comments or feedback?:</h2>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<iframe width=200 height=800 frameborder="0" src="http://debestekleurplaten.nl/tradetracker-store/news.php"></iframe>
 	</div>
	<?php if (get_option(Tradetracker_settings)==1){ ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
   <li><a href="admin.php?page=tradetracker-shop-items#tab3">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab4">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab5" class="active">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab6" class="redhelp">Help</a></li>
</ul>
<div id="tab5" class="tabset_content">
   <h2 class="tabset_label">Feedback</h2>
<?php } if (get_option(Tradetracker_settings)==2){ ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
		<?php if ( get_option( Tradetracker_statsdash ) == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab7">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab8" class="active">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab9" class="redhelp">Help</a></li>
</ul>
<div id="tab8" class="tabset_content">
   <h2 class="tabset_label">Feedback</h2>
	<?php } ?>
		<p>
		<table>
			<form method='post' action=''>
				<tr>
					<td>
						Name: 
					</td>
					<td align="right">
						<input name='name' type='text' />
					</td>
				</tr>
  				<tr>
					<td>
						Email: 
					</td>
					<td align="right">
						<input name='email' type='text' />
					</td>
				</tr>
  				<tr>
					<td>
						Subject:
					</td>
					<td align="right">
						<input name='subject' type='text' />
					</td>
				</tr>
  				<tr>
					<td colspan="2">
						Message:
					</td>
				</tr>
  				<tr>
					<td colspan="2">
						<textarea name='message' rows='15' cols='40'></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p class="submit">
							<input type="submit" name="Submit" class="button-primary" value="Send feedback" />
							<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help<?php if (get_option(Tradetracker_settings)==2){ echo "9"; } else { echo "6"; } ?>'">

						</p>
					</td>
				</tr>
  			</form>
		</table>
	</div></div>
<?php
  }
}

?>