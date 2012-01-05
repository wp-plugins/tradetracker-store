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
ttstoreheader();
	echo '<div class="wrap">';
	if (get_option("Tradetracker_settings")==1){
			
?>
	<h2>Overview of selected options:</h2>
<div class="plugindiv">
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
   <li><a href="admin.php?page=tradetracker-shop-items#tab3">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab4" class="active">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab5">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-premium#tab6" class="greenpremium">Premium</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab7" class="redhelp">Help</a></li>
</ul>

<div id="tab4" class="tabset_content">
   <h2 class="tabset_label">Overview</h2>
	<table width="700">
		<tr>
			<td width="150">
				Selected XML File(s):
			</td>
			<td>
				<?php
					$file = explode(",", get_option("Tradetracker_xml"));
					$i="1";
					foreach($file as $filename) {
						echo "<a href=\"".$filename."\">Click here to open XML</a><br>";
					}
				?>
			</td>

		</tr>
		<tr>
			<td width="150">
				XML File size:
			</td>
			<td>
			<?php
				$folder =  WP_PLUGIN_DIR . "/tradetracker-store/splits";
				foreach(glob($folder."/*xml") as $filename) {
					$xmlsize = format_bytes(filesize($filename))+$xmlsize;
				}
				if ($xmlsize!="") {
					echo round($xmlsize/1024, 2)." MB";
				} else { 
					echo "Not calculated yet"; 
				} 
			?>
			</td>

		</tr>
		<tr>
			<td width="150">
				Monthly bandwidth:
			</td>
			<td>
				<?php 				
					if ($xmlsize!="") {
						echo round(format_bytes((720/24)*$xmlsize), 2)." MB";
					} else { 
						echo "Not calculated yet"; 
					}
				?>
			</td>

		</tr>		<?php if (get_option("Tradetracker_productid")==""){ ?>
		<tr>
			<td width="150">
				Amount of items:
			</td>
			<td>
				<?php if (get_option("Tradetracker_amount")=="") { echo "12"; } else { echo get_option("Tradetracker_amount"); } ?>
			</td>

		</tr>
		<?php } else { ?>
		<tr>
			<td width="150">
				Items being shown:
			</td>
			<td width="550">
				<?php echo str_replace(",",", ", get_option("Tradetracker_productid")); ?>
			</td>

		</tr>
		<?php } ?>
		<tr>
			<td width="150">
				Use Lightbox:
			</td>
			<td>
				<?php if (get_option("Tradetracker_lightbox")==0 || get_option("Tradetracker_lightbox")=="") { echo "No"; } else { echo "Yes"; } ?>
			</td>

		</tr>
	</table>
	<hr>
	If you agree with this you can put this in any page or post you make: [display_store]
	<br> Or use this in your theme files: display_store_items();
	<br>
	<hr>

			<?php if (get_option("Tradetracker_settings")==1){ ?> 
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-feedback'"> 
			<?php } ?>
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help5'">
</div>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<?php news(); ?>
 	</div>
</div>
<?php

}}
?>