<?php
	require_once("sharer.php");
	$sharer = new Sharer();
	$files = $_FILES["upload"];
	$ip = $_SERVER["REMOTE_ADDR"];
	$time = time();
	$password = NULL;
	if(isset($_POST["password"]) && !empty($_POST["password"])) {
		$password =  hash("sha256", $_POST["password"].$sharer->getSalt());
	}
	$array = Array();
	if(isset($files)) {
		$amount = count($files["name"]);
		for($i = 0; $i < $amount; $i++) {
			$name = $files["name"][$i];
			$type = $files["type"][$i];
			$tmp_name = $files["tmp_name"][$i];
			$size = $files["size"][$i];
			$id = $sharer->registerFile($name, $type, $size, $ip, $time, $password);
			move_uploaded_file($tmp_name, "uploads/".$id);
			$array[] = $sharer->retrieveFileInfo($id);
		}
	}
	print_r(json_encode($array));
?>
