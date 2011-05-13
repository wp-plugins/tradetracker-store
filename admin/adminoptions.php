<?php
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

	$Tradetracker_update_name = 'Tradetracker_update';
	$Tradetracker_update_field_name = 'Tradetracker_update';

	$Tradetracker_productid_name = 'Tradetracker_productid';
	$Tradetracker_productid_field_name = 'Tradetracker_productid';

	$Tradetracker_amount_name = 'Tradetracker_amount';
	$Tradetracker_amount_field_name = 'Tradetracker_amount';

	$Tradetracker_lightbox_name = 'Tradetracker_lightbox';
	$Tradetracker_lightbox_field_name = 'Tradetracker_lightbox';

	$Tradetracker_statsdash_name = 'Tradetracker_statsdash';
	$Tradetracker_statsdash_field_name = 'Tradetracker_statsdash';
	


	// Read in existing option value from database
	$Tradetracker_xml_val = get_option( $Tradetracker_xml_name );
	$Tradetracker_update_val = get_option( $Tradetracker_update_name );
	$Tradetracker_productid_val = get_option( $Tradetracker_productid_name );
	$Tradetracker_amount_val = get_option( $Tradetracker_amount_name );
	$Tradetracker_lightbox_val = get_option( $Tradetracker_lightbox_name );
	$Tradetracker_statsdash_val = get_option( $Tradetracker_statsdash_name );

	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
	if (get_option(Tradetracker_settings)==1){
	// Read their posted value
		$Tradetracker_xml_val = $_POST[ $Tradetracker_xml_field_name ];
		$Tradetracker_update_val = get_option( $Tradetracker_update_name );
		$Tradetracker_productid_val = $_POST[ $Tradetracker_productid_field_name ];
		$Tradetracker_amount_val = $_POST[ $Tradetracker_amount_field_name ];
		$Tradetracker_lightbox_val = $_POST[ $Tradetracker_lightbox_field_name ];
		$Tradetracker_statsdash_val = get_option( $Tradetracker_statsdash_name );
	}

	if (get_option(Tradetracker_settings)==2){
	// Read their posted value
		$Tradetracker_xml_val = $_POST[ $Tradetracker_xml_field_name ];
		$Tradetracker_update_val = $_POST[ $Tradetracker_update_field_name ];
		$Tradetracker_productid_val = get_option( $Tradetracker_productid_name );
		$Tradetracker_amount_val = get_option( $Tradetracker_amount_name );
		$Tradetracker_lightbox_val = get_option( $Tradetracker_lightbox_name );
		$Tradetracker_statsdash_val = $_POST[ $Tradetracker_statsdash_field_name ];
	}


        // Save the posted value in the database
		if ( get_option(Tradetracker_xml)  != $Tradetracker_xml_val) {
			$myFile = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml';
			$fh = fopen($myFile, 'w') or die("can't open file");
			fclose($fh);
			unlink($myFile);
			update_option( $Tradetracker_xml_name, $Tradetracker_xml_val );
		}
		if ( get_option(Tradetracker_update)  != $Tradetracker_update_val) {
			update_option( $Tradetracker_update_name, $Tradetracker_update_val );
		}
		if ( get_option(Tradetracker_productid)  != $Tradetracker_productid_val) {
			update_option( $Tradetracker_productid_name, $Tradetracker_productid_val );
		}
		if ( get_option(Tradetracker_amount)  != $Tradetracker_amount_val) {
			update_option( $Tradetracker_amount_name, $Tradetracker_amount_val );
		}	
		if ( get_option(Tradetracker_lightbox)  != $Tradetracker_lightbox_val) {
			update_option( $Tradetracker_lightbox_name, $Tradetracker_lightbox_val );
		}
		if ( get_option(Tradetracker_statsdash)  != $Tradetracker_statsdash_val) {
			update_option( $Tradetracker_statsdash_name, $Tradetracker_statsdash_val );
		}

        // Put an settings updated message on the screen

