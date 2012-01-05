<?php
$providers = get_option('Tradetracker_premiumapi');
if($providers != "") {
foreach ($providers as $key => $value){
	$update = get_option('Tradetracker_premiumaccepted');
	if($update[$key]== "1") {
		$filename = WP_PLUGIN_DIR.'/tradetracker-store/cache/'.$value.'.php';
		if (file_exists($filename)) {
			include($filename);
		} else {
			premium_updater();
		}
	}

}
}
function premiumcheck() {
	$settingsselected = get_option("Tradetracker_settings");
	if (!empty($settingsselected)) { 
		if(function_exists('curl_init')) {
			if(get_option("Tradetracker_premiumapi")==""){
				echo "<div class=\"updated\"><p><strong><a href=\"admin.php?page=tradetracker-shop-premium\">Premium content</a> has not been enabled</strong></p></div>";
			}
		}
	}
}
function premium_ttstore() {
	global $wpdb;
	$hidden_field_name = 'mt_submit_hidden';

	$Tradetracker_premiumapi_name = 'Tradetracker_premiumapi';
	$Tradetracker_premiumapi_field_name = 'Tradetracker_premiumapi';

	$Tradetracker_premiumprov_name = 'Tradetracker_premiumprov';
	$Tradetracker_premiumprov_field_name = 'Tradetracker_premiumprov';

	$Tradetracker_premiumapi_val = get_option( $Tradetracker_premiumapi_name );
	$Tradetracker_premiumprov_val = get_option( $Tradetracker_premiumprov_name );

	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		$Tradetracker_premiumprov_val = $_POST['premiumprov'];
		$Tradetracker_premiumapi_val = str_replace(" ","", $_POST['premiumapi']);
		$remove_null_number = true;
		//$Tradetracker_premiumprov_val = remove_array_empty_values($Tradetracker_premiumprov_val, $remove_null_number);
		//$Tradetracker_premiumapi_val = remove_array_empty_values($Tradetracker_premiumapi_val, $remove_null_number);
		//$Tradetracker_premiumapi_val = safeArrayCombine($Tradetracker_premiumprov_val, $Tradetracker_premiumapi_val);
		$Tradetracker_premiumapi_val = array_combine($Tradetracker_premiumprov_val, $Tradetracker_premiumapi_val);
		if ( get_option("Tradetracker_premiumapi")  != $Tradetracker_premiumapi_val) {
			update_option( $Tradetracker_premiumapi_name, $Tradetracker_premiumapi_val );
			premium_updater();
		}
?>
		<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php
	}
