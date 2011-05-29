<?php
function tradetracker_store_layout() {
	if (!current_user_can('manage_options'))
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
global $wpdb;
$pro_table_prefix=$wpdb->prefix.'tradetracker_';
$tablelayout = PRO_TABLE_PREFIX."layout";
define('PRO_TABLE_PREFIX', $pro_table_prefix);
    $structurelay = "CREATE TABLE IF NOT EXISTS $tablelayout (
        id INT(9) NOT NULL AUTO_INCREMENT,
	layname VARCHAR(100) NOT NULL,
	laywidth INT(10) NOT NULL,
        laycolortitle VARCHAR(7) NOT NULL,
        laycolorfooter VARCHAR(7) NOT NULL,
	laycolorimagebg VARCHAR(7) NOT NULL,
	laycolorfont VARCHAR(7) NOT NULL,
	layfont VARCHAR(50) NOT NULL,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structurelay);

	$hidden_field_name = 'mt_submit_hidden';

	if (!empty($_GET['layoutid']) || !empty($_POST['layoutid'])){
		if(!empty($_GET['layoutid'])){
			$layoutid = $_GET['layoutid'];
		} 
		if(!empty($_POST['layoutid'])){
			$layoutid = $_POST['layoutid'];
		} 
		$layout=$wpdb->get_results("SELECT laywidth, layname, layfont, laycolortitle, laycolorfooter, laycolorimagebg, laycolorfont FROM ".$tablelayout." where id=".$layoutid."");
		foreach ($layout as $layout_val){
			
			$Tradetracker_width_val = $layout_val->laywidth;
			$Tradetracker_layoutname_val = $layout_val->layname;
			$Tradetracker_font_val = $layout_val->layfont;
			$Tradetracker_colortitle_val = $layout_val->laycolortitle;
			$Tradetracker_colorfooter_val = $layout_val->laycolorfooter;
			$Tradetracker_colorimagebg_val = $layout_val->laycolorimagebg;
			$Tradetracker_colorfont_val = $layout_val->laycolorfont;

			$db_width_val = $layout_val->laywidth;
			$db_layoutname_val = $layout_val->layname;
			$db_font_val = $layout_val->layfont;
			$db_colortitle_val = $layout_val->laycolortitle;
			$db_colorfooter_val = $layout_val->laycolorfooter;
			$db_colorimagebg_val = $layout_val->laycolorimagebg;
			$db_colorfont_val = $layout_val->laycolorfont;
		}

	}
	$Tradetracker_width_name = 'Tradetracker_width';
	$Tradetracker_width_field_name = 'Tradetracker_width';

	$Tradetracker_layoutname_name = 'Tradetracker_layoutname';
	$Tradetracker_layoutname_field_name = 'Tradetracker_layoutname';

	$Tradetracker_font_name = 'Tradetracker_font';
	$Tradetracker_font_field_name = 'Tradetracker_font';

	$Tradetracker_colortitle_name = 'Tradetracker_colortitle';
	$Tradetracker_colortitle_field_name = 'Tradetracker_colortitle';

	$Tradetracker_colorfooter_name = 'Tradetracker_colorfooter';
	$Tradetracker_colorfooter_field_name = 'Tradetracker_colorfooter';

	$Tradetracker_colorimagebg_name = 'Tradetracker_colorimagebg';
	$Tradetracker_colorimagebg_field_name = 'Tradetracker_colorimagebg';

	$Tradetracker_colorfont_name = 'Tradetracker_colorfont';
	$Tradetracker_colorfont_field_name = 'Tradetracker_colorfont';


	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value

        	$Tradetracker_width_val = $_POST[ $Tradetracker_width_field_name ];
        	$Tradetracker_layoutname_val = $_POST[ $Tradetracker_layoutname_field_name ];
        	$Tradetracker_font_val = $_POST[ $Tradetracker_font_field_name ];
		$Tradetracker_colortitle_val = $_POST[ $Tradetracker_colortitle_field_name ];
		$Tradetracker_colorfooter_val = $_POST[ $Tradetracker_colorfooter_field_name ];
		$Tradetracker_colorimagebg_val = $_POST[ $Tradetracker_colorimagebg_field_name ];
		$Tradetracker_colorfont_val = $_POST[ $Tradetracker_colorfont_field_name ];


        // Save the posted value in the database
		if(!empty($_POST['layoutid'])) {
 		if ( $db_width_val  != $Tradetracker_width_val) {
			$query = $wpdb->update( $tablelayout, array( 'laywidth' => $Tradetracker_width_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
  		}
 		if ( $db_layoutname_val  != $Tradetracker_layoutname_val) {
			$query = $wpdb->update( $tablelayout, array( 'layname' => $Tradetracker_layoutname_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
  		}
		if ( $db_font_val  != $Tradetracker_font_val) {
			$query = $wpdb->update( $tablelayout, array( 'layfont' => $Tradetracker_font_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
		}
		if ( $db_colortitle_val  != $Tradetracker_colortitle_val) {
			$query = $wpdb->update( $tablelayout, array( 'laycolortitle' => $Tradetracker_colortitle_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
  		}	
 		if ( $db_colorfooter_val  != $Tradetracker_colorfooter_val) {
			$query = $wpdb->update( $tablelayout, array( 'laycolorfooter' => $Tradetracker_colorfooter_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
 		}
		if ( $db_colorimagebg_val  != $Tradetracker_colorimagebg_val) {
			$query = $wpdb->update( $tablelayout, array( 'laycolorimagebg' => $Tradetracker_colorimagebg_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
		}
		if ( $db_colorfont_val  != $Tradetracker_colorfont_val) {
			$query = $wpdb->update( $tablelayout, array( 'laycolorfont' => $Tradetracker_colorfont_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
		}
		} else {
        		$currentpage["laywidth"]=$Tradetracker_width_val;
        		$currentpage["layname"]=$Tradetracker_layoutname_val;
        		$currentpage["layfont"]=$Tradetracker_font_val;
        		$currentpage["laycolortitle"]=$Tradetracker_colortitle_val;
        		$currentpage["laycolorfooter"]=$Tradetracker_colorfooter_val;
        		$currentpage["laycolorimagebg"]=$Tradetracker_colorimagebg_val;
        		$currentpage["laycolorfont"]=$Tradetracker_colorfont_val;
			$wpdb->insert( $tablelayout, $currentpage);
			$layoutid = $wpdb->insert_id;
		}
        // Put an settings updated message on the screen
?>
	<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

	}
	if( $Tradetracker_width_val == "" ){
		$width= "250";
	} else {
		$width= $Tradetracker_width_val;
	}
	if( $Tradetracker_layoutname_val == "" ){
		$name= "Wordpress Plugin";
	} else {
		$name= $Tradetracker_layoutname_val;
	}
	if( $Tradetracker_font_val == "" ){
		$font= "Verdana";
	} else {
		$font= $Tradetracker_font_val;
	}
	$widthtitle = $width-6;
	if( $Tradetracker_colortitle_val == "" ){
		$colortitle = "#ececed";
	} else {
		$colortitle = $Tradetracker_colortitle_val;
	}
	if( $Tradetracker_colorfooter_val == "" ){
		$colorfooter = "#ececed";
	} else {
		$colorfooter = $Tradetracker_colorfooter_val;
	}
	if( $Tradetracker_colorimagebg_val == "" ){
		$colorimagebg = "#ffffff";
	} else {
	$colorimagebg = $Tradetracker_colorimagebg_val;
	}
	if( $Tradetracker_colorfont_val == "" ){
		$colorfont = "#000000";
	} else {
	$colorfont = $Tradetracker_colorfont_val;
	}

?>

<style type="text/css" media="screen">
.info {
		border-bottom: 1px dotted #666;
		cursor: help;
	}
<?php
	echo ".store-outerbox{width:".$width."px;color:".$colorfont.";font-family:".$font.";float:left;margin:0px 15px 15px 0;height:353px;border:solid 1px #999999;position:relative;}";
	echo ".store-titel{width:".$widthtitle."px;background-color:".$colortitle.";color:".$colorfont.";float:left;position:relative;height:30px;line-height:15px;font-size:11px;padding:3px;font-weight:bold;text-align:center;}";
	echo ".store-image{width:".$width."px;height:180px;padding:0px;overflow:hidden;margin: auto;background-color:".$colorimagebg.";}";
	echo ".store-image img{display: block;border:0px;margin: auto;}";
	echo ".store-footer{width:".$width."px;background-color:".$colorfooter.";float:left;position:relative;height:137px;}";
	echo ".store-description{width:".$widthtitle."px;color:".$colorfont.";position:absolute;top:5px;left:5px;height:90px;line-height:14px;font-size:10px;overflow:auto;}";

?>

</style>
<div class="wrap">
<?php 	echo "<h2>" . __( 'Tradetracker Store Setup', 'menu-test' ) . "</h2>"; ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
		<?php if ( get_option( Tradetracker_statsdash ) == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4" class="active">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab7">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab8">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab9" class="redhelp">Help</a></li>
</ul>
<div id="tab4" class="tabset_content">
   <h2 class="tabset_label">Layout</h2>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<?php if(!empty($layoutid)){ ?>
<input type="hidden" name="layoutid" value="<?php echo $layoutid; ?>">
<?php } ?>
<table>
	<tr>
		<td>
			<label for="tradetrackername" title="Fill in the name for the layout." class="info">
				<?php _e("Name for Layout:", 'tradetracker-layoutname' ); ?>
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_layoutname_field_name; ?>" value="<?php echo $Tradetracker_layoutname_val; ?>" size="20">
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackerwidth" title="Fill in how width you want 1 item to be." class="info">
				<?php _e("Store width:", 'tradetracker-width' ); ?>
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_width_field_name; ?>" value="<?php echo $Tradetracker_width_val; ?>" size="7">
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackerfont" title="Fill in which font you want to use. Standard font is Verdana." class="info">
				<?php _e("Font:", 'tradetracker-font' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_font_field_name; ?>" value="<?php echo $Tradetracker_font_val; ?>" size="7">
			<a href="http://www.fonttester.com/help/list_of_web_safe_fonts.html" target="_blank">WebSafe Fonts</a>
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackercolortitle" title="What color would you like to use for your title background." class="info">
				<?php _e("Title background color:", 'tradetracker-colortitle' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colortitle_field_name; ?>" value="<?php echo $Tradetracker_colortitle_val; ?>" size="7"> 
			<a href="http://www.2createawebsite.com/build/hex-colors.html#colorgenerator" target="_blank">Color Picker</a> (use the hex code including #. for instance: #000000)
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackercolorimagebg" title="What color would you like to use for your image background." class="info">
				<?php _e("Image background color:", 'tradetracker-colorimagebg' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorimagebg_field_name; ?>" value="<?php echo $Tradetracker_colorimagebg_val; ?>" size="7">
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackercolorfooter" title="What color would you like to use for your footer background." class="info">
				<?php _e("Footer background color:", 'tradetracker-colorfooter' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorfooter_field_name; ?>" value="<?php echo $Tradetracker_colorfooter_val; ?>" size="7">
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackercolorfont" title="What font color would you like to use." class="info">
				<?php _e("Font color:", 'tradetracker-colorfont' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorfont_field_name; ?>" value="<?php echo $Tradetracker_colorfont_val; ?>" size="7">
		</td>
	</tr>
</table>
<hr />

<p class="submit">
	<b>Always save changes before pressing next.</b><br>
	<input type="submit" name="Submit" class="button-primary" value="<?php if($layoutid>="1"){ esc_attr_e('Save Changes'); } else { esc_attr_e('Create'); } ?>" />
	<INPUT type="button" name="New" value="<?php esc_attr_e('New') ?>" onclick="location.href='admin.php?page=tradetracker-shop-layout'"> 
	<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-multi'"> 
	<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help5'">

</p>

</form>
	<table width="700">
		<tr>
			<td>
				<b>Name</b>
			</td>
			<td>
				<b>Width</b>
			</td>
			<td>
				<b>Font</b>
			</td>
			<td>
				<b>Title Color</b>
			</td>
			<td>
				<b>Image Color</b>
			</td>
			<td>
				<b>Footer Color</b>
			</td>
			<td>
				<b>Font Color</b>
			</td>
			<td>
			</td>
		</tr>
<?php
		$layoutedit=$wpdb->get_results("SELECT id, laywidth, layname, layfont, laycolortitle, laycolorfooter, laycolorimagebg, laycolorfont FROM ".$tablelayout."");
		foreach ($layoutedit as $layout_val){
?>

		<tr>
			<td>
				<?php echo $layout_val->layname; ?>
			</td>
			<td>
				<?php echo $layout_val->laywidth; ?>
			</td>
			<td>
				<?php echo $layout_val->layfont; ?>
			</td>
			<td>
				<?php echo $layout_val->laycolortitle; ?>
			</td>
			<td>
				<?php echo $layout_val->laycolorimagebg; ?>
			</td>
			<td>
				<?php echo $layout_val->laycolorfooter; ?>
			</td>
			<td>
				<?php echo $layout_val->laycolorfont; ?>
			</td>
			<td>
				<a href="admin.php?page=tradetracker-shop-layout&layoutid=<?php echo $layout_val->id; ?>">Edit</a>
			</td>
		</tr>
			
<?php		
		
		}
?>
	</table>
</div>
	<div id="sideblock" style="float:left;width:<?php echo $width; ?>px;margin-left:20px;border:1px;"> 
		<div class="store-outerbox">
				<div class="store-titel">
					<?php echo $name; ?>
				</div>			
				<div class="store-image">
					<img src="<?php echo "".WP_PLUGIN_URL."/tradetracker-store/screenshot-1.png"; ?>" style="max-width:<?php echo $width; ?>px;max-height:180px;">
				</div>
				<div class="store-footer">
					<div class="store-description">
						The description for the item you can buy using the <?php echo $font; ?> font.
					</div>
					<div class="buttons">
						<a href="#" class="regular">
							Buy Item
						</a>
					</div>
					<div class="store-price">
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td style="width:55px;height:20px;" class="euros">
									0,00 EUR
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
 	</div>

</div>
<?php



}
?>