?>
		<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

	}

	// Now display the settings editing screen

	echo '<div class="wrap">';



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
	<?php if (get_option(Tradetracker_settings)==1){ ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2" class="active">Settings</a></li>
   <li><a href="admin.php?page=tradetracker-shop-items#tab3">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab4">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab5">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab6" class="redhelp">Help</a></li>
</ul>
	<?php } if (get_option(Tradetracker_settings)==2){ ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2" class="active">Settings</a></li>
		<?php if ( get_option( Tradetracker_statsdash ) == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab7">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab8">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab9" class="redhelp">Help</a></li>
</ul>
	<?php } ?>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<iframe width=200 height=800 frameborder="0" src="http://debestekleurplaten.nl/tradetracker-store/news.php"></iframe>
 	</div>
<div id="tab2" class="tabset_content">
   <h2 class="tabset_label">Setup</h2>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<table>
		<tr>
			<td>
				<label for="tradetrackerxml" title="Add the link to the XML file you've recieved from tradetracker here." class="info">
					<?php _e("Tradetracker XML:", 'tradetracker-xml' ); ?> 
				</label> 
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_xml_field_name; ?>" value="<?php echo $Tradetracker_xml_val; ?>" size="50"> 
				<a href="admin.php?page=tradetracker-shop-help#help2" target="_blank">Install Instructions</a>
			</td>
		</tr>
	<?php if (get_option( Tradetracker_settings )>=2){ ?>
		<tr>
			<td>
				<label for="tradetrackerupdate" title="How often should the store get the newest xml. Default is 24 hours." class="info">
					<?php _e("Tradetracker update:", 'tradetracker-update' ); ?> 
				</label> 
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_update_field_name; ?>" value="<?php echo $Tradetracker_update_val; ?>" size="5"> 
				Only use whole hours.
			</td>
		</tr>
	<?php } ?>
	<?php if (get_option( Tradetracker_settings )==1){ ?>
		<tr>
			<td colspan="2">
				<h2>Product Settings</h2>
			</td>
		</tr>

		<tr>
			<td>
				<label for="tradetrackerproductid" title="If you'd only like to show certain items fill in the productID here, seperated by a comma (for instance: 298,300,500 ). This will override the limit you've set below." class="info">
					<?php _e("Tradetracker productID:", 'tradetracker-xml' ); ?> 
				</label> 
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_productid_field_name; ?>" value="<?php echo $Tradetracker_productid_val; ?>" size="50"> 
				<a href="admin.php?page=tradetracker-shop-items" target="_blank">Find productID</a>
			</td>
		</tr>
		<?php if ($Tradetracker_productid_val==""){ ?>
		<tr>
			<td>
				<label for="tradetrackerxml" title="Choose the amount of items to show on the site. Default is 12." class="info">
					<?php _e("Amount of items to show:", 'tradetracker-amount' ); ?> 
				</label>
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_amount_field_name; ?>" value="<?php echo $Tradetracker_amount_val; ?>" size="5">
			</td>
		</tr>
		<?php } else {?>
			<input type="hidden" name="<?php echo $Tradetracker_amount_field_name; ?>" value="<?php echo $Tradetracker_amount_val; ?>">
		<?php } ?>


		<tr>
			<td>
				<label for="tradetrackerlightbox" title="Do you want to use lightbox for the images? You will need an extra plugin for that" class="info">
					<?php _e("Use Lightbox:", 'tradetracker-lightbox' ); ?> 
				</label>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_lightbox_field_name; ?>" <?php if($Tradetracker_lightbox_val==1) {echo "checked";} ?> value="1"> Yes (<a href="http://wordpress.org/extend/plugins/wp-jquery-lightbox/" target="_blank">You will need this plugin</a>)
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_lightbox_field_name; ?>" <?php if($Tradetracker_lightbox_val==0){echo "checked";} ?> value="0"> No
			</td>
		</tr>
		<?php } ?>
		<?php if (get_option( Tradetracker_settings )==2){ ?>
		<?php if(class_exists('SoapClient')){ ?>
		<tr>
			<td>
				<label for="tradetrackerstatsdash" title="Do you want to see the income statistics in your dashboard?" class="info">
					<?php _e("Income stats on your Dashboard?:", 'tradetracker-statsdash' ); ?> 
				</label>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_statsdash_field_name; ?>" <?php if($Tradetracker_statsdash_val==1) {echo "checked";} ?> value="1"> Yes 
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_statsdash_field_name; ?>" <?php if($Tradetracker_statsdash_val==0){echo "checked";} ?> value="0"> No
			</td>
		</tr>

		<?php }} ?>

	</table>
	<hr />

	<p class="submit">
	<b>Always save changes before pressing next.</b><br>
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /> 	
			<?php if (get_option( Tradetracker_settings )==1){ ?> 
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-items'"> 
			<?php } ?>
			<?php if ( get_option( Tradetracker_settings )==2){ ?> 
			<?php if ( $Tradetracker_statsdash_val==1 ){ ?> 
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-stats'">
			<?php } else { ?>
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-layout'"> 
			<?php }} ?>
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help3'">
	</p>

</form>
</div>
</div>
<?php 
}
?>