<?php
function xmlfeed(){
	//global variables
	global $wpdb;
	global $ttstoresubmit;
	global $ttstorehidden;

	//variables for this function
	$Tradetracker_xml_name = 'Tradetracker_xml';
	$Tradetracker_xmlname_name = 'Tradetracker_xmlname';

	//filling variables from database
	$Tradetracker_xml_val = get_option( $Tradetracker_xml_name );
	$Tradetracker_xmlname_val = get_option( $Tradetracker_xmlname_name );

	//see if form has been submitted
	if( isset($_POST[ $ttstoresubmit ]) && $_POST[ $ttstoresubmit ] == 'Y' ) {

		//get posted data
		$Tradetracker_xml_val = $_POST['xmlfeed'];
		$Tradetracker_xmlconv_val = $_POST['xmlfeedconv'];
		$Tradetracker_xmlname_val = $_POST['xmlname'];

		//create an array of the xmlname and the needed converter
		foreach($Tradetracker_xml_val as $key => $value) {
			if($value==""){
				$Tradetracker_xmlconv_val[$key]="";
				$Tradetracker_xmlname_val[$key]="";
			}
		}
	
		//make sure the array is filled correctly
		$remove_null_number = true;
		$Tradetracker_xml_val = remove_array_empty_values($Tradetracker_xml_val, $remove_null_number);
		$Tradetracker_xmlconv_val = remove_array_empty_values($Tradetracker_xmlconv_val, $remove_null_number);
		$Tradetracker_xml_val = safeArrayCombine($Tradetracker_xml_val, $Tradetracker_xmlconv_val);

		//save the posted value in the database
		if ( get_option("Tradetracker_xml")  != $Tradetracker_xml_val) {
			update_option( $Tradetracker_xml_name, $Tradetracker_xml_val );
		}
		if ( get_option("Tradetracker_xmlname")  != $Tradetracker_xmlname_val) {
			$Tradetracker_xmlname_val = remove_array_empty_values($Tradetracker_xmlname_val, $remove_null_number);
			delete_option($Tradetracker_xmlname_name);
			update_option( $Tradetracker_xmlname_name, $Tradetracker_xmlname_val );
		}
		

	        //put an settings updated message on the screen
		$savedmessage = __("Settings saved, click Update Items when all feeds are added", "ttstore");
		$saved = "<div id=\"ttstoreboxsaved\"><strong>".$savedmessage."</strong></div>";
?>
		
<?php
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
			<div id="TB_ajaxWindowTitle"><?php _e("Add or Edit XML feeds.","ttstore"); ?></div>
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
					<strong><?php _e("Link to XML","ttstore"); ?></strong>
				</td>
				<td>
					<strong><?php _e("XML Name","ttstore"); ?></strong>
				</td>
				<td>
					<strong><?php _e("XML Provider","ttstore"); ?></strong>
				</td>
			</tr>
			
					<?php
					$i="0";
					if($Tradetracker_xml_val != ""){
						$file = $Tradetracker_xml_val;
						foreach($file as $key => $value) {
							echo "<tr><td>";
							if($key !=""){
								if($Tradetracker_xmlname_val[$i]=="") { 
									$xmlname = "input a xml name"; 
								} else { 
									$xmlname = $Tradetracker_xmlname_val[$i]; 
								}
								
								echo "<input type=\"text\" name=\"xmlfeed[".$i."]\" value=\"".$key."\" size=\"50\">";
								echo "</td><td>";
								echo "<input type=\"text\" name=\"xmlname[".$i."]\" value=\"".$xmlname."\" size=\"20\">";
								echo "</td><td>";
								if(get_option('tt_premium_provider')=="") {
									echo "<input type=\"hidden\" name=\"xmlfeedconv[".$i."]\" value=\"".$value."\" size=\"60\">";
								} else {
									echo "<select name=\"xmlfeedconv[".$i."]\" width=\"120\" style=\"width: 120px\">";
									$provider = get_option('tt_premium_provider');
									foreach($provider as $providers) {
										if($providers==$value){
											echo "<option value=\"".$providers."\" selected=\"selected\">".$providers."</option>";
										} else {
											echo "<option value=\"".$providers."\" >".$providers."</option>";
										}
									}
									echo "</select>";
								}
							echo "</td></tr>";
							$i++;
							}
						}
					}
					echo "<tr><td>";
					echo "<input type=\"text\" name=\"xmlfeed[".$i."]\" value=\"\" size=\"50\">";
					echo "</td><td>";
					echo "<input type=\"text\" name=\"xmlname[".$i."]\" value=\"input a xml name\" size=\"20\">";
					echo "</td><td>";
					if(get_option('tt_premium_provider')=="") {
						echo "<input type=\"hidden\" name=\"xmlfeedconv[".$i."]\" value=\"tradetracker\" size=\"40\">";
					} else {
						echo "<select name=\"xmlfeedconv[".$i."]\" width=\"120\" style=\"width: 120px\">";
						$provider = get_option('tt_premium_provider');
						foreach($provider as $providers) {
							echo "<option value=\"".$providers."\">".$providers."</option>";
						}
						echo "</select>";
					}
					?>			
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
			<INPUT type="button" name="Close" class="button-secondary" value="<?php  _e('Update Items','ttstore') ?>" onclick="location.href='admin.php?page=tt-store&update=yes'"> 
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /> 
			<INPUT type="button" name="Close" class="button-secondary" value="<?php esc_attr_e('Close') ?>" onclick="location.href='admin.php?page=tt-store'"> 
		</div>
	</form>
	</div>
</div>
<?php	
}
?>