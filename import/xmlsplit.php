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
	$chunksize=500;
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
    			$buffer = stream_get_line($handle, 10000000, "</product>"); 
			$recordnum++;
			$processed++;
			fwrite($exportfile, $buffer);
			if(!preg_match('/<\/'.$xmldatadelimiter.'>/i', $buffer)){
				fwrite($exportfile,"<xmlfile>$xmlfeedID</xmlfile></product>");
			}
			if ($recordnum>$chunksize) {
				fwrite($exportfile, "</$xmldatadelimiter>");
				$recordnum=0;
				$filenum++;
				fclose($exportfile);
				$newfile = "splits/".$basefilename."-".$filenum.".xml";
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
	$chunksize=500;
	$recordnum = 1; 
	GLOBAL $processed;
	$newfile = "splits/".$basefilename."-".$filenum.".xml";
	$exportfile = fopen($folderhome."/$newfile","w");
	$xmldatadelimiter = "productFeed";
	$xmlstring =''."\n";
	$xmlstring.="<$xmldatadelimiter>\n";
	$delivered = array("product ", "id=\"", "\" delete=\"false\">");
	$needed   = array("product>", "<productID>", "</productID>");
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
			if(preg_match('/\<categories\>/i', $buffer)){
				$buffer = str_replace("<categories>", "<categories><category path=\"" . $categoriename . "\">".$categoriename."</category>", $buffer);
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
				$newfile = "splits/".$basefilename."-".$filenum.".xml";
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