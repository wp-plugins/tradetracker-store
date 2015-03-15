<?php
if(get_option('tt_premium_provider')=="") {
	update_option('tt_premium_provider', array('tradetracker', 'tradetrackerdaily') );
} else if (!in_array('tradetrackerdaily', get_option('tt_premium_provider'))){
	$providers = get_option('tt_premium_provider');
	array_push($providers, "tradetrackerdaily");
	update_option('tt_premium_provider', $providers );
}
function tradetracker( $xmlfeedID, $basefilename, $xmlfile, $filenum, $recordnum, $processed, $xmldatadelimiter, $xmlitemdelimiter){
	GLOBAL $filenum;
	global $folderhome;
	$chunksize=100;
	$recordnum = 1; 
	GLOBAL $processed;
	GLOBAL $errorfile;
	GLOBAL $oserrorfile;
	$error = "";
	$xmlstring = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
	$xmlstring.=''."\n";
	$xmlstring.="<$xmldatadelimiter>\n";
	$newfile = "splits/".$basefilename."-".$filenum.".xml";
	$exportfile = fopen($folderhome."/$newfile","w");
	if (get_option('Tradetracker_importtool')=="1"){
		$handle = fopen($xmlfile,"r");
	} else if (get_option('Tradetracker_importtool')=="2") {
		$ch = curl_init($xmlfile);
		$fp = fopen($folderhome."/cache/cache.xml", "w");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		fwrite($fp, $data); 
		fclose($fp);
		$handle = fopen($folderhome."/cache/cache.xml","r");
	} else if (get_option('Tradetracker_importtool')=="3") {
		$ch = curl_init($xmlfile);
		$fp = fopen($folderhome."/cache/cache.xml", "w");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		$handle = fopen($folderhome."/cache/cache.xml","r");
	}
	if ($handle) {
		while (!feof($handle)) {
    			$buffer = stream_get_line($handle, 100000, "</product>"); 
			$recordnum++;
			$processed++;
			$buffer = str_replace("<productFeed", "<products", $buffer);
			$buffer = str_replace("</productFeed", "</products", $buffer);

			if(preg_match('/<images>(.+?)\<\/images>/is', $buffer, $matches)){
				$buffer = str_replace($matches[0], $matches[1], $buffer);
			}
			if(preg_match('/<price>(.+?)urrency>(.+?)\<\/price>/is', $buffer, $matches)){
				$buffer = str_replace($matches[0], "<price currency=\"".$matches[2]."</price>", $buffer);
				if(preg_match('/<\/currency>(.+?)amount>(.+?)\<\/price>/is', $buffer, $matches)){
					$buffer = str_replace($matches[0], "\">".$matches[2]."</price>", $buffer);
				}
				if(preg_match('/<\/amount>(.+?)\/price>/is', $buffer, $matches)){
					$buffer = str_replace($matches[0], "</price>", $buffer);
				}
			}
			if(preg_match('/<image>(.+?)\<\/image>/is', $buffer, $matches)){
				$buffer = str_replace($matches[0], "<imageURL>".$matches[1]."</imageURL>", $buffer);
			}
			if(preg_match('/<URL>(.+?)\<\/URL>/is', $buffer, $matches)){
				$buffer = str_replace($matches[0], "<productURL>".$matches[1]."</productURL>", $buffer);
			}
			if(preg_match('/<categories>(.+?)\<\/categories>/is', $buffer, $matches)){
				if(preg_match('/<category>(.+?)\<\/category>/is', $matches[0], $matchess)){
					$buffer = str_replace($matches[0], "<categories><category path=\"".$matchess[1]."\">".$matchess[1]."</category></categories>", $buffer);
				}
			}
			if(preg_match('/<categoryPath>(.+?)\<\/categoryPath>/is', $buffer, $matches)){
				if(preg_match('/<value>(.+?)\<\/value>/is', $matches[0], $matchess)){
					$buffer = str_replace($matches[0], "<categories><category path=\"".$matchess[1]."\">".$matchess[1]."</category></categories>", $buffer);
				}
			}
			fwrite($exportfile, $buffer);
			if(preg_match('/<product>/i', $buffer)){
				fwrite($exportfile,"<xmlfile>$xmlfeedID</xmlfile></product>");
			}elseif(preg_match('/<product ID/i', $buffer)){
				fwrite($exportfile,"<xmlfile>$xmlfeedID</xmlfile></product>");
			}
			if ($recordnum>$chunksize) {
				fwrite($exportfile, "</$xmldatadelimiter>");
				$recordnum=0;
				$filenum++;
				fclose($exportfile);
				$newfile = "splits/".$basefilename."-".sprintf("%09s", $filenum).".xml";
				$exportfile = fopen($folderhome."/$newfile","w");
				fwrite($exportfile, $xmlstring);
			}
			if ($filenum>5000) {
				die();
			}
		}
		fclose($exportfile);
		fclose($handle);
	}
}
function tradetrackerdaily($xmlfeedID, $basefilename, $xmlfile, $filenum, $recordnum, $processed, $xmldatadelimiter, $xmlitemdelimiter){
	GLOBAL $filenum;
	$chunksize=100;
	$recordnum = 1; 
	GLOBAL $processed;
	global $folderhome;
	$newfile = "splits/".$basefilename."-".$filenum.".xml";
	$exportfile = fopen($folderhome."/$newfile","w");
	$xmldatadelimiter = "productFeed";
	$xmlstring =''."\n";
	$xmlstring.="<$xmldatadelimiter>\n";
	//$delivered = array("product ", "id=\"", "\" delete=\"false\">");
	//$needed   = array("product>", "<productID>", "</productID>");
	if (function_exists('curl_init')) {
		$ch = curl_init($xmlfile);
		$fp = fopen($folderhome."/cache/cache.xml", "w");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_exec($ch);
		curl_close($ch);
		//fwrite($fp, $data); 
		fclose($fp);
		$handle = fopen($folderhome."/cache/cache.xml","r");
	} else if (ini_get('allow_url_fopen') == true) {
		$handle = fopen($xmlfile,"r");
	}
	if ($handle) {
		while (!feof($handle)) {
    			$buffer = stream_get_line($handle, 1000000, "</product>"); 
			$buffer = str_replace($delivered, $needed, $buffer);
			$recordnum++;
			$processed++;
				if(preg_match_all('/<field name=\"(.+?)\" value=\"(.+?)\"( \/)?>/i', $buffer, $matches, PREG_PATTERN_ORDER)){
				$categoriename = $matches[2][0];
				$i=0;
				while($i < count($matches[1])){
					$buffer = str_replace("<field name=\"".$matches[1][$i]."\" value=\"".$matches[2][$i]."\" />", "<field name=\"".$matches[1][$i]."\">".$matches[2][$i]."</field>", $buffer);
					$i++;
				}
			}
			if(preg_match('/<product (.+?)\">/is', $buffer, $matches)){
					$buffer = str_replace($matches[0], "<product>", $buffer);
			}
			if(preg_match('/\<categories\>/i', $buffer)){
				$buffer = str_replace("<categories>", "<categories><category path=\"" . $categoriename . "\">".$categoriename."</category>", $buffer);
			}
			if(preg_match('/<category name(.+?)\/\>/i', $buffer, $matches)){
				$buffer = str_replace($matches[0], "", $buffer);
			}
			fwrite($exportfile, $buffer);
			if(!preg_match('/<\/'.$xmldatadelimiter.'>/i', $buffer)){
				fwrite($exportfile, "<xmlfile>$xmlfeedID</xmlfile>");
				fwrite($exportfile, "</product>");
			}
				if ($recordnum>$chunksize) {
				fwrite($exportfile, "</$xmldatadelimiter>");
				$recordnum=0;
				$filenum++;
				fclose($exportfile);
				$newfile = "splits/".$basefilename."-".sprintf("%09s", $filenum).".xml";
				$exportfile = fopen($folderhome."/$newfile","w");
				fwrite($exportfile, $xmlstring);
			}
			if ($filenum>5000) {
				die();
			}
		}
		fclose($exportfile);
		fclose($handle);
	}
}
?>