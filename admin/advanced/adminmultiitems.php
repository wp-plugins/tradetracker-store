<?php

function adminstore_multiitems()
{
	global $wpdb;
	if( get_option("Tradetracker_update") == "" ){
		$update= "24";
	} else {
		$update= get_option("Tradetracker_update");
	}
	$Tradetracker_xml = get_option("Tradetracker_xml");
	if ($Tradetracker_xml == null) 
	{
		echo "No XML filled in yet please change the settings first.";
	} else {
		return adminshow_multiitems();
	}
}

function adminshow_multiitems()
{
	if (!current_user_can('manage_options'))
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
global $wpdb;
ttstoreheader();
$tablemulti = PRO_TABLE_PREFIX."multi";


	echo '<div class="wrap">';
?>
<?php 	echo "<h2>" . __( 'Tradetracker Store Setup', 'menu-test' ) . "</h2>"; ?>
<div class="plugindiv">
<ul class="tabset_tabs">
   <li><a href="admin.php?page=tradetracker-shop#tab1">Setup</a></li>
   <li><a href="admin.php?page=tradetracker-shop-settings#tab2">Settings</a></li>
		<?php if ( get_option("Tradetracker_statsdash") == 1 ) { ?>
   <li><a href="admin.php?page=tradetracker-shop-stats#tab3">Stats</a></li>
		<?php } ?>
   <li><a href="admin.php?page=tradetracker-shop-layout#tab4">Layout</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multi#tab5">Store</a></li>
   <li><a href="admin.php?page=tradetracker-shop-multiitems#tab6" class="active">Items</a></li>
   <li><a href="admin.php?page=tradetracker-shop-overview#tab7">Overview</a></li>
   <li><a href="admin.php?page=tradetracker-shop-feedback#tab8">Feedback</a></li>
   <li><a href="admin.php?page=tradetracker-shop-premium#tab9" class="greenpremium">Premium</a></li>
   <li><a href="admin.php?page=tradetracker-shop-help#tab10" class="redhelp">Help</a></li>
</ul>

<div id="tab6" class="tabset_content">
   <h2 class="tabset_label">Items</h2>


<?php
	if (empty($_GET['multiid'])){
?>
	<table width="400">
		<tr>
			<td>
				<b>Store Name</b>
			</td>
			<td>
			</td>
			<td>
			</td>
		</tr>
<?php
		$layoutedit=$wpdb->get_results("SELECT id, multiname FROM ".$tablemulti."");
		foreach ($layoutedit as $layout_val){
?>

		<tr>
			<td>
				<?php echo $layout_val->multiname; ?>
			</td>
			<td>
				<a href="admin.php?page=tradetracker-shop-multi&multiid=<?php echo $layout_val->id; ?>">Edit</a>
			</td>
			<td>
				<a href="admin.php?page=tradetracker-shop-multiitems&multiid=<?php echo $layout_val->id; ?>">Select Items</a>
			</td>
		</tr>
			
<?php		
		
		}
?>
	</table>
<?php
	} else {
	$multiid = $_GET['multiid'];
		$layoutedit=$wpdb->get_results("SELECT id, multixmlfeed, multiitems, multiname, categories FROM ".$tablemulti." where id=".$multiid."");
		foreach ($layoutedit as $layout_val){
			$multiitems = $layout_val->multiitems;
			$multiname = $layout_val->multiname;
			if($layout_val->multixmlfeed == "*" ){
				$multixmlfeed = "";
			}elseif($layout_val->multixmlfeed == "" ){
				$multixmlfeed = "";
			} else {
				$multixmlfeed = "where xmlfeed = ".$layout_val->multixmlfeed." ";
			}
			$i="1";
			$categories = unserialize($layout_val->categories);
			if(!empty($categories)){
				foreach ($categories as $categories){
					if($i == "1" ) {
						if($multixmlfeed == ""){
							$categorieselect = " where (categorie = \"".$categories."\"";
						}else {
							$categorieselect = " and (categorie = \"".$categories."\"";
						}
					$i = "2";
					} else {
							$categorieselect .= " or categorie = \"".$categories."\"";
					}
				}
				$categorieselect .= ") ";
			} else {
				$categorieselect = "";
			}

		}
    	if( isset($_POST['posted']) && $_POST['posted'] == 'Y' ) 
	{

		$Tradetracker_items = $_POST['item'];
		$Tradetracker_items = implode(",", $Tradetracker_items);
		if($_POST['itemsother']!="")
		{
			$Tradetracker_items = $Tradetracker_items.",".$_POST['itemsother'];
		}
		if ( $multiitems != $Tradetracker_items ) 
		{ 
			$query = $wpdb->update( $tablemulti, array( 'multiitems' => $Tradetracker_items), array( 'id' => $multiid), array( '%s'), array( '%s'), array( '%d' ) );
			$multiitems = $Tradetracker_items;
  		}
		echo "<div class=\"updated\"><p><strong>Settings Saved</strong></p></div>";	
	}
	if($_GET['order']==null)
	{
		$order = "name";
	} else {
		$order = $_GET['order'];
	}
	global $wpdb; 
	$limit = $_GET['limit'];
	$currentpage = $_GET['currentpage'];
	if (!($limit))
	{
		$limit = 100;
	}
	if (!($currentpage)){
		$currentpage = 0;
	}
	$table = PRO_TABLE_PREFIX."store";
	$countquery=$wpdb->get_results("SELECT * FROM ".$table." ".$multixmlfeed." ".$categorieselect."");
	$numrows = $wpdb->num_rows;
	$pages = intval($numrows/$limit); // Number of results pages.
	if ($numrows%$limit) 
	{
		$pages++;
	} 
	$current = ($currentpage/$limit) + 1;
	if (($pages < 1) || ($pages == 0)) 
	{
		$total = 1;
	} else {
		$total = $pages;
	} 
	$first = $currentpage + 1;
	if (!((($currentpage + $limit) / $limit) >= $pages) && $pages != 1) 
	{
		$last = $currentpage + $limit;
	} else {
		$last = $numrows;
	}
?>
<style type="text/css" media="screen">
#screenshot{
	position:absolute;
	border:1px solid #ccc;
	background:#333;
	padding:5px;
	display:none;
	color:#fff;
	max-width:400px;
	max-height:400px;
	}

span.link {
    	position: relative;
}

    span.link a span {
    	display: none;
}

span.link a:hover {
    	font-size: 99%;
    	font-color: #000000;
}
span.link a:hover span { 
	display: block; 
    	position: absolute; 
    	margin-top: 10px; 
    	margin-left: -500px; 
	min-width: 500px; 
	padding: 5px; 
    	z-index: 100; 
    	color: #000000; 
    	background: #FFFFAA; 
    	font: 12px "Arial", sans-serif;
    	text-align: left; 
    	text-decoration: none;
}
span table, span table tr, span table td{
	border: 0 none;
	padding-right: 5px;
	min-width: 75px;
	padding-left: 5px;
}
/*  */
</style>
<?php
	echo '<div class="wrap">';
	if (get_option("Tradetracker_settings")==1){
		echo "<a href=\"admin.php?page=tradetracker-shop\">Basic Setup</a> 
			> 
			<a href=\"admin.php?page=tradetracker-shop-settings\">Basic Settings</a>
			> 
			<b><a href=\"admin.php?page=tradetracker-shop-items\">Basic Items Selection</a></b>
			>
			<a href=\"admin.php?page=tradetracker-shop-overview\">Overview</a>
			>
			<a href=\"admin.php?page=tradetracker-shop-feedback\">Feedback</a>";
		}
	echo "<h2>" . __( 'Tradetracker Item Selection for "<b>'.$multiname.'</b>"', 'menu-test' ) . "</h2>";



?>
<script type="text/javascript">
function selectToggle(toggle, form) {
     var myForm = document.forms[form];
     for( var i=0; i < myForm.length; i++ ) { 
          if(toggle) {
               myForm.elements[i].checked = "checked";
          } 
          else {
               myForm.elements[i].checked = "";
          }
     }
}
</script>
<table width="700" border="0">
	<tr>
		<td width="50%" align="left">
			Showing products <b><? echo $first; ?></b> - <b><?php echo $last; ?></b> of <b><?php echo $numrows; ?></b>
  		</td>
  		<td width="50%" align="right">
			<?php if ($currentpage != 0) { $back_page = $currentpage - $limit; echo("<a href=\"admin.php?page=tradetracker-shop-multiitems&multiid=".$multiid."&order=$order&currentpage=$back_page&limit=$limit\"><</a>");} ?> Page <b><?php echo $current; ?></b> of <b><?php echo $total; ?></b> <?php if (!((($currentpage+$limit) / $limit) >= $pages) && $pages != 1) { $next_page = $currentpage + $limit; echo("<a href=\"admin.php?page=tradetracker-shop-multiitems&multiid=".$multiid."&order=$order&currentpage=$next_page&limit=$limit\">></a>");} ?>
  		</td>
 	</tr>
 	<tr>
  		<td colspan="2" align="right">
			Results per-page: <a href="admin.php?page=tradetracker-shop-multiitems&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=100">100</a> | <a href="admin.php?page=tradetracker-shop-multiitems&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=200">200</a> | <a href="admin.php?page=tradetracker-shop-multiitems&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=500">500</a> | <a href="admin.php?page=tradetracker-shop-multiitems&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=1000">1000</a>
  		</td>
 	</tr>
</table>
<?php
$visits=$wpdb->get_results("SELECT * FROM ".$table." ".$multixmlfeed." ".$categorieselect." ORDER BY ".$order." ASC LIMIT ".$currentpage.", ".$limit."");
	echo "<table width=\"700\" border=\"0\" style=\"border-width: 0px;padding:0px;border-spacing:0px;\">";
		echo "<tr><td width=\"100\">";
			echo "<b><a href=\"admin.php?page=tradetracker-shop-multiitems&multiid=".$multiid."&order=productID\">ProductID</a></b>";
		echo "</td><td width=\"435\">";
			echo "<b><a href=\"admin.php?page=tradetracker-shop-multiitems&multiid=".$multiid."&order=name\">Product name</a></b>";
		echo "</td><td width=\"50\">";
			echo "<b><a href=\"admin.php?page=tradetracker-shop-multiitems&multiid=".$multiid."&order=price\">Price</a></b>";
		echo "</td><td width=\"65\">";
			echo "<b>Currency</b>";
		echo "</td><td width=\"50\">";
			echo "<b>Extra's</b>";
		echo "</td></tr>";
	echo "<form name=\"form2\" method=\"post\" action=\"\">";
		echo "<input type=\"hidden\" name=\"posted\" value=\"Y\">";
			$array2="";
			$colors = "1";
			foreach ($visits as $product){
				if ($colors == "1"){
					$tdbgcolor= "background-color:#f0f0f0";
				} else {
					$tdbgcolor= "background-color:#ffffff";
				}
				$array2 .= ",".$product->productID."";
				echo "<tr style=\"".$tdbgcolor.";\"><td>";

				$productID = $multiitems;
				$productID = explode(",",$productID);
				if(in_array($product->productID, $productID, true))
				{
					echo "<input type=\"checkbox\" checked=\"yes\" name=\"item[]\" value=".$product->productID." />";
				} else {
					echo "<input type=\"checkbox\" name=\"item[]\" value=".$product->productID." />";
				}
				if($product->imageURL==""){
					$imageURL = WP_PLUGIN_URL."/tradetracker-store/images/No_image.png";
				} else {
					$imageURL = $product->imageURL;
				}
				echo $product->productID;
				echo "</td><td><a href=\"#thumb\" class=\"screenshot\" rel=\"".$imageURL."\">";
				echo $product->name;
				echo "</a></td><td>";
				echo $product->price;
				echo "</td><td>";
				echo $product->currency;

				//$extrafield = str_replace(",", "</b></td><td width=\"1000px\"><b>", "$product->extrafield");
				//$extravalue = str_replace(",", "</td><td>", "$product->extravalue");
				$extrafield = explode(",",$product->extrafield);
				$extravalue = explode(",",$product->extravalue);
				$extras = array_combine($extrafield, $extravalue);
				$extraname = "";
				$extravar = "";
				foreach ($extras as $key => $value) {
					$Tradetracker_extra_val = get_option("Tradetracker_extra");
					if(!empty($Tradetracker_extra_val)){
						if(in_array($key, $Tradetracker_extra_val, true)) {
							$extraname .= "<td width=\"50\"><b>".$key."</b></td>";
							$extravar .= "<td>".$value."</td>";
						}
					}
				}
				if($extraname != ""){
					echo "</td><td><span class=\"link\"><a href=\"javascript: void(0)\">Yes<span><table><tr>".$extraname."</tr><tr>".$extravar."</tr></table> </span></a></span></td></tr>";
				} else {
					echo "</td><td>No</td></tr>";
				}
				unset($extras);
				if ($colors == "1"){
					$colors++;
				} else {
					$colors--;
				}

			}
		if(!empty($array2)){
			$array1 = $productID;
			$array2 = explode(",", $array2);
			$result = array_diff($array1, $array2);
			$result = implode(",", $result);
		}
			echo "<input type=\"hidden\" name=\"itemsother\" value=\"".$result."\" />";
	echo "<tr><td colspan=\"5\">Select <a href=\"javascript:selectToggle(true, 'form2');\">All</a> | <a href=\"javascript:selectToggle(false, 'form2');\">None</a></td></tr>";
	echo "</table>";
	echo "<p class=\"submit\">";
	?>
	<b>Always save changes before pressing next.</b><br>
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /> 	
		<INPUT type="button" name="Storeselect" value="<?php esc_attr_e('Store Selection') ?>" onclick="location.href='admin.php?page=tradetracker-shop-multiitems'"> 
		<INPUT type="button" name="Next" value="<?php esc_attr_e('Next') ?>" onclick="location.href='admin.php?page=tradetracker-shop-overview'"> 
		<INPUT type="button" name="Help" value="<?php esc_attr_e('Help') ?>" onclick="location.href='admin.php?page=tradetracker-shop-help#help7'">
<?php
	echo "</p>";
	echo "</form>";
echo "<table width=\"700\"><tr><td>";
	if ($currentpage != 0) { // Don't show back link if current page is first page.
		$back_page = $currentpage - $limit;
			echo("<a href=\"admin.php?page=tradetracker-shop-multiitems&multiid=".$multiid."&order=$order&currentpage=$back_page&limit=$limit\">back</a>    \n");}

			for ($i=1; $i <= $pages; $i++) // loop through each page and give link to it.
			{
				$ppage = $limit*($i - 1);
					if ($ppage == $currentpage){
						echo("<b>$i</b> \n");} // If current page don't give link, just text.
					else{
						echo("<a href=\"admin.php?page=tradetracker-shop-multiitems&multiid=".$multiid."&order=$order&currentpage=$ppage&limit=$limit\">$i</a> \n");}
					}
					if (!((($currentpage+$limit) / $limit) >= $pages) && $pages != 1) { // If last page don't give next link.
						$next_page = $currentpage + $limit;
						echo("    <a href=\"admin.php?page=tradetracker-shop-multiitems&multiid=".$multiid."&order=$order&currentpage=$next_page&limit=$limit\">next</a>\n");}
						echo "</td></tr></table></div>";
}
?>
</div>
	<div id="sideblock" style="float:right;width:200px;margin-left:10px;border:1px;position:relative;border-color:#000000;border-style:solid;"> 
		<?php news(); ?>
 	</div>
</div>
</div>
<?php
}
?>