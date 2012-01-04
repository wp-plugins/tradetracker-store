<?php
function remove_array_empty_values($array, $remove_null_number = true)
{
	$new_array = array();

	$null_exceptions = array();

	foreach ($array as $key => $value)
	{
		$value = trim($value);

        if($remove_null_number)
		{
	        $null_exceptions[] = '0';
		}

        if(!in_array($value, $null_exceptions) && $value != "")
		{
            $new_array[] = $value;
        }
    }
    return $new_array;
}
function safeArrayCombine($keys, $values) { 
    $combinedArray = array(); 
        
    for ($i=0, $keyCount = count($keys); $i < $keyCount; $i++) { 
         $combinedArray[$keys[$i]] = $values[$i]; 
    } 
        
    return $combinedArray; 
} 

function tradetracker_store_options() {
	global $wpdb;
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	if(!class_exists('SoapClient')){
		echo "<div class=\"updated\"><p><strong><a href=\"http://www.electrictoolbox.com/class-soapclient-not-found/\" target=\"_blank\">SoapClient</a> is not enabled on your hosting. Stats are disabled.</strong></p></div>"; 
	}
	// variables for the field and option names 
	$hidden_field_name = 'mt_submit_hidden';
	$Tradetracker_buynow_name = 'Tradetracker_buynow';
	$Tradetracker_buynow_field_name = 'Tradetracker_buynow';

	$Tradetracker_xml_name = 'Tradetracker_xml';
	$Tradetracker_xml_field_name = 'Tradetracker_xml';

	$Tradetracker_xmlupdate_name = 'Tradetracker_xmlupdate';
	$Tradetracker_xmlupdate_field_name = 'Tradetracker_xmlupdate';

	$Tradetracker_debugemail_name = 'Tradetracker_debugemail';
	$Tradetracker_debugemail_field_name = 'Tradetracker_debugemail';

	$Tradetracker_xmlname_name = 'Tradetracker_xmlname';
	$Tradetracker_xmlname_field_name = 'Tradetracker_xmlname';

	$Tradetracker_productid_name = 'Tradetracker_productid';
	$Tradetracker_productid_field_name = 'Tradetracker_productid';

	$Tradetracker_amount_name = 'Tradetracker_amount';
	$Tradetracker_amount_field_name = 'Tradetracker_amount';

	$Tradetracker_lightbox_name = 'Tradetracker_lightbox';
	$Tradetracker_lightbox_field_name = 'Tradetracker_lightbox';

	$Tradetracker_statsdash_name = 'Tradetracker_statsdash';
	$Tradetracker_statsdash_field_name = 'Tradetracker_statsdash';

	$Tradetracker_currency_name = 'Tradetracker_currency';
	$Tradetracker_currency_field_name = 'Tradetracker_currency';
	
	$Tradetracker_newcur_name = 'Tradetracker_newcur';
	$Tradetracker_newcur_field_name = 'Tradetracker_newcur';

	$Tradetracker_extra_name = 'Tradetracker_extra';
	$Tradetracker_extra_field_name = 'Tradetracker_extra';

	$Tradetracker_currencyloc_name = 'Tradetracker_currencyloc';
	$Tradetracker_currencyloc_field_name = 'Tradetracker_currencyloc';

	// Read in existing option value from database
	$Tradetracker_buynow_val = get_option( $Tradetracker_buynow_name );
	$Tradetracker_xml_val = get_option( $Tradetracker_xml_name );
	$Tradetracker_xmlupdate_val = get_option( $Tradetracker_xmlupdate_name );
	$Tradetracker_debugemail_val = get_option( $Tradetracker_debugemail_name );
	$Tradetracker_xmlname_val = get_option( $Tradetracker_xmlname_name );
	$Tradetracker_productid_val = get_option( $Tradetracker_productid_name );
	$Tradetracker_amount_val = get_option( $Tradetracker_amount_name );
	$Tradetracker_lightbox_val = get_option( $Tradetracker_lightbox_name );
	$Tradetracker_statsdash_val = get_option( $Tradetracker_statsdash_name );
	$Tradetracker_currency_val = get_option( $Tradetracker_currency_name );
	$Tradetracker_currencyloc_val = get_option( $Tradetracker_currencyloc_name );
	$Tradetracker_newcur_val = get_option( $Tradetracker_newcur_name );
	$Tradetracker_extra_val = get_option( $Tradetracker_extra_name );


	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
	if (get_option("Tradetracker_settings")==1){
	// Read their posted value
		$Tradetracker_buynow_val = $_POST[ $Tradetracker_buynow_field_name ];
		//$Tradetracker_xml_val = $_POST[ $Tradetracker_xml_field_name ];
		if($_POST['oldcur'] != ""){
			$Tradetracker_newcur_val = "";
			$a1=$_POST['oldcur']; 
			$a2 = $_POST['newcur']; 
			$Tradetracker_newcur_val = array_combine($a1,$a2);
		}
		

		$Tradetracker_xml_val = $_POST['xmlfeed'];
		$Tradetracker_xmlconv_val = $_POST['xmlfeedconv'];
		$Tradetracker_xmlname_val = $_POST['xmlname'];
		$remove_null_number = true;
		$Tradetracker_xml_val = remove_array_empty_values($Tradetracker_xml_val, $remove_null_number);
		$Tradetracker_xmlconv_val = remove_array_empty_values($Tradetracker_xmlconv_val, $remove_null_number);
		$Tradetracker_xmlname_val = safeArrayCombine($Tradetracker_xml_val, $Tradetracker_xmlname_val);
		$Tradetracker_xml_val = safeArrayCombine($Tradetracker_xml_val, $Tradetracker_xmlconv_val);
		$Tradetracker_extra_val = $_POST['extra'];
		$Tradetracker_debugemail_val = $_POST[ $Tradetracker_debugemail_field_name ];
		$Tradetracker_xmlupdate_val = $_POST[ $Tradetracker_xmlupdate_field_name ];
		$Tradetracker_productid_val = $_POST[ $Tradetracker_productid_field_name ];
		$Tradetracker_amount_val = $_POST[ $Tradetracker_amount_field_name ];
		$Tradetracker_lightbox_val = $_POST[ $Tradetracker_lightbox_field_name ];
		$Tradetracker_statsdash_val = get_option( $Tradetracker_statsdash_name );
		$Tradetracker_currency_val = $_POST[ $Tradetracker_currency_field_name ];
		$Tradetracker_currencyloc_val = $_POST[ $Tradetracker_currencyloc_field_name ];
	}

	if (get_option("Tradetracker_settings")==2){
	// Read their posted value
		// $Tradetracker_xml_val = $_POST[ $Tradetracker_xml_field_name ];
		if($_POST['oldcur'] != ""){
			$Tradetracker_newcur_val = "";
			$a1=$_POST['oldcur']; 
			$a2 = $_POST['newcur']; 
			$Tradetracker_newcur_val = array_combine($a1,$a2);
		}
		$Tradetracker_xml_val = $_POST['xmlfeed'];
		$Tradetracker_xmlconv_val = $_POST['xmlfeedconv'];
		$Tradetracker_extra_val = $_POST['extra'];
		$Tradetracker_xmlname_val = $_POST['xmlname'];
		foreach($Tradetracker_xml_val as $key => $value) {
			if($value==""){
				$Tradetracker_xmlconv_val[$key]="";
				$Tradetracker_xmlname_val[$key]="";
			}
		}		
		$remove_null_number = true;
		// $Tradetracker_xmlname_val = array_combine($Tradetracker_xml_val, $Tradetracker_xmlname_val);
		$Tradetracker_xml_val = remove_array_empty_values($Tradetracker_xml_val, $remove_null_number);
		$Tradetracker_xmlconv_val = remove_array_empty_values($Tradetracker_xmlconv_val, $remove_null_number);
		$Tradetracker_xml_val = safeArrayCombine($Tradetracker_xml_val, $Tradetracker_xmlconv_val);		
		$Tradetracker_xmlupdate_val = $_POST[ $Tradetracker_xmlupdate_field_name ];
		$Tradetracker_debugemail_val = $_POST[ $Tradetracker_debugemail_field_name ];
		$Tradetracker_productid_val = get_option( $Tradetracker_productid_name );
		$Tradetracker_amount_val = get_option( $Tradetracker_amount_name );
		$Tradetracker_lightbox_val = get_option( $Tradetracker_lightbox_name );
		$Tradetracker_statsdash_val = $_POST[ $Tradetracker_statsdash_field_name ];
		$Tradetracker_currency_val = $_POST[ $Tradetracker_currency_field_name ];
		$Tradetracker_currencyloc_val = $_POST[ $Tradetracker_currencyloc_field_name ];
		
	}


        // Save the posted value in the database
		if ( get_option("Tradetracker_xml")  != $Tradetracker_xml_val) {
			update_option( $Tradetracker_xml_name, $Tradetracker_xml_val );
			xml_updater();
		}
		if ( get_option("Tradetracker_xmlupdate")  != $Tradetracker_xmlupdate_val) {
			update_option( $Tradetracker_xmlupdate_name, $Tradetracker_xmlupdate_val );
			wp_clear_scheduled_hook('xmlscheduler');
		}
		if ( get_option("Tradetracker_debugemail")  != $Tradetracker_debugemail_val) {
			update_option( $Tradetracker_debugemail_name, $Tradetracker_debugemail_val );
		}
		if ( get_option("Tradetracker_buynow")  != $Tradetracker_buynow_val) {
			update_option( $Tradetracker_buynow_name, $Tradetracker_buynow_val );
		}
		if ( get_option("Tradetracker_xmlname")  != $Tradetracker_xmlname_val) {
			update_option( $Tradetracker_xmlname_name, $Tradetracker_xmlname_val );
		}
		if ( get_option("Tradetracker_productid")  != $Tradetracker_productid_val) {
			update_option( $Tradetracker_productid_name, $Tradetracker_productid_val );
		}
		if ( get_option("Tradetracker_amount")  != $Tradetracker_amount_val) {
			update_option( $Tradetracker_amount_name, $Tradetracker_amount_val );
		}	
		if ( get_option("Tradetracker_lightbox")  != $Tradetracker_lightbox_val) {
			update_option( $Tradetracker_lightbox_name, $Tradetracker_lightbox_val );
		}
		if ( get_option("Tradetracker_statsdash")  != $Tradetracker_statsdash_val) {
			update_option( $Tradetracker_statsdash_name, $Tradetracker_statsdash_val );
		}
		if ( get_option("Tradetracker_currency")  != $Tradetracker_currency_val) {
			update_option( $Tradetracker_currency_name, $Tradetracker_currency_val );
		}
		if ( get_option("Tradetracker_currencyloc")  != $Tradetracker_currencyloc_val) {
			update_option( $Tradetracker_currencyloc_name, $Tradetracker_currencyloc_val );
		}
		if ( get_option("Tradetracker_newcur")  != $Tradetracker_newcur_val) {
			update_option( $Tradetracker_newcur_name, $Tradetracker_newcur_val );
		}
		if ( get_option("Tradetracker_extra")  != $Tradetracker_extra_val) {
			update_option( $Tradetracker_extra_name, $Tradetracker_extra_val );
		}

        // Put an settings updated message on the screen

?>
		<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

	}

	// Now display the settings editing screen

	echo '<div class="wrap">';



	// header

	echo "<h2>" . __( 'Tradetracker Store Setup', 'menu-test' ) . "</h2>";

	// settings form
    	ttstoreheader();
