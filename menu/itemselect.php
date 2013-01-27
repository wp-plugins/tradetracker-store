<?php
function itemselect() {
	global $wpdb;
	global $ttstoresubmit;
	global $ttstorehidden;
	global $ttstoremultitable;
	global $ttstoreitemtable;
	global $ttstoretable;
	global $ttstoreextratable;
	global $ttstorexmltable;
	global $ttstorecattable;
	global $folderhome;
	if(!isset($_GET['function']) || $_GET['function']=="delete" || $_GET['function']=="deleteempty"){
 		if(isset($_GET['function']) && $_GET['function']=="delete") {
			$multiid = $_GET['multiid'];
			$query = "DELETE FROM `".$ttstoreitemtable."` WHERE `".$ttstoreitemtable."`.`storeID` = ".$multiid."";
			$wpdb->query($query);
			$deleted = __("deleted all items", "ttstore");
		} 
 		if(isset($_GET['function']) && $_GET['function']=="deleteempty") {
			$multiid = $_GET['multiid'];
			$itemsid = explode(",", $_GET['emptyitems']);
			foreach($itemsid as $item){
				$query = "DELETE FROM `".$ttstoreitemtable."` WHERE `".$ttstoreitemtable."`.`productID` = '".$item."'";
				$wpdb->query($query);
			}
			$deleted = __("deleted the items", "ttstore");
		} 
?>
<?php $adminwidth = get_option("Tradetracker_adminwidth"); ?>
<?php $adminheight = get_option("Tradetracker_adminheight"); ?>

<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle"><?php _e("Select items for which store", "ttstore"); ?></div>
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
				<b><?php _e("Store Name", "ttstore"); ?></b>
			</td>
			<td>
				<b><?php _e("Edit", "ttstore"); ?></b>
			</td>
			<td>
				<b><?php _e("Select Item", "ttstore"); ?></b>
			</td>
			<td>
				<b><?php _e("Delete", "ttstore"); ?></b>
			</td>
			<td>
				<b><?php _e("Delete", "ttstore"); ?></b>
			</td>
			<td>
			</td>
		</tr>
<?php
		$layoutedit=$wpdb->get_results("SELECT ".$ttstoremultitable.".id, multiname, multiitems, count(".$ttstoreitemtable.".id) as totalitems FROM ".$ttstoremultitable." left join ".$ttstoreitemtable." on ".$ttstoremultitable.".id = ".$ttstoreitemtable.".storeID group by storeID, multiname order by ".$ttstoremultitable.".id");
		foreach ($layoutedit as $layout_val){
			if($layout_val->totalitems > "0" ){
				$nonexisting=$wpdb->get_results('select t1.productID from '.$ttstoreitemtable.' t1 left join '.$ttstoretable.' t2 on t1.productID = t2.productID  where t1.storeID = "'.$layout_val->id.'" and t2.productID is null group by t1.productID');
				$productcount = $layout_val->totalitems;
				$emptyproductcount = count($nonexisting);
				$result = array();
				foreach($nonexisting as $term){
					$result[] = $term->productID;
				}
				$emptyitems = implode(",", $result);
			} else {
				$emptyproductcount = "";
				$emptyitems = "";
			}
?>

		<tr>
			<td>
				<?php echo $layout_val->multiname; ?>
			</td>
			<td>
				<?php if($layout_val->id > "1"){ ?>
					<a href="admin.php?page=tt-store&option=store&function=new&return=item&multiid=<?php echo $layout_val->id; ?>"><?php _e("Edit Store", "ttstore"); ?></a>
				<?php } ?>
			</td>
			<td>
				<a href="admin.php?page=tt-store&option=itemselect&function=select&multiid=<?php echo $layout_val->id; ?>"><?php _e("Select Items", "ttstore"); ?></a>
			</td>
			<td>
				<?php if(isset($productcount)){ ?>
					<a href="admin.php?page=tt-store&option=itemselect&function=delete&multiid=<?php echo $layout_val->id; ?>"><?php printf(__('All %d selected Item(s)', 'ttstore'), $productcount); ?></a>
				<?php } ?>
			</td>
			<td>
				<?php if(isset($emptyproductcount) && $emptyproductcount > "0"){ ?>
					<a href="admin.php?page=tt-store&option=itemselect&function=deleteempty&multiid=<?php echo $layout_val->id; ?>&emptyitems=<?php echo $emptyitems; ?>"><?php echo $emptyproductcount; ?> <?php _e("items no longer in a feed", "ttstore"); ?></a>
					<?php $emptyproductcount = ""; ?>
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
		$query = "DELETE FROM `".$ttstoreitemtable."` WHERE `".$ttstoreitemtable."`.`storeID` = ".$multiid."";
		$wpdb->query($query);
		if($_POST['itemsother']!="")
		{
			$itemsother = explode(",",$_POST['itemsother']);
			$Tradetracker_items = array_merge($Tradetracker_items, $itemsother);
		}
		foreach ($Tradetracker_items as $itemoverview){
			$wpdb->insert( 
				$ttstoreitemtable, 
				array( 
					storeID => $multiid, 
					productID => $itemoverview 
				), 
				array( 
					'%d', 
					'%s' 
				) 
			);
		}
		$savedmessage = __("Settings saved", "ttstore");
		$saved = "<div id=\"ttstoreboxsaved\"><strong>".$savedmessage."</strong></div>";
	}
		$layoutedit=$wpdb->get_results("SELECT ".$ttstoremultitable.".id, multixmlfeed, multiitems, multiname, categories, count(".$ttstoreitemtable.".id) as totalitems FROM ".$ttstoremultitable." left join ".$ttstoreitemtable." on ".$ttstoremultitable.".id = ".$ttstoreitemtable.".storeID where ".$ttstoremultitable.".id='".$multiid."' group by storeID, multiname");
		foreach ($layoutedit as $layout_val){
			if($layout_val->totalitems >0){
				$multiitems=$wpdb->get_results("SELECT ".$ttstoreitemtable.".productID FROM ".$ttstoreitemtable." where ".$ttstoreitemtable.".storeID = ".$layout_val->id."");
				$productID = array();
				foreach($multiitems as $term){
					$productID[] = $term->productID;
				}	
			}
			$multiname = $layout_val->multiname;
			if($layout_val->multixmlfeed == "*" ){
				$multixmlfeed = "";
				$searchxmlfeed = "";
			}elseif($layout_val->multixmlfeed == "" ){
				$multixmlfeed = "";
				$searchxmlfeed = "";
			} else {
				$multixmlfeed = "and xmlfeed = ".$layout_val->multixmlfeed." ";
				$searchxmlfeed = " and xmlfeed = ".$layout_val->multixmlfeed." ";
			}
			$i="1";
			$categories = unserialize($layout_val->categories);
			if(!empty($categories)){
				foreach ($categories as $categories){
					if($i == "1" ) {
						if($multixmlfeed == ""){
							$categorieselect = " and (categorieid = \"".$categories."\"";
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
	if (!isset($limit))
	{
		$limit = 100;
	}
	if (!isset($currentpage)){
		$currentpage = 0;
	}
	if(isset($_GET['search']) && $_GET['search'] !=""){
		$keyword = $_GET['search'];
		$searchlink = "&search=".$keyword;
		$countquery=$wpdb->get_results("SELECT * FROM ".$ttstoretable.", ".$ttstorecattable." left join ".$ttstoreextratable." on ".$ttstoreextratable.".productID = ".$ttstoretable.".productID and ".$ttstoreextratable.".`extravalue` LIKE '%$keyword%' where ".$ttstorecattable.".productID = ".$ttstoretable.".productID and (MATCH(name,description) AGAINST ('$keyword') or ".$ttstoreextratable.".`extravalue` != null) ".$searchcategorieselect." ".$searchxmlfeed." group by ".$ttstoretable.".productID");
	} else {
		$searchlink = "";
		$countquery=$wpdb->get_results("SELECT * FROM ".$ttstoretable.", ".$ttstorecattable." where ".$ttstorecattable.".productID = ".$ttstoretable.".productID ".$multixmlfeed." ".$categorieselect." group by ".$ttstoretable.".productID");
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
<?php $adminwidth = get_option("Tradetracker_adminwidth"); ?>
<?php $adminheight = get_option("Tradetracker_adminheight"); ?>

<div  id="TB_overlay" class="TB_overlayBG"></div>
<div id="TB_window1" style="left: auto;margin-left: auto;margin-right: auto; margin-top: 0;right: auto;top: 48px;visibility: visible;width: <?php echo $adminwidth; ?>px;">
	<div id="ttstorebox">
		<div id="TB_title">
			<div id="TB_ajaxWindowTitle"><?php _e("Select the items you want to show", "ttstore"); ?></div>
			<div id="TB_closeAjaxWindow">
				<a title="Close" id="TB_closeWindowButton" href="admin.php?page=tt-store&option=itemselect">
					<img src="<?php echo plugins_url( 'images/tb-close.png' , __FILE__ )?>">
				</a>
			</div>
		</div>
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
<table width="<?php echo $adminwidth-30; ?>" border="0">
	<tr>
		<td width="50%" align="left">
			<?php _e("Showing products", "ttstore"); ?> <b><? echo $first; ?></b> - <b><?php echo $last; ?></b> <?php _e("of", "ttstore"); ?> <b><?php echo $numrows; ?></b>
  		</td>
  		<td width="50%" align="right">
			<?php if ($currentpage != 0) { $back_page = $currentpage - $limit; echo("<a href=\"admin.php?page=tt-store&option=itemselect&function=select".$searchlink."&multiid=".$multiid."&order=$order&currentpage=$back_page&limit=$limit\"><</a>");} ?> <?php _e("Page", "ttstore"); ?> <b><?php echo $current; ?></b> <?php _e("of", "ttstore"); ?> <b><?php echo $total; ?></b> <?php if (!((($currentpage+$limit) / $limit) >= $pages) && $pages != 1) { $next_page = $currentpage + $limit; echo("<a href=\"admin.php?page=tt-store&option=itemselect&function=select".$searchlink."&multiid=".$multiid."&order=$order&currentpage=$next_page&limit=$limit\">></a>");} ?>
  		</td>
 	</tr>
 	<tr>
  		<td colspan="2" align="right">
			<?php _e("Results per-page:", "ttstore"); ?> <a href="admin.php?page=tt-store&option=itemselect&function=select<?php echo $searchlink; ?>&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=100">100</a> | <a href="admin.php?page=tt-store&option=itemselect&function=select<?php echo $searchlink; ?>&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=200">200</a> | <a href="admin.php?page=tt-store&option=itemselect&function=select<?php echo $searchlink; ?>&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=500">500</a> | <a href="admin.php?page=tt-store&option=itemselect&function=select<?php echo $searchlink; ?>&multiid=<?php echo $multiid; ?>&order=<?php echo $order; ?>&currentpage=<?php echo $currentpage; ?>&limit=1000">1000</a>
  		</td>
 	</tr>
</table>
<?php
if(isset($_GET['search']) && $_GET['search']!=""){
	$keyword = $_GET['search'];
	$visits=$wpdb->get_results("SELECT ".$ttstoretable.".*, ".$ttstorecattable.".categorieid, ".$ttstorecattable.".categorie FROM ".$ttstoretable.", ".$ttstorecattable." where ".$ttstorecattable.".productID = ".$ttstoretable.".productID and (`name` LIKE '%$keyword%' or `description` LIKE '%$keyword%' or `extravalue` LIKE '%$keyword%') ".$searchcategorieselect." ".$searchxmlfeed." group by ".$ttstoretable.".productID ORDER BY ".$order." ASC  LIMIT ".$currentpage.", ".$limit."");
} else {
	$visits=$wpdb->get_results("SELECT ".$ttstoretable.".*, ".$ttstorecattable.".categorieid, ".$ttstorecattable.".categorie FROM ".$ttstoretable.", ".$ttstorecattable." where ".$ttstorecattable.".productID = ".$ttstoretable.".productID ".$multixmlfeed." ".$categorieselect." group by ".$ttstoretable.".productID ORDER BY ".$order." ASC LIMIT ".$currentpage.", ".$limit."");
}
	echo "<table width=\"<?php echo $adminwidth-15; ?>\" border=\"0\" style=\"border-width: 0px;padding:0px;border-spacing:0px;\">";
		echo "<tr><td width=\"200\">";
			echo "<b><a href=\"admin.php?page=tt-store&option=itemselect&limit=".$limit."&function=select".$searchlink."&multiid=".$multiid."&order=productID\">"; _e('ProductID', 'ttstore'); echo "</a></b>";
		echo "</td><td width=\"435\">";
			echo "<b><a href=\"admin.php?page=tt-store&option=itemselect&limit=".$limit."&function=select".$searchlink."&multiid=".$multiid."&order=name\">"; _e('Product name', 'ttstore'); echo "</a></b>";
		echo "</td><td width=\"200\">";
			echo "<b>"; _e('XMLFeed', 'ttstore'); echo "</b>";
		echo "</td><td width=\"50\">";
			echo "<b><a href=\"admin.php?page=tt-store&option=itemselect&limit=".$limit."&function=select".$searchlink."&multiid=".$multiid."&order=price\">"; _e('Price', 'ttstore'); echo "</a></b>";
		echo "</td><td width=\"65\">";
			echo "<b>"; _e('Currency', 'ttstore'); echo "</b>";
		echo "</td><td width=\"50\">";
			echo "<b>"; _e("Extra's", "ttstore"); echo "</b>";
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

				if(!empty($productID) && in_array($product->productID, $productID, true))
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
				$xmlfeed=$wpdb->get_var("SELECT xmlname FROM ".$ttstorexmltable." where id=".$product->xmlfeed.""); 
				echo $xmlfeed;
				echo "</td><td>";
				echo $product->price;
				echo "</td><td>";
				echo $product->currency;
				$extraname = "";
				$extravar ="";
				$extras = $wpdb->get_results("SELECT extravalue, extrafield FROM $ttstoreextratable where productID='".$product->productID."'", ARRAY_A);
				foreach ($extras as $extra) {
					$Tradetracker_extra_val = get_option("Tradetracker_extra");
					if(!empty($Tradetracker_extra_val)){
						if(in_array($extra[extrafield], $Tradetracker_extra_val, true)) {
							$extraname .= "<td><b>".$extra[extrafield]."</b></td>";
							$extravar .= "<td>".$extra[extravalue]."</td>";
						}
					}
				}
				if($extraname != ""){
					echo "</td><td><span class=\"link\"><a href=\"javascript: void(0)\">"; _e("Yes", "ttstore"); echo "<span><table><tr>".$extraname."</tr><tr>".$extravar."</tr></table> </span></a></span></td></tr>";
				} else {
					echo "</td><td>"; _e("No", "ttstore"); echo "</td></tr>";
				}
				unset($extras);
				if ($colors == "1"){
					$colors++;
				} else {
					$colors--;
				}

			}
		if(!empty($array2) && !empty($productID)){
			$array1 = $productID;
			$array2 = explode(",", $array2);
			$result = array_diff($array1, $array2);
			if(isset($result)){
				$result = implode(",", $result);
			}
		}
			echo "<input type=\"hidden\" name=\"itemsother\" value=\"".$result."\" />";
	echo "<tr><td colspan=\"5\">"; _e("Select", "ttstore"); echo " <a href=\"javascript:selectToggle(true, 'form2');\">"; _e("All", "ttstore"); echo "</a> | <a href=\"javascript:selectToggle(false, 'form2');\">"; _e("None", "ttstore"); echo "</a></td></tr>";
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


<?php

	}
}
?>