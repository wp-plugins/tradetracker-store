<?php

/* 
..--==[ Function to add the stylesheet for the store ]==--.. 
*/

function TTstore_scripts() {   
	wp_enqueue_script( 'ttstoreexpand-script', WP_PLUGIN_URL . '/tradetracker-store/js/expand.js');
}       
	
add_action('init', 'TTstore_scripts'); 

function store_items($used, $winkel, $searching)
{
	$Tradetracker_xml = get_option("Tradetracker_xml");
	if ($Tradetracker_xml == null)
	{
		echo "No XML filled in yet please change the settings first.";
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
	} else {
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

function show_items($usedhow, $winkelvol, $searching)
{
	global $wpdb;
	global $ttstorelayouttable;
	global $ttstoremultitable;
	global $ttstoretable;
	global $folderhome;
	if ($searching == "1") {
		$multi=$wpdb->get_results("SELECT buynow, multisorting, multiorder, categories, multixmlfeed, multiname, laywidth, multiitems, multiamount, multilightbox FROM ".$ttstoremultitable.",".$ttstorelayouttable." where ".$ttstoremultitable.".multilayout=".$ttstorelayouttable.".id and ".$ttstoremultitable.".id=".get_option("Tradetracker_searchlayout")."");
	} else {
		$multi=$wpdb->get_results("SELECT buynow, multisorting, multiorder, categories, multixmlfeed, multiproductpage, multiname, laywidth, multiitems, multiamount, multilightbox FROM ".$ttstoremultitable.",".$ttstorelayouttable." where ".$ttstoremultitable.".multilayout=".$ttstorelayouttable.".id and ".$ttstoremultitable.".id=".$winkelvol."");
	}
	foreach ($multi as $multi_val){	
		$Tradetracker_amount = $multi_val->multiamount;
		if($multi_val->multixmlfeed == "*" ){
			$multixmlfeed = "";
		} else {
			$multixmlfeed = "where xmlfeed = ".$multi_val->multixmlfeed." ";
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
						$categorieselect = " where (categorieid = \"".$categories."\"";
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
		if( $multi_val->buynow == "" ){
			$buynow= "Buy Item";
		} else {
			$buynow= $multi_val->buynow;
		}
		if ($multi_val->multiamount == "") {
			$Tradetracker_amount_i = "LIMIT 12"; 
		} else if ($multi_val->multiamount == "0") {
			$Tradetracker_amount_i = "";
		} else {
			$Tradetracker_amount_i = "LIMIT ".$multi_val->multiamount.""; 
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
		$Tradetracker_productid = $multi_val->multiitems;
		$storename = create_slug($multi_val->multiname);
		if($multi_val->multilightbox==1){
			$uselightbox = "1";
		} else {
			$uselightbox = "0";
		}
	}

	if ($searching == "1") {
		$term = mysql_real_escape_string(get_search_query());
		$visits=$wpdb->get_results("SELECT * FROM ".$ttstoretable." WHERE `name` LIKE '%$term%' or `description` LIKE '%$term%' ORDER BY ".$multisorting." ".$multiorder." ".$Tradetracker_amount_i."");
	} else {
		if ($Tradetracker_productid == null) 
		{
			$visits=$wpdb->get_results("SELECT * FROM ".$ttstoretable." ".$multixmlfeed." ".$categorieselect." ORDER BY ".$multisorting." ".$multiorder." ".$Tradetracker_amount_i."");
		} else {
			$productID = $Tradetracker_productid;
			$productID = str_replace(",", "' or productID='", $productID);
			$visits=$wpdb->get_results("SELECT * FROM ".$ttstoretable." where productID='".$productID."' ORDER BY ".$multisorting." ".$multiorder." ".$Tradetracker_amount_i."");
		}
	}
	$storeitems = "";
	$i="1";
	foreach ($visits as $product){
		$Tradetracker_extra_val = get_option("Tradetracker_extra");
		if(!empty($Tradetracker_extra_val)){
			$extrafield = explode(",",$product->extrafield);
			$extravalue = explode(",",$product->extravalue);
			$extras = array_combine($extrafield, $extravalue);
			$extraname = "";
			$extravar = "";
			foreach ($extras as $key => $value) {
				$Tradetracker_extra_val = get_option("Tradetracker_extra");
				if(!empty($Tradetracker_extra_val)){
					if(in_array($key, $Tradetracker_extra_val, true)) {
						$extraname .= "<tr><td width=\"50\"><b>".$key."</b></td><td>".$value."</td></tr>";
					}
				}
			}
			if($extraname != ""){
				$more = "<div class=\"".$storename."store-more\">
						<img src=\"/wp-content/plugins/tradetracker-store/images/more.png\" style=\"border:0;\" border=\"0\" name=\"img".$i."\" width=\"11\" height=\"13\" border=\"0\" >
						<a href=\"#first\" onClick=\"shoh('".$i."');\" >More info</a> 
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
			$producturl = $product->productURL;
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
			$rel = "rel=\"lightbox[store]\"";
		} else {
			$image = $producturl;
			$target = $urltarget;
		}
		$productname = str_replace("&", "&amp;", $product->name);
		$productdescription = $product->description;
		//$productdescription = mb_convert_encoding($product->description, "UTF-8");
		//$productdescription = str_replace("&", "&amp;", $productdescription);
		if(get_option("Tradetracker_currency")=="1") {
			$array = get_option("Tradetracker_newcur");
			$key = $product->currency; 
			$currency = $array[$key]; 
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
					<a href=\"".$image."\" ".$rel." ".$target.">
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