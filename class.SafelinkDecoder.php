<?php
class SafelinkDecoder{
	public static $author      = "Semicolon;";
	public static $description = "Safelink Dekoder Gratis, hanya untuk safelink berbasis Base64. Created with Love Semicolon;";
	public static $parameter   = array("site","url","r","go","id","get");
	public static $shortlink   = array("www.telondasmu.com","ani-share.com","coeg.in","short.awsubs.co","st.awsubs.co","www.shortenurl.pw","bagilagi.com","autofans.pw");
	public static function filter($url, $name, $raw = false){
		$parsing = parse_url($url);
		if(isset($parsing["query"])){
			parse_str($parsing["query"], $query);
			if(isset($query[$name]) && $raw == false){
				return $query[$name];
			}elseif($raw !== false || isset($query[$name]) && $raw !== false || $name == ""){
				return $query;
			}
		}else{
			return null;
		}
	}
	public static function check($case, $data = null){
		if(isset($case)){
			switch($case){
				case "raw_url_data":
				$raw = self::filter($data, "", true);
				if($raw !== null){
    				foreach($raw as $name => $value){
    					if(strpos(base64_decode(self::filter($data, $name)), "https://") !== false || strpos(base64_decode(self::filter($data, $name)), "http://") !== false){
    						return array(
    							"condition" => true,
    							"parameter" => $name,
    							"value"     => $value
    						);
    					}else{
    						return null;
    					}
    				}
				}
				break;
				case "site_url_data":
				foreach(self::$shortlink as $url){
					if(strpos($data, $url) == true){
						if(strpos($data, "telondasmu.com") == true || strpos($data, "coeg.in") == true){
							$source = file_get_contents($data);
							$pattern = '/(href=[\'"]+?\s*(?P<link>\S+)\s*[\'"]+?)/i';
							preg_match_all($pattern, $source, $output);
							foreach($output["link"] as $key => $value){
								if(strpos($value, "?r=") == true){
									if(self::check("raw_url_data", $value)["condition"] == true){
										return base64_decode(self::check("raw_url_data", $value)["value"]);
									}
								}
							}
						}elseif((strpos($data, "ani-share") || strpos($data, "bagilagi.com") || strpos($data, "autofans.pw")) == true){
							$source = file_get_contents($data);
							$pattern = "/(var\s*a=[\"']+?\s*(?P<link>\S+)\s*[\"']+?)/i";
							preg_match_all($pattern, $source, $output);
							if(strpos($output["link"][0], "';window.open(a,\"_blank") == true){
								return str_replace("';window.open(a,\"_blank", "", $output["link"][0]);
							}else{
								return $ouput["link"][0];
							}
						}elseif((strpos($data, "short.awsubs.co") || strpos($data, "st.awsubs.co")) == true){
						    $source = file_get_contents($data);
						    preg_match_all("'<a\s*href=[\"\"]+?\s*(?P<link>\S+)\s*[\"\"]+?\s*rel=\"nofollow\"\s*target=\"_blank\"><b>'si", $source, $match);
						    return $match["link"][0];
						}elseif(strpos($data, "shortenurl.pw") == true){
						    $source = file_get_contents($data);
						    preg_match_all("'window.location=[\"\"]+?\s*(?P<link>\S+)\s*[\"\"]+?'si", $source, $match);
						    return $match["link"][0];
						}
					}
				}
				break;
				case "url_data":
				if($data !== null){
					foreach(self::$parameter as $name){
						if(strpos(base64_decode(self::filter($data, $name)), "https://") !== false || strpos(base64_decode(self::filter($data, $name)), "http://") !== false){
							return true;
						}else{
							return false;
						}
					}
				}else{
					echo "Masukkan datanya.";
				}
				break;
			}
		}
	}
}
?>
