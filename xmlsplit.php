<?php
 function tradetracker( $xmlfeedID, $basefilename, $xmlfile, $filenum, $recordnum, $processed, $xmldatadelimiter, $xmlitemdelimiter){
		GLOBAL $filenum;
		$dir = WP_PLUGIN_DIR . "/tradetracker-store";
		$chunksize=2000;
		$recordnum = 1; 
		GLOBAL $processed;
		$xmlstring =''."\n";
		$xmlstring.="<$xmldatadelimiter>\n";
		$newfile = "splits/".$basefilename."-".$filenum.".xml";
		$exportfile = fopen($dir."/$newfile","w") or die ("Can not create $newfile.");
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
					$exportfile = fopen($dir."/$newfile","w") or die ("could not create file.");
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