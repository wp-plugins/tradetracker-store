<?php
function xmlfeed(){
	//global variables
	global $wpdb;
	global $ttstoresubmit;
	global $ttstorehidden;
	global $ttstorexmltable;

	//variables for this function
	$Tradetracker_xml_name = 'Tradetracker_xml';
	$Tradetracker_xmlname_name = 'Tradetracker_xmlname';

	//filling variables from database
	$Tradetracker_xml_val = get_option( $Tradetracker_xml_name );
	$Tradetracker_xmlname_val = get_option( $Tradetracker_xmlname_name );
	if(isset($_GET['edit'])){
		$TTedit = $_GET['edit'];
	}
	
	if(isset($_GET['delete'])){
		$wpdb->query( $wpdb->prepare( "DELETE FROM $ttstorexmltable WHERE id = %d",$_GET['delete'] ));
		$savedmessage = __("Feed deleted", "ttstore");
		$saved = "<div id=\"ttstoreboxsaved\"><strong>".$savedmessage."</strong></div>";
	}

	//see if form has been submitted
	if( isset($_POST[ $ttstoresubmit ]) && $_POST[ $ttstoresubmit ] == 'Y' ) {
		if(isset($_POST['xmlfeedid']) && !empty($_POST['xmlfeedid'])){
			$Tradetracker_xmlid_val = $_POST['xmlfeedid'];
			$Tradetracker_xml_val = $_POST['xmlfeed'];
			$Tradetracker_xmlconv_val = $_POST['xmlfeedconv'];
			$Tradetracker_xmlname_val = $_POST['xmlname'];	
			if(!empty($Tradetracker_xml_val)){
				$wpdb->update( 
					''.$ttstorexmltable.'', 
					array( 
						'xmlfeed' => ''.$Tradetracker_xml_val.'',	
						'xmlprovider' => ''.$Tradetracker_xmlconv_val.'',
						'xmlname' => ''.$Tradetracker_xmlname_val.''
					), 
					array( 'id' => ''.$Tradetracker_xmlid_val.'' ), 
					array( 
						'%s',
						'%s',
						'%s'
					), 
					array( '%d' ) 
				);
				?>
<script type="text/javascript">
<!--
window.location = "admin.php?page=tt-store&option=xmlfeed"
//-->
</script>

				<?php
			}			
		} else {
			//get posted data
			$Tradetracker_xml_val = $_POST['xmlfeed'];
			$Tradetracker_xmlconv_val = $_POST['xmlfeedconv'];
			$Tradetracker_xmlname_val = $_POST['xmlname'];
			if(!empty($Tradetracker_xml_val)){
	       	 		$currentpage["xmlfeed"]=$Tradetracker_xml_val;
       		 		$currentpage["xmlprovider"]=$Tradetracker_xmlconv_val;
       		 		$currentpage["xmlname"]=$Tradetracker_xmlname_val;
       		 		$currentpage["xmltitle"]=" ";
       		 		$currentpage["xmlimage"]= " ";
       		 		$currentpage["xmldescription"]= " ";
       		 		$currentpage["xmlprice"]= " ";
       		 		$wpdb->insert( $ttstorexmltable, $currentpage);
				$multiid = $wpdb->insert_id;
			}
		}

	        //put an settings updated message on the screen
		$savedmessage = __("Feed added, click Update Items when all feeds are added", "ttstore");
		$saved = "<div id=\"ttstoreboxsaved\"><strong>".$savedmessage."</strong></div>";
?>
		
<?php
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
			<div id="TB_ajaxWindowTitle"><?php _e("Add or Edit XML feeds.","ttstore"); ?></div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">
			<table width="<?php echo $adminwidth-15; ?>">
			<?php
				$xmlfeed=$wpdb->get_results("SELECT xmlfeed, xmlname, xmlprovider, id FROM ".$ttstorexmltable." order by xmlname");
				if(count($xmlfeed)>0){
			?>
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
				<td>
					<strong><?php  _e("Edit","ttstore"); ?></strong>
				</td>
				<td>
					<strong><?php _e("Delete","ttstore"); ?></strong>
				</td>
			</tr>
				<?php

					//$xmlfeed1 = get_option("Tradetracker_xmlname");
					foreach ($xmlfeed as $xml) {
						echo "<tr><td>";
						echo "<a href=\"".$xml->xmlfeed."\">Feed</a>";
						echo "</td><td>";
						echo $xml->xmlname;
						echo "</td><td>";
						echo $xml->xmlprovider;	
						echo "</td><td>";
						echo "<a href=\"admin.php?page=tt-store&option=xmlfeed&edit=".$xml->id."\">".__("Edit","ttstore")."</a>";
						echo "</td><td>";
						echo "<a href=\"admin.php?page=tt-store&option=xmlfeed&delete=".$xml->id."\">".__("Delete","ttstore")."</a>";
						echo "</td></tr>";				
					}
					echo "<td colspan=\"5\"><hr></td></tr></table>";
				}
					?>
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
				if(isset($TTedit)&&!empty($TTedit)){
					$oldfeed = $wpdb->get_row("SELECT xmlfeed, xmlname, xmlprovider, id FROM ".$ttstorexmltable." where id='$TTedit'");
					echo "<tr><td>";
					echo "<input type=\"text\" name=\"xmlfeed\" value=\"".$oldfeed->xmlfeed."\" size=\"50\">";
					echo "</td><td>";
					echo "<input type=\"text\" name=\"xmlname\" value=\"".$oldfeed->xmlname."\" size=\"20\">";
					echo "</td><td>";
					echo "<input type=\"hidden\" name=\"xmlfeedid\" value=\"".$oldfeed->id."\" size=\"40\">";

					if(get_option('tt_premium_provider')=="") {
						echo "<input type=\"hidden\" name=\"xmlfeedconv\" value=\"tradetracker\" size=\"40\">";
					} else {
						echo "<select name=\"xmlfeedconv\" width=\"120\" style=\"width: 120px\">";
						$provider = get_option('tt_premium_provider');
						foreach($provider as $providers) {
							if($providers == $oldfeed->xmlprovider){
								echo "<option value=\"".$providers."\" selected=\"selected\">".$providers."</option>";
							} else {
								echo "<option value=\"".$providers."\">".$providers."</option>";
							}
						}
						echo "</select>";
					}

				} else {
					echo "<tr><td>";
					echo "<input type=\"text\" name=\"xmlfeed\" value=\"\" size=\"50\">";
					echo "</td><td>";
					echo "<input type=\"text\" name=\"xmlname\" value=\"input a xml name\" size=\"20\">";
					echo "</td><td>";
					if(get_option('tt_premium_provider')=="") {
						echo "<input type=\"hidden\" name=\"xmlfeedconv\" value=\"tradetracker\" size=\"40\">";
					} else {
						echo "<select name=\"xmlfeedconv\" width=\"120\" style=\"width: 120px\">";
						$provider = get_option('tt_premium_provider');
						foreach($provider as $providers) {
							echo "<option value=\"".$providers."\">".$providers."</option>";
						}
						echo "</select>";
					}

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