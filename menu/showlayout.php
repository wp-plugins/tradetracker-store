<?php
require( '../../../../wp-load.php');
$name = $_POST['layoutname'];
$width = $_POST['layoutwidth'];
if($width == ""){
	$width="200";
}
$font = $_POST['layoutfont'];
$fontsize = $_POST['layoutfontsize'];
$colortitle = $_POST['layoutcolortitle'];
$colorimagebg = $_POST['layoutcolorimagebg'];
$colorfooter = $_POST['layoutcolorfooter'];
$colorborder = $_POST['layoutcolorborder'];
$colorbutton = $_POST['layoutcolorbutton'];
$colorbuttonfont = $_POST['layoutcolorbuttonfont'];
$colorfont = $_POST['layoutcolorfont'];


$widthtitle = $width-6;
echo "<style type=\"text/css\" media=\"screen\">";
echo ".store-outerbox{width:".$width."px;color:".$colorfont.";font-family:".$font.";float:left;min-height:353px;border:solid 1px ".$colorborder.";position:relative;}";
echo ".store-titel{width:".$widthtitle."px;background-color:".$colortitle.";color:".$colorfont.";float:left;position:relative;height:30px;line-height:15px;font-size:".$fontsize."px;padding:3px;font-weight:bold;text-align:center;}";
echo ".store-image{width:".$width."px;height:180px;padding:0px;overflow:hidden;margin: auto;background-color:".$colorimagebg.";}";
echo ".store-image img{display: block;border:0px;margin: auto;}";
echo ".store-footer{width:".$width."px;background-color:".$colorfooter.";float:left;position:relative;min-height:137px;}";
echo ".store-description{width:".$widthtitle."px;color:".$colorfont.";position:relative;top:5px;left:5px;height:90px;line-height:14px;font-size:".$fontsize."px;overflow:auto;}";
echo ".store-more{min-height:20px; width:".$widthtitle."px;position: relative;float: left;margin-top:10px;margin-left:5px;margin-bottom: 5px;}";
echo ".store-more img{margin:0px !important;}";
echo ".store-price {border: 0 solid #65B9C1;color: #4E4E4E !important;float: right;font-size: ".$fontsize."px !important;font-weight: bold !important;height: 30px !important;position: relative;text-align: center !important;width: 80px !important;}";
echo ".store-price table {background-color: ".$colorfooter." !important;border: 1px none !important;border-collapse: inherit !important;float: right;margin-left: 1px;margin-top: 1px;text-align: center !important;}";
echo ".store-price table tr {padding: 1px !important;}";
echo ".store-price table tr td {padding: 1px !important;}";
echo ".store-price table td, table th, table tr {border: 1px solid #CCCCCC;padding: 0 !important;}";
echo ".store-price table td.euros {font-size: ".$fontsize."px !important;letter-spacing: -1px !important; }";
echo ".store-price {background-color: ".$colorborder." !important;}";
echo ".buttons a, .buttons button {height:18px;background-color: ".$colorbutton.";border: 1px solid ".$colorbutton.";bottom: 0;color: ".$colorbuttonfont.";cursor: pointer;display: block;float: left;font-size: ".$fontsize."px;font-weight: bold;margin-top: 0;padding: 5px 10px 5px 7px;position: relative;text-decoration: none;width: 100px;}";
echo ".buttons button {overflow: visible;padding: 4px 10px 3px 7px;width: auto;}";
echo ".buttons button[type] {line-height: 17px;padding: 5px 10px 5px 7px;}";
echo ":first-child + html button[type] {padding: 4px 10px 3px 7px;}";
echo ".buttons button img, .buttons a img {border: medium none;margin: 0 3px -3px 0 !important;padding: 0;}";
echo ".button.regular, .buttons a.regular {color: ".$colorbuttonfont.";}";
echo ".buttons a.regular:hover, button.regular:hover {background-color: #4E4E4E;border: 1px solid #4E4E4E;color: ".$colorbuttonfont.";}";
echo ".buttons a.regular:active {background-color: #FFFFFF;border: 1px solid ".$colorbutton.";color: ".$colorbuttonfont.";}";
echo "</style>";

?>
		<div class="store-outerbox">
			<div class="store-titel">
				<?php echo $name; ?>
			</div>			
			<div class="store-image">
				<img src="<?php echo plugins_url( 'images/screenshot-1.png' , __FILE__ ); ?>" style="max-width:<?php echo $width; ?>px;max-height:180px;">
			</div>
			<div class="store-footer">
				<div class="store-description">
					The description for the item you can buy using the <?php echo $font; ?> font using font-size <?php echo $fontsize; ?>
				</div>
				<div class="store-more"></div>
				<div class="buttons">
					<a href="#" class="regular">
						Buy Item
					</a>
				</div>
				<div class="store-price">
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td style="border: 1px none; width: 100px; margin: 1px; height: 29px;" class="euros">
								0,00 EUR
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>