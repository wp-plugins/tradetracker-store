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
	if (get_option(Tradetracker_settings)==1){
			
?>
	<h2>Overview of selected options:</h2>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
   <li><a href="admin.php?page=tradetracker-shop-items#tab3">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab4" class="active">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab5">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab6" class="redhelp">Help</a></li>
</ul>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<iframe width=200 height=800 frameborder="0" src="http://debestekleurplaten.nl/tradetracker-store/news.php"></iframe>
 	</div>
<div id="tab4" class="tabset_content">
   <h2 class="tabset_label">Overview</h2>
	<table width="700">
		<tr>
			<td width="150">
				Selected XML File:
			</td>
			<td rowspan="2">
				<a href="<?php echo get_option( Tradetracker_xml ); ?>">Click here to open XML</a>
			</td>

		</tr>
		<tr>
			<td>
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
			<td width="150">
				Monthly bandwidth:
			</td>
			<td>
				<?php $filename = WP_PLUGIN_DIR . '/tradetracker-store/cache.xml';	if (file_exists($filename)) {echo format_bytes(30*filesize($filename));} else { echo "Not calculated yet"; } ?>
			</td>

		</tr>
		<?php if (get_option( Tradetracker_productid )==""){ ?>
		<tr>
			<td width="150">
				Amount of items:
			</td>
			<td>
				<?php if (get_option( Tradetracker_amount )=="") { echo "12"; } else { echo get_option( Tradetracker_amount ); } ?>
			</td>

		</tr>
		<?php } else { ?>
		<tr>
			<td width="150">
				Items being shown:
			</td>
			<td width="550">
				<?php echo str_replace(",",", ", get_option( Tradetracker_productid )); ?>
			</td>

		</tr>
		<?php } ?>
		<tr>
			<td width="150">
				Use Lightbox:
			</td>
			<td>
				<?php if (get_option( Tradetracker_lightbox )==0 || get_option( Tradetracker_lightbox )=="") { echo "No"; } else { echo "Yes"; } ?>
			</td>

		</tr>
	</table>
	<hr>
	If you agree with this you can put <b>[display_store]</b> in any page or post you make. Or use <b>display_store_items()</b> in your theme files.
	<br>
	<hr>

			<?php if (get_option( Tradetracker_settings )==1){ ?> 
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-feedback'"> 
			<?php } ?>
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help5'">
</div>
<?php

}}
?>