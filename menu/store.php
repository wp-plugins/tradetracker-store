<?php
function store() {

	//global variables
	global $wpdb;
	global $ttstoresubmit;
	global $ttstorehidden;
	global $ttstoremultitable;
	global $ttstorelayouttable;
	global $ttstoretable;

	//variables for this function
	$Tradetracker_buynow_name = 'Tradetracker_buynow';
	$Tradetracker_multiname_name = 'Tradetracker_multiname';
	$Tradetracker_multisorting_name = 'Tradetracker_multisorting';
	$Tradetracker_multiorder_name = 'Tradetracker_multiorder';
	$Tradetracker_multilayout_name = 'Tradetracker_multilayout';
	$Tradetracker_multiamount_name = 'Tradetracker_multiamount';
	$Tradetracker_multilightbox_name = 'Tradetracker_multilightbox';
	$Tradetracker_multixmlfeed_name = 'Tradetracker_multixmlfeed';
	$Tradetracker_multiproductpage_name = 'Tradetracker_multiproductpage';
	$Tradetracker_categories_name = 'Tradetracker_categories';
	$readonlylock = "no";
	if(isset($_GET['return'])){
		$returnpage = "1";
	}
	if(isset($post['return'])){
		$returnpage = "1";
	}
	if(isset($_GET['delete'])){
		echo "ik detecteer dit wel";
		if($_GET['delete']>"1"){
			$wpdb->query($wpdb->prepare("DELETE FROM ".$ttstoremultitable." WHERE `id` = ".$_GET['delete'].""));
		}
	}
	//filling variables from database when editting
	if (isset($_GET['multiid']) || isset($_POST['multiid'])){
		if(isset($_GET['multiid'])){
			$multiid = $_GET['multiid'];
		} 
		if(isset($_POST['multiid'])){
			$multiid = $_POST['multiid'];
		} 
		$multi=$wpdb->get_results("SELECT buynow, multixmlfeed, multisorting, multiorder, multiproductpage, multiname, multilayout, multiamount, multilightbox, categories FROM ".$ttstoremultitable." where id='".$multiid."'");
		foreach ($multi as $multi_val){
			$Tradetracker_buynow_val = $multi_val->buynow;
			$db_buynow_val = $multi_val->buynow;
			$Tradetracker_multiname_val = $multi_val->multiname;
			$db_multiname_val = $multi_val->multiname;
			$Tradetracker_multisorting_val = $multi_val->multisorting;
			$db_multisorting_val = $multi_val->multisorting;
			$Tradetracker_multiorder_val = $multi_val->multiorder;
			$db_multiorder_val = $multi_val->multiorder;
			$Tradetracker_multixmlfeed_val = $multi_val->multixmlfeed;
			$db_multixmlfeed_val = $multi_val->multixmlfeed;
			$Tradetracker_multilayout_val = $multi_val->multilayout;
			$db_multilayout_val = $multi_val->multilayout;
			$Tradetracker_multiamount_val = $multi_val->multiamount;
			$db_multiamount_val = $multi_val->multiamount;
			$Tradetracker_multilightbox_val = $multi_val->multilightbox;
			$db_multilightbox_val = $multi_val->multilightbox;
			$Tradetracker_categories_val = $multi_val->categories;
			$db_categories_val = $multi_val->categories;
			$Tradetracker_multiproductpage_val = $multi_val->multiproductpage;
			$db_multiproductpage_val = $multi_val->multiproductpage;
		}

	}
	//see if form has been submitted
	if( isset($_POST[ $ttstoresubmit ]) && $_POST[ $ttstoresubmit ] == 'Y' ) {
        // Read their posted value
        	$Tradetracker_buynow_val = $_POST[ $Tradetracker_buynow_name ];
       		$Tradetracker_multixmlfeed_val = $_POST[ $Tradetracker_multixmlfeed_name ];
        	$Tradetracker_multiname_val = $_POST[ $Tradetracker_multiname_name ];
        	$Tradetracker_multisorting_val = $_POST[ $Tradetracker_multisorting_name ];
        	$Tradetracker_multiorder_val = $_POST[ $Tradetracker_multiorder_name ];
        	$Tradetracker_multilayout_val = $_POST[ $Tradetracker_multilayout_name ];
		$Tradetracker_multiamount_val = $_POST[ $Tradetracker_multiamount_name ];
		$Tradetracker_multilightbox_val = $_POST[ $Tradetracker_multilightbox_name ];
		$Tradetracker_multiproductpage_val = $_POST[ $Tradetracker_multiproductpage_name ];
		if(isset($_POST[ $Tradetracker_categories_name ])){
			$Tradetracker_categories_val = serialize($_POST[ $Tradetracker_categories_name ]);
		} else {
			$Tradetracker_categories_val = "";
		}
		if(isset($_GET['multiid'])){
			$Tradetracker_multiid_val = $_GET['multiid'];
		}

		if($Tradetracker_multiname_val=="" || $Tradetracker_multiamount_val==""){
			$error = "<div id=\"ttstoreboxerror\"><strong>Please fill mandatory fields</strong></div>";
		} else {
			// Save the posted value in the database
			if(!empty($_POST['multiid'])) {
				if ( $db_buynow_val  != $Tradetracker_buynow_val) {
					$query = $wpdb->update( $ttstoremultitable, array( 'buynow' => $Tradetracker_buynow_val), array( 'id' => $_POST['multiid']), array( '%s'), array( '%s'), array( '%d' ) );
  				}
 				if ( $db_multiname_val  != $Tradetracker_multiname_val) {
					$query = $wpdb->update( $ttstoremultitable, array( 'multiname' => $Tradetracker_multiname_val), array( 'id' => $_POST['multiid']), array( '%s'), array( '%s'), array( '%d' ) );
  				}
 				if ( $db_multisorting_val  != $Tradetracker_multisorting_val) {
					$query = $wpdb->update( $ttstoremultitable, array( 'multisorting' => $Tradetracker_multisorting_val), array( 'id' => $_POST['multiid']), array( '%s'), array( '%s'), array( '%d' ) );
  				}
 				if ( $db_multiorder_val  != $Tradetracker_multiorder_val) {
					$query = $wpdb->update( $ttstoremultitable, array( 'multiorder' => $Tradetracker_multiorder_val), array( 'id' => $_POST['multiid']), array( '%s'), array( '%s'), array( '%d' ) );
  				}
 				if ( $db_multixmlfeed_val  != $Tradetracker_multixmlfeed_val) {
					$query = $wpdb->update( $ttstoremultitable, array( 'multixmlfeed' => $Tradetracker_multixmlfeed_val), array( 'id' => $_POST['multiid']), array( '%s'), array( '%s'), array( '%d' ) );
  				}
 				if ( $db_multilayout_val  != $Tradetracker_multilayout_val) {
					$query = $wpdb->update( $ttstoremultitable, array( 'multilayout' => $Tradetracker_multilayout_val), array( 'id' => $_POST['multiid']), array( '%s'), array( '%s'), array( '%d' ) );
  				}
				if ( $db_multiamount_val  != $Tradetracker_multiamount_val) {
					$query = $wpdb->update( $ttstoremultitable, array( 'multiamount' => $Tradetracker_multiamount_val), array( 'id' => $_POST['multiid']), array( '%s'), array( '%s'), array( '%d' ) );
				}
 				if ( $db_multilightbox_val  != $Tradetracker_multilightbox_val) {
					$query = $wpdb->update( $ttstoremultitable, array( 'multilightbox' => $Tradetracker_multilightbox_val), array( 'id' => $_POST['multiid']), array( '%s'), array( '%s'), array( '%d' ) );
 				}
				if ( $db_multiproductpage_val  != $Tradetracker_multiproductpage_val) {
					$query = $wpdb->update( $ttstoremultitable, array( 'multiproductpage' => $Tradetracker_multiproductpage_val), array( 'id' => $_POST['multiid']), array( '%s'), array( '%s'), array( '%d' ) );
 				}
 				if ( $db_categories_val  != $Tradetracker_categories_val) {
					$query = $wpdb->update( $ttstoremultitable, array( 'categories' =>  $Tradetracker_categories_val), array( 'id' => $_POST['multiid']), array( '%s'), array( '%s'), array( '%d' ) );
 				}
				$multiid = $_POST['multiid'];
			} else {
				//save new variables
       	 			$currentpage["buynow"]=$Tradetracker_buynow_val;
       	 			$currentpage["multiname"]=$Tradetracker_multiname_val;
       	 			$currentpage["multisorting"]=$Tradetracker_multisorting_val;
       	 			$currentpage["multiorder"]=$Tradetracker_multiorder_val;
        			$currentpage["multilayout"]=$Tradetracker_multilayout_val;
        			$currentpage["multiamount"]=$Tradetracker_multiamount_val;
        			$currentpage["multilightbox"]=$Tradetracker_multilightbox_val;
        			$currentpage["multixmlfeed"]=$Tradetracker_multixmlfeed_val;
        			$currentpage["multiproductpage"]=$Tradetracker_multiproductpage_val;
        			$currentpage["categories"]=$Tradetracker_categories_val;
				$wpdb->insert( $ttstoremultitable, $currentpage);
				$multiid = $wpdb->insert_id;
			}
			//put an settings updated message on the screen
			$saved = "<div id=\"ttstoreboxsaved\"><strong>Settings saved</strong></div>";
		}
	}
	if(!isset($_GET['function'])){
?>
<?php $adminwidth = get_option("Tradetracker_adminwidth"); ?>
<?php $adminheight = get_option("Tradetracker_adminheight"); ?>
<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle">Would you like to edit or add a store?</div>
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
					<strong>Store Name</strong>
				</td>
				<td>
					<strong>Sorting</strong>
				</td>
				<td>
					<strong>Order</strong>
				</td>
				<td>
					<strong>Layout</strong>
				</td>
				<td>
					<strong>Feed</strong>
				</td>
				<td>
					<strong>Button Text</strong>
				</td>
				<td>
					<strong>Items</strong>
				</td>
				<td>
					<strong>Lightbox</strong>
				</td>
				<td>
					<strong>Edit</strong>
				</td>
				<td>
					<strong>Delete</strong>
				</td>
			</tr>
<?php
		$storeedit=$wpdb->get_results("SELECT ".$ttstoremultitable.".id, layname, multisorting, multiorder, multilayout, multiname, multiamount, multilightbox, multixmlfeed, buynow FROM ".$ttstoremultitable.", ".$ttstorelayouttable." where ".$ttstoremultitable.".multilayout = ".$ttstorelayouttable.".id");
		foreach ($storeedit as $store_val){
?>
			<tr>
				<td>
					<?php echo $store_val->multiname; ?>
				</td>
				<td>
					<?php echo $store_val->multisorting; ?>
				</td>
				<td>
					<?php echo $store_val->multiorder; ?>
				</td>
				<td>
					<?php echo $store_val->layname; ?>
				</td>
				<td>
					<?php if ($store_val->multixmlfeed == "*"){echo "All Feeds";} else { $xmlfeed = get_option("Tradetracker_xmlname"); echo $xmlfeed[$store_val->multixmlfeed]; }?>
				</td>
				<td>
					<?php echo $store_val->buynow; ?>
				</td>
				<td>
					<?php echo $store_val->multiamount; ?>
				</td>
				<td>
					<?php if($store_val->multilightbox=="1"){ echo "Yes";} else {echo "No";} ?>
				</td>
				<td>
					<?php if($store_val->id>"1"){ echo "<a href=\"admin.php?page=tt-store&option=store&function=new&multiid=".$store_val->id."\">Edit</a>"; } ?>
				</td>
				<td>
					<?php if($store_val->id>"1"){ echo "<a href=\"admin.php?page=tt-store&option=store&delete=".$store_val->id."\">Delete</a>"; } ?>
				</td>
			</tr>
			
<?php		
		
		}
?>
		</table>
		</div>
		<div id="ttstoreboxbottom">
			<INPUT type="button" name="Close" class="button-primary" value="<?php esc_attr_e('Add New') ?>" onclick="location.href='admin.php?page=tt-store&option=store&function=new'"> 
			<INPUT type="button" name="Close" class="button-secondary" value="<?php esc_attr_e('Close') ?>" onclick="location.href='admin.php?page=tt-store'"> 
		</div>
	</div>
</div>
<?php
	}else if($_GET['function']=="new") {
?>
<?php $adminwidth = get_option("Tradetracker_adminwidth"); ?>
<?php $adminheight = get_option("Tradetracker_adminheight"); ?>
<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
	<form name="form1" method="post" action="">
	<?php echo $ttstorehidden; ?>
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle"><?php if(isset($multiid)){ esc_attr_e('Edit Store'); } else { esc_attr_e('Create Store'); } ?></div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store&option=<?php if(isset($returnpage)){echo "itemselect"; } else {echo "store";}?>">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">

		<input type="hidden" name="multiid" value="<?php if(isset($multiid)){ echo $multiid;} ?>">
		<?php if(isset($returnpage)){ echo "<input type=\"hidden\" name=\"return\" value=\"item\">"; }?>
<table width="<?php echo $adminwidth-15; ?>">
	<tr>
		<td>
			<label for="tradetrackername" title="Fill in the name for the store." class="info">
				<?php _e("Name for Store:", 'tradetracker-storename' ); ?>
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_multiname_name; ?>" value="<?php if(isset($Tradetracker_multiname_val)) {echo $Tradetracker_multiname_val;} ?>" size="30"> 
			<?php if(isset($error)){ echo "<font color=\"red\">*</font>"; }?>
			This cannot start with a number
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackersorting" title="It will sort the items on this field." class="info">
				<?php _e("Order by this field:", 'tradetracker-multisorting' ); ?>
			</label> 
		</td>
		<td>
			<select width="200" style="width: 200px" name="<?php echo $Tradetracker_multisorting_name; ?>">
<?php
		$sorting=array('rand()','price', 'categorie', 'name');
		foreach ($sorting as $sorting_val){
			if(isset($Tradetracker_multisorting_val) && $sorting_val == $Tradetracker_multisorting_val) {
				echo "<option selected=\"selected\" value=\"".$sorting_val."\">$sorting_val</option>";
			} else {
				echo "<option value=\"".$sorting_val."\">$sorting_val</option>";
			}
		}
		
?>
			</select>		
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackerorder" title="How should it be ordered." class="info">
				<?php _e("Descending or Ascending:", 'tradetracker-multiorder' ); ?>
			</label> 
		</td>
		<td>
			<select width="200" style="width: 200px" name="<?php echo $Tradetracker_multiorder_name; ?>">
<?php
		$ordering=array('desc','asc');
		foreach ($ordering as $ordering_val){
			if(isset($Tradetracker_multiorder_val) && $ordering_val == $Tradetracker_multiorder_val) {
				echo "<option selected=\"selected\" value=\"".$ordering_val."\">$ordering_val</option>";
			} else {
				echo "<option value=\"".$ordering_val."\">$ordering_val</option>";
			}
		}
		
?>
			</select>		
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackerwidth" title="Which layout would you like to use." class="info">
				<?php _e("Layout:", 'tradetracker-multilayout' ); ?>
			</label> 
		</td>
		<td>
			<select width="200" style="width: 200px" name="<?php echo $Tradetracker_multilayout_name; ?>">
<?php
		$layout=$wpdb->get_results("SELECT id, layname FROM ".$ttstorelayouttable."");
		foreach ($layout as $layout_val){
			if(isset($Tradetracker_multilayout_val) && $layout_val->id == $Tradetracker_multilayout_val) {
				echo "<option selected=\"selected\" value=\"".$layout_val->id."\">$layout_val->layname</option>";
			} else {
				echo "<option value=\"".$layout_val->id."\">$layout_val->layname</option>";
			}
		}
		
?>
			</select>		
		</td>
	</tr>
<?php 	$provider = get_option('tt_premium_function');
	if(!empty($provider)){
		foreach($provider as $providers) {
			if($providers == "productpage"){ 
			?>	
			<tr>
				<td>
					<label for="tradetrackerproductpage" title="Do you like to use a product page?" class="info">
						<?php _e("Use a productpage:", 'tradetracker-productpage' ); ?> 
					</label>
				</td>
				<td>
					<input type="radio" name="<?php echo $Tradetracker_multiproductpage_name; ?>" <?php if(isset($Tradetracker_multiproductpage_val) && $Tradetracker_multiproductpage_val=="1") {echo "checked";} ?> value="1"> Yes 
					<br>
					<input type="radio" name="<?php echo $Tradetracker_multiproductpage_name; ?>" <?php if((isset($Tradetracker_multiproductpage_val) && $Tradetracker_multiproductpage_val=="0") || !isset($Tradetracker_multiproductpage_val)){echo "checked";} ?> value="0"> No
				</td>
			</tr>
			<?php
			} else {
				echo "<input type=\"hidden\" name=\"".$Tradetracker_multiproductpage_name."\" value=\"".$Tradetracker_multiproductpage_val."\">";
			}
		}
	}
?>

	<tr>
		<td>
			<label for="tradetrackerwidth" title="Which feed would you like to use." class="info">
				<?php _e("Feed:", 'tradetracker-multifeed' ); ?>
			</label> 
		</td>
		<td>
			<select width="200" style="width: 200px" name="<?php echo $Tradetracker_multixmlfeed_name; ?>" onchange="toggleOther();">
<?php
		if(!isset($$Tradetracker_multixmlfeed_val) || $Tradetracker_multixmlfeed_val == "*"){
			echo "<option selected=\"selected\" value=\"*\">All feeds</option>";
		} else {
			echo "<option value=\"*\">All feeds</option>";
		}
		//$xmlfeed=$wpdb->get_results("SELECT xmlfeed FROM ".$ttstoretable." group by xmlfeed");
		$xmlfeed1 = get_option("Tradetracker_xmlname");
		foreach ($xmlfeed1 as $key => $value) {
			if($Tradetracker_multixmlfeed_val != "*" && $Tradetracker_multixmlfeed_val == $key) {
				echo "<option selected=\"selected\" value=\"".$key."\">".$value."</option>";
			} else {
				echo "<option value=\"".$key."\">".$value."</option>";
			}
		}
?>
			</select>		
		</td>
	</tr>
<script>
function toggleOther(){
  document.getElementById('Save').style.visibility = 'visible';
  document.getElementById('Option').style.display = 'none';


}
</script>

	<tr>
		<td valign="top">
			<label for="tradetrackercategoriesfield" title="Which categories would you like to use?" class="info">
				<?php _e("Which categories?:", 'tradetracker-categories' ); ?> 
			</label>
		</td>
		<td>
			<div id="Save" style="visibility:hidden">
				You changed the XML Feed, You need to save first before you can select any category.
			</div>
			<div id="Option" style="visibility:visible">

		<?php
		if((isset($Tradetracker_multixmlfeed_val) && $Tradetracker_multixmlfeed_val == "*") || !isset($Tradetracker_multixmlfeed_val) ){
			$multixmlfeed = "";
		} else {
			$multixmlfeed = "where xmlfeed = '".$Tradetracker_multixmlfeed_val."' ";
		}
			$categorie = $wpdb->get_results('SELECT categorie, xmlfeed, categorieid FROM '.$ttstoretable.' '.$multixmlfeed.' group by xmlfeed, categorie ORDER BY xmlfeed, `'.$ttstoretable.'`.`categorie` ASC', OBJECT);
			if(!empty($categorie)){
				echo "<table width=\"400\">";
				$i="1";
				$xmlfeedname = get_option('Tradetracker_xmlname');
				foreach($categorie as $categorieselect) {
					echo "<tr><td>";
					if(is_serialized($Tradetracker_categories_val)){
						if(in_array($categorieselect->categorieid, unserialize($Tradetracker_categories_val), true)) {
							echo "<input type=\"checkbox\" checked=\"yes\" name=\"".$Tradetracker_categories_name."[]\" value=\"".$categorieselect->categorieid."\" />".$xmlfeedname[$categorieselect->xmlfeed]." - ".$categorieselect->categorie."<br />";
						} else {
							echo "<input type=\"checkbox\" name=\"".$Tradetracker_categories_name."[]\" value=\"".$categorieselect->categorieid."\" />".$xmlfeedname[$categorieselect->xmlfeed]." - ".$categorieselect->categorie."<br />";
						}
					} else {
						echo "<input type=\"checkbox\" name=\"".$Tradetracker_categories_name."[]\" value=\"".$categorieselect->categorieid."\" />".$xmlfeedname[$categorieselect->xmlfeed]." - ".$categorieselect->categorie."<br />";
					}
					echo "</td></tr>";
				}
				echo "</table>";
			}
		?>
			</div>
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<hr />
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackerbuynow" title="What text would you like to use on the button (standard is Buy now)." class="info">
				<?php _e("Text on button:", 'tradetracker-buynow' ); ?>
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_buynow_name; ?>" value="<?php if(isset($Tradetracker_buynow_val)) { echo $Tradetracker_buynow_val; }?>" size="30"> 
		</td>
	</tr>
	<tr>
		<td>
			<label for="tradetrackerfont" title="How much items would you like to show." class="info">
				<?php _e("Amount of items:", 'tradetracker-amount' ); ?> 
			</label> 
		</td>
		<td>
			<input type="text" name="<?php echo $Tradetracker_multiamount_name; ?>" value="<?php if (!isset($Tradetracker_multiamount_val)) {echo "10"; } else {echo $Tradetracker_multiamount_val;} ?>" size="30">
			<?php if(isset($error)){ echo "<font color=\"red\">*</font>"; }?> use 0 if you don't want a limit at all
		</td>
	</tr>

	<tr>
		<td>
			<label for="tradetrackerlightbox" title="Do you want to use lightbox for the images? You will need an extra plugin for that" class="info">
				<?php _e("Use Lightbox:", 'tradetracker-lightbox' ); ?> 
			</label>
		</td>
		<td>
			<input type="radio" name="<?php echo $Tradetracker_multilightbox_name; ?>" <?php if(isset($Tradetracker_multilightbox_val) && $Tradetracker_multilightbox_val=="1") {echo "checked";} ?> value="1"> Yes (<a href="http://wordpress.org/extend/plugins/wp-jquery-lightbox/" target="_blank">You will need this plugin</a>)
		</td>
	</tr>
	<tr>
		<td>
		</td>
		<td>
			<input type="radio" name="<?php echo $Tradetracker_multilightbox_name; ?>" <?php if((isset($Tradetracker_multilightbox_val) && $Tradetracker_multilightbox_val=="0") || !isset($Tradetracker_multilightbox_val)){echo "checked";} ?> value="0"> No
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
			<input type="submit" name="Submit" class="button-primary" value="<?php if(isset($multiid)){ esc_attr_e('Save Changes'); } else { esc_attr_e('Create'); } ?>" /> 
			<INPUT type="button" name="Close" class="button-secondary" value="<?php esc_attr_e('Close') ?>" onclick="location.href='admin.php?page=tt-store&option=<?php if(isset($returnpage)){echo "itemselect"; } else {echo "store";}?>'"> 
		</div>
	</form>
	</div>
</div>
<?php
	}	
}
?>