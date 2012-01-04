<?php
function tradetracker_store_search() {
	if (!current_user_can('manage_options'))
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
ttstoreheader();
global $wpdb;
	$hidden_field_name = 'mt_submit_hidden';
	$Tradetracker_searchlayout_name = 'Tradetracker_searchlayout';
	$Tradetracker_searchlayout_field_name = 'Tradetracker_searchlayout';
	$Tradetracker_searchlayout_val = get_option( $Tradetracker_searchlayout_name );
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		$Tradetracker_searchlayout_val = $_POST[ $Tradetracker_searchlayout_field_name ];
		if ( get_option("Tradetracker_searchlayout")  != $Tradetracker_searchlayout_val) {
			update_option( $Tradetracker_searchlayout_name, $Tradetracker_searchlayout_val );
		}
?>
		<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php
	}
?>

<style type="text/css" media="screen">
.info {
		border-bottom: 1px dotted #666;
		cursor: help;
	}

</style>
<div class="wrap">
<?php 	echo "<h2>" . __( 'Tradetracker Store Setup', 'menu-test' ) . "</h2>"; ?>
<div class="plugindiv">

<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
		<?php if ( get_option("Tradetracker_statsdash") == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-search#tab7" class="active">Search</a></li>
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

<div id="tab7" class="tabset_content">
   <h2 class="tabset_label">Search Setings</h2>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<table width="700">

	<tr>
		<td>
			<label for="tradetrackerwidth" title="Use the same settings as used for this store." class="info">
				<?php _e("Store:", 'tradetracker-multilayout' ); ?>
			</label> 
		</td>
		<td>
			<select width="200" style="width: 200px" name="<?php echo $Tradetracker_searchlayout_field_name; ?>">
<?php

		$tablelayout = PRO_TABLE_PREFIX."multi";
		$layout=$wpdb->get_results("SELECT id, multiname FROM ".$tablelayout."");
		foreach ($layout as $layout_val){

			if($layout_val->id == get_option("Tradetracker_searchlayout")) {
				echo "<option selected=\"selected\" value=\"".$layout_val->id."\">$layout_val->multiname</option>";
			} else {
				echo "<option value=\"".$layout_val->id."\">$layout_val->multiname</option>";
			}
		}
		
?>
			</select>		
		</td>
	</tr>


</table>
<hr />

	<p class="submit">
	<b>Always save changes before pressing next.</b><br>
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /> 
		<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-overview'">
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help8'">
	</p>


</form>

</div>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<?php news(); ?>
 	</div>
</div></div>
<?php
}
?>