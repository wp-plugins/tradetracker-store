<?php
function layout() {

	//global variables
	global $wpdb;
	global $ttstoresubmit;
	global $ttstorehidden;
	global $ttstorelayouttable;

	//variables for this function
	$Tradetracker_width_name = 'Tradetracker_width';
	$Tradetracker_layoutname_name = 'Tradetracker_layoutname';
	$Tradetracker_font_name = 'Tradetracker_font';
	$Tradetracker_fontsize_name = 'Tradetracker_fontsize';
	$Tradetracker_colortitle_name = 'Tradetracker_colortitle';
	$Tradetracker_colorfooter_name = 'Tradetracker_colorfooter';
	$Tradetracker_colorimagebg_name = 'Tradetracker_colorimagebg';
	$Tradetracker_colorfont_name = 'Tradetracker_colorfont';
	$Tradetracker_colorborder_name = 'Tradetracker_colorborder';
	$Tradetracker_colorbutton_name = 'Tradetracker_colorbutton';
	$Tradetracker_colorbuttonfont_name = 'Tradetracker_colorbuttonfont';
	$readonlylock = "no";


	//filling variables from database when editting
	if (isset($_GET['layoutid']) || isset($_POST['layoutid'])){
		if(isset($_GET['layoutid'])){
			$layoutid = $_GET['layoutid'];
		} 
		if(isset($_POST['layoutid'])){
			$layoutid = $_POST['layoutid'];
		} 
		$layout=$wpdb->get_results("SELECT id, laywidth, layname, laycolorbuttonfont, layfont, layfontsize, laycolortitle, laycolorfooter, laycolorbutton, laycolorimagebg, laycolorfont, laycolorborder FROM ".$ttstorelayouttable." where id='".$layoutid."'");
		foreach ($layout as $layout_val){
			
			$Tradetracker_width_val = $layout_val->laywidth;
			$Tradetracker_layoutname_val = $layout_val->layname;
			$Tradetracker_font_val = $layout_val->layfont;
			$Tradetracker_fontsize_val = $layout_val->layfontsize;
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
			$db_fontsize_val = $layout_val->layfontsize;
			$db_colortitle_val = $layout_val->laycolortitle;
			$db_colorfooter_val = $layout_val->laycolorfooter;
			$db_colorimagebg_val = $layout_val->laycolorimagebg;
			$db_colorfont_val = $layout_val->laycolorfont;
			$db_colorborder_val = $layout_val->laycolorborder;
			$db_colorbutton_val = $layout_val->laycolorbutton;
			$db_colorbuttonfont_val = $layout_val->laycolorbuttonfont;
			if($layout_val->id == "1" && $Tradetracker_layoutname_val == "basic"){
			$readonlylock = "yes";
			}
		}

	}



	//see if form has been submitted
	if( isset($_POST[ $ttstoresubmit ]) && $_POST[ $ttstoresubmit ] == 'Y' ) {
		$Tradetracker_width_val = $_POST[ $Tradetracker_width_name ];
		$Tradetracker_layoutname_val = $_POST[ $Tradetracker_layoutname_name ];
      		$Tradetracker_font_val = $_POST[ $Tradetracker_font_name ];
      		$Tradetracker_fontsize_val = $_POST[ $Tradetracker_fontsize_name ];
		$Tradetracker_colortitle_val = $_POST[ $Tradetracker_colortitle_name ];
		$Tradetracker_colorfooter_val = $_POST[ $Tradetracker_colorfooter_name ];
		$Tradetracker_colorimagebg_val = $_POST[ $Tradetracker_colorimagebg_name ];
		$Tradetracker_colorfont_val = $_POST[ $Tradetracker_colorfont_name ];
		$Tradetracker_colorborder_val = $_POST[ $Tradetracker_colorborder_name ];
		$Tradetracker_colorbutton_val = $_POST[ $Tradetracker_colorbutton_name ];
		$Tradetracker_colorbuttonfont_val = $_POST[ $Tradetracker_colorbuttonfont_name ];

		if($Tradetracker_width_val=="" || $Tradetracker_layoutname_val ==""){
			$error = "<div id=\"ttstoreboxsaved\"><strong>Please fill mandatory fields</strong></div>";
		} else {
			if($Tradetracker_font_val == ""){
				$Tradetracker_font_val="verdana";
				$emptyfield = "Empty fields filled with default value";
			}
			if($Tradetracker_fontsize_val == ""){
				$Tradetracker_fontsize_val = "10";
				$emptyfield = "Empty fields filled with default value";
			}
			if($Tradetracker_colortitle_val==""){
				$Tradetracker_colortitle_val = "#ececed";
				$emptyfield = "Empty fields filled with default value";
			}
			if($Tradetracker_colorfooter_val == ""){
				$Tradetracker_colorfooter_val = "#ececed";
				$emptyfield = "Empty fields filled with default value";
			}
			if($Tradetracker_colorimagebg_val == ""){
				$Tradetracker_colorimagebg_val = "#FFFFFF";
				$emptyfield = "Empty fields filled with default value";
			}
			if($Tradetracker_colorfont_val == ""){
				$Tradetracker_colorfont_val = "#000000";
				$emptyfield = "Empty fields filled with default value";
			}
			if($Tradetracker_colorborder_val == ""){
				$Tradetracker_colorborder_val = "#65B9C1";
				$emptyfield = "Empty fields filled with default value";
			}
			if($Tradetracker_colorbutton_val == ""){
				$Tradetracker_colorbutton_val = "#65B9C1";
				$emptyfield = "Empty fields filled with default value";
			}
			if($Tradetracker_colorbuttonfont_val == ""){
				$Tradetracker_colorbuttonfont_val = "#000000";
				$emptyfield = "Empty fields filled with default value";
			}
			//get posted data
			//save new variables when editting
			if(!empty($_POST['layoutid'])) {
		 		if ( $db_width_val  != $Tradetracker_width_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'laywidth' => $Tradetracker_width_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
  				}
 				if ( $db_layoutname_val  != $Tradetracker_layoutname_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'layname' => $Tradetracker_layoutname_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
  				}
				if ( $db_fontsize_val  != $Tradetracker_fontsize_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'layfontsize' => $Tradetracker_fontsize_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
				}
				if ( $db_font_val  != $Tradetracker_font_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'layfont' => $Tradetracker_font_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
				}
				if ( $db_colortitle_val  != $Tradetracker_colortitle_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'laycolortitle' => $Tradetracker_colortitle_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
  				}	
 				if ( $db_colorfooter_val  != $Tradetracker_colorfooter_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'laycolorfooter' => $Tradetracker_colorfooter_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
 				}
				if ( $db_colorimagebg_val  != $Tradetracker_colorimagebg_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'laycolorimagebg' => $Tradetracker_colorimagebg_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
				}
				if ( $db_colorfont_val  != $Tradetracker_colorfont_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'laycolorfont' => $Tradetracker_colorfont_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
				}
				if ( $db_colorborder_val  != $Tradetracker_colorborder_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'laycolorborder' => $Tradetracker_colorborder_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
				}
				if ( $db_colorbutton_val  != $Tradetracker_colorbutton_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'laycolorbutton' => $Tradetracker_colorbutton_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
				}
				if ( $db_colorbuttonfont_val  != $Tradetracker_colorbuttonfont_val) {
					$query = $wpdb->update( $ttstorelayouttable, array( 'laycolorbuttonfont' => $Tradetracker_colorbuttonfont_val), array( 'id' => $_POST['layoutid']), array( '%s'), array( '%s'), array( '%d' ) );
				}
			} else {
				//save new variables
       		 		$currentpage["laywidth"]=$Tradetracker_width_val;
       	 			$currentpage["layname"]=$Tradetracker_layoutname_val;
        			$currentpage["layfont"]=$Tradetracker_font_val;
        			$currentpage["layfontsize"]=$Tradetracker_fontsize_val;
        			$currentpage["laycolortitle"]=$Tradetracker_colortitle_val;
        			$currentpage["laycolorfooter"]=$Tradetracker_colorfooter_val;
        			$currentpage["laycolorimagebg"]=$Tradetracker_colorimagebg_val;
        			$currentpage["laycolorfont"]=$Tradetracker_colorfont_val;
        			$currentpage["laycolorborder"]=$Tradetracker_colorborder_val;
        			$currentpage["laycolorbutton"]=$Tradetracker_colorbutton_val;
        			$currentpage["laycolorbuttonfont"]=$Tradetracker_colorbuttonfont_val;
				$wpdb->insert( $ttstorelayouttable, $currentpage);
				$layoutid = $wpdb->insert_id;
			}
		        //put an settings updated message on the screen
				if(isset($emptyfield)){
					$saved = "<div id=\"ttstoreboxsaved\"><strong>Settings saved ".$emptyfield."</strong></div>";
				} else {
					$saved = "<div id=\"ttstoreboxsaved\"><strong>Settings saved</strong></div>";
				}
		}
?>
		
<?php
	}
	if(!isset($_GET['function'])){
?>
<?php $adminwidth = get_option("Tradetracker_adminwidth"); ?>
<?php $adminheight = get_option("Tradetracker_adminheight"); ?>
<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle">Would you like to edit or add a layout?</div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">
		<table width="<?php echo $adminwidth-15; ?>">
			<tr>
				<td colspan="4">
				</td>
				<td colspan="7">
				Colors:
				</td>
			</tr>
			<tr>
				<td>
					<strong>Name</strong>
				</td>
				<td>
					<strong>Width</strong>
				</td>
				<td>
					<strong>Font</strong>
				</td>
				<td>
					<strong>Fontsize</strong>
				</td>
				<td>
					<strong>Title</strong>
				</td>
				<td>
					<strong>Image</strong>
				</td>
				<td>
					<strong>Footer</strong>
				</td>
				<td>
					<strong>Font</strong>
				</td>
				<td>
					<strong>Border</strong>
				</td>
				<td>
					<strong>Button</strong>
				</td>
				<td>
					<strong>Button font</strong>
				</td>
				<td>
				</td>
			</tr>
<?php
		$layoutedit=$wpdb->get_results("SELECT id, laywidth,laycolorbuttonfont,laycolorbutton,laycolorborder, layname, layfont, layfontsize, laycolortitle, laycolorfooter, laycolorimagebg, laycolorfont FROM ".$ttstorelayouttable."");
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
					<?php echo $layout_val->layfontsize; ?>
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
					<?php echo $layout_val->laycolorborder; ?>
				</td>
				<td>
					<?php echo $layout_val->laycolorbutton; ?>
				</td>
				<td>
					<?php echo $layout_val->laycolorbuttonfont; ?>
				</td>
				<td>
					<?php if($layout_val->id>"1"){ echo "<a href=\"admin.php?page=tt-store&option=layout&function=new&layoutid=".$layout_val->id."\">Edit</a>"; } ?>
				</td>
			</tr>
<?php		
		}
?>
		</table>
		</div>
		<div id="ttstoreboxbottom">
			<INPUT type="button" name="Close" class="button-primary" value="<?php esc_attr_e('Add New') ?>" onclick="location.href='admin.php?page=tt-store&option=layout&function=new'"> 
			<INPUT type="button" name="Close" class="button-secondary" value="<?php esc_attr_e('Close') ?>" onclick="location.href='admin.php?page=tt-store'"> 
		</div>
	</div>
</div>

<?php
} else if($_GET['function']=="new") {
?>

<div  id="TB_overlay" class="TB_overlayBG"></div>

<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
	<form name="form1" method="post" action="">
	<?php echo $ttstorehidden; ?>
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle"><?php if(isset($layoutid)){ esc_attr_e('Edit layout'); } else { esc_attr_e('Create layout'); } ?></div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store&option=layout">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		<?php $adminheight = get_option("Tradetracker_adminheight"); ?>
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">
	<div id="ttstoreboxlayout">
	</div>
		<input type="hidden" name="layoutid" value="<?php if(isset($layoutid)){ echo $layoutid;} ?>">
<table width="<?php echo $adminwidth-15; ?>">
	<tr>
		<td>
			<label for="tradetrackername" title="Fill in the name for the layout." class="info">
				<?php _e("Name for Layout:", 'tradetracker-layoutname' ); ?>
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_layoutname_name; ?>" class="target" id="layoutname" value="<?php if(isset($Tradetracker_layoutname_val)){ echo $Tradetracker_layoutname_val; }?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>>
			<?php if(isset($error)){ echo "<font color=\"red\">*</font>"; }?>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackerwidth" title="Fill in how width you want 1 item to be." class="info">
				<?php _e("Store width:", 'tradetracker-width' ); ?>
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_width_name; ?>" class="target" id="layoutwidth" value="<?php if(isset($Tradetracker_width_val)){ echo $Tradetracker_width_val;} ?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>>
			<?php if(isset($error)){ echo "<font color=\"red\">*</font>"; }?>
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackerfont" title="Fill in which font you want to use. Standard font is Verdana." class="info">
				<?php _e("Font:", 'tradetracker-font' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_font_name; ?>" class="target" id="layoutfont" value="<?php if(isset($Tradetracker_font_val)){ echo $Tradetracker_font_val;} ?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>>
			<a href="http://www.fonttester.com/help/list_of_web_safe_fonts.html" target="_blank">WebSafe Fonts</a>
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackerfontsize" title="Fill in which size the font should be. Standard is 10" class="info">
				<?php _e("Fontsize:", 'tradetracker-fontsize' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_fontsize_name; ?>" class="target" id="layoutfontsize" value="<?php if(isset($Tradetracker_fontsize_val)){ echo $Tradetracker_fontsize_val;} ?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>>
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackercolortitle" title="What color would you like to use for your title background." class="info">
				<?php _e("Title background color:", 'tradetracker-colortitle' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colortitle_name; ?>" class="target" id="layoutcolortitle" value="<?php if(isset($Tradetracker_colortitle_val)){ echo $Tradetracker_colortitle_val;} ?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>> 
			<a href="http://www.2createawebsite.com/build/hex-colors.html#colorgenerator" target="_blank">Color Picker</a> (use hex code including #. Like: #000000)
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackercolorimagebg" title="What color would you like to use for your image background." class="info">
				<?php _e("Image background color:", 'tradetracker-colorimagebg' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorimagebg_name; ?>" class="target" id="layoutcolorimagebg" value="<?php if(isset($Tradetracker_colorimagebg_val)){ echo $Tradetracker_colorimagebg_val;} ?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>>
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackercolorfooter" title="What color would you like to use for your footer background." class="info">
				<?php _e("Footer background color:", 'tradetracker-colorfooter' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorfooter_name; ?>" class="target" id="layoutcolorfooter" value="<?php if(isset($Tradetracker_colorfooter_val)){ echo $Tradetracker_colorfooter_val;} ?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackercolorfooter" title="What color would you like to use for the border." class="info">
				<?php _e("Border color:", 'tradetracker-colorborder' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorborder_name; ?>" class="target" id="layoutcolorborder" value="<?php if(isset($Tradetracker_colorborder_val)){  echo $Tradetracker_colorborder_val;} ?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackercolorbutton" title="What color would you like to use for the button." class="info">
				<?php _e("Button color:", 'tradetracker-colorbutton' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorbutton_name; ?>" class="target" id="layoutcolorbutton" value="<?php if(isset($Tradetracker_colorbutton_val)){ echo $Tradetracker_colorbutton_val;} ?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackerbuttoncolorfont" title="What font color would you like to use for the button." class="info">
				<?php _e("Button Font color:", 'tradetracker-colorbuttonfont' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorbuttonfont_name; ?>" class="target" id="layoutcolorbuttonfont" value="<?php if(isset($Tradetracker_colorbuttonfont_val)){ echo $Tradetracker_colorbuttonfont_val; } ?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackercolorfont" title="What font color would you like to use." class="info">
				<?php _e("Font color:", 'tradetracker-colorfont' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_colorfont_name; ?>" class="target" id="layoutcolorfont" value="<?php if(isset($Tradetracker_colorfont_val)){ echo $Tradetracker_colorfont_val;} ?>" size="20" <?php if($readonlylock == "yes"){echo "readonly";} ?>>
		</td>
	</tr>
</table>
		</div>
		<div id="ttstoreboxbottom">
			<?php
				if(isset($saved)){
					echo $saved;
				}
				if(isset($error)){
					echo $error;
				}
			?>
			<input type="submit" name="Submit" class="button-primary" value="<?php if(isset($layoutid)){ esc_attr_e('Save Changes'); } else { esc_attr_e('Create'); } ?>" /> 
			<INPUT type="button" name="Close" class="button-secondary" value="<?php esc_attr_e('Close') ?>" onclick="location.href='admin.php?page=tt-store&option=layout'"> 
		</div>
	</form>
	</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<SCRIPT>
$('.target').change(function() {
$('#ttstoreboxlayout').load("<?php echo plugins_url( 'showlayout.php' , __FILE__ )?>", {layoutname:$('#layoutname').val(),layoutwidth:$('#layoutwidth').val(),layoutfontsize:$('#layoutfontsize').val(),layoutfont:$('#layoutfont').val(),layoutcolortitle:$('#layoutcolortitle').val(),layoutcolorimagebg:$('#layoutcolorimagebg').val(),layoutcolorfooter:$('#layoutcolorfooter').val(),layoutcolorborder:$('#layoutcolorborder').val(),layoutcolorbutton:$('#layoutcolorbutton').val(),layoutcolorbuttonfont:$('#layoutcolorbuttonfont').val(),layoutcolorfont:$('#layoutcolorfont').val() });
});
</SCRIPT>
</div>
<?php
}	
}
?>