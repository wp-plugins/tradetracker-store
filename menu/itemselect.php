<?php
function itemselect() {
	global $wpdb;
	global $ttstoresubmit;
	global $ttstorehidden;
	global $ttstoremultitable;
	global $ttstoretable;
	global $folderhome;
	if(!isset($_GET['function']) || $_GET['function']=="delete" || $_GET['function']=="deleteempty"){
 		if(isset($_GET['function']) && $_GET['function']=="delete") {
			$multiid = $_GET['multiid'];
			$query = $wpdb->update( $ttstoremultitable, array( 'multiitems' => ""), array( 'id' => $multiid), array( '%s'), array( '%s'), array( '%d' ) );
			$deleted = "deleted all items";
		} 
 		if(isset($_GET['function']) && $_GET['function']=="deleteempty") {
			$multiid = $_GET['multiid'];
			$itemsid = explode(",",$_GET['emptyitems']);
			$currentitems = $wpdb->get_col("SELECT multiitems FROM ".$ttstoremultitable." where id=".$multiid."");
			$currentitems = explode(",",$currentitems[0]);
			$result = arrayDiffEmulation($currentitems, $itemsid);
			$result = implode(",",$result);
			$query = $wpdb->update( $ttstoremultitable, array( 'multiitems' => $result), array( 'id' => $multiid), array( '%s'), array( '%s'), array( '%d' ) );
			$deleted = "deleted the items";
		} 
?>
<?php $adminwidth = get_option("Tradetracker_adminwidth"); ?>
<?php $adminheight = get_option("Tradetracker_adminheight"); ?>

<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle">Select items for which store</div>
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
				<b>Store Name</b>
			</td>
			<td>
				<b>Edit</b>
			</td>
			<td>
				<b>Select Item</b>
			</td>
			<td>
				<b>Delete</b>
			</td>
			<td>
				<b>Delete</b>
			</td>
			<td>
			</td>
		</tr>
<?php
		$layoutedit=$wpdb->get_results("SELECT id, multiname, multiitems FROM ".$ttstoremultitable."");
		foreach ($layoutedit as $layout_val){
		if($layout_val->multiitems != "" ) {
			$productID = $layout_val->multiitems;
			$productID = explode(",",$productID);
			$productcount = count($productID);
			if($productcount > "0" ){
				$fivesdrafts = $wpdb->get_col("SELECT productID FROM $ttstoretable");
				$result = arrayDiffEmulation($productID, $fivesdrafts);
				$emptyproductcount = count($result);
				$emptyitems = implode(",", $result);				
			}
		}
?>

		<tr>
			<td>
				<?php echo $layout_val->multiname; ?>
			</td>
			<td>
				<?php if($layout_val->id > "1"){ ?>
					<a href="admin.php?page=tt-store&option=store&function=new&return=item&multiid=<?php echo $layout_val->id; ?>">Edit Store</a>
				<?php } ?>
			</td>
			<td>
				<a href="admin.php?page=tt-store&option=itemselect&function=select&multiid=<?php echo $layout_val->id; ?>">Select Items</a>
			</td>
			<td>
				<?php if(isset($productcount)){ ?>
					<a href="admin.php?page=tt-store&option=itemselect&function=delete&multiid=<?php echo $layout_val->id; ?>">All <?php echo $productcount; ?> selected Item(s)</a>
				<?php } ?>
			</td>
			<td>
				<?php if(isset($emptyproductcount) && $emptyproductcount > "0"){ ?>
					<a href="admin.php?page=tt-store&option=itemselect&function=deleteempty&multiid=<?php echo $layout_val->id; ?>&emptyitems=<?php echo $emptyitems; ?>"><?php echo $emptyproductcount; ?> items no longer in a feed</a>
				<?php } ?>
			</td>
			<td>
				<?php if(isset($multiid) && $layout_val->id == $multiid){
					echo $deleted;
				} ?>
			</td>
		</tr>
			
<?php		
		unset($productcount);
		}
?>
	</table>
		</div>
		<div id="ttstoreboxbottom">
			<INPUT type="button" name="Close" class="button-secondary" value="<?php esc_attr_e('Close') ?>" onclick="location.href='admin.php?page=tt-store'"> 
		</div>
	</div>
</div>

<?php
	} else if($_GET['function']=="select") {
		if(!isset($_GET['order'])) {
			$order = "name";
		} else {
			$order = $_GET['order'];
		}
		$multiid = $_GET['multiid'];
	if( isset($_POST[ $ttstoresubmit ]) && $_POST[ $ttstoresubmit ] == 'Y' ) {
		$Tradetracker_items = $_POST['item'];
		$Tradetracker_items = implode(",", $Tradetracker_items);
		if($_POST['itemsother']!="")
		{
			$Tradetracker_items = $Tradetracker_items.",".$_POST['itemsother'];
		}
		if ( $multiitems != $Tradetracker_items ) 
		{ 
			$query = $wpdb->update( $ttstoremultitable, array( 'multiitems' => $Tradetracker_items), array( 'id' => $multiid), array( '%s'), array( '%s'), array( '%d' ) );
			$multiitems = $Tradetracker_items;
  		}
		$saved = "<div id=\"ttstoreboxsaved\"><strong>Settings saved</strong></div>";
	}
		$layoutedit=$wpdb->get_results("SELECT id, multixmlfeed, multiitems, multiname, categories FROM ".$ttstoremultitable." where id=".$multiid."");
		foreach ($layoutedit as $layout_val){
			$multiitems = $layout_val->multiitems;
			$multiname = $layout_val->multiname;
			if($layout_val->multixmlfeed == "*" ){
				$multixmlfeed = "";
				$searchxmlfeed = "";
			}elseif($layout_val->multixmlfeed == "" ){
				$multixmlfeed = "";
				$searchxmlfeed = "";
			} else {
				$multixmlfeed = "where xmlfeed = ".$layout_val->multixmlfeed." ";
				$searchxmlfeed = " and xmlfeed = ".$layout_val->multixmlfeed." ";
			}
			$i="1";
			$categories = unserialize($layout_val->categories);
			if(!empty($categories)){
				foreach ($categories as $categories){
					if($i == "1" ) {
						if($multixmlfeed == ""){
							$categorieselect = " where (categorieid = \"".$categories."\"";
							$searchcategorieselect = " and (categorieid = \"".$categories."\"";
						}else {
							$categorieselect = " and (categorieid = \"".$categories."\"";
							$searchcategorieselect = " and (categorieid = \"".$categories."\"";
						}
					$i = "2";
					} else {
							$categorieselect .= " or categorieid = \"".$categories."\"";
							$searchcategorieselect .= " or categorieid = \"".$categories."\"";
					}
				}
				$categorieselect .= ") ";
				$searchcategorieselect .= ") ";
			} else {
				$categorieselect = "";
				$searchcategorieselect = "";
			}
		}
	if(isset($_GET['limit'])){
		$limit = $_GET['limit'];
	}
	if(isset($_GET['currentpage'])){
		$currentpage = $_GET['currentpage'];
	}
	if (!($limit))
	{
		$limit = 100;
	}
	if (!($currentpage)){
		$currentpage = 0;
	}
	if(isset($_GET['search']) && $_GET['search'] !=""){
		$keyword = $_GET['search'];
		$searchlink = "&search=".$keyword;
		$countquery=$wpdb->get_results("SELECT * FROM ".$ttstoretable." where (`name` LIKE '%$keyword%' or `description` LIKE '%$keyword%' or `extravalue` LIKE '%$keyword%') ".$searchcategorieselect." ".$searchxmlfeed."");
	} else {
		$searchlink = "";
		$countquery=$wpdb->get_results("SELECT * FROM ".$ttstoretable." ".$multixmlfeed." ".$categorieselect."");
	}
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

span.link1 {

}

span.link1 a span {
	display: none;
	left: 0;
	position: absolute;
	top: 0;
}

span.link1 a:hover {
    	font-size: 99%;
    	font-color: #000000;
}
span.link1 a:hover span { 
	display: block; 
    	position: absolute; 
    	margin-top: 10px; 
    	margin-left: -100px; 
	max-width:700px;
	max-height:700px;
	padding: 5px; 
    	z-index: 1001; 
    	color: #000000; 
    	background: #FFFFAA; 
    	font: 12px "Arial", sans-serif;
    	text-align: left; 
    	text-decoration: none;
}

span.link {

}

span.link a span {
	display: none;
	left: 0;
	position: absolute;
	top: 0;
}

span.link a:hover {
    	font-size: 99%;
    	font-color: #000000;
}
span.link a:hover span { 
	display: block; 
    	position: absolute; 
    	margin-top: 10px; 
    	margin-left: -100px; 
	max-width:700px;
	max-height:700px;
	padding: 5px; 
    	z-index: 1001; 
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

<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle">Select the items you want to show</div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store&option=itemselect">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
		<?php $adminheight = get_option("Tradetracker_adminheight"); ?>
		<div id="ttstoreboxoptions" style="max-height:<?php echo $adminheight; ?>px;">
	<?php echo "<form class=\"\" action=\"admin.php\" method=\"get\">
		<input type=\"hidden\" name=\"page\" value=\"tt-store\">
		<input type=\"hidden\" name=\"option\" value=\"itemselect\">
		<input type=\"hidden\" name=\"function\" value=\"select\">
		<input type=\"hidden\" name=\"multiid\" value=\"".$multiid."\">
		<input type=\"hidden\" name=\"limit\" value=\"".$limit."\">
		<input type=\"hidden\" name=\"order\" value=\"".$order."\">
		<input class=\"s\" type=\"text\" name=\"search\" value=\"\">

		<input class=\"searchsubmit\" type=\"submit\" title=\"search item\" value=\"Search\">
		</form>"; ?>
<table width="<?php echo $adminwidth-15; ?>" border="0">
	<tr>
		<td width="50%" align="left">
			Showing products <b><? echo $first; ?></b> - <b><?php echo $last; ?></b> of <b><?php echo $numrows; ?></b>
  		</td>
  		<td width="50%" align="right">
			<?php if ($currentpage != 0) { $back_page = $currentpage - $limit; echo("<a href=\"admin.php?page=tt-store&option=itemselect&function=select".$searchlink."&multiid=".$multiid."&order=$order&currentpage=$back_page&limit=$limit\"><</a>");} ?> Page <b><?php echo $current; ?></b> of <b><?php echo $total; ?></b> <?php if (!((($currentpage+$limit) / $limit) >= $pages) && $pages != 1) { $next_page = $currentpage + $limit; echo("<a href=\"admin.php?page=tt-store&option=itemselect&function=select".$searchlink."&multiid=".$multiid."&order=$order&currentpage=$next_page&limit=$limit\">></a>");} ?>
  		</td>
 	</tr>
 	<tr>
  		<td colspan="2" align="right">
			Results per-page: <a href="admin.php?page=tt-store&option=itemselect&function=select<?php echo $searchlink; ?>&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=100">100</a> | <a href="admin.php?page=tt-store&option=itemselect&function=select<?php echo $searchlink; ?>&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=200">200</a> | <a href="admin.php?page=tt-store&option=itemselect&function=select<?php echo $searchlink; ?>&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=500">500</a> | <a href="admin.php?page=tt-store&option=itemselect&function=select<?php echo $searchlink; ?>&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=1000">1000</a>
  		</td>
 	</tr>
</table>
<?php
if(isset($_GET['search']) && $_GET['search']!=""){
	$keyword = $_GET['search'];
	$visits=$wpdb->get_results("SELECT * FROM ".$ttstoretable." where (`name` LIKE '%$keyword%' or `description` LIKE '%$keyword%' or `extravalue` LIKE '%$keyword%') ".$searchcategorieselect." ".$searchxmlfeed." ORDER BY ".$order." ASC LIMIT ".$currentpage.", ".$limit."");
} else {
	$visits=$wpdb->get_results("SELECT * FROM ".$ttstoretable." ".$multixmlfeed." ".$categorieselect." ORDER BY ".$order." ASC LIMIT ".$currentpage.", ".$limit."");
}
	echo "<table width=\"<?php echo $adminwidth-15; ?>\" border=\"0\" style=\"border-width: 0px;padding:0px;border-spacing:0px;\">";
		echo "<tr><td width=\"200\">";
			echo "<b><a href=\"admin.php?page=tt-store&option=itemselect&limit=".$limit."&function=select".$searchlink."&multiid=".$multiid."&order=productID\">ProductID</a></b>";
		echo "</td><td width=\"435\">";
			echo "<b><a href=\"admin.php?page=tt-store&option=itemselect&limit=".$limit."&function=select".$searchlink."&multiid=".$multiid."&order=name\">Product name</a></b>";
		echo "</td><td width=\"200\">";
			echo "<b>XMLFeed</b>";
		echo "</td><td width=\"50\">";
			echo "<b><a href=\"admin.php?page=tt-store&option=itemselect&limit=".$limit."&function=select".$searchlink."&multiid=".$multiid."&order=price\">Price</a></b>";
		echo "</td><td width=\"65\">";
			echo "<b>Currency</b>";
		echo "</td><td width=\"50\">";
			echo "<b>Extra's</b>";
		echo "</td></tr>";
	echo "<form name=\"form2\" method=\"post\" action=\"\">";
		echo $ttstorehidden;
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
					$imageURL = plugins_url( 'images/No_image.png' , __FILE__ );
				} else {
					$imageURL = $product->imageURL;
				}
				$xmlfeedname = get_option('Tradetracker_xmlname');
				echo $product->productID;
				echo "</td><td><span class=\"link1\"><a href=\"javascript: void(0)\">";
				echo $product->name;
				echo "<span><img src=\"".$imageURL."\" width=\"400px\"></span></a></span></td><td>";
				echo $xmlfeedname[$product->xmlfeed];
				echo "</td><td>";
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
							$extraname .= "<td><b>".$key."</b></td>";
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
	echo "<table width=\"<?php echo $adminwidth-15; ?>\"><tr><td>";
	if ($currentpage != 0) { // Don't show back link if current page is first page.
		$back_page = $currentpage - $limit;
		echo("<a href=\"admin.php?page=tt-store&option=itemselect&function=select".$searchlink."&multiid=".$multiid."&order=$order&currentpage=$back_page&limit=$limit\">back</a>    \n");
	}
	for ($i=1; $i <= $pages; $i++){
		$ppage = $limit*($i - 1);
		if ($ppage == $currentpage){
			echo("<b>$i</b> \n"); // If current page don't give link, just text.
		}else{
			echo("<a href=\"admin.php?page=tt-store&option=itemselect&function=select".$searchlink."&multiid=".$multiid."&order=$order&currentpage=$ppage&limit=$limit\">$i</a> \n");
		}
	}
	if (!((($currentpage+$limit) / $limit) >= $pages) && $pages != 1) { // If last page don't give next link.
		$next_page = $currentpage + $limit;
		echo("    <a href=\"admin.php?page=tt-store&option=itemselect&function=select".$searchlink."&multiid=".$multiid."&order=$order&currentpage=$next_page&limit=$limit\">next</a>\n");
	}
	echo "</td></tr></table>";
?>

		</div>
		<div id="ttstoreboxbottom">
			<?php
				if(isset($saved)){
					echo $saved;
				}
			?>
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /> 
			<INPUT type="button" name="Close" class="button-secondary" value="<?php esc_attr_e('Close') ?>" onclick="location.href='admin.php?page=tt-store&option=itemselect'"> 
		</div>
	</div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>

<?php

	}
}
?>