<?php
class tradetracker
{
	/*$basefilename // the base file name for the chunks
	$xmlfile // the xml file name to be processed
	$xmldatadelimiter // core data delimiter
	$xmlitemdelimiter // record delimiter
	$chunksize = 2000; // number of records in each chunk file
	$dir // path to where splits will be stored
	*/
		function doChunk( $xmlfeedID, $basefilename, $xmlfile, $filenum, $recordnum, $processed, $xmldatadelimiter, $xmlitemdelimiter, $chunksize=2000, $dir= "/var/www/public_html"){
			GLOBAL $filenum;
			//initialize vars
			$begin=time(); // script start time
			$start = time(); // last gate time
			$interval=time(); // current gate time
			$minutes=1; // intervals for gates
			$recordnum = 1; // start at record 1
			GLOBAL $processed;

			$xmlstring =''."\n";
			$xmlstring.="<$xmldatadelimiter>\n";
			// xmlchunk file header
			//dirs and files

			// $exportfile = "$dir"."/splits/$basefilename-$filenum.xml";
			$newfile = "splits/".$basefilename."-".$filenum.".xml";
			// echo "Creating ".$dir."/".$newfile."<br>";
			// echo "Reading ".$xmlfile."<br>";
			$exportfile = fopen($dir."/$newfile","w") or die ("Can not create $newfile.");
			// start processing
			// echo "Processing ($dir."/$xmlfile")\n<br>";
			$site_file = 'http://wpaffiliatefeed.com/tradetracker-store/sites.xml';
			if (function_exists('curl_init')) {
				$ch = curl_init($site_file);
				$fp = fopen($dir."/cache/sites.xml", "w");
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);
			}
			$news_file = 'http://wpaffiliatefeed.com/category/news/feed/';
			if (function_exists('curl_init')) {
				$ch = curl_init($news_file);
				$fp = fopen($dir."/cache/news.xml", "w");
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);
			}
			if (function_exists('curl_init')) {
				$ch = curl_init($xmlfile);
				$fp = fopen($dir."/cache/cache.xml", "w");
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);
				$handle = fopen($dir."/cache/cache.xml","r");
			} else if (ini_get('allow_url_fopen') == true) {
				$handle = fopen($xmlfile,"r");
			}


			$handle = fopen($xmlfile,"r");
			if ($handle) {
				while (!feof($handle)) {

				$buffer = fgets($handle, 4096);
				// if item delimiter reached
				// increment record number iterator
					if (ereg("</product>",$buffer)==true) {
						fwrite($exportfile, "<xmlfile>$xmlfeedID</xmlfile>");
						$recordnum++;
						$processed++;
					}
					//write line to chunk file
					//error_log("$buffer",3,$exportfile);
					fwrite($exportfile, $buffer);
					// if chunk limit reached then start to
					// close the file with well formed xml
					if ($recordnum>$chunksize) {
						// post feed end tag
						//error_log("",3,$exportfile);
						fwrite($exportfile, "</$xmldatadelimiter>");

						// and increment file number to start new log file chunk
						//reset record counter number for new chunk file
						$recordnum=0;
						$filenum++;
						fclose($exportfile);
						//update export file name
						//$exportfile = "$dir"."/splits/$basefilename-$filenum.xml";
						$newfile = "splits/".$basefilename."-".$filenum.".xml";
						$exportfile = fopen($dir."/$newfile","w") or die ("could not create file.");
						//echo status report to STDOUT
						//echo"Segment $filenum. Record ".($chunksize*$filenum).".\n";

						// write new chunk xml file header
						//error_log($xmlstring,3,$exportfile);
						fwrite($exportfile, $xmlstring);
					}
					//put in a catch so that script doesn't run riot and
					//will die after X number of cycles
					if ($filenum>5000) {
						die();
					}

					if (($interval-$start)>60) {
						$minutes++;
						// echo $minutes." Minutes so far.\n";
						$start=time();
					} else {
						$interval = time();
					}
				}
				fclose($handle);
			} else {
				// echo"Unable to open file! (".$dir."$xmlfile\")\n";
			}
			$procend = time();

			// echo "\n####\n";
			// echo "Split Complete (".floor((($procend-$begin)/60))." Minutes, ";
		}

	}

?>