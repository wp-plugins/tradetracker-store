<?php
function tradetracker_store_setup() {

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	ttstoreheader();
	global $wpdb;
	$pro_table_prefix=$wpdb->prefix.'tradetracker_';
	$table = PRO_TABLE_PREFIX."store";
	$tablemulti = PRO_TABLE_PREFIX."multi";
	// variables for the field and option names 
	$hidden_field_name = 'mt_submit_hidden';

	$Tradetracker_settings_name = 'Tradetracker_settings';
	$Tradetracker_settings_field_name = 'Tradetracker_settings';

	// Read in existing option value from database
	$Tradetracker_settings_val = get_option( $Tradetracker_settings_name );

	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		// Read their posted value

		$Tradetracker_settings_val = $_POST[ $Tradetracker_settings_field_name ];
       		// Save the posted value in the database

		if ( get_option("Tradetracker_settings")  != $Tradetracker_settings_val) {
			update_option( $Tradetracker_settings_name, $Tradetracker_settings_val );
		}

		// Put an settings updated message on the screen

?>
		<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

	}

	// Now display the settings editing screen
	echo '<div class="wrap">';
	$folder =  WP_PLUGIN_DIR . "/tradetracker-store/splits";
	if(!is_writable($folder)){
		$warning = __('Please make sure the directory '.$folder.'/ is writable else Tradetracker-Store will not function','ttstore' );
		add_action('admin_notices', create_function( '', "echo \"<div class='error'><p>$warning</p></div>\";" ) );
	}



	// header
	echo "<h2>" . __( 'Tradetracker Store Setup', 'menu-test' ) . "</h2>";
	// settings form

?>
<style type="text/css" media="screen">
.info {
		border-bottom: 1px dotted #666;
		cursor: help;
	}

</style>
<div class="plugindiv">
<?php if (empty($Tradetracker_settings_val)) { ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1" class="active">Setup</a></li>
</ul>
<?php } ?>

	<?php if ($Tradetracker_settings_val==1){ ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1" class="active">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
   <li><a href="admin.php?page=tradetracker-shop-items#tab3">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab4">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab5">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-premium#tab6" class="greenpremium">Premium</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab7" class="redhelp">Help</a></li>
</ul>
	<?php } if ($Tradetracker_settings_val==2){ ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1" class="active">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
		<?php if ( get_option("Tradetracker_statsdash") == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-search#tab7">Search</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab8">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab9">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-premium#tab10" class="greenpremium">Premium</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab11" class="redhelp">Help</a></li>
<?php 	$provider = get_option('tt_premium_function');
	foreach($provider as $providers) {
		if($providers == "productpage"){ 
			echo "<li><a href=\"admin.php?page=tradetracker-shop-productpage#tab12\">Product Page</a></li>"; 
		} 
	}
?>

</ul>
	<?php } ?>
<div id="tab1" class="tabset_content">
   <h2 class="tabset_label">Setup</h2>
<?php if (empty($Tradetracker_settings_val)) { ?>
<b>If you don't have a tradetracker account yet go to <a href="admin.php?page=tradetracker-shop-help#help2">the help pages</a> to see how to create one.</b>
<p>
<?php } ?>
<form name="form1" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<table>
		<tr>
			<td>
				<label for="tradetrackerxml" title="Choose the basic settings when you only want the basic settings, With advanced you have a lot more you can configure." class="info">
					<?php _e("Settings:", 'tradetracker-xml' ); ?>
				</label> 
			</td>
			<td>	<input type="radio" name="Tradetracker_settings" value="1" <?php if($Tradetracker_settings_val=="1"){ echo "checked"; } ?> />

			</td>
			<td>
				Basic (Input XML file, Select which items or how many items you would like to show)
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<input type="radio" name="Tradetracker_settings" value="2" <?php if($Tradetracker_settings_val=="2"){ echo "checked"; } ?> />
			</td>
			<td>
				Advanced (Same as Basic + stats, multistore support and layout adjustments)
			</td>
		</tr>
	</table>
	<hr />
	
	<p class="submit">
	<b>Always save changes before pressing next.</b><br>
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /> 
<?php if (!empty($Tradetracker_settings_val)) { ?>
		<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-settings'">
<?php } ?>
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help'">
	</p>

</form>
</div>

	<div id="sideblock" style="float:left;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<?php news(); ?>
 	</div>
</div>
</div>
<?php
}
?>