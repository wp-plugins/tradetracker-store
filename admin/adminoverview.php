<?php
function tradetracker_store_overview() {
global $wpdb;
$pro_table_prefix=$wpdb->prefix.'tradetracker_';
$tablemulti = PRO_TABLE_PREFIX."multi";
$tablelayout = PRO_TABLE_PREFIX."layout";
define('PRO_TABLE_PREFIX', $pro_table_prefix);

	echo '<div class="wrap">';
	if (get_option(Tradetracker_settings)==1){
		echo "<a href=\"admin.php?page=tradetracker-shop\">Basic Setup</a> 
			> 
			<a href=\"admin.php?page=tradetracker-shop-settings\">Basic Settings</a>
			> 
			<a href=\"admin.php?page=tradetracker-shop-items\">Basic Items Selection</a>
			>
			<b><a href=\"admin.php?page=tradetracker-shop-overview\">Overview</a></b>
			>
			<a href=\"admin.php?page=tradetracker-shop-feedback\">Feedback</a>";
		
?>
	<h2>Overview of selected options:</h2>
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
				Items to show:
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
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help'">
<?php

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
			<a href=\"admin.php?page=tradetracker-shop-multiitems\">Item Selection</a>
			>
			<b><a href=\"admin.php?page=tradetracker-shop-overview\">Overview</a></b>
			>
			<a href=\"admin.php?page=tradetracker-shop-feedback\">Feedback</a>";
	
?>
	<h2>Overview of selected options:</h2>
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
			<td>
				Update Frequency:
			</td>
			<td>
				<?php if (get_option( Tradetracker_update )=="") { echo "24"; } else { echo get_option( Tradetracker_update ); } ?>
			</td>
		</tr>
	<table>
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
		
	$multi=$wpdb->get_results("SELECT ".$tablemulti.".id as multiid, layname, multiname, multilayout, multiitems, multiamount, multilightbox FROM ".$tablemulti.",".$tablelayout." where ".$tablemulti.".multilayout=".$tablelayout.".id");
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
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help'">
<?php
}
	echo '</div>';


}
?>