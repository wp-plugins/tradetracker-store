<?php
function pluginsettings(){
	//global variables
	global $wpdb;
	global $ttstoresubmit;
	global $ttstorehidden;

	//variables for this function
	$Tradetracker_debugemail_name = 'Tradetracker_debugemail';
	$Tradetracker_importtool_name = 'Tradetracker_importtool';
	$Tradetracker_removelayout_name = 'Tradetracker_removelayout';
	$Tradetracker_removestores_name = 'Tradetracker_removestores';
	$Tradetracker_removeproducts_name = 'Tradetracker_removeproducts';
	$Tradetracker_removexml_name = 'Tradetracker_removexml';
	$Tradetracker_removeother_name = 'Tradetracker_removeother';
	$Tradetracker_adminheight_name = 'Tradetracker_adminheight';
	$Tradetracker_showurl_name = 'Tradetracker_showurl';


	//filling variables from database
	$Tradetracker_debugemail_val = get_option( $Tradetracker_debugemail_name );
	$Tradetracker_importtool_val = get_option( $Tradetracker_importtool_name );
	$Tradetracker_removelayout_val = get_option( $Tradetracker_removelayout_name );
	$Tradetracker_removestores_val = get_option( $Tradetracker_removestores_name );
	$Tradetracker_removeproducts_val = get_option( $Tradetracker_removeproducts_name );
	$Tradetracker_removexml_val = get_option( $Tradetracker_removexml_name );
	$Tradetracker_removeother_val = get_option( $Tradetracker_removeother_name );
	$Tradetracker_adminheight_val = get_option( $Tradetracker_adminheight_name );
	$Tradetracker_showurl_val = get_option( $Tradetracker_showurl_name );


	//see if form has been submitted
	if( isset($_POST[ $ttstoresubmit ]) && $_POST[ $ttstoresubmit ] == 'Y' ) {
		$Tradetracker_debugemail_val = $_POST[ $Tradetracker_debugemail_name ];
		$Tradetracker_importtool_val = $_POST[ $Tradetracker_importtool_name ];
		$Tradetracker_removelayout_val = $_POST[ $Tradetracker_removelayout_name ];
		$Tradetracker_removestores_val = $_POST[ $Tradetracker_removestores_name ];
		$Tradetracker_removeproducts_val = $_POST[ $Tradetracker_removeproducts_name ];
		$Tradetracker_removexml_val = $_POST[ $Tradetracker_removexml_name ];
		$Tradetracker_removeother_val = $_POST[ $Tradetracker_removeother_name ];
		$Tradetracker_adminheight_val = $_POST[ $Tradetracker_adminheight_name ];
		$Tradetracker_showurl_val = $_POST[ $Tradetracker_showurl_name ];

		if ( get_option("Tradetracker_debugemail")  != $Tradetracker_debugemail_val) {
			update_option( $Tradetracker_debugemail_name, $Tradetracker_debugemail_val );
		}
		if ( get_option("Tradetracker_importtool")  != $Tradetracker_importtool_val) {
			update_option( $Tradetracker_importtool_name, $Tradetracker_importtool_val );
		}
		if ( get_option("Tradetracker_removelayout")  != $Tradetracker_removelayout_val) {
			update_option( $Tradetracker_removelayout_name, $Tradetracker_removelayout_val );
		}
		if ( get_option("Tradetracker_removestores")  != $Tradetracker_removestores_val) {
			update_option( $Tradetracker_removestores_name, $Tradetracker_removestores_val );
		}
		if ( get_option("Tradetracker_removeproducts")  != $Tradetracker_removeproducts_val) {
			update_option( $Tradetracker_removeproducts_name, $Tradetracker_removeproducts_val );
		}
		if ( get_option("Tradetracker_removexml")  != $Tradetracker_removexml_val) {
			update_option( $Tradetracker_removexml_name, $Tradetracker_removexml_val );
		}
		if ( get_option("Tradetracker_removeother")  != $Tradetracker_removeother_val) {
			update_option( $Tradetracker_removeother_name, $Tradetracker_removeother_val );
		}
		if ( get_option("Tradetracker_adminheight") != "" && get_option("Tradetracker_adminheight")  != $Tradetracker_adminheight_val) {
			update_option( $Tradetracker_adminheight_name, $Tradetracker_adminheight_val );
		}
		if ( get_option("Tradetracker_showurl")  != $Tradetracker_showurl_val) {
			update_option( $Tradetracker_showurl_name, $Tradetracker_showurl_val );
		}

	        //put an settings updated message on the screen
		$saved = "<div id=\"ttstoreboxsaved\"><strong>Settings saved</strong></div>";
	}
?>
<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: 1000px;">
	<div id="ttstorebox">
	<form name="form1" method="post" action="">
	<?php echo $ttstorehidden; ?>
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle">Change plugin options.</div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		<?php $adminheight = get_option("Tradetracker_adminheight"); ?>
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">
			<table width="985">
				<tr>
					<td width="400px">
						<label for="tradetrackerdebugemail" title="Do you like to get an email when XML feeds are not imported?" class="info">
							<?php _e("Get email when import fails:", 'tradetracker-debugemail' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_debugemail_name; ?>" <?php if($Tradetracker_debugemail_val==1) {echo "checked";} ?> value="1"> Yes 
						<br>
						<input type="radio" name="<?php echo $Tradetracker_debugemail_name; ?>" <?php if($Tradetracker_debugemail_val==0){echo "checked";} ?> value="0"> No
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerimporttool" title="Which tool should be used to import XML?" class="info">
							<?php _e("Which import tool:", 'tradetracker-importtool' ); ?> 
						</label>
					</td>
					<td>
						<?php if (ini_get('allow_url_fopen') == true) { ?>
							<input type="radio" name="<?php echo $Tradetracker_importtool_name; ?>" <?php if($Tradetracker_importtool_val==1) {echo "checked";} ?> value="1"> Fopen (most reliable)
						<?php } ?>
						<?php if (function_exists('curl_init')) { ?>
							<br>
							<input type="radio" name="<?php echo $Tradetracker_importtool_name; ?>" <?php if($Tradetracker_importtool_val==2){echo "checked";} ?> value="2"> Curl/Fwrite (can run out of memory)
							<br>
							<input type="radio" name="<?php echo $Tradetracker_importtool_name; ?>" <?php if($Tradetracker_importtool_val==3){echo "checked";} ?> value="3"> Curl (sometimes causes issues)
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackeradminheight" title="What height should the amdin menu be?, standard is 460" class="info">
							<?php _e("Admin menu height:", 'tradetracker-adminheight' ); ?> 
						</label>
					</td>
					<td>
						<input type="text" name="<?php echo $Tradetracker_adminheight_name; ?>" value="<?php echo $Tradetracker_adminheight_val; ?>" size="20">
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackershowurl" title="Show url to plugin website in the source of the site" class="info">
							<?php _e("Show url to plugin website in the source:", 'tradetracker-showurl' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_showurl_name; ?>" <?php if($Tradetracker_showurl_val==1) {echo "checked";} ?> value="1"> Yes 
						<br>
						<input type="radio" name="<?php echo $Tradetracker_showurl_name; ?>" <?php if($Tradetracker_showurl_val==0){echo "checked";} ?> value="0"> No
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr />
						What should happen when you deactivate the plugin:
					</td>
				<tr>
					<td>
						<label for="tradetrackerremovelayout" title="Should all layouts be removed" class="info">
							<?php _e("Remove all layouts:", 'tradetracker-removelayout' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_removelayout_name; ?>" <?php if($Tradetracker_removelayout_val==1) {echo "checked";} ?> value="1"> Yes 
						<br>
						<input type="radio" name="<?php echo $Tradetracker_removelayout_name; ?>" <?php if($Tradetracker_removelayout_val==0){echo "checked";} ?> value="0"> No
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerremovestores" title="Should all stores be removed" class="info">
							<?php _e("Remove all stores:", 'tradetracker-removestores' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_removestores_name; ?>" <?php if($Tradetracker_removestores_val==1) {echo "checked";} ?> value="1"> Yes 
						<br>
						<input type="radio" name="<?php echo $Tradetracker_removestores_name; ?>" <?php if($Tradetracker_removestores_val==0){echo "checked";} ?> value="0"> No
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerremoveproducts" title="Should all products be removed" class="info">
							<?php _e("Remove all products:", 'tradetracker-removeproducts' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_removeproducts_name; ?>" <?php if($Tradetracker_removeproducts_val==1) {echo "checked";} ?> value="1"> Yes 
						<br>
						<input type="radio" name="<?php echo $Tradetracker_removeproducts_name; ?>" <?php if($Tradetracker_removeproducts_val==0){echo "checked";} ?> value="0"> No
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerremovexml" title="Should all XML settings be removed" class="info">
							<?php _e("Remove all XML settings:", 'tradetracker-removexml' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_removexml_name; ?>" <?php if($Tradetracker_removexml_val==1) {echo "checked";} ?> value="1"> Yes 
						<br>
						<input type="radio" name="<?php echo $Tradetracker_removexml_name; ?>" <?php if($Tradetracker_removexml_val==0){echo "checked";} ?> value="0"> No
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerremovexml" title="Should all other settings be removed" class="info">
							<?php _e("Remove all other settings:", 'tradetracker-removeother' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_removeother_name; ?>" <?php if($Tradetracker_removeother_val==1) {echo "checked";} ?> value="1"> Yes 
						<br>
						<input type="radio" name="<?php echo $Tradetracker_removeother_name; ?>" <?php if($Tradetracker_removeother_val==0){echo "checked";} ?> value="0"> No
					</td>
				</tr>
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