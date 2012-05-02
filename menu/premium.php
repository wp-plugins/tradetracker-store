<?php

function premium() {
	//global variables
	global $wpdb;
	global $ttstoresubmit;
	global $ttstorehidden;
	global $ttstoretable;
	//variables for this function
	$Tradetracker_premiumapi_name = 'Tradetracker_premiumapi';
	$Tradetracker_premiumprov_name = 'Tradetracker_premiumprov';
	$Tradetracker_premiumapi_val = get_option( $Tradetracker_premiumapi_name );

	//see if form has been submitted
	if( isset($_POST[ $ttstoresubmit ]) && $_POST[ $ttstoresubmit ] == 'Y' ) {
		$Tradetracker_premiumprov_val = $_POST['premiumprov'];
		$Tradetracker_premiumapi_val = str_replace(" ","", $_POST['premiumapi']);
		$remove_null_number = true;
		$Tradetracker_premiumapi_val = array_combine($Tradetracker_premiumprov_val, $Tradetracker_premiumapi_val);
		if ( get_option("Tradetracker_premiumapi")  != $Tradetracker_premiumapi_val) {
			update_option( $Tradetracker_premiumapi_name, $Tradetracker_premiumapi_val );
			update_option('Tradetracker_premiumupdate', "" );
			premium_updater();
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
			<div id="TB_ajaxWindowTitle">Insert the addon apikeys.</div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		<?php $adminheight = get_option("Tradetracker_adminheight"); ?>
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">
		<table width="985">
		<tr><td colspan="2"><b>Add extra XML Feeds</b></td></tr>
	<?php
		$providers = array('Daisycon' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=2', 'Zanox' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=4', 'Cleafs' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=3', 'TradeDoubler' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=5', 'Paidonresults' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=6', 'M4N' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=8', 'Bol' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=9', 'Belboon' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=10', 'Affiliatewindow' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=11');
		$i="1";
		foreach ($providers as $key => $value){
		$update = get_option('Tradetracker_premiumaccepted');
			if($update[$key]== "1") {
				$accepted = "Accepted";
			}elseif($update[$key]== "0") {
				$accepted = "buy an APIKey for ".$key." <a href=\"".$value."\" target=\"_blank\">here</a>";
			} else {
				$accepted = "buy an APIKey for ".$key." <a href=\"".$value."\" target=\"_blank\">here</a>";
			}

	?>
			<tr>
				<td>
					<label for="<?php echo $key; ?>" title="If you bought an API key to use <?php echo $key; ?> please fill it in here." class="info">
						<?php _e("".$key." APIKey:", 'tradetracker-xml' ); ?> 
					</label> 
				</td>
				<td>
					<input type="hidden" name="premiumprov[<?php echo $i;?>]" value="<?php echo $key; ?>">
					<input type="text" name="premiumapi[<?php echo $i;?>]" value="<?php echo $Tradetracker_premiumapi_val[$key]; ?>" size="40"> <?php echo $accepted; ?>
				</td>
			</tr>
	<?php
		$i++;
		}
	?>
		<tr><td colspan="2"><b>Add extra functions</b></td></tr>
	<?php
		$i="50";
		$providers = array('ProductPages'=> 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=7' );
		foreach ($providers as $key => $value){
		$update = get_option('Tradetracker_premiumaccepted');
			if($update[$key]== "1") {
				$accepted = "Accepted";
			}elseif($update[$key]== "0") {
				$accepted = "buy an APIKey for ".$key." <a href=\"".$value."\" target=\"_blank\">here</a>";
			} else {
				$accepted = "buy an APIKey for ".$key." <a href=\"".$value."\" target=\"_blank\">here</a>";
			}

	?>
			<tr>
				<td>
					<label for="<?php echo $key; ?>" title="If you bought an API key to use <?php echo $key; ?> please fill it in here." class="info">
						<?php _e("".$key." APIKey:", 'tradetracker-xml' ); ?> 
					</label> 
				</td>
				<td>
					<input type="hidden" name="premiumprov[<?php echo $i;?>]" value="<?php echo $key; ?>">
					<input type="text" name="premiumapi[<?php echo $i;?>]" value="<?php echo $Tradetracker_premiumapi_val[$key]; ?>" size="40"> <?php echo $accepted; ?>
				</td>
			</tr>
	<?php
		$i++;
		}
	?>
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