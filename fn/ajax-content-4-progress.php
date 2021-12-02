<?php

/*********************************

	Вывод значения прогресса
	
*********************************/

define ("DIONA", true);
define ("CDIR", __DIR__ . "/");

$ip = $_SERVER["REMOTE_ADDR"];
$local_ip = array("90.150.172.7", "192.169.11.202");

if (in_array($ip, $local_ip)){
	error_reporting(-1);
	ini_set("display_errors", true);
}else{
	error_reporting(0);
	ini_set("display_errors", false);
}

#######################################################################

date_default_timezone_set("Asia/Yekaterinburg");

session_start();

header('Content-type: application/json');

$filename = "sessions/" . session_id();
$progress = file_get_contents($filename);
echo json_encode(Array("progress" => $progress));

?>