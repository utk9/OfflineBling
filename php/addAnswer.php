<?php
	$config = require("../../config_offline.php");

	$servername = $config["servername"];
	$serverusername = $config["username"];
	$serverpassword = $config["password"];
	$database = $config["database"];

	$question = $_GET["question"];
	$answer = $_GET["answer"];

	$conn = new mysqli($servername, $serverusername, $serverpassword, $database);

	$query = "UPDATE Questions SET answer='$answer' WHERE question='$question'";
	if($conn->query($query) !== TRUE)
	{
		error_log("couldn't update answer");
	}

?>