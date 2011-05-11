<?php
function tradetracker_store_stats() {
		if (get_option( Tradetracker_settings )>=2){
		if(class_exists('SoapClient')){
	$hidden_field_name = 'mt_submit_hidden';
	$Tradetracker_customerid_name = 'Tradetracker_customerid';
	$Tradetracker_customerid_field_name = 'Tradetracker_customerid';

	$Tradetracker_access_code_name = 'Tradetracker_access_code';
	$Tradetracker_access_code_field_name = 'Tradetracker_access_code';

	$Tradetracker_siteid_name = 'Tradetracker_siteid';
	$Tradetracker_siteid_field_name = 'Tradetracker_siteid';


	$Tradetracker_statstime_name = 'Tradetracker_statstime';
	$Tradetracker_statstime_field_name = 'Tradetracker_statstime';

	$Tradetracker_customerid_val = get_option( $Tradetracker_customerid_name );
	$Tradetracker_access_code_val = get_option( $Tradetracker_access_code_name );
	$Tradetracker_siteid_val = get_option( $Tradetracker_siteid_name );
	$Tradetracker_statstime_val = get_option( $Tradetracker_statstime_name );

	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		$Tradetracker_customerid_val = $_POST[ $Tradetracker_customerid_field_name ];
		$Tradetracker_access_code_val = $_POST[ $Tradetracker_access_code_field_name ];
		$Tradetracker_siteid_val = $_POST[ $Tradetracker_siteid_field_name ];
		$Tradetracker_statstime_val = $_POST[ $Tradetracker_statstime_field_name ];
		if ( get_option(Tradetracker_customerid)  != $Tradetracker_customerid_val) {
			update_option( $Tradetracker_customerid_name, $Tradetracker_customerid_val );
		}
		if ( get_option(Tradetracker_access_code)  != $Tradetracker_access_code_val) {
			update_option( $Tradetracker_access_code_name, $Tradetracker_access_code_val );
		}
		if ( get_option(Tradetracker_siteid)  != $Tradetracker_siteid_val) {
			update_option( $Tradetracker_siteid_name, $Tradetracker_siteid_val );
		}
		if ( get_option(Tradetracker_statstime)  != $Tradetracker_statstime_val) {
			update_option( $Tradetracker_statstime_name, $Tradetracker_statstime_val );
		}
?>
		<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php
	}
	


?>
<div class="wrap">
	<?php echo "<h2>" . __( 'Tradetracker Store Setup', 'menu-test' ) . "</h2>"; ?>
<style type="text/css" media="screen">
.info {
		border-bottom: 1px dotted #666;
		cursor: help;
	}

</style>
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
		<?php if ( get_option( Tradetracker_statsdash ) == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3" class="active">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab7">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab8">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab9" class="redhelp">Help</a></li>
</ul>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<iframe width=200 height=800 frameborder="0" src="http://debestekleurplaten.nl/tradetracker-store/news.php"></iframe>
 	</div>
<div id="tab3" class="tabset_content">
   <h2 class="tabset_label">Stats</h2>
<form name="form1" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<table>
		<tr>
			<td>
				<label for="tradetrackercustomerid" title="Fill in your customer ID. You will need to enable webservices in Tradetracker" class="info">
					<?php _e("Customer ID:", 'tradetracker-customerid' ); ?> 
				</label>
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_customerid_field_name; ?>" value="<?php echo $Tradetracker_customerid_val; ?>" size="5"> 
				<a href="https://affiliate.tradetracker.com/webService" target="_blank">Get Customer ID</a>
			</td>
		</tr>
		<tr>
			<td>
				<label for="tradetrackeraccess_code" title="Fill in your access code." class="info">
					<?php _e("Access code:", 'tradetracker-access_code' ); ?> 
				</label>
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_access_code_field_name; ?>" value="<?php echo $Tradetracker_access_code_val; ?>" size="50">
			</td>
		</tr>
		<tr>
			<td>
				<label for="tradetrackersiteid" title="Fill in your Site ID." class="info">
					<?php _e("Site ID:", 'tradetracker-siteid' ); ?> 
				</label>
			</td>
			<td>
				<input type="text" name="<?php echo $Tradetracker_siteid_field_name; ?>" value="<?php echo $Tradetracker_siteid_val; ?>" size="5"> 
				<a href="https://affiliate.tradetracker.com/customerSite/list" target="_blank">Get Site ID</a>
			</td>
		</tr>
		<tr>
			<td>
				<label for="tradetrackerstatstime" title="What timeframe would you like?" class="info">
					<?php _e("Timeframe of stats:", 'tradetracker-statstime' ); ?> 
				</label>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_statstime_field_name; ?>" <?php if($Tradetracker_statstime_val==1 || $Tradetracker_statstime_val=="") {echo "CHECKED";} ?> value="1"> Day 
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_statstime_field_name; ?>" <?php if($Tradetracker_statstime_val==2){echo "checked";} ?> value="2"> Week
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<input type="radio" name="<?php echo $Tradetracker_statstime_field_name; ?>" <?php if($Tradetracker_statstime_val==3){echo "checked";} ?> value="3"> Month
			</td>
		</tr>

	</table>
	<p class="submit">
		<b>Always save changes before pressing next.</b><br>
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /> 	
			<?php if (get_option( Tradetracker_settings )==1){ ?> 
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-items'"> 
			<?php } ?>
			<?php if ( get_option( Tradetracker_settings )==2){ ?> 
				<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-layout'">
			<?php } ?>
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help4'">
	</p>

</form>
</div>
<?php
		}}


}