?>

<style type="text/css" media="screen">
.info {
		border-bottom: 1px dotted #666;
		cursor: help;
	}

</style>
<div class="plugindiv">
	<?php if (get_option("Tradetracker_settings")==1){ ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2" class="active">Settings</a></li>
   <li><a href="admin.php?page=tradetracker-shop-items#tab3">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab4">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab5">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-premium#tab6" class="greenpremium">Premium</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab7" class="redhelp">Help</a></li>
</ul>
	<?php } if (get_option("Tradetracker_settings")==2){ ?>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2" class="active">Settings</a></li>
		<?php if ( get_option("Tradetracker_statsdash") == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-search#tab7">Search</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab8">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab9">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-premium#tab10" class="greenpremium">Premium</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab11" class="redhelp">Help</a></li>
<?php 	$provider = get_option('tt_premium_function');
	foreach($provider as $providers) {
		if($providers == "productpage"){ 
			echo "<li><a href=\"admin.php?page=tradetracker-shop-productpage#tab12\">Product Page</a></li>"; 
		} 
	}
?>
</ul>
	<?php } ?>

<div id="tab2" class="tabset_content">
   <h2 class="tabset_label">Setup</h2>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<table width="700">

		<tr>
			<td width="150">
				<label for="tradetrackerxml" title="Add the link to the XML file you've recieved from tradetracker here." class="info">
					<?php _e("Tradetracker XML:", 'tradetracker-xml' ); ?> 
				</label><br><a href="admin.php?page=tradetracker-shop-help#help2" target="_blank">Install Instructions</a> 
			</td>
			<td>
				<?php
				if($Tradetracker_xml_val != ""){
					$file = $Tradetracker_xml_val;
					$i="0";
					foreach($file as $key => $value) {
						if($key !=""){
							if($Tradetracker_xmlname_val[$i]=="") { $xmlname = "input a xml name"; } else { $xmlname = $Tradetracker_xmlname_val[$i]; }
							echo "<input type=\"text\" name=\"xmlfeed[".$i."]\" value=\"".$key."\" size=\"40\"><input type=\"text\" name=\"xmlname[".$i."]\" value=\"".$xmlname."\" size=\"20\">";
							if(get_option('tt_premium_provider')=="") {
								echo "<input type=\"hidden\" name=\"xmlfeedconv[".$i."]\" value=\"".$value."\" size=\"60\">";
							} else {
								echo "<select name=\"xmlfeedconv[".$i."]\" width=\"100\" style=\"width: 100px\">";
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

							echo "<br>";
							$i++;
						}
					}
				}
				echo "<input type=\"text\" name=\"xmlfeed[".$i."]\" value=\"\" size=\"40\"><input type=\"text\" name=\"xmlname[".$i."]\" value=\"input a xml name\" size=\"20\">";
				if(get_option('tt_premium_provider')=="") {
					echo "<input type=\"hidden\" name=\"xmlfeedconv[".$i."]\" value=\"tradetracker\" size=\"40\">";
				} else {
					echo "<select name=\"xmlfeedconv[".$i."]\" width=\"100\" style=\"width: 100px\">";
					$provider = get_option('tt_premium_provider');
					foreach($provider as $providers) {
						echo "<option value=\"".$providers."\">".$providers."</option>";
					}
					echo "</select>";
				}


				?>
				
			</td>
		</tr>
		<tr>
			<td>
				<label for="tradetrackerdebugemail" title="Do you like to get an email when XML feeds are not imported?" class="info">
					<?php _e("Get email when import fails:", 'tradetracker-debugemail' ); ?> 
				</label>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_debugemail_field_name; ?>" <?php if($Tradetracker_debugemail_val==1) {echo "checked";} ?> value="1"> Yes 
				<br>
				<input type="radio" name="<?php echo $Tradetracker_debugemail_field_name; ?>" <?php if($Tradetracker_debugemail_val==0){echo "checked";} ?> value="0"> No
			</td>
		</tr>

		<tr>
			<td>
				<label for="tradetrackerextrafield" title="Which extra fields would you like to use?" class="info">
					<?php _e("Which extra fields?:", 'tradetracker-extra' ); ?> 
				</label>
			</td>
			<td>
			<?php
				$extra = get_option("Tradetracker_xml_extra");
				if(!empty($extra)){
				echo "<table width=\"400\">";
				$i="1";
				foreach($extra as $extraselect) {
					if($i=="1"){
						echo "<tr><td>";
					} else {
						echo "<td>";
					}
					if(!empty($Tradetracker_extra_val)){
						if(in_array($extraselect, $Tradetracker_extra_val, true)) {
							echo "<input type=\"checkbox\" checked=\"yes\" name=\"extra[]\" value=\"".$extraselect."\" />".$extraselect."<br />";
						} else {
							echo "<input type=\"checkbox\" name=\"extra[]\" value=\"".$extraselect."\" />".$extraselect."<br />";
						}
					} else {
							echo "<input type=\"checkbox\" name=\"extra[]\" value=\"".$extraselect."\" />".$extraselect."<br />";
					}
					if($i=="1"){
						echo "</td>";
						$i++;
					} else {
						echo "</td></tr>";
						$i--;
					}
					
				}
				echo "</table>";
				}
			?>
			</td>
		</tr>
		<tr>
		<tr>
			<td>
				<label for="tradetrackerupdate" title="When should it update?, standard is 00:00:00" class="info">
					<?php _e("Update time:", 'tradetracker-update' ); ?> 
				</label>
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_xmlupdate_field_name; ?>" value="<?php if($Tradetracker_xmlupdate_val==""){ echo "00:00:00"; } else { echo $Tradetracker_xmlupdate_val;} ?>" size="50"> Time has to be in hh:mm:ss
			</td>
		</tr>

			<td colspan="2">
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<label for="tradetrackercurrency" title="Do you like to use fill in your own currency or get it from the XML?" class="info">
					<?php _e("Use your own currency symbol:", 'tradetracker-currency' ); ?> 
				</label>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_currency_field_name; ?>" <?php if($Tradetracker_currency_val==1) {echo "checked";} ?> value="1"> Yes 
				<br>
				<input type="radio" name="<?php echo $Tradetracker_currency_field_name; ?>" <?php if($Tradetracker_currency_val==0){echo "checked";} ?> value="0"> No
			</td>
		</tr>
		<tr>
			<td>
				<label for="tradetrackercurrencyloc" title="Do you like to have the currency before or after the price?" class="info">
					<?php _e("Location of the currency:", 'tradetracker-currencyloc' ); ?> 
				</label>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_currencyloc_field_name; ?>" <?php if($Tradetracker_currencyloc_val==1) {echo "checked";} ?> value="1"> After the price
				<br>
				<input type="radio" name="<?php echo $Tradetracker_currencyloc_field_name; ?>" <?php if($Tradetracker_currencyloc_val==0){echo "checked";} ?> value="0"> Before the price
			</td>
		</tr>
		<?php 
		if($Tradetracker_currency_val==1) {
		?>
			<tr>
				<td colspan="2">
					<b>Adjust Currency:</b>
				</td>
			</tr>
			<tr>
				<td>
					<label for="tradetrackercurrency" title="Current currency in the XML?" class="info">
						<?php _e("XML Currency:", 'tradetracker-currency' ); ?> 
					</label>
				</td>
				<td>
					<label for="tradetrackercurrency" title="What would you like to show instead of the XML currency?" class="info">
						<?php _e("New Currency:", 'tradetracker-currency' ); ?> 
					</label>
				</td>
			</tr>
			<?php
			
			$tablexml = PRO_TABLE_PREFIX."store";
			$currency=$wpdb->get_results("SELECT currency FROM ".$tablexml." group by currency");
			$i="0";
			foreach ($currency as $currency_val){
			$array = get_option( $Tradetracker_newcur_name );
			$key = $currency_val->currency; 
			$value = $array[$key]; 
			?>
				<tr>
					<td>
						<input type="text" readonly="readonly" name="oldcur[<?php echo $i; ?>]" value="<?php echo $currency_val->currency; ?>">
					</td>
					<td>
						<input type="text" name="newcur[<?php echo $i; ?>]" value="<?php echo $array[$key]; ?>">						
					</td>
				</tr>
			<?php
			$i++;
			}
		}
		?>

	<?php if (get_option("Tradetracker_settings")==1){ ?>
		<tr>
			<td colspan="2">
				<hr>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<h2>Product Settings</h2>
			</td>
		</tr>

		<tr>
			<td>
				<label for="tradetrackerproductid" title="If you'd only like to show certain items fill in the productID here, seperated by a comma (for instance: 298,300,500 ). This will override the limit you've set below." class="info">
					<?php _e("Tradetracker productID:", 'tradetracker-xml' ); ?> 
				</label> 
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_productid_field_name; ?>" value="<?php echo $Tradetracker_productid_val; ?>" size="50"> 
				<a href="admin.php?page=tradetracker-shop-items" target="_blank">Find productID</a>
			</td>
		</tr>
		<tr>
			<td>
				<label for="tradetrackerbuynow" title="What text would you like on the button (standard will be Buy Now)." class="info">
					<?php _e("Buy Button text:", 'tradetracker-buynow' ); ?> 
				</label> 
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_buynow_field_name; ?>" value="<?php echo $Tradetracker_buynow_val; ?>" size="50"> 
			</td>
		</tr>
		<?php if ($Tradetracker_productid_val==""){ ?>
		<tr>
			<td>
				<label for="tradetrackerxml" title="Choose the amount of items to show on the site. Default is 12." class="info">
					<?php _e("Amount of items to show:", 'tradetracker-amount' ); ?> 
				</label>
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_amount_field_name; ?>" value="<?php echo $Tradetracker_amount_val; ?>" size="5">
			</td>
		</tr>
		<?php } else {?>
			<input type="hidden" name="<?php echo $Tradetracker_amount_field_name; ?>" value="<?php echo $Tradetracker_amount_val; ?>">
		<?php } ?>


		<tr>
			<td>
				<label for="tradetrackerlightbox" title="Do you want to use lightbox for the images? You will need an extra plugin for that" class="info">
					<?php _e("Use Lightbox:", 'tradetracker-lightbox' ); ?> 
				</label>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_lightbox_field_name; ?>" <?php if($Tradetracker_lightbox_val==1) {echo "checked";} ?> value="1"> Yes (<a href="http://wordpress.org/extend/plugins/wp-jquery-lightbox/" target="_blank">You will need this plugin</a>)
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_lightbox_field_name; ?>" <?php if($Tradetracker_lightbox_val==0){echo "checked";} ?> value="0"> No
			</td>
		</tr>
		<?php } ?>
		<?php if (get_option("Tradetracker_settings")==2){ ?>
		<?php if(class_exists('SoapClient')){ ?>
		<tr>
			<td colspan="2">
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<label for="tradetrackerstatsdash" title="Do you want to see the income statistics in your dashboard?" class="info">
					<?php _e("Income stats on your Dashboard?:", 'tradetracker-statsdash' ); ?> 
				</label>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_statsdash_field_name; ?>" <?php if($Tradetracker_statsdash_val==1) {echo "checked";} ?> value="1"> Yes 
				<br>
				<input type="radio" name="<?php echo $Tradetracker_statsdash_field_name; ?>" <?php if($Tradetracker_statsdash_val==0){echo "checked";} ?> value="0"> No
			</td>
		</tr>

		<?php }} ?>

	</table>
	<hr />

	<p class="submit">
	<b>Always save changes before pressing next.</b><br>
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /> 	
			<?php if (get_option("Tradetracker_settings")==1){ ?> 
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-items'"> 
			<?php } ?>
			<?php if ( get_option("Tradetracker_settings")==2){ ?> 
			<?php if ( $Tradetracker_statsdash_val==1 ){ ?> 
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-stats'">
			<?php } else { ?>
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-layout'"> 
			<?php }} ?>
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help3'">
	</p>

</form>
</div>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<?php news(); ?>
 	</div>
</div>
</div>
<?php 
}
?>