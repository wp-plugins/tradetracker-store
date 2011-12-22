<?php
function tradetracker_store_layout() {
	if (!current_user_can('manage_options'))
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
global $wpdb;
ttstoreheader();
$tablelayout = PRO_TABLE_PREFIX."layout";
    $structurelay = "CREATE TABLE IF NOT EXISTS $tablelayout (
        id INT(9) NOT NULL AUTO_INCREMENT,
	layname VARCHAR(100) NOT NULL,
	laywidth INT(10) NOT NULL,
        laycolortitle VARCHAR(7) NOT NULL,
        laycolorfooter VARCHAR(7) NOT NULL,
	laycolorimagebg VARCHAR(7) NOT NULL,
	laycolorfont VARCHAR(7) NOT NULL,
	laycolorborder VARCHAR(7) NOT NULL,
	laycolorbutton VARCHAR(7) NOT NULL,
	laycolorbuttonfont VARCHAR(50) NOT NULL,
	layfont VARCHAR(50) NOT NULL,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structurelay);
$Tradetracker_width_val = "";
$Tradetracker_layoutname_val = "";
$Tradetracker_font_val = "";
$Tradetracker_colortitle_val = "";
$Tradetracker_colorfooter_val = "";
$Tradetracker_colorimagebg_val = "";
$Tradetracker_colorfont_val = "";
$Tradetracker_layoutname_val = "";
$Tradetracker_width_val = "";
$Tradetracker_font_val = "";
$Tradetracker_colortitle_val = "";
$Tradetracker_colorimagebg_val = "";
$Tradetracker_colorfooter_val = "";
$Tradetracker_colorfont_val = "";
$Tradetracker_colorborder_val = "";
$Tradetracker_colorbutton_val = "";
$Tradetracker_colorbuttonfont_val = "";
$layoutid = "";
	$hidden_field_name = 'mt_submit_hidden';

	if (!empty($_GET['layoutid']) || !empty($_POST['layoutid'])){
		if(!empty($_GET['layoutid'])){
			$layoutid = $_GET['layoutid'];
		} 
		if(!empty($_POST['layoutid'])){
			$layoutid = $_POST['layoutid'];
		} 
		$layout=$wpdb->get_results("SELECT laywidth, layname, laycolorbuttonfont, layfont, laycolortitle, laycolorfooter, laycolorbutton, laycolorimagebg, laycolorfont, laycolorborder FROM ".$tablelayout." where id=".$layoutid."");
		foreach ($layout as $layout_val){
			
			$Tradetracker_width_val = $layout_val->laywidth;
			$Tradetracker_layoutname_val = $layout_val->layname;
			$Tradetracker_font_val = $layout_val->layfont;
			$Tradetracker_colortitle_val = $layout_val->laycolortitle;
			$Tradetracker_colorfooter_val = $layout_val->laycolorfooter;
			$Tradetracker_colorimagebg_val = $layout_val->laycolorimagebg;
			$Tradetracker_colorfont_val = $layout_val->laycolorfont;
			$Tradetracker_colorborder_val = $layout_val->laycolorborder;
			$Tradetracker_colorbutton_val = $layout_val->laycolorbutton;
			$Tradetracker_colorbuttonfont_val = $layout_val->laycolorbuttonfont;

			$db_width_val = $layout_val->laywidth;
			$db_layoutname_val = $layout_val->layname;
			$db_font_val = $layout_val->layfont;
			$db_colortitle_val = $layout_val->laycolortitle;
			$db_colorfooter_val = $layout_val->laycolorfooter;
			$db_colorimagebg_val = $layout_val->laycolorimagebg;
			$db_colorfont_val = $layout_val->laycolorfont;
			$db_colorborder_val = $layout_val->laycolorborder;
			$db_colorbutton_val = $layout_val->laycolorbutton;
			$db_colorbuttonfont_val = $layout_val->laycolorbuttonfont;
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

	$Tradetracker_colorborder_name = 'Tradetracker_colorborder';
	$Tradetracker_colorborder_field_name = 'Tradetracker_colorborder';

	$Tradetracker_colorbutton_name = 'Tradetracker_colorbutton';
	$Tradetracker_colorbutton_field_name = 'Tradetracker_colorbutton';

	$Tradetracker_colorbuttonfont_name = 'Tradetracker_colorbuttonfont';
	$Tradetracker_colorbuttonfont_field_name = 'Tradetracker_colorbuttonfont';

	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value

        	$Tradetracker_width_val = $_POST[ $Tradetracker_width_field_name ];
        	$Tradetracker_layoutname_val = $_POST[ $Tradetracker_layoutname_field_name ];
        	$Tradetracker_font_val = $_POST[ $Tradetracker_font_field_name ];
		$Tradetracker_colortitle_val = $_POST[ $Tradetracker_colortitle_field_name ];
		$Tradetracker_colorfooter_val = $_POST[ $Tradetracker_colorfooter_field_name ];
		$Tradetracker_colorimagebg_val = $_POST[ $Tradetracker_colorimagebg_field_name ];
		$Tradetracker_colorfont_val = $_POST[ $Tradetracker_colorfont_field_name ];
		$Tradetracker_colorborder_val = $_POST[ $Tradetracker_colorborder_field_name ];
		$Tradetracker_colorbutton_val = $_POST[ $Tradetracker_colorbutton_field_name ];
		$Tradetracker_colorbuttonfont_val = $_POST[ $Tradetracker_colorbuttonfont_field_name ];


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
		if ( $db_colorborder_val  != $Tradetracker_colorborder_val) {
			$query = $wpdb->update( $tablelayout, array( 'laycolorborder' => $Tradetracker_colorborder_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
		}
		if ( $db_colorbutton_val  != $Tradetracker_colorbutton_val) {
			$query = $wpdb->update( $tablelayout, array( 'laycolorbutton' => $Tradetracker_colorbutton_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
		}
		if ( $db_colorbuttonfont_val  != $Tradetracker_colorbuttonfont_val) {
			$query = $wpdb->update( $tablelayout, array( 'laycolorbuttonfont' => $Tradetracker_colorbuttonfont_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
		}

		} else {
        		$currentpage["laywidth"]=$Tradetracker_width_val;
        		$currentpage["layname"]=$Tradetracker_layoutname_val;
        		$currentpage["layfont"]=$Tradetracker_font_val;
        		$currentpage["laycolortitle"]=$Tradetracker_colortitle_val;
        		$currentpage["laycolorfooter"]=$Tradetracker_colorfooter_val;
        		$currentpage["laycolorimagebg"]=$Tradetracker_colorimagebg_val;
        		$currentpage["laycolorfont"]=$Tradetracker_colorfont_val;
        		$currentpage["laycolorborder"]=$Tradetracker_colorborder_val;
        		$currentpage["laycolorbutton"]=$Tradetracker_colorbutton_val;
        		$currentpage["laycolorbuttonfont"]=$Tradetracker_colorbuttonfont_val;
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
	if( $Tradetracker_colorborder_val == "" ){
		$colorborder = "#65B9C1";
	} else {
	$colorborder = $Tradetracker_colorborder_val;
	}
	if( $Tradetracker_colorbutton_val == "" ){
		$colorbutton = "#65B9C1";
	} else {
	$colorbutton = $Tradetracker_colorbutton_val;
	}
	if( $Tradetracker_colorbuttonfont_val == "" ){
		$colorbuttonfont = "#ffffff";
	} else {
		$colorbuttonfont = $Tradetracker_colorbuttonfont_val;
	}

?>

<style type="text/css" media="screen">
.info {
		border-bottom: 1px dotted #666;
		cursor: help;
	}
<?php
		echo ".store-outerbox{width:".$width."px;color:".$colorfont.";font-family:".$font.";float:left;margin:0px 15px 15px 0;min-height:353px;border:solid 1px ".$colorborder.";position:relative;}";
		echo ".store-titel{width:".$widthtitle."px;background-color:".$colortitle.";color:".$colorfont.";float:left;position:relative;height:30px;line-height:15px;font-size:11px;padding:3px;font-weight:bold;text-align:center;}";
		echo ".store-image{width:".$width."px;height:180px;padding:0px;overflow:hidden;margin: auto;background-color:".$colorimagebg.";}";
		echo ".store-image img{display: block;border:0px;margin: auto;}";
		echo ".store-footer{width:".$width."px;background-color:".$colorfooter.";float:left;position:relative;min-height:137px;}";
		echo ".store-description{width:".$widthtitle."px;color:".$colorfont.";position:relative;top:5px;left:5px;height:90px;line-height:14px;font-size:10px;overflow:auto;}";
		echo ".store-more{min-height:20px; width:".$widthtitle."px;position: relative;float: left;margin-top:10px;margin-left:5px;margin-bottom: 5px;}";
		echo ".store-more img{margin:0px !important;}";
		echo ".store-price {border: 0 solid #65B9C1;color: #4E4E4E !important;float: right;font-size: 12px !important;font-weight: bold !important;height: 30px !important;position: relative;text-align: center !important;width: 80px !important;}";
		echo ".store-price table {background-color: ".$colorfooter." !important;border: 1px none !important;border-collapse: inherit !important;float: right;margin-left: 1px;margin-top: 1px;text-align: center !important;}";
		echo ".store-price table tr {padding: 1px !important;}";
		echo ".store-price table tr td {padding: 1px !important;}";
		echo ".store-price table td, table th, table tr {border: 1px solid #CCCCCC;padding: 0 !important;}";
		echo ".store-price table td.euros {font-size: 12px !important;letter-spacing: -1px !important; }";
		echo ".store-price {background-color: ".$colorborder." !important;}";
		echo ".buttons a, .buttons button {background-color: ".$colorbutton.";border: 1px solid ".$colorbutton.";bottom: 0;color: ".$colorbuttonfont.";cursor: pointer;display: block;float: left;font-size: 12px;font-weight: bold;margin-top: 0;padding: 5px 10px 5px 7px;position: relative;text-decoration: none;width: 100px;}";
		echo ".buttons button {overflow: visible;padding: 4px 10px 3px 7px;width: auto;}";
		echo ".buttons button[type] {line-height: 17px;padding: 5px 10px 5px 7px;}";
		echo ":first-child + html button[type] {padding: 4px 10px 3px 7px;}";
		echo ".buttons button img, .buttons a img {border: medium none;margin: 0 3px -3px 0 !important;padding: 0;}";
		echo ".button.regular, .buttons a.regular {color: ".$colorbuttonfont.";}";
		echo ".buttons a.regular:hover, button.regular:hover {background-color: #4E4E4E;border: 1px solid #4E4E4E;color: ".$colorbuttonfont.";}";
		echo ".buttons a.regular:active {background-color: #FFFFFF;border: 1px solid ".$colorbutton.";color: ".$colorbuttonfont.";}";

?>

</style>
<div class="wrap">
<?php 	echo "<h2>" . __( 'Tradetracker Store Setup', 'menu-test' ) . "</h2>"; ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
		<?php if ( get_option("Tradetracker_statsdash") == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4" class="active">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-search#tab7">Search</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab8">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab9">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-premium#tab10" class="greenpremium">Premium</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab11" class="redhelp">Help</a></li>
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
			<input type="text" name="<?php echo $Tradetracker_width_field_name; ?>" value="<?php echo $Tradetracker_width_val; ?>" size="20">
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackerfont" title="Fill in which font you want to use. Standard font is Verdana." class="info">
				<?php _e("Font:", 'tradetracker-font' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_font_field_name; ?>" value="<?php echo $Tradetracker_font_val; ?>" size="20">
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
			<input type="text" name="<?php echo $Tradetracker_colortitle_field_name; ?>" value="<?php echo $Tradetracker_colortitle_val; ?>" size="20"> 
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
			<input type="text" name="<?php echo $Tradetracker_colorimagebg_field_name; ?>" value="<?php echo $Tradetracker_colorimagebg_val; ?>" size="20">
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackercolorfooter" title="What color would you like to use for your footer background." class="info">
				<?php _e("Footer background color:", 'tradetracker-colorfooter' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorfooter_field_name; ?>" value="<?php echo $Tradetracker_colorfooter_val; ?>" size="20">
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackercolorfooter" title="What color would you like to use for the border." class="info">
				<?php _e("Border color:", 'tradetracker-colorborder' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorborder_field_name; ?>" value="<?php echo $Tradetracker_colorborder_val; ?>" size="20">
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackercolorbutton" title="What color would you like to use for the button." class="info">
				<?php _e("Button color:", 'tradetracker-colorbutton' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorbutton_field_name; ?>" value="<?php echo $Tradetracker_colorbutton_val; ?>" size="20">
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackerbuttoncolorfont" title="What font color would you like to use for the button." class="info">
				<?php _e("Button Font color:", 'tradetracker-colorbuttonfont' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorbuttonfont_field_name; ?>" value="<?php echo $Tradetracker_colorbuttonfont_val; ?>" size="20">
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackercolorfont" title="What font color would you like to use." class="info">
				<?php _e("Font color:", 'tradetracker-colorfont' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorfont_field_name; ?>" value="<?php echo $Tradetracker_colorfont_val; ?>" size="20">
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
				<div class="store-more"></div>
				<div class="buttons">
					<a href="#" class="regular">
						Buy Item
					</a>
				</div>
				<div class="store-price">
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td style="border: 1px none; width: 100px; margin: 1px; height: 29px;" class="euros">
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