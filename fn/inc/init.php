<?php

error_reporting(E_ALL);
ini_set("display_errors", true);

#######################################################################

date_default_timezone_set("Asia/Yekaterinburg");

include (CDIR . "inc/classes/class.mssql.php");
include (CDIR . "inc/config.php");
include (CDIR . "inc/functions.php");
include (CDIR . "inc/classes/class.template.php");

$db = new db;
$db->connect($config["dbuser"] , $config["dbpass"], $config["dbname"], $config["dbserver"]);

/*******************/
$loginFromCookie = checkLoginFromCookie();

if (!$loginFromCookie){
	$pa = @$db->safesql($_REQUEST["pa"]);
	$lo = @$db->safesql($_REQUEST["lo"]);
	
	if (($pa != "")&&($lo != "")){
		$loginFromForm = checkLoginFromForm($lo,$pa);
		if (!$loginFromForm){
			showLoginForm('<div class="alert alert-danger" role="alert"><h4 class="alert-title">Ошибка</h4><div class="text-muted">Имя пользователя или пароль неправильные</div></div>');
			die();
		}
	}else{
		showLoginForm();
		die();
	}
}
/*******************/


	
$newsDates = showNewsDates();
	
$tpl = new template;
$tpl->dir = "template";
$tpl->load_template("main.tpl");

$tpl->set("{news-dates}", $newsDates);
$tpl->compile('content');
echo $tpl->result['content'];
$tpl->global_clear();

?>