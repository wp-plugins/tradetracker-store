<?php
function pluginsettings(){
	//global variables
	global $wpdb;
	global $ttstoresubmit;
	global $ttstorehidden;

	//variables for this function
	$Tradetracker_debugemail_name = 'Tradetracker_debugemail';
	$Tradetracker_importtool_name = 'Tradetracker_importtool';
	$Tradetracker_loadextra_name = 'Tradetracker_loadextra';
	$Tradetracker_removelayout_name = 'Tradetracker_removelayout';
	$Tradetracker_removestores_name = 'Tradetracker_removestores';
	$Tradetracker_removeproducts_name = 'Tradetracker_removeproducts';
	$Tradetracker_removexml_name = 'Tradetracker_removexml';
	$Tradetracker_removeother_name = 'Tradetracker_removeother';
	$Tradetracker_adminheight_name = 'Tradetracker_adminheight';
	$Tradetracker_adminwidth_name = 'Tradetracker_adminwidth';
	$Tradetracker_showurl_name = 'Tradetracker_showurl';
	$Tradetracker_usecss_name = 'Tradetracker_usecss';
	$Tradetracker_csslink_name = 'Tradetracker_csslink';
	$Tradetracker_TTnewcategory_name = 'TTnewcategory';
	$Tradetracker_slidertheme_name = 'Tradetracker_slidertheme';


	//filling variables from database
	$Tradetracker_debugemail_val = get_option( $Tradetracker_debugemail_name );
	$Tradetracker_importtool_val = get_option( $Tradetracker_importtool_name );
	$Tradetracker_loadextra_val = get_option( $Tradetracker_loadextra_name );
	$Tradetracker_removelayout_val = get_option( $Tradetracker_removelayout_name );
	$Tradetracker_removestores_val = get_option( $Tradetracker_removestores_name );
	$Tradetracker_removeproducts_val = get_option( $Tradetracker_removeproducts_name );
	$Tradetracker_removexml_val = get_option( $Tradetracker_removexml_name );
	$Tradetracker_removeother_val = get_option( $Tradetracker_removeother_name );
	$Tradetracker_adminheight_val = get_option( $Tradetracker_adminheight_name );
	$Tradetracker_adminwidth_val = get_option( $Tradetracker_adminwidth_name );
	$Tradetracker_showurl_val = get_option( $Tradetracker_showurl_name );
	$Tradetracker_usecss_val = get_option( $Tradetracker_usecss_name );
	$Tradetracker_csslink_val = get_option( $Tradetracker_csslink_name );
	$Tradetracker_TTnewcategory_val = get_option( $Tradetracker_TTnewcategory_name );
	$Tradetracker_slidertheme_val = get_option( $Tradetracker_slidertheme_name );



	//see if form has been submitted
	if( isset($_POST[ $ttstoresubmit ]) && $_POST[ $ttstoresubmit ] == 'Y' ) {
		$Tradetracker_debugemail_val = $_POST[ $Tradetracker_debugemail_name ];
		$Tradetracker_importtool_val = $_POST[ $Tradetracker_importtool_name ];
		$Tradetracker_loadextra_val = $_POST[ $Tradetracker_loadextra_name ];
		$Tradetracker_removelayout_val = $_POST[ $Tradetracker_removelayout_name ];
		$Tradetracker_removestores_val = $_POST[ $Tradetracker_removestores_name ];
		$Tradetracker_removeproducts_val = $_POST[ $Tradetracker_removeproducts_name ];
		$Tradetracker_removexml_val = $_POST[ $Tradetracker_removexml_name ];
		$Tradetracker_removeother_val = $_POST[ $Tradetracker_removeother_name ];
		$Tradetracker_adminheight_val = $_POST[ $Tradetracker_adminheight_name ];
		$Tradetracker_adminwidth_val = $_POST[ $Tradetracker_adminwidth_name ];
		$Tradetracker_showurl_val = $_POST[ $Tradetracker_showurl_name ];
		$Tradetracker_usecss_val = $_POST[ $Tradetracker_usecss_name ];
		$Tradetracker_csslink_val = $_POST[ $Tradetracker_csslink_name ];
		$Tradetracker_TTnewcategory_val = $_POST[ $Tradetracker_TTnewcategory_name ];
		$Tradetracker_slidertheme_val = $_POST[ $Tradetracker_slidertheme_name ];

		if ( get_option("Tradetracker_debugemail")  != $Tradetracker_debugemail_val) {
			update_option( $Tradetracker_debugemail_name, $Tradetracker_debugemail_val );
		}
		if ( get_option("Tradetracker_importtool")  != $Tradetracker_importtool_val) {
			update_option( $Tradetracker_importtool_name, $Tradetracker_importtool_val );
		}
		if ( get_option("Tradetracker_loadextra")  != $Tradetracker_loadextra_val) {
			update_option( $Tradetracker_loadextra_name, $Tradetracker_loadextra_val );
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
		if ( get_option("Tradetracker_adminwidth") != "" && get_option("Tradetracker_adminwidth")  != $Tradetracker_adminwidth_val) {
			update_option( $Tradetracker_adminwidth_name, $Tradetracker_adminwidth_val );
		}
		if ( get_option("Tradetracker_showurl")  != $Tradetracker_showurl_val) {
			update_option( $Tradetracker_showurl_name, $Tradetracker_showurl_val );
		}
		if ( get_option("Tradetracker_usecss")  != $Tradetracker_usecss_val) {
			update_option( $Tradetracker_usecss_name, $Tradetracker_usecss_val );
		}
		if ( get_option("Tradetracker_csslink")  != $Tradetracker_csslink_val) {
			update_option( $Tradetracker_csslink_name, $Tradetracker_csslink_val );
		}
		if ( get_option("TTnewcategory")  != $Tradetracker_TTnewcategory_val) {
			update_option( $Tradetracker_TTnewcategory_name, $Tradetracker_TTnewcategory_val );
		}
		if ( get_option("Tradetracker_slidertheme")  != $Tradetracker_slidertheme_val) {
			update_option( $Tradetracker_slidertheme_name, $Tradetracker_slidertheme_val );
		}

	        //put an settings updated message on the screen
		$savedmessage = __("Settings saved", "ttstore");
		$saved = "<div id=\"ttstoreboxsaved\"><strong>".$savedmessage."</strong></div>";
	}
?>
<?php $adminwidth = get_option("Tradetracker_adminwidth"); ?>
<?php $adminheight = get_option("Tradetracker_adminheight"); ?>

<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
	<form name="form1" method="post" action="">
	<?php echo $ttstorehidden; ?>
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle"><? _e('Change plugin options.','ttstore'); ?></div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">
			<table width="<?php echo $adminwidth-15; ?>">
				<tr>
					<td width="400px">
						<label for="tradetrackerusenewcategorye" title="<?php _e('Do you want to use new category structure.','ttstore');?>" class="info">
							<?php _e("Do you want to use new category structure:", 'ttstore' ); ?> 
							<br />
							<?php _e('If you change this you will have to manually reselect all categories for all your stores.','ttstore');?>
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_TTnewcategory_name; ?>" <?php if($Tradetracker_TTnewcategory_val==1) {echo "checked";} ?> value="1"> <?php _e('Yes','ttstore');?>
						<br>
						<input type="radio" name="<?php echo $Tradetracker_TTnewcategory_name; ?>" <?php if($Tradetracker_TTnewcategory_val==0){echo "checked";} ?> value="0"> <?php _e('No','ttstore');?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr />
					</td>
				</tr>

				<tr>
					<td width="400px">
						<label for="tradetrackerslidertheme" title="<?php _e('Choose the theme of the price slider.','ttstore');?>" class="info">
							<?php _e("Choose the theme of the price slider:", 'ttstore' ); ?> 
							<br />
							<?php _e('You can preview them by going to Gallery on http://jqueryui.com/themeroller/.','ttstore');?>
						</label>
					</td>
					<td>
						<select name="<?php echo $Tradetracker_slidertheme_name; ?>">
							<option <?php if($Tradetracker_slidertheme_val == "base") { echo "selected=\"selected\""; } ?> value="base">Base</option>
							<option <?php if($Tradetracker_slidertheme_val == "ui-lightness") { echo "selected=\"selected\""; } ?> value="ui-lightness">Ui Lightness</option>
							<option <?php if($Tradetracker_slidertheme_val == "ui-darkness") { echo "selected=\"selected\""; } ?> value="ui-darkness">Ui Darkness</option>
							<option <?php if($Tradetracker_slidertheme_val == "smoothness") { echo "selected=\"selected\""; } ?> value="smoothness">Smoothness</option>
							<option <?php if($Tradetracker_slidertheme_val == "start") { echo "selected=\"selected\""; } ?> value="start">Start</option>
							<option <?php if($Tradetracker_slidertheme_val == "redmond") { echo "selected=\"selected\""; } ?> value="redmond">Redmond</option>
							<option <?php if($Tradetracker_slidertheme_val == "sunny") { echo "selected=\"selected\""; } ?> value="sunny">Sunny</option>
							<option <?php if($Tradetracker_slidertheme_val == "overcast") { echo "selected=\"selected\""; } ?> value="overcast">Overcast</option>
							<option <?php if($Tradetracker_slidertheme_val == "le-frog") { echo "selected=\"selected\""; } ?> value="le-frog">Le Frog</option>
							<option <?php if($Tradetracker_slidertheme_val == "flick") { echo "selected=\"selected\""; } ?> value="flick">Flick</option>
							<option <?php if($Tradetracker_slidertheme_val == "pepper-grinder") { echo "selected=\"selected\""; } ?> value="pepper-grinder">Pepper Grinder</option>
							<option <?php if($Tradetracker_slidertheme_val == "eggplant") { echo "selected=\"selected\""; } ?> value="eggplant">Eggplant</option>
							<option <?php if($Tradetracker_slidertheme_val == "dark-hive") { echo "selected=\"selected\""; } ?> value="dark-hive">Dark Hive</option>
							<option <?php if($Tradetracker_slidertheme_val == "cupertino") { echo "selected=\"selected\""; } ?> value="cupertino">Cupertino</option>
							<option <?php if($Tradetracker_slidertheme_val == "south-street") { echo "selected=\"selected\""; } ?> value="south-street">South Street</option>
							<option <?php if($Tradetracker_slidertheme_val == "blitzer") { echo "selected=\"selected\""; } ?> value="blitzer">Blitzer</option>
							<option <?php if($Tradetracker_slidertheme_val == "humanity") { echo "selected=\"selected\""; } ?> value="humanity">Humanity</option>
							<option <?php if($Tradetracker_slidertheme_val == "hot-sneaks") { echo "selected=\"selected\""; } ?> value="hot-sneaks">Hot Sneaks</option>
							<option <?php if($Tradetracker_slidertheme_val == "excite-bike") { echo "selected=\"selected\""; } ?> value="excite-bike">Excite Bike</option>
							<option <?php if($Tradetracker_slidertheme_val == "vader") { echo "selected=\"selected\""; } ?> value="vader">Vader</option>
							<option <?php if($Tradetracker_slidertheme_val == "dot-luv") { echo "selected=\"selected\""; } ?> value="dot-luv">Dot Luv</option>
							<option <?php if($Tradetracker_slidertheme_val == "mint-choc") { echo "selected=\"selected\""; } ?> value="mint-choc">Mint Choc</option>
							<option <?php if($Tradetracker_slidertheme_val == "black-tie") { echo "selected=\"selected\""; } ?> value="black-tie">Black Tie</option>
							<option <?php if($Tradetracker_slidertheme_val == "trontastic") { echo "selected=\"selected\""; } ?> value="trontastic">Trontastic</option>
							<option <?php if($Tradetracker_slidertheme_val == "swanky-purse") { echo "selected=\"selected\""; } ?> value="swanky-purse">Swanky Purse</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr />
					</td>
				</tr>

				<tr>
					<td width="400px">
						<label for="tradetrackerusecss" title="<?php _e('If you want to create your own CSS file.','ttstore');?>" class="info">
							<?php _e("Do you want to use a CSS file.:", 'ttstore' ); ?> 
							<br />
							<?php _e('If you enable this you won\'t be able to use add/delete layout. You can however use your own css file.','ttstore');?>
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_usecss_name; ?>" <?php if($Tradetracker_usecss_val==1) {echo "checked";} ?> value="1"> <?php _e('Yes','ttstore');?>
						<br>
						<input type="radio" name="<?php echo $Tradetracker_usecss_name; ?>" <?php if($Tradetracker_usecss_val==0){echo "checked";} ?> value="0"> <?php _e('No','ttstore');?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackercsslink" title="<?php _e('Where is the CSS file located','ttstore');?>" class="info">
							<?php _e("Full url to the CSS file:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="text" name="<?php echo $Tradetracker_csslink_name; ?>" value="<?php echo $Tradetracker_csslink_val; ?>" size="70"> <br />
<?php $exampleurl = plugins_url( 'style.css' , __FILE__ ); ?>
<?php printf(__('Make sure this is not saved in the plugins folder. Cause that will be overwritten with an update. For an example go to <a href="%s" target="_blank">here</a>','ttstore'),$exampleurl);?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr />
					</td>
				</tr>
				<tr>
					<td width="400px">
						<label for="tradetrackerdebugemail" title="<?php _e('Do you like to get an email when XML feeds are not imported?', 'ttstore');?>" class="info">
							<?php _e("Get email when import fails:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_debugemail_name; ?>" <?php if($Tradetracker_debugemail_val==1) {echo "checked";} ?> value="1"> <?php _e('Yes','ttstore');?>
						<br>
						<input type="radio" name="<?php echo $Tradetracker_debugemail_name; ?>" <?php if($Tradetracker_debugemail_val==0){echo "checked";} ?> value="0"> <?php _e('No','ttstore');?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerimporttool" title="<?php _e('Which tool should be used to import XML?','ttstore'); ?>" class="info">
							<?php _e("Which import tool:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<?php if (ini_get('allow_url_fopen') == true) { ?>
							<input type="radio" name="<?php echo $Tradetracker_importtool_name; ?>" <?php if($Tradetracker_importtool_val==1) {echo "checked";} ?> value="1"> <?php _e('Fopen (most reliable)','ttstore'); ?>
						<?php } ?>
						<?php if (function_exists('curl_init')) { ?>
							<br>
							<input type="radio" name="<?php echo $Tradetracker_importtool_name; ?>" <?php if($Tradetracker_importtool_val==2){echo "checked";} ?> value="2"> <?php _e('Curl/Fwrite (can run out of memory)','ttstore'); ?>
							<br>
							<input type="radio" name="<?php echo $Tradetracker_importtool_name; ?>" <?php if($Tradetracker_importtool_val==3){echo "checked";} ?> value="3"> <?php _e('Curl (sometimes causes issues)','ttstore'); ?>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerloadextra" title="<?php _e('Load the extra fields in the database, if you don\'t use extra fields it is smarter to disable them here','ttstore'); ?>" class="info">
							<?php _e("Import extra fields:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_loadextra_name; ?>" <?php if($Tradetracker_loadextra_val==1) {echo "checked";} ?> value="1"> <?php _e('Yes','ttstore'); ?>
						<br>
						<input type="radio" name="<?php echo $Tradetracker_loadextra_name; ?>" <?php if($Tradetracker_loadextra_val==0){echo "checked";} ?> value="0"> <?php _e('No','ttstore'); ?> <?php _e('(Can prevent timeouts, But then you cannot show extra fields)','ttstore'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackeradminheight" title="<?php _e('What height should the admin menu be?, standard is 460','ttstore'); ?>" class="info">
							<?php _e("Admin menu height:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="text" name="<?php echo $Tradetracker_adminheight_name; ?>" value="<?php echo $Tradetracker_adminheight_val; ?>" size="20">
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackeradminwidth" title="<?php _e('What width should the admin menu be?, standard is 1000','ttstore'); ?>" class="info">
							<?php _e("Admin menu width:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="text" name="<?php echo $Tradetracker_adminwidth_name; ?>" value="<?php echo $Tradetracker_adminwidth_val; ?>" size="20">
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackershowurl" title="<?php _e('Show url to plugin website in the source of the site','ttstore'); ?>" class="info">
							<?php _e("Show url to plugin website in the source:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_showurl_name; ?>" <?php if($Tradetracker_showurl_val==1) {echo "checked";} ?> value="1"> <?php _e('Yes','ttstore'); ?>
						<br>
						<input type="radio" name="<?php echo $Tradetracker_showurl_name; ?>" <?php if($Tradetracker_showurl_val==0){echo "checked";} ?> value="0"> <?php _e('No','ttstore'); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr />
						<?php _e('What should happen when you deactivate the plugin:','ttstore'); ?>
					</td>
				<tr>
					<td>
						<label for="tradetrackerremovelayout" title="<?php _e('Should all layouts be removed','ttstore'); ?>" class="info">
							<?php _e("Remove all layouts:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_removelayout_name; ?>" <?php if($Tradetracker_removelayout_val==1) {echo "checked";} ?> value="1"> <?php _e('Yes','ttstore'); ?> 
						<br>
						<input type="radio" name="<?php echo $Tradetracker_removelayout_name; ?>" <?php if($Tradetracker_removelayout_val==0){echo "checked";} ?> value="0"> <?php _e('No','ttstore'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerremovestores" title="<?php _e('Should all stores be removed','ttstore'); ?>" class="info">
							<?php _e("Remove all stores:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_removestores_name; ?>" <?php if($Tradetracker_removestores_val==1) {echo "checked";} ?> value="1"> <?php _e('Yes','ttstore'); ?>
						<br>
						<input type="radio" name="<?php echo $Tradetracker_removestores_name; ?>" <?php if($Tradetracker_removestores_val==0){echo "checked";} ?> value="0"> <?php _e('No','ttstore'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerremoveproducts" title="<?php _e('Should all products be removed','ttstore'); ?>" class="info">
							<?php _e("Remove all products:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_removeproducts_name; ?>" <?php if($Tradetracker_removeproducts_val==1) {echo "checked";} ?> value="1"> <?php _e('Yes','ttstore'); ?>
						<br>
						<input type="radio" name="<?php echo $Tradetracker_removeproducts_name; ?>" <?php if($Tradetracker_removeproducts_val==0){echo "checked";} ?> value="0"> <?php _e('No','ttstore'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerremovexml" title="<?php _e('Should all XML settings be removed','ttstore'); ?>" class="info">
							<?php _e("Remove all XML settings:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_removexml_name; ?>" <?php if($Tradetracker_removexml_val==1) {echo "checked";} ?> value="1"> <?php _e('Yes','ttstore'); ?>
						<br>
						<input type="radio" name="<?php echo $Tradetracker_removexml_name; ?>" <?php if($Tradetracker_removexml_val==0){echo "checked";} ?> value="0"> <?php _e('No','ttstore'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="tradetrackerremovexml" title="<?php _e('Should all other settings be removed','ttstore'); ?>" class="info">
							<?php _e("Remove all other settings:", 'ttstore' ); ?> 
						</label>
					</td>
					<td>
						<input type="radio" name="<?php echo $Tradetracker_removeother_name; ?>" <?php if($Tradetracker_removeother_val==1) {echo "checked";} ?> value="1"> <?php _e('Yes','ttstore'); ?>
						<br>
						<input type="radio" name="<?php echo $Tradetracker_removeother_name; ?>" <?php if($Tradetracker_removeother_val==0){echo "checked";} ?> value="0"> <?php _e('No','ttstore'); ?>
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