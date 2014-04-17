<?php
function ttsearch(){
	//global variables
	global $wpdb;
	global $ttstoresubmit;
	global $ttstorehidden;
	global $ttstoretable;

	//variables for this function
	$Tradetracker_searchlayout_name = 'Tradetracker_searchlayout';

	//filling variables from database
	$Tradetracker_searchlayout_val = get_option( $Tradetracker_searchlayout_name );


	//see if form has been submitted
	if( isset($_POST[ $ttstoresubmit ]) && $_POST[ $ttstoresubmit ] == 'Y' ) {

		//get posted data
		$Tradetracker_searchlayout_val = $_POST[ $Tradetracker_searchlayout_name ];

		//save the posted value in the database
		if ( get_option("Tradetracker_searchlayout")  != $Tradetracker_searchlayout_val) {
			update_option( $Tradetracker_searchlayout_name, $Tradetracker_searchlayout_val );
		}

	        //put an settings updated message on the screen
		$savedmessage = __("Settings saved", "ttstore");
		$saved = "<div id=\"ttstoreboxsaved\"><strong>".$savedmessage."</strong></div>";
	}
?>
<?php $adminwidth = get_option("Tradetracker_adminwidth"); ?>
<?php $adminheight = get_option("Tradetracker_adminheight"); ?>
<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;z-index:100051;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
	<form name="form1" method="post" action="">
	<?php echo $ttstorehidden; ?>
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle"><?php _e('Search Settings.', 'ttstore'); ?></div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">
			<table width="<?php echo $adminwidth-15; ?>">
	<tr>
		<td>
			<label for="tradetrackerwidth" title="<?php _e('Use the same settings as used for this store.', 'ttstore'); ?>" class="info">
				<?php _e("Use same setting as this Store:", 'ttstore' ); ?>
			</label> 
		</td>
		<td>
			<select width="200" style="width: 200px" name="<?php echo $Tradetracker_searchlayout_name; ?>">
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
	<tr>
		<td colspan="2">
			<?php _e('<br>This is only interesting if you have created a search option on your site (more information can be found <a href="http://codex.wordpress.org/Creating_a_Search_Page">here</a>) <br>You could use the following shortcode or code for your theme to show items related to the keyword the user searched on:<br>Shortcode: [display_search]<br>Or in your theme file: display_search_items();', 'ttstore'); ?>
		</td>
	</td>
			</table>
		</div>
		<div id="ttstoreboxbottom">
			<?php
				if(isset($saved)){
					echo $saved;
				}
			?>
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /> 
			<INPUT type="button" name="Close" class="button-secondary" value="<?php esc_attr_e('Close') ?>" onclick="location.href='admin.php?page=tt-store'"> 
		</div>
	</form>
	</div>
</div>
<?php	
}
?>