<?php
/**
 * @author	Semicolon;
 * @github	https://github.com/semicolonsmith/Safelink-Shortlink-Decoder
 * @date	7 Juli 2017
 * @version	1.0
 **/
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Safelink URL Decoder!</title>
		<meta name="description" content="Free Safelink URL Dekoder"/>
	</head>
	<body>
        <?php
        require_once("class.SafelinkDecoder.php");
        if(isset($_GET["output"])){
        	if(isset($_POST["input"]) && $_POST["input"] !== ""){
        		$input = $_POST["input"];
        		$split = explode("\r\n", $input);
        		foreach($split as $data){
        			$safelink = new SafelinkDecoder;
        			foreach($safelink::$shortlink as $match){
        				if(strpos($data, $match) == true){
        					echo $safelink::check("site_url_data", $data), "<br/>";
        				}
        			}
        			if($safelink::check("raw_url_data", $data)["condition"] == true){
        				echo base64_decode($safelink::check("raw_url_data", $data)["value"]), "<br/>";
        			}
        		}
        		echo "<br/><a href=\"/\" title=\"Kembali\">Kembali</a>";
        	}else{
        		echo "Mohon masukan url safelink agar bisa di proses!";
        	}
        }
        ?>
	</body>
</html>