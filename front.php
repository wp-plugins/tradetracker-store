<?php

/* 
..--==[ Function to add the stylesheet for the store ]==--.. 
*/

function TTstore_scripts() { 

	wp_deregister_script('jquery');   
	wp_enqueue_script( 'ttstoreexpand-script', WP_PLUGIN_URL . '/tradetracker-store/js/expand.js');
	wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
	wp_enqueue_script( 'jquery-filter', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js');
}       
	
add_action('wp_enqueue_scripts', 'TTstore_scripts'); 

function store_items($used, $winkel, $searching)
{
	global $wpdb;
	global $ttstorexmltable;
	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $ttstorexmltable;" ) ); 
	if($tradetracker_xml>0)	
	{
		_e('No XML filled in yet please change the settings first.', 'ttstore');
	} else {
		return show_items($used, $winkel, $searching);
	}
} 
/* 
..--==[ Function to show the items. ]==--.. 
*/
add_action('wp_head', 'header_css_style');

function header_css_style() {
	global $wpdb;
	if(get_option("Tradetracker_usecss") == "1"){
		echo "<link rel=\"stylesheet\" href=\"".get_option('Tradetracker_csslink')."\" type=\"text/css\" />";
  		echo "<link href=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css\" rel=\"stylesheet\" type=\"text/css\"/>";


	} else {
  		echo "<link href=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css\" rel=\"stylesheet\" type=\"text/css\"/>";
	$style="";
	global $ttstorelayouttable;
	global $ttstoremultitable;
	$style .= "<style type=\"text/css\" media=\"screen\">";
	$multi=$wpdb->get_results("SELECT multiname, laywidth, layfont, laycolortitle, laycolorbuttonfont, laycolorbutton, laycolorborder, laycolorfooter, laycolorimagebg, laycolorfont FROM ".$ttstoremultitable.",".$ttstorelayouttable." where ".$ttstoremultitable.".multilayout=".$ttstorelayouttable.".id");
	foreach ($multi as $multi_val){
		$i="1";
		if( $multi_val->laywidth == "" ){
			$width= "250";
		} else {
			$width= $multi_val->laywidth;
		}
		if( $multi_val->layfont == "" ){
			$font= "Verdana";
		} else {
			$font= $multi_val->layfont;
		}
		$widthtitle = $width-6;
		$widthmore = $width-10;
		if( $multi_val->laycolortitle == "" ){
			$colortitle = "#ececed";
		} else {
			$colortitle = $multi_val->laycolortitle;
		}
		if( $multi_val->laycolorfooter == "" ){
			$colorfooter = "#ececed";
		} else {
			$colorfooter = $multi_val->laycolorfooter;
		}
		if( $multi_val->laycolorimagebg == "" ){
			$colorimagebg = "#ffffff";
		} else {
			$colorimagebg = $multi_val->laycolorimagebg;
		}
		if( $multi_val->laycolorfont == "" ){
			$colorfont = "#000000";
		} else {
			$colorfont = $multi_val->laycolorfont;
		}
		if( $multi_val->laycolorborder == "" ){
			$colorborder = "#65B9C1";
		} else {
			$colorborder = $multi_val->laycolorborder;
		}
		if( $multi_val->laycolorbutton == "" ){
			$colorbutton = "#65B9C1";
		} else {
			$colorbutton = $multi_val->laycolorbutton;
		}
		if( $multi_val->laycolorbuttonfont == "" ){
			$colorbuttonfont = "#ffffff";
		} else {
			$colorbuttonfont = $multi_val->laycolorbuttonfont;
		}
		$storename = create_slug($multi_val->multiname);
		$style .= "\n.".$storename."store-outerbox{width:".$width."px;color:".$colorfont.";font-family:".$font.";float:left;margin:0px 15px 15px 0;min-height:353px;border:solid 1px ".$colorborder.";position:relative;}";
		$style .= "\n.".$storename."store-titel{width:".$widthtitle."px;background-color:".$colortitle.";color:".$colorfont.";float:left;position:relative;height:30px;line-height:15px;font-size:11px;padding:3px;font-weight:bold;text-align:center;}";
		$style .= "\n.".$storename."store-image{width:".$width."px;height:180px;padding:0px;overflow:hidden;margin: auto;background-color:".$colorimagebg.";}";
		$style .= "\n.".$storename."store-image img{display: block;border:0px;margin: auto;}";
		$style .= "\n.".$storename."store-footer{width:".$width."px;background-color:".$colorfooter.";float:left;position:relative;min-height:137px;}";
		$style .= "\n.".$storename."store-description{width:".$widthtitle."px;color:".$colorfont.";position:relative;top:5px;left:5px;height:90px;line-height:14px;font-size:10px;overflow:auto;}";
		$style .= "\n.".$storename."store-more{min-height:20px; width:".$widthtitle."px;position: relative;float: left;margin-top:10px;margin-left:5px;margin-bottom: 5px;}";
		$style .= "\n.".$storename."store-more img{margin:0px !important;}";
		$style .= "\n.".$storename."store-price {border: 0 solid #65B9C1;color: #4E4E4E !important;float: right;font-size: 12px !important;font-weight: bold !important;height: 30px !important;position: relative;text-align: center !important;width: 80px !important;}";
		$style .= "\n.".$storename."store-price table {height:29px;width:79px;background-color: ".$colorfooter." !important; border: 1px none !important;border-collapse: inherit !important;float: right;margin: 1px 0 1px 1px;text-align: center !important;}";
		$style .= "\n.".$storename."store-price table tr {padding: 1px !important;}";
		$style .= "\n.".$storename."store-price table tr td {padding: 1px !important;}";
		$style .= "\n.".$storename."store-price table td, table th, table tr {border: 1px solid #CCCCCC;padding: 0 !important;}";
		$style .= "\n.".$storename."store-price table td.euros {font-size: 12px !important;letter-spacing: -1px !important; }";
		$style .= "\n.".$storename."store-price {background-color: ".$colorborder." !important;}";
		$style .= "\n.".$storename."buttons a, .".$storename."buttons button {background-color: ".$colorbutton.";border: 1px solid ".$colorbutton.";bottom: 0;color: ".$colorbuttonfont.";cursor: pointer;display: block;float: left;font-size: 12px;font-weight: bold;margin-top: 0;padding: 5px 10px 5px 7px;position: relative;text-decoration: none;width: 100px;}";
		$style .= "\n.".$storename."buttons button {overflow: visible;padding: 4px 10px 3px 7px;width: auto;}";
		$style .= "\n.".$storename."buttons button[type] {line-height: 17px;padding: 5px 10px 5px 7px;}";
		$style .= "\n.".$storename.":first-child + html button[type] {padding: 4px 10px 3px 7px;}";
		$style .= "\n.".$storename."buttons button img, .".$storename."buttons a img {border: medium none;margin: 0 3px -3px 0 !important;padding: 0;}";
		$style .= "\n.".$storename."button.regular, .".$storename."buttons a.regular {color: ".$colorbuttonfont.";}";
		$style .= "\n.".$storename."buttons a.regular:hover, button.regular:hover {background-color: #4E4E4E;border: 1px solid #4E4E4E;color: ".$colorbuttonfont.";}";
		$style .= "\n.".$storename."buttons a.regular:active {background-color: #FFFFFF;border: 1px solid ".$colorbutton.";color: ".$colorbuttonfont.";}";
		
	}
	$style .= "\n.cleared {border: medium none;clear: both;float: none;font-size: 1px;margin: 0;padding: 0;}";
	$style .= "\n.ttstorelink a { display:none; }";
	$style .= "</style>";
	echo $style;
	}
}
function show_ttuserpages($winkelvol)
{
	if(isset($_GET['ipp'])){
		$itemsperpage = $_GET['ipp'];
	} else {
		$itemsperpage = "0";
	}
	$userperpage = "<form action=\"\" method=\"get\" name=\"itemsperpage\" >";
	if(isset($_GET['pmax'])){
		$userperpage .= "<input type=\"hidden\" value=\"".$_GET['pmax']."\" name=\"pmax\">";
	}
	if(isset($_GET['pmin'])){
		$userperpage .= "<input type=\"hidden\" value=\"".$_GET['pmin']."\" name=\"pmin\">";
	}
	$userperpage .= __('Items per page:','ttstore');
	$userperpage .= "<select name=\"ipp\" onchange=\"this.form.submit();\">";
	$userperpage .= "<option name=\"\"> </option>";
	$userperpage .= "<option name=\"10\""; 
	if($itemsperpage == "10"){ 
		$userperpage .= "selected"; 
	} 
	$userperpage .= ">10</option>";
	$userperpage .= "<option name=\"20\""; 
	if($itemsperpage == "20"){ 
		$userperpage .= "selected"; 
	} 
	$userperpage .= ">20</option>";
	$userperpage .= "<option name=\"50\""; 
	if($itemsperpage == "50"){ 
		$userperpage .= "selected"; 
	} 
	$userperpage .= ">50</option>";
	$userperpage .= "<option name=\"100\""; 
	if($itemsperpage == "100"){ 
		$userperpage .= "selected"; 
	} 
	$userperpage .= ">100</option>";
	$userperpage .= "</select>";
	$userperpage .= "</form>";
	return $userperpage;
}
function show_ttfilter($winkelvol)
{
	global $wpdb;
	global $ttstoremultitable;
	global $ttstoreitemtable;
	global $ttstoretable;
	$max_price = $wpdb->get_var( $wpdb->prepare( "SELECT MAX(price) FROM $ttstoretable;" ) );
	$max_price = round($max_price+1);
	if(isset($_GET['ipp'])){
		$ipp = $_GET['ipp'];
	} else {
		$ipp = "10";
	}
	if(isset($_GET['pmin']) && isset($_GET['pmax'])){
		$min_price = $_GET['pmin'];
		$max_pricecur = $_GET['pmax'];
	} else {
		$min_price = "0";
		$max_pricecur = $max_price;
	}
	$filter = "<style>#demo-frame > div.demo { padding: 10px !important; };</style>";
	$filter .= "<script>
	$(function() {
		$( \"#slider-range\" ).slider({
			range: true,
			min: 0,
			max: ".$max_price.",
			values: [ ".$min_price.", ".$max_pricecur." ],
			slide: function( event, ui ) {
				$( \"#amount\" ).val( \"\u20AC\" + ui.values[ 0 ] + \" - \u20AC\" + ui.values[ 1 ] );
			}, change: function(event, ui) { 


location.href = '?ipp=".$ipp."&tsp=0&pmin=' + ui.values[0] + '&pmax=' + ui.values[1] ; 
    
     }
		});
		$( \"#amount\" ).val( \"\u20AC\" + $( \"#slider-range\" ).slider( \"values\", 0 ) +
			\" - \u20AC\" + $( \"#slider-range\" ).slider( \"values\", 1 ) );
	});
	</script>
<div class=\"demo\">

<p>
	<label for=\"amount\">".__('Price range:','ttstore')."</label>
	<input type=\"text\" readonly=\"readonly\" id=\"amount\" style=\"border:0; color:#f6931f; font-weight:bold;\" />
</p>

<div id=\"slider-range\"></div>

</div><!-- End demo -->";
	return $filter;

}
function show_ttpages($winkelvol)
{
	global $wpdb;
	global $ttstoremultitable;
	global $ttstoreitemtable;
	global $ttstoretable;
	global $ttstorecattable;
	$multi=$wpdb->get_results("SELECT multiamount, multiitems, multipageamount, categories, multixmlfeed, count(".$ttstoreitemtable.".id) as totalitems FROM ".$ttstoremultitable." left join ".$ttstoreitemtable." on ".$ttstoremultitable.".id = ".$ttstoreitemtable.".storeID where ".$ttstoremultitable.".id=".$winkelvol." group by storeID, multiname");
	foreach ($multi as $multi_val){	
		$Tradetracker_productid = $multi_val->totalitems;
		$Tradetracker_amount = $multi_val->multiamount;
		if($multi_val->multixmlfeed == "*" ){
			$multixmlfeed = "";
		} else {
			$multixmlfeed = "and xmlfeed = ".$multi_val->multixmlfeed." ";
		}
		$i="1";
		$categories = unserialize($multi_val->categories);
		if(!empty($categories)){
			foreach ($categories as $categories){
				if($i == "1" ) {
					if($multixmlfeed == ""){
						$categorieselect = " and (categorieid = \"".$categories."\"";
					}else {
						$categorieselect = " and (categorieid = \"".$categories."\"";
					}
				$i = "2";
				} else {
						$categorieselect .= " or categorieid = \"".$categories."\"";
				}
			}
			$categorieselect .= ") ";
		} else {
			$categorieselect = "";
		}
		if(isset($_GET['pmin']) && isset($_GET['pmax'])){
			if($multixmlfeed == ""){
				if($categorieselect == ""){
					$priceselect = " and price > ".mysql_real_escape_string($_GET['pmin'])." and price < ".mysql_real_escape_string($_GET['pmax'])."";
				} else {
					$priceselect = " and price > ".mysql_real_escape_string($_GET['pmin'])." and price < ".mysql_real_escape_string($_GET['pmax'])."";
				}
			} else {
				$priceselect = " and price > ".mysql_real_escape_string($_GET['pmin'])." and price < ".mysql_real_escape_string($_GET['pmax'])."";
			}
		} else {
			$priceselect = "";
		}

		if($multi_val->multipageamount > "0" || isset($_GET['ipp'])){
			if ($Tradetracker_productid == "0") 
			{
				if ($multi_val->multiamount == "") {
					$Tradetracker_amount_i = "LIMIT 12"; 
				} else if ($multi_val->multiamount == "0") {
					$Tradetracker_amount_i = "";
				} else {
					$Tradetracker_amount_i = "LIMIT ".$multi_val->multiamount.""; 
				}
				$totalitems=count($wpdb->get_results("SELECT id FROM ".$ttstoretable.", ".$ttstorecattable." where ".$ttstorecattable.".productID = ".$ttstoretable.".productID ".$multixmlfeed." ".$categorieselect." ".$priceselect." ".$Tradetracker_amount_i.""));
			} else {
				if ($multi_val->multiamount == "") {
					$Tradetracker_amount_i = "LIMIT 12";
				} else if ($multi_val->multiamount == "0") {
					$Tradetracker_amount_i = "LIMIT ".$Tradetracker_productid;
				} else if ($Tradetracker_productid < $multi_val->multiamount){
					$Tradetracker_amount_i = "LIMIT ".$Tradetracker_productid;
				} else {
					$Tradetracker_amount_i = "LIMIT ".$multi_val->multiamount.""; 
				}
				$totalitems=count($wpdb->get_results("SELECT id FROM ".$ttstoretable.", ".$ttstorecattable." where ".$ttstorecattable.".productID = ".$ttstoretable.".productID ".$priceselect." ".$Tradetracker_amount_i.""));
			}
			if(isset($_GET['ipp']) && $_GET['ipp']>"0"){
				$itemsperpage = mysql_real_escape_string($_GET['ipp']);
			} else {
				$itemsperpage = $multi_val->multipageamount;
			}
			$pages = round($totalitems / $itemsperpage)-1;
			if(isset($_GET['tsp'])){
				$currentpage = mysql_real_escape_string($_GET['tsp']);
				$nextpage = $currentpage * $multi_val->multipageamount;
			} else {
				$currentpage = "0";
				$nextpage = $currentpage + $multi_val->multipageamount;
			}
			if(isset($_GET['pmin']) && isset($_GET['pmax'])){
				$min_price = "&pmin=".$_GET['pmin'];
				$max_pricecur = "&pmax=".$_GET['pmax'];
			} else {
				$min_price = "";
				$max_pricecur = "";
			}
			if($pages > "0"){
				$pagetext ="";
				if ($currentpage != 0) { // Don't show back link if current page is first page.
					$back_page = $currentpage - "1";
					$pagetext = "<a href=\"?ipp=".$itemsperpage."&tsp=".$back_page."".$min_price."".$max_pricecur."\">".__('back','ttstore')."</a>\n";			
				}
				for ($i=0; $i <= $pages; $i++){
					if ($i == $currentpage){
						$pagetext .= "<b>$i</b> \n"; // If current page don't give link, just text.
					}else{
						$pagetext .= "<a href=\"?ipp=".$itemsperpage."&tsp=".$i."".$min_price."".$max_pricecur."\">$i</a> \n";
					}
				}
				if ($currentpage < $pages ) { // If last page don't give next link.
					$next_page = $currentpage + "1";
					$pagetext .= "<a href=\"?ipp=".$itemsperpage."&tsp=".$next_page."".$min_price."".$max_pricecur."\">".__('next','ttstore')."</a>\n";
				}
			}
		}
	}
	if(isset($pagetext)){
		return $pagetext;
	}
}

function show_items($usedhow, $winkelvol, $searching)
{
	global $wpdb;
	global $ttstorelayouttable;
	global $ttstoremultitable;
	global $ttstoreitemtable;
	global $ttstoretable;
	global $folderhome;
	global $ttstoreextratable;
	global $ttstorecattable;
	global $pagetext;
	if ($searching == "1") {
		$multi=$wpdb->get_results("SELECT buynow, multisorting, multiorder, categories, multixmlfeed, multiproductpage, multiname, laywidth, multiamount, multipageamount, multilightbox FROM ".$ttstoremultitable.",".$ttstorelayouttable." where ".$ttstoremultitable.".multilayout=".$ttstorelayouttable.".id and ".$ttstoremultitable.".id=".get_option("Tradetracker_searchlayout")."");
	} else {
		$multi=$wpdb->get_results("SELECT buynow, multisorting, multiorder, categories, multixmlfeed, multiproductpage, multiname, laywidth, multiamount, multipageamount, multilightbox FROM ".$ttstoremultitable.",".$ttstorelayouttable." where ".$ttstoremultitable.".multilayout=".$ttstorelayouttable.".id and ".$ttstoremultitable.".id=".$winkelvol."");
	}
	foreach ($multi as $multi_val){	
		$Tradetracker_amount = $multi_val->multiamount;
		$nonexisting = $wpdb->get_results("SELECT productID from ".$ttstoreitemtable." where storeID = ".$winkelvol."");
		if(count($nonexisting)>0){
			$Tradetracker_productid = array();
			foreach($nonexisting as $term){
				$Tradetracker_productid[] = $term->productID;
			}
			$Tradetracker_productid = implode(",", $Tradetracker_productid);
		}
		if($multi_val->multixmlfeed == "*" ){
			$multixmlfeed = "";
		} else {
			$multixmlfeed = "and xmlfeed = ".$multi_val->multixmlfeed." ";
		}
		if($multi_val->multiproductpage == "1" ){
			$multiproductpage = "1";
		} else {
			$multiproductpage = "0";
		}
		$i="1";
		$categories = unserialize($multi_val->categories);
		if(!empty($categories)){
			foreach ($categories as $categories){
				if($i == "1" ) {
					if($multixmlfeed == ""){
						$categorieselect = " and (categorieid = \"".$categories."\"";
					}else {
						$categorieselect = " and (categorieid = \"".$categories."\"";
					}
				$i = "2";
				} else {
						$categorieselect .= " or categorieid = \"".$categories."\"";
				}
			}
			$categorieselect .= ") ";
		} else {
			$categorieselect = "";
		}
		if(isset($_GET['pmin']) && isset($_GET['pmax'])){
			if($multixmlfeed == ""){
				if($categorieselect == ""){
					$priceselect = " and price > ".mysql_real_escape_string($_GET['pmin'])." and price < ".mysql_real_escape_string($_GET['pmax'])."";
				} else {
					$priceselect = " and price > ".mysql_real_escape_string($_GET['pmin'])." and price < ".mysql_real_escape_string($_GET['pmax'])."";
				}
			} else {
				$priceselect = " and price > ".mysql_real_escape_string($_GET['pmin'])." and price < ".mysql_real_escape_string($_GET['pmax'])."";
			}
			$priceselectcur = " and price > ".mysql_real_escape_string($_GET['pmin'])." and price < ".mysql_real_escape_string($_GET['pmax'])."";
		} else {
			$priceselect = "";
			$priceselectcur = "";
		}
		if( $multi_val->buynow == "" ){
			$buynow= "Buy Item";
		} else {
			$buynow= $multi_val->buynow;
		}
		if($multi_val->multipageamount > "0" || isset($_GET['ipp'])){
			if (!isset($Tradetracker_productid) || $Tradetracker_productid == null) 
			{
				if ($multi_val->multiamount == "") {
					$Tradetracker_amount_i = "LIMIT 12"; 
				} else if ($multi_val->multiamount == "0") {
					$Tradetracker_amount_i = "";
				} else {
					$Tradetracker_amount_i = "LIMIT ".$multi_val->multiamount.""; 
				}
				$totalitems=count($wpdb->get_results("SELECT id FROM ".$ttstoretable.", ".$ttstorecattable." where ".$ttstorecattable.".productID = ".$ttstoretable.".productID ".$multixmlfeed." ".$categorieselect." ".$priceselect." ".$Tradetracker_amount_i.""));
			} else {
				if ($multi_val->multiamount == "") {
					$Tradetracker_amount_i = "LIMIT 12";
				} else if ($multi_val->multiamount == "0") {
					$Tradetracker_amount_i = "";
				} else {
					$Tradetracker_amount_i = "LIMIT ".$multi_val->multiamount.""; 
				}
				$productID = $Tradetracker_productid;
				$productID = str_replace(",", "' or productID='", $productID);
				$totalitems=count($wpdb->get_results("SELECT id FROM ".$ttstoretable.", ".$ttstorecattable." where ".$ttstorecattable.".productID = ".$ttstoretable.".productID and productID='".$productID."' ".$priceselectcur." ".$Tradetracker_amount_i.""));
			}
			if(isset($_GET['ipp']) && $_GET['ipp']>"0"){
				$itemsperpage = mysql_real_escape_string($_GET['ipp']);
			} else {
				$itemsperpage = $multi_val->multipageamount;
			}
			$pages = round($totalitems / $itemsperpage)-1;
			if(isset($_GET['tsp'])){
				$currentpage = mysql_real_escape_string($_GET['tsp']);
				$nextpage = $currentpage * $multi_val->multipageamount;
				if($totalitems <= $nextpage ){
					$Tradetracker_amount_i = "LIMIT ".$nextpage.", ".$totalitems.""; 
				} else {
					$Tradetracker_amount_i = "LIMIT ".$nextpage.", ".$itemsperpage.""; 
				}
			} else {
				$currentpage = "0";
				$nextpage = $currentpage + $multi_val->multipageamount;
				if($totalitems <= $nextpage ){
					$Tradetracker_amount_i = "LIMIT ".$currentpage.", ".$totalitems.""; 
				} else {
					$Tradetracker_amount_i = "LIMIT ".$currentpage.", ".$itemsperpage.""; 
				}
			}
		} else {
			if ($multi_val->multiamount == "") {
				$Tradetracker_amount_i = "LIMIT 12"; 
			} else if ($multi_val->multiamount == "0") {
				$Tradetracker_amount_i = "";
			} else {
				$Tradetracker_amount_i = "LIMIT ".$multi_val->multiamount.""; 
			}
		}
		
		if( $multi_val->laywidth == "" ){
			$width= "250";
		} else {
			$width= $multi_val->laywidth;
		}
		$multisorting = $multi_val->multisorting;
		$multiorder = $multi_val->multiorder;
		$widthtitle = $width-6;
		$widthmore = $width-10;
		$storename = create_slug($multi_val->multiname);
		if($multi_val->multilightbox==1){
			$uselightbox = "1";
		} else {
			$uselightbox = "0";
		}
	}

	if ($searching == "1") {
		$term = mysql_real_escape_string(get_search_query());
		$visits=$wpdb->get_results("SELECT * FROM ".$ttstoretable.", ".$ttstorecattable." where ".$ttstorecattable.".productID = ".$ttstoretable.".productID and `name` LIKE '%$term%' or `description` LIKE '%$term%' ORDER BY ".$multisorting." ".$multiorder." ".$Tradetracker_amount_i."");
		echo "SELECT * FROM ".$ttstoretable." WHERE `name` LIKE '%$term%' or `description` LIKE '%$term%' ORDER BY ".$multisorting." ".$multiorder." ".$Tradetracker_amount_i."";
	} else {
		if (!isset($Tradetracker_productid) || $Tradetracker_productid == null) 
		{
			$visits=$wpdb->get_results("SELECT * FROM ".$ttstoretable.", ".$ttstorecattable." where ".$ttstorecattable.".productID = ".$ttstoretable.".productID ".$multixmlfeed." ".$categorieselect." ".$priceselect." ORDER BY ".$multisorting." ".$multiorder." ".$Tradetracker_amount_i."");
		} else {
			$productID = $Tradetracker_productid;
			$productID = str_replace(",", "' or productID='", $productID);
			$visits=$wpdb->get_results("SELECT * FROM ".$ttstoretable.", ".$ttstorecattable." where ".$ttstorecattable.".productID = ".$ttstoretable.".productID and productID='".$productID."' ".$priceselectcur." ORDER BY ".$multisorting." ".$multiorder." ".$Tradetracker_amount_i."");
		}
	}
	$storeitems = "";
	$i="1";
	foreach ($visits as $product){
		$Tradetracker_extra_val = get_option("Tradetracker_extra");
		if(!empty($Tradetracker_extra_val)){
			$extraname = "";
			$extravar = "";
			$extras = $wpdb->get_results("SELECT extravalue, extrafield FROM $ttstoreextratable where productID='".$product->productID."'", ARRAY_A);
			foreach ($extras as $extra) {
				$Tradetracker_extra_val = get_option("Tradetracker_extra");
				if(!empty($Tradetracker_extra_val)){
					if(in_array($extra['extrafield'], $Tradetracker_extra_val, true)) {
						$extraname .= "<tr><td width=\"50\"><b>".$extra['extrafield']."</b></td><td>".$extra['extravalue']."</td></tr>";
					}
				}
			}
			if($extraname != ""){
				$moretext = __('More info', 'ttstore');
				$more = "<div class=\"".$storename."store-more\">
						<img src=\"/wp-content/plugins/tradetracker-store/images/more.png\" style=\"border:0;\" border=\"0\" name=\"img".$i."\" width=\"11\" height=\"13\" border=\"0\" >
						<a href=\"#first\" onClick=\"shoh('".$i."');\" >".$moretext."</a> 
						<div style=\"display: none;\" id=\"".$i."\" > 
							<table style=\"width:".$widthmore."px;\" width=\"".$widthmore."\">".$extraname."</table>
						</div>
					</div>";
			} else {
				$more = "<div class=\"".$storename."store-more\"></div>";
			}
		} else {
			$more = "<div class=\"".$storename."store-more\"></div>";
		}
		if($multiproductpage == "1" ){
			$producturl = "".get_option("Tradetracker_productpageURL")."?ttproductid=".$product->productID."";
			$urltarget ="";
			$rel = "";
		} else {
			$producturl = htmlspecialchars($product->productURL);
			$urltarget ="target=\"_blank\"";
			$rel = "rel=\"nofollow\"";
		}
		if($product->imageURL==""){
			$imageURL = plugins_url( 'images/No_image.png' , __FILE__ );
		} else {
			$imageURL = $product->imageURL;
		}
		if($uselightbox==1){
			$image = $imageURL;
			$target = "";	
			$rel = "";
			$imagerel = "rel=\"lightbox[store]\"";
		} else {
			$image = $producturl;
			$target = $urltarget;
			$imagerel = "";
		}
		$productname = str_replace("&", "&amp;", $product->name);
		$productdescription = $product->description;
		//$productdescription = mb_convert_encoding($product->description, "UTF-8");
		//$productdescription = str_replace("&", "&amp;", $productdescription);
		if(get_option("Tradetracker_currency")=="1") {
			$curarray = get_option("Tradetracker_newcur");
			$key = $product->currency;
			if(isset($curarray) && !empty($curarray)){
				if(isset($key)){
					$currency = $curarray[$key];
				} else {
					$currency = $product->currency;
				}
			} else {
				$currency = $product->currency;
			}
		} else {
			$currency = $product->currency;
		}
		if(get_option("Tradetracker_currencyloc")=="1") {
			$price = $product->price." ".$currency;
		}else {
			$price = $currency." ".$product->price;
		}
		$storeitems .= "<div class=\"".$storename."store-outerbox store-outerbox\">
				<div class=\"".$storename."store-titel store-titel\">
					".$productname."
				</div>			
				<div class=\"".$storename."store-image store-image\">
					<a href=\"".$image."\" ".$rel." ".$imagerel." ".$target.">
						<img src=\"".$imageURL."\" alt=\"".$productname."\" title=\"".$productname."\" style=\"max-width:".$width."px;max-height:180px;width:auto;height:auto;\"/>
					</a>
				</div>
				<div class=\"".$storename."store-footer store-footer\">
					<div class=\"".$storename."store-description store-description\">
						".$productdescription."
					</div>
					".$more."
					<div class=\"".$storename."buttons buttons\">
						<a href=\"".$producturl."\" ".$rel." class=\"regular\" ".$urltarget." title=\"".$productname."\">
							".$buynow."
						</a>
					</div>
					<div class=\"".$storename."store-price store-price\">
						<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
							<tr>
								<td style=\"border: 1px none;\" class=\"euros\">
									".$price."
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>";
	$i++;
	}
	$storeitems .= "<div class=\"cleared\"></div>";
	if(get_option("Tradetracker_showurl")=="1"){
		$storeitems .= "<div class=\"ttstorelink\"><a target=\"_blank\" href=\"http://wpaffiliatefeed.com\">TradeTracker wordpress plugin</a></div>";
	}
	if ($usedhow == 1){
		return $storeitems;
	}
	if ($usedhow == 2){
		echo $storeitems; 
	}
	
}
add_shortcode('user_pages', 'display_store_userpages_short');
function display_store_userpages_short($store)
{
	extract(shortcode_atts(array("store" => '0'), $store));
	return show_ttuserpages($store);
}
add_shortcode('display_pages', 'display_store_pages_short');
function display_store_pages_short($store)
{
	extract(shortcode_atts(array("store" => '0'), $store));
	return show_ttpages($store);
}

add_shortcode('display_filter', 'display_store_filter_short');
function display_store_filter_short($store)
{
	extract(shortcode_atts(array("store" => '0'), $store));
	return show_ttfilter($store);
}

add_shortcode('display_store', 'display_store_items_short');
function display_store_items_short()
{
	return store_items(1, 0, 0);
}
	
function display_store_items()
{
	return store_items(2, 0, 0);
}
add_shortcode('display_multi', 'display_multi_items_short');
function display_multi_items_short($store)
{
	extract(shortcode_atts(array("store" => '0'), $store));
	return store_items(1, $store, 0);
}
function display_multi_items($store)
{
	return store_items(2, $store, 0);
}
add_shortcode('display_search', 'display_search_items_short');
function display_search_items_short()
{
	return store_items(1, 1, 1);
}
function display_search_items()
{
	return store_items(2, 1, 1);
} 
?>