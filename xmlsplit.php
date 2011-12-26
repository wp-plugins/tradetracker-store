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
		$dir = WP_PLUGIN_DIR . "/tradetracker-store";
		$chunksize=2000;
		$recordnum = 1; 
		GLOBAL $processed;
		GLOBAL $errorfile;
		GLOBAL $oserrorfile;
		$error = "";
		$xmlstring =''."\n";
		$xmlstring.="<$xmldatadelimiter>\n";
		$delivered = array("’", "‘");
		$needed   = array("", "");
		$newfile = "splits/".$basefilename."-".$filenum.".xml";
		$exportfile = fopen($dir."/$newfile","w");
		if (function_exists('curl_init')) {
			$ch = curl_init($xmlfile);
			$fp = fopen($dir."/cache/cache.xml", "w");
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($ch, CURLOPT_FILE, $fp);
			$data = curl_exec($ch);
			curl_close($ch);
			fwrite($fp, $data); 
			fclose($fp);
			$handle = fopen($dir."/cache/cache.xml","r");
		} else if (ini_get('allow_url_fopen') == true) {
			$handle = fopen($xmlfile,"r");
		}
		$content = file($dir."/cache/cache.xml");
		if($content[0]=="<html><head><title>pf.tradetracker.net</title></head><body><h1>An error has occured.</h1><p>The requested product feed could not be generated:<br/><br/><code>No suitable product feeds found</code></p></body></html>"){
			$errorfile .= ""."\r\n"."".$xmlfile;
			$oserrorfile .= "<br>".$xmlfile;
			$error = "1";
		}
		if (empty($error)){
		if ($handle) {
			while (!feof($handle)) {
			$buffer = stream_get_line($handle, 81920, "</product>"); 
			$buffer = str_replace($delivered, $needed, $buffer);
				$recordnum++;
				$processed++;
				fwrite($exportfile, $buffer);
				if(!preg_match('</'.$xmldatadelimiter.'>', $buffer)){
					fwrite($exportfile, "<xmlfile>$xmlfeedID</xmlfile>");		
					fwrite($exportfile, "</product>");
				}
				if ($recordnum>$chunksize) {
					fwrite($exportfile, "</$xmldatadelimiter>");
					$recordnum=0;
					$filenum++;
					fclose($exportfile);
					$newfile = "splits/".$basefilename."-".$filenum.".xml";
					$exportfile = fopen($dir."/$newfile","w");
					fwrite($exportfile, $xmlstring);
				}
				if ($filenum>5000) {
					die();
				}
			}
			fclose($exportfile);
			fclose($handle);
			unset($buffer);
		}
		}
	}
	function tradetrackerdaily($xmlfeedID, $basefilename, $xmlfile, $filenum, $recordnum, $processed, $xmldatadelimiter, $xmlitemdelimiter){
		GLOBAL $filenum;
		$dir = WP_PLUGIN_DIR . "/tradetracker-store";
		$chunksize=2000;
		$recordnum = 1; 
		GLOBAL $processed;
		$newfile = "splits/".$basefilename."-".$filenum.".xml";
		$exportfile = fopen($dir."/$newfile","w");
		$xmldatadelimiter = "productFeed";
		$xmlstring =''."\n";
		$xmlstring.="<$xmldatadelimiter>\n";
		$delivered = array("product ", "id=\"", "\" delete=\"false\">");
		$needed   = array("product>", "<productID>", "</productID>");
		if (function_exists('curl_init')) {
			$ch = curl_init($xmlfile);
			$fp = fopen($dir."/cache/cache.xml", "w");
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($ch, CURLOPT_FILE, $fp);
			$data = curl_exec($ch);
			curl_close($ch);
			fwrite($fp, $data); 
			fclose($fp);
			$handle = fopen($dir."/cache/cache.xml","r");
		} else if (ini_get('allow_url_fopen') == true) {
			$handle = fopen($xmlfile,"r");
		}
		if ($handle) {
			while (!feof($handle)) {
			$buffer = stream_get_line($handle, 8192, "</product>"); 
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
				if(!preg_match('</'.$xmldatadelimiter.'>', $buffer)){
					fwrite($exportfile, "<xmlfile>$xmlfeedID</xmlfile>");		
					fwrite($exportfile, "</product>");
				}

				if ($recordnum>$chunksize) {
					fwrite($exportfile, "</$xmldatadelimiter>");
					$recordnum=0;
					$filenum++;
					fclose($exportfile);
					$newfile = "splits/".$basefilename."-".$filenum.".xml";
					$exportfile = fopen($dir."/$newfile","w");
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