function tradetracker_store_statistics() {
$siteid = get_option( Tradetracker_siteid );
$customerid = get_option( Tradetracker_customerid );
$acces_code = get_option( Tradetracker_access_code );
if (get_option( Tradetracker_siteid ) == null || get_option( Tradetracker_customerid ) == null || get_option( Tradetracker_access_code ) == null){
echo "Please adjust your stats settings";
} else {
try { 
$client = new SoapClient('http://ws.tradetracker.com/soap/affiliate?wsdl');
$client->authenticate($customerid, ''.$acces_code.'');
$affiliateSiteID = $siteid;



echo "<table width=\"500\">";
if(get_option( Tradetracker_statstime )=="3"){
$registrationDateFrom = ''.Date('Y-m-d', strtotime("-30 days")).'';
$registrationDateTo = ''.Date("Y-m-d").'';
}
else if(get_option( Tradetracker_statstime )=="2"){
$registrationDateFrom = ''.Date('Y-m-d', strtotime("-7 days")).'';
$registrationDateTo = ''.Date("Y-m-d").'';
}else{
$registrationDateFrom = ''.Date("Y-m-d").'';
$registrationDateTo = ''.Date("Y-m-d").'';
}
$options = array(
	'dateFrom' => ''.$registrationDateFrom.'',
	'dateTo' => ''.$registrationDateTo.'',
);
echo "</table>";
echo "<table width=\"500\">";
echo "<tr><td><b>Campaign</b></td><td><b>Leads</b></td><td><b>Commission</b></td><td><b>Sales</b></td><td><b>Commission</b></td></tr>";
foreach ($client->getReportCampaign($affiliateSiteID, $options) as $report) {
	echo '<tr><td>' . $report->campaign->name . '</td>';
	echo '<td>' . $report->reportData->leadCount . '</td>';
	echo '<td>' . round($report->reportData->leadCommission,2) . '</td>';
	echo '<td>' . $report->reportData->saleCount . '</td>';
	echo '<td>' . round($report->reportData->saleCommission,2) . '</td></tr>';

}



 $report = $client->getReportAffiliateSite($affiliateSiteID, $options);
	echo '<tr><td><b>Total</b></td>';
	echo '<td><b>' . $report->leadCount . '</b></td>';
	echo '<td><b>' . round($report->leadCommission,2) . '</b></td>';
	echo '<td><b>' . $report->saleCount . '</b></td>';
	echo '<td><b>' . round($report->saleCommission,2) . '</b></td>';
} catch (SoapFault $exception) { 

} 
echo "</table></div>";
}
}


function add_custom_dashboard_widget() {
	if ( get_option( Tradetracker_statsdash ) == 1 ) {
if(get_option( Tradetracker_statstime )=="3"){
$registrationDateFrom = ''.Date('Y-m-d', strtotime("-30 days")).'';
$registrationDateTo = ''.Date("Y-m-d").'';
$statstitle = "Income Statistics for ".Date('d-M-Y', strtotime('-30 days'))." till ".Date('d-M-Y')."";
}
else if(get_option( Tradetracker_statstime )=="2"){
$registrationDateFrom = ''.Date('Y-m-d', strtotime("-7 days")).'';
$registrationDateTo = ''.Date("Y-m-d").'';
$statstitle = "Income Statistics for ".Date('d-M-Y', strtotime('-7 days'))." till ".Date('d-M-Y')."";
}else{
$registrationDateFrom = ''.Date("Y-m-d").'';
$registrationDateTo = ''.Date("Y-m-d").'';
$statstitle = "Income Statistics for ".Date('d-M-Y')."";
}
	wp_add_dashboard_widget('tradetracker_store_statistics', $statstitle, 'tradetracker_store_statistics');
	}
}
add_action('wp_dashboard_setup', 'add_custom_dashboard_widget');
?>