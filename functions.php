<?php
function arrayDiffEmulation($arrayFrom, $arrayAgainst)
{
	$arrayAgainst = array_flip($arrayAgainst);
	foreach ($arrayFrom as $key => $value) {
		if(isset($arrayAgainst[$value])) {
			unset($arrayFrom[$key]);
		}
	}
	return $arrayFrom;
}
function create_slug($string){
   $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
   return $slug;
}

function XmlIsWellFormed($xmlString, &$message) {
	libxml_use_internal_errors(true);
	$doc = new DOMDocument('1.0', 'utf-8');
	$doc->loadXML($xmlString);
	$errors = libxml_get_errors();
	if (empty($errors)) {
		return true;
	}
	$error = $errors[ 0 ];
	if ($error->level < 3){
		return true;
	}
	$lines = explode("r", $xmlString);
	$line = $lines[($error->line)-1];
	$message = $error->message . ' at line ' . $error->line . ': ' . htmlentities($line);
	return false;
}


function news_updater(){
	$foldercache = plugin_dir_path( __FILE__ )."cache/";
	$site_file = 'http://wpaffiliatefeed.com/tradetracker-store/sites.xml';
	if (function_exists('curl_init')) {
		$ch = curl_init($site_file);
		$fp = fopen($foldercache."sites.xml", "w");
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
	$faq_file = 'http://wpaffiliatefeed.com/tradetracker-store/faq.xml';
	if (function_exists('curl_init')) {
		$ch = curl_init($faq_file);
		$fp = fopen($foldercache."faq.xml", "w");
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
	$news_file = 'http://wpaffiliatefeed.com/category/news/feed/';
	if (function_exists('curl_init')) {
		$ch = curl_init($news_file);
		$fp = fopen($foldercache."news.xml", "w");
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
	$releaselog_file = 'http://wpaffiliatefeed.com/category/releaselog/feed/';
	if (function_exists('curl_init')) {
		$ch = curl_init($releaselog_file);
		$fp = fopen($foldercache."releaselog.xml", "w");
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
}
function isTime($time){	
	return preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $time);
}
function remove_array_empty_values($array, $remove_null_number = true)
{
	$new_array = array();

	$null_exceptions = array();

	foreach ($array as $key => $value)
	{
		$value = trim($value);

        if($remove_null_number)
		{
	        $null_exceptions[] = '0';
		}

        if(!in_array($value, $null_exceptions) && $value != "")
		{
            $new_array[] = $value;
        }
    }
    return $new_array;
}
function safeArrayCombine($keys, $values) { 
    $combinedArray = array(); 
        
    for ($i=0, $keyCount = count($keys); $i < $keyCount; $i++) { 
         $combinedArray[$keys[$i]] = $values[$i]; 
    } 
        
    return $combinedArray; 
}
function loadpremium(){
	$foldercache = plugin_dir_path( __FILE__ )."cache/";
	if(is_writable($foldercache)){
		$providers = get_option('Tradetracker_premiumapi');
		if($providers != "") {
			foreach ($providers as $key => $value){
				$update = get_option('Tradetracker_premiumaccepted');
				if($update[$key]== "1") {
					$filename = $foldercache.''.$value.'.php';
					if (file_exists($filename)) {
						include($filename);
					} else {
						premium_updater();
					}
				}
			}
		}
	}
}
function premium_updater(){
	global $wpdb;
	$foldercache = plugin_dir_path( __FILE__ )."cache/";
	$us = $_SERVER['HTTP_HOST'];
	delete_option('tt_premium_function');
	delete_option('tt_premium_provider');
	$providers = get_option('Tradetracker_premiumapi');
	if(isset($providers) && $providers!=""){
		foreach ($providers as $key => $value){
			$api = $value;
			if(!empty($api)){
				if($api=="0"){
					$search_array = get_option('Tradetracker_premiumaccepted');
					$search_array[$key] = '0'; 
					update_option('Tradetracker_premiumaccepted', $search_array );
				} else {
					$network = strtolower($key);
					$response = wp_remote_get("http://wpaffiliatefeed.com/premium/answernew.php?where=".$us."&api=".$api."&network=".$network."");
					if( is_wp_error( $response ) ) {
					//Do something. For our example, kill the script.
						//$search_array = get_option('Tradetracker_premiumaccepted');
						//$search_array[$key] = '0'; 
						//update_option('Tradetracker_premiumaccepted', $search_array );
						//$search_array = get_option('Tradetracker_premiumupdate');
						//if (array_key_exists($key, $search_array)) {
						//	unset($search_array[$key]);
						//}
						//update_option('Tradetracker_premiumupdate', $search_array );
					} else {
						if($response['body'] == "0"){
							$search_array = get_option('Tradetracker_premiumaccepted');
							$search_array[$key] = '0'; 
							update_option('Tradetracker_premiumaccepted', $search_array );
							$search_array = get_option('Tradetracker_premiumupdate');
							if (array_key_exists($key, $search_array)) {
								unset($search_array[$key]);
							}
							update_option('Tradetracker_premiumupdate', $search_array );
						} else {				
							$update = get_option('Tradetracker_premiumupdate');
							$filename = $foldercache.''.$api.'.php';
							if (file_exists($filename)) {
								if($update[$key]!=$response['body']) {
									$search_array = get_option('Tradetracker_premiumaccepted');
									$search_array[$key] = '1'; 
									update_option('Tradetracker_premiumaccepted', $search_array );
									$search_array = get_option('Tradetracker_premiumupdate');
									if (array_key_exists($key, $search_array)) {
										$search_array[$key] = $response['body']; 
									} else {
										$search_array[$key] = $response['body']; 
									}
									update_option('Tradetracker_premiumupdate', $search_array );
									$site_file = wp_remote_get('http://wpaffiliatefeed.com/premium/'.$network.'/'.$api.'.txt');
									if( is_wp_error( $site_file ) ) {
									} else {
										$fp = fopen($filename, "w");
										fwrite($fp, $site_file['body']); 
										fclose($fp);
									}
								}
							} else {
								$search_array = get_option('Tradetracker_premiumaccepted');
								$search_array[$key] = '1'; 
								update_option('Tradetracker_premiumaccepted', $search_array );
								$search_array = get_option('Tradetracker_premiumupdate');
								if (array_key_exists($key, $search_array)) {
									$search_array[$key] = $response['body']; 
								} else {
									array_push($search_array[$key], $response['body']); 
								}
								update_option('Tradetracker_premiumupdate', $search_array );
								$site_file = wp_remote_get('http://wpaffiliatefeed.com/premium/'.$network.'/'.$api.'.txt');
								if( is_wp_error( $site_file ) ) {
								} else {
									$fp = fopen($filename, "w");
									fwrite($fp, $site_file['body']); 
									fclose($fp);
								}
							}
						}
					}
				}
			} else {
				$search_array = get_option('Tradetracker_premiumaccepted');
				$search_array[$key] = '0'; 
				update_option('Tradetracker_premiumaccepted', $search_array );
			}
		}
	}
}
function normalize_special_characters( $str )
{
    # Quotes cleanup
    $str = ereg_replace( chr(ord("`")), "'", $str );       // # `
    $str = ereg_replace( chr(ord("´")), "'", $str );       // # ´
    $str = ereg_replace( chr(ord("„")), ",", $str );       // # „
    $str = ereg_replace( chr(ord("`")), "'", $str );       // # `
    $str = ereg_replace( chr(ord("´")), "'", $str );       // # ´
    $str = ereg_replace( chr(ord("“")), "\"", $str );       // # “
    $str = ereg_replace( chr(ord("”")), "\"", $str );       // # ”
    $str = ereg_replace( chr(ord("´")), "'", $str );       // # ´

$unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
$str = strtr( $str, $unwanted_array );

// # Bullets, dashes, and trademarks
$str = ereg_replace( chr(149), "&#8226;", $str );    // # bullet •
$str = ereg_replace( chr(150), "&ndash;", $str );    // # en dash
$str = ereg_replace( chr(151), "&mdash;", $str );    // # em dash
$str = ereg_replace( chr(153), "&#8482;", $str );    // # trademark
$str = ereg_replace( chr(169), "&copy;", $str );    // # copyright mark
$str = ereg_replace( chr(174), "&reg;", $str );        // # registration mark

    return $str;
}
?>