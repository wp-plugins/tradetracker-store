<?php
add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
	global $wpdb;
	add_menu_page('Tradetracker Store', 'Tt Store', 'manage_options', 'tradetracker-shop', 'tradetracker_store_setup');
	add_submenu_page('tradetracker-shop', 'Tradetracker Store setup', 'Tt Store Setup', 'manage_options', 'tradetracker-shop', 'tradetracker_store_setup');
	if (get_option( Tradetracker_settings )==1){
		add_submenu_page('tradetracker-shop', 'Tradetracker Store settings', 'Tt Store Settings', 'manage_options', 'tradetracker-shop-settings', 'tradetracker_store_options');
		$mypage = add_submenu_page('tradetracker-shop', 'Tradetracker Store Items', 'Tt Store Items', 'manage_options', 'tradetracker-shop-items', 'adminstore_items');
		add_submenu_page('tradetracker-shop', 'Tradetracker Store Overview', 'Tt Store Overview', 'manage_options', 'tradetracker-shop-overview', 'tradetracker_store_overview');
		add_submenu_page('tradetracker-shop', 'Tradetracker Store Feedback', 'Tt Store Feedback', 'manage_options', 'tradetracker-shop-feedback', 'tradetracker_store_feedback');
		add_submenu_page('tradetracker-shop', 'Tradetracker Store help', 'Tt Store Help', 'manage_options', 'tradetracker-shop-help', 'tradetracker_store_help');
	}
	if (get_option( Tradetracker_settings )==2){
		add_submenu_page('tradetracker-shop', 'Tradetracker Store settings', 'Tt Store Settings', 'manage_options', 'tradetracker-shop-settings', 'tradetracker_store_options');
		if(class_exists('SoapClient')){
			if (get_option( Tradetracker_statsdash )==1){
				add_submenu_page('tradetracker-shop', 'Tradetracker Store Stats', 'Tt Store Stats', 'manage_options', 'tradetracker-shop-stats', 'tradetracker_store_stats');
			}
		}
		$mylayout =  add_submenu_page('tradetracker-shop', 'Tradetracker Store layout', 'Tt Store Layout', 'manage_options', 'tradetracker-shop-layout', 'tradetracker_store_layout');
		add_submenu_page('tradetracker-shop', 'Tradetracker Store Multi', 'Tt Store Multi', 'manage_options', 'tradetracker-shop-multi', 'tradetracker_store_multi');
		$mypage = add_submenu_page('tradetracker-shop', 'Tradetracker Store Items', 'Tt Store Items', 'manage_options', 'tradetracker-shop-multiitems', 'adminstore_multiitems');
		add_submenu_page('tradetracker-shop', 'Tradetracker Store Overview', 'Tt Store Overview', 'manage_options', 'tradetracker-shop-overview', 'tradetracker_store_overview');
		add_submenu_page('tradetracker-shop', 'Tradetracker Store Feedback', 'Tt Store Feedback', 'manage_options', 'tradetracker-shop-feedback', 'tradetracker_store_feedback');
		add_submenu_page('tradetracker-shop', 'Tradetracker Store help', 'Tt Store Help', 'manage_options', 'tradetracker-shop-help', 'tradetracker_store_help');
		if (get_option( Tradetracker_stores )>1){
			add_submenu_page('tradetracker-shop', 'Tradetracker Multi', 'Tt Store Multi', 'manage_options', 'tradetracker-shop-multi', 'tradetracker_store_multi');
		}
	}
	add_action( "admin_print_scripts-$mypage", 'ozh_loadjs_admin_head' );
	add_action( "admin_print_scripts-$mylayout", 'ozh_loadcss_admin_head' );
}

function ozh_loadjs_admin_head() {
	wp_enqueue_script('loadjs', WP_PLUGIN_URL .'/tradetracker-store/js/jquery.js');
	wp_enqueue_script('loadjs1', WP_PLUGIN_URL .'/tradetracker-store/js/main.js');
}
function ozh_loadcss_admin_head() {
	echo "<link rel='stylesheet' href='".WP_PLUGIN_URL ."/tradetracker-store/store.css' type='text/css' />\n";
}

function tradetracker_store_help() {
?>

<h2>Tradetracker:</h2>
<p>
<ul>
<li>First you will need to register with <a href="http://tc.tradetracker.net/?c=1065&amp;m=64910&amp;a=66047&amp;r=register&amp;u=" target="_blank">TradeTracker UK</a>
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
<li>Use [display_store] in a page you created (make sure you do this in the HTML view)
</ul>
<h2>Extra Plugins:</h2>
<ul>
<li>Plugin also supports Lightbox. So if you don't have it yet i would advise to install <a href=http://wordpress.org/extend/plugins/wp-jquery-lightbox/>this plugin.</a>
</ul>
<?php 
 
}

function tradetracker_store_feedback() {

	echo '<div class="wrap">';
	if (get_option(Tradetracker_settings)==1){
		echo "<a href=\"admin.php?page=tradetracker-shop\">Basic Setup</a> 
			> 
			<a href=\"admin.php?page=tradetracker-shop-settings\">Basic Settings</a>
			> 
			<a href=\"admin.php?page=tradetracker-shop-items\">Basic Items Selection</a>
			>
			<a href=\"admin.php?page=tradetracker-shop-overview\">Overview</a>
			>
			<b><a href=\"admin.php?page=tradetracker-shop-feedback\">Feedback</a></b>";
		}
	if (get_option(Tradetracker_settings)==2){
		echo "<a href=\"admin.php?page=tradetracker-shop\">Setup</a> 
			> 
			<a href=\"admin.php?page=tradetracker-shop-settings\">Settings</a>";
		if ( get_option( Tradetracker_statsdash ) == 1 ) {
		echo " >
			<a href=\"admin.php?page=tradetracker-shop-stats\">Statistics</a>";
		}
		echo " >
			<a href=\"admin.php?page=tradetracker-shop-layout\">Layout</a>
			>
			<a href=\"admin.php?page=tradetracker-shop-multi\">Store Settings</a>
			>
			<a href=\"admin.php?page=tradetracker-shop-multiitem\">Item Selection</a>
			>
			<a href=\"admin.php?page=tradetracker-shop-overview\">Overview</a>
			>
			<b><a href=\"admin.php?page=tradetracker-shop-feedback\">Feedback</a></b>";
	}
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
?>
<h2>Ideas, comments or feedback?:</h2>
		<div id="sideblock" style="float:right;width:270px;margin-left:10px;border:1px;"> 
			<iframe width=270 height=800 frameborder="0" src="http://debestekleurplaten.nl/tradetracker-store/news.php"></iframe>
 		</div>
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
						</p>
					</td>
				</tr>
  			</form>
		</table>
	</div>
<?php
  }
}

?>