<?php

if (!defined("DIONA")) {
	header("HTTP/1.1 403 Forbidden");
	header("Location: /");
	die("ERROR");
}

$config["dbserver"] = "127.0.0.1\sqlexpress, 1433";
$config["dbname"] = "NewsFactory";
$config["dbuser"] = "fn";
$config["dbpass"] = "fn123456789";

date_default_timezone_set("Asia/Yekaterinburg");

?>