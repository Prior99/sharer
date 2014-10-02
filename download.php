<?php
	require_once("sharer.php");
	$sharer = new Sharer();
	if(isset($_GET["id"])) {
		$id = $_GET["id"];
		$info = $sharer->retrieveFileInfo($id);
		header('Content-Type: '.$info["mime"]);
		header('Content-Disposition: inline; filename="'.$info["filename"].'"');
		readfile("uploads/".$id);
	}
?>