?>
	<div class="plugindiv">
	<?php if (get_option("Tradetracker_settings")==1){ ?>
		<h2>Premium settings:</h2>
		<ul class="tabset_tabs">
			<li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
			<li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
			<li><a href="admin.php?page=tradetracker-shop-items#tab3">Items</a></li>
			<li><a href="admin.php?page=tradetracker-shop-overview#tab4">Overview</a></li>
			<li><a href="admin.php?page=tradetracker-shop-feedback#tab5">Feedback</a></li>
			<li><a href="admin.php?page=tradetracker-shop-premium#tab6" class="active">Premium</a></li>
			<li><a href="admin.php?page=tradetracker-shop-help#tab7" class="redhelp">Help</a></li>
		</ul>
		<div id="tab6" class="tabset_content">
   		<h2 class="tabset_label">Help</h2>
	<?php } if (get_option("Tradetracker_settings")==2){ ?>
		<h2>Premium settings:</h2>
		<ul class="tabset_tabs">
			<li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
			<li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
			<?php if ( get_option("Tradetracker_statsdash") == 1 ) { ?>
				<li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
			<?php } ?>
			<li><a href="admin.php?page=tradetracker-shop-layout#tab4">Layout</a></li>
			<li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
			<li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
			<li><a href="admin.php?page=tradetracker-shop-search#tab7">Overview</a></li>
			<li><a href="admin.php?page=tradetracker-shop-overview#tab8">Overview</a></li>
			<li><a href="admin.php?page=tradetracker-shop-feedback#tab9">Feedback</a></li>
			<li><a href="admin.php?page=tradetracker-shop-premium#tab10" class="active">Premium</a></li>
			<li><a href="admin.php?page=tradetracker-shop-help#tab11" class="redhelp">Help</a></li>
		</ul>
		<div id="tab10" class="tabset_content">
	<?php }
	if(!function_exists('curl_init')) {
		echo "Because your host does not support Curl you cannot use the premium content";
	} else {
	?>
		Below you are able to add your APIkey if you bought one. These will add extra possibilities to your site.
		<hr>
		<form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<table width="700">
		<tr><td colspan="2"><b>Add extra XML Feeds</b></td></tr>
	<?php
		$providers = array('Daisycon' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=2', 'Zanox' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=4', 'Cleafs' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=3', 'TradeDoubler' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=5', 'Paidonresults' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=6', 'Affilinet' => 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=8');
		$i="1";
		foreach ($providers as $key => $value){
		$update = get_option('Tradetracker_premiumaccepted');
			if($update[$key]== "1") {
				$accepted = "Accepted";
			}elseif($update[$key]== "0") {
				$accepted = "buy an APIKey for ".$key." <a href=\"".$value."\">here</a>";
			} else {
				$accepted = "buy an APIKey for ".$key." <a href=\"".$value."\">here</a>";
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
					<input type="text" name="premiumapi[<?php echo $i;?>]" value="<?php echo $Tradetracker_premiumapi_val[$key]; ?>" size="50"> <?php echo $accepted; ?>
				</td>
			</tr>
	<?php
		$i++;
		}
	?>
		</tr><td colspan="2">If you have extra XML providers you would like to have added please <a href="admin.php?page=tradetracker-shop-feedback">contact me</a>. If you are the first to request it you will get it for free.</td></tr>
		<tr><td colspan="2"><b>Add extra functions</b></td></tr>
	<?php
		$i="50";
		$providers = array('Product Pages'=> 'http://shop.wpaffiliatefeed.com/index.php?main_page=product_info&cPath=1&products_id=7' );
		foreach ($providers as $key => $value){
		$update = get_option('Tradetracker_premiumaccepted');
			if($update[$key]== "1") {
				$accepted = "Accepted";
			}elseif($update[$key]== "0") {
				$accepted = "buy an APIKey for ".$key." <a href=\"".$value."\">here</a>";
			} else {
				$accepted = "buy an APIKey for ".$key." <a href=\"".$value."\">here</a>";
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
					<input type="text" name="premiumapi[<?php echo $i;?>]" value="<?php echo $Tradetracker_premiumapi_val[$key]; ?>" size="50"> <?php echo $accepted; ?>
				</td>
			</tr>
	<?php
		$i++;
		}
	?>
		</table>
		<hr />
		<p class="submit">
		<b>Always save changes before pressing next.</b><br>
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /> 	
			<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help3'">
		</p>
	</form>
	 If you have other ideas i could add as premium content you will receive 10% of the income from that idea.
	<?php } ?>
		</div>
	</div>
<?php }

function premium_updater(){
	global $wpdb;
	$us = $_SERVER['HTTP_HOST'];
	$providers = get_option('Tradetracker_premiumapi');
	foreach ($providers as $key => $value){
		$api = $value;
		if(!empty($api)){
			$network = strtolower($key);
			$url = "http://wpaffiliatefeed.com/premium/answer.php?where=".$us."&api=".$api."";
			$ch = curl_init();
			$timeout = 5; // set to zero for no timeout
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$content = curl_exec($ch);
			curl_close($ch);
			if( $content == '0' ) {
			//Do something. For our example, kill the script.
				$search_array = get_option('Tradetracker_premiumaccepted');
				$search_array[$key] = '0'; 
				update_option('Tradetracker_premiumaccepted', $search_array );
				$search_array = get_option('Tradetracker_premiumupdate');
				if (array_key_exists($key, $search_array)) {
					unset($search_array[$key]);
				}
				update_option('Tradetracker_premiumupdate', $search_array );
			} else {
				$update = get_option('Tradetracker_premiumupdate');
				$filename = WP_PLUGIN_DIR.'/tradetracker-store/cache/'.$api.'.php';
				if (file_exists($filename)) {
					if($update[$key]!=$content) {
						$search_array = get_option('Tradetracker_premiumaccepted');
						$search_array[$key] = '1'; 
						update_option('Tradetracker_premiumaccepted', $search_array );
						$search_array = get_option('Tradetracker_premiumupdate');
						if (array_key_exists($key, $search_array)) {
							$search_array[$key] = $content; 
						} else {
							$search_array[$key] = $content; 
						}
						update_option('Tradetracker_premiumupdate', $search_array );
						$dir = WP_PLUGIN_DIR . "/tradetracker-store";
						$site_file = 'http://wpaffiliatefeed.com/premium/'.$network.'/'.$api.'.txt';
						if (function_exists('curl_init')) {
							$ch = curl_init($site_file);
							$fp = fopen($dir."/cache/".$api.".php", "w");
							curl_setopt($ch, CURLOPT_FILE, $fp);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_exec($ch);
							curl_close($ch);
							fclose($fp);
						}
					}
				} else {
					$search_array = get_option('Tradetracker_premiumaccepted');
					$search_array[$key] = '1'; 
					update_option('Tradetracker_premiumaccepted', $search_array );
					$search_array = get_option('Tradetracker_premiumupdate');
					if (array_key_exists($key, $search_array)) {
						$search_array[$key] = $content; 
					} else {
						array_push($search_array[$key], $content); 
					}
					update_option('Tradetracker_premiumupdate', $search_array );
					$dir = WP_PLUGIN_DIR . "/tradetracker-store";
					$site_file = 'http://wpaffiliatefeed.com/premium/'.$network.'/'.$api.'.txt';
					if (function_exists('curl_init')) {
						$ch = curl_init($site_file);
						$fp = fopen($dir."/cache/".$api.".php", "w");
						curl_setopt($ch, CURLOPT_FILE, $fp);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_exec($ch);
						curl_close($ch);
						fclose($fp);
					}
				}
			}
		}
	}
}
?>