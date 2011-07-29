<?php
function format_bytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}

function tradetracker_store_overview() {
global $wpdb;
$pro_table_prefix=$wpdb->prefix.'tradetracker_';
$tablemulti = PRO_TABLE_PREFIX."multi";
$tablelayout = PRO_TABLE_PREFIX."layout";
define('PRO_TABLE_PREFIX', $pro_table_prefix);

	echo '<div class="wrap">';
	if (get_option(Tradetracker_settings)==2){
	
?>
	<h2>Overview of selected options:</h2>
<div class="plugindiv">
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
		<?php if ( get_option( Tradetracker_statsdash ) == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab7" class="active">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab8">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab9" class="redhelp">Help</a></li>
</ul>

<div id="tab7" class="tabset_content">
   <h2 class="tabset_label">Overview</h2>
	<table width="700">
		<tr>
			<td width="150">
				Selected XML File:
			</td>
			<td>
				<a href="<?php echo get_option( Tradetracker_xml ); ?>">Click here to open XML</a>
			</td>

		</tr>
		<tr>
			<td width="150">
				XML File size:
			</td>
			<td>
				<?php $filename = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml';	if (file_exists($filename)) {echo format_bytes(filesize($filename));} else { echo "Not calculated yet"; } ?>
			</td>

		</tr>
		<tr>
			<td>
				Update Frequency:
			</td>
			<td>
				<?php if (get_option( Tradetracker_update )=="") { echo "every 24 hours"; } else { echo "every ".get_option( Tradetracker_update ). " hours"; } ?>
			</td>
		</tr>
		<tr>
			<td width="150">
				Monthly bandwidth:
			</td>
			<td>
				<?php $filename = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml'; if (get_option( Tradetracker_update )=="") { $hoursplanned = "24"; } else { $hoursplanned = get_option( Tradetracker_update ); }	if (file_exists($filename)) {echo format_bytes((720/$hoursplanned)*filesize($filename));} else { echo "Not calculated yet"; } ?>
			</td>

		</tr>
	</table>
		<hr>
	<table width="700">
		<tr>
			<td width="150">
				Stats in Dashboard:
			</td>
			<td>
				<?php if (get_option( Tradetracker_statsdash )=="1") { echo "Yes"; } if (get_option( Tradetracker_statsdash )=="0" || get_option( Tradetracker_statsdash )=="") { echo "No"; } ?>
			</td>
		</tr>
		<?php if (get_option( Tradetracker_statsdash )=="1") { ?>

		<tr>
			<td width="150">
				CustomerID:
			</td>
			<td>
				<?php echo get_option( Tradetracker_customerid ); ?>
			</td>
		</tr>

		<tr>
			<td width="150">
				Access code:
			</td>
			<td>
				<?php echo get_option( Tradetracker_access_code ); ?>
			</td>
		</tr>

		<tr>
			<td width="150">
				Site ID:
			</td>
			<td>
				<?php echo get_option( Tradetracker_siteid ); ?>
			</td>
		</tr>

		<tr>
			<td width="150">
				Timeframe:
			</td>
			<td>
				<?php if (get_option( Tradetracker_statstime )=="1") { echo "Day"; } if (get_option( Tradetracker_statstime )=="2") { echo "Week"; } if (get_option( Tradetracker_statstime )=="3") { echo "Month"; } ?>
			</td>
		</tr>
		<?php } ?>
	</table>
	<hr>
	<h2>Overview of created stores:</h2>
<?php	
		
	$multi=$wpdb->get_results("SELECT ".$tablemulti.".id as multiid, buynow, layname, multiname, multilayout, multiitems, multiamount, multilightbox FROM ".$tablemulti.",".$tablelayout." where ".$tablemulti.".multilayout=".$tablelayout.".id");
	foreach ($multi as $multi_val){
?>
	<table width="700">
		<tr>
			<td width="150">
				Store Name:
			</td>
			<td>
				<?php echo $multi_val->multiname; ?>
			</td>
		</tr>
		<tr>
			<td width="150">
				Layout Name:
			</td>
			<td>
				<?php echo $multi_val->layname; ?>
			</td>
		</tr>
		<tr>
			<td width="150">
				Buy now text:
			</td>
			<td>
				<?php echo $multi_val->buynow; ?>
			</td>
		</tr>
		<tr>
			<td width="150" rowspan="2">
				Amount of items visible at same time:
			</td>
			<td>
				<?php echo $multi_val->multiamount; ?>
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
		</tr>
		<tr>
			<td width="150">
				Use Lightbox:
			</td>
			<td>
				<?php if ($multi_val->multilightbox==0 || $multi_val->multilightbox == "") { echo "No"; } else { echo "Yes"; } ?>
			</td>
		</tr>
		<tr>
			<td width="150">
				Items to show:
			</td>
			<td>
				<?php echo str_replace(",",", ", $multi_val->multiitems); ?>
			</td>
		</tr>
	</table>
	<hr>
	If you agree with this you can put <b>[display_multi store="<?php echo $multi_val->multiid; ?>"]</b> in any page or post you make. Or use <b>display_multi_items($store="<?php echo $multi_val->multiid; ?>")</b> in your theme files.
	<br>
	<hr>
<?php
	}
?>

			<?php if (get_option( Tradetracker_settings )==2){ ?> 
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-feedback'"> 
			<?php } ?>
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help8'">
<?php
}
?>
</div>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<?php news(); ?>
 	</div>
</div></div>

<?php
}
?>