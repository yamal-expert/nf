<?php

/*******************************

	Вывод списка блоков
	
*******************************/

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

include (CDIR . "inc/classes/class.mssql.php");
include (CDIR . "inc/config.php");
include (CDIR . "inc/functions.php");

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
			die("ERROR");
		}
	}else{
		die("ERROR");
	}
}

	$id = @$_REQUEST["id"];
	$id = $db->safesql($id);

	if (!$id) die("ERROR");

/***************************/

	$SQL = "SELECT Name, CreatorId, BlockText, Ready FROM Blocks WHERE (Id = '{$id}')";
	
	$row = $db->super_query($SQL);
	
	$name = $row["Name"];
	
	$creatorid = $row["CreatorId"];
	
	$text = $row["BlockText"];
	
	$ready = $row["Ready"];
	
	$name = iconv("WINDOWS-1251", "UTF-8", $name);
	
	$text = iconv("WINDOWS-1251", "UTF-8", $text);
	
	
	$SQL = "SELECT UserName FROM Users WHERE UserID='{$creatorid}'";
	
	$row = $db->super_query($SQL);

	$owner = $row["UserName"];
	
	$owner = iconv("WINDOWS-1251", "UTF-8", $owner);
	
?>

<div class="row row-cards">
	<div class="col-12">
		<div class="card card-sm">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-auto">
						<span class="bg-blue text-white avatar">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-columns" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
							  <line x1="4" y1="6" x2="9.5" y2="6" />
							  <line x1="4" y1="10" x2="9.5" y2="10" />
							  <line x1="4" y1="14" x2="9.5" y2="14" />
							  <line x1="4" y1="18" x2="9.5" y2="18" />
							  <line x1="14.5" y1="6" x2="20" y2="6" />
							  <line x1="14.5" y1="10" x2="20" y2="10" />
							  <line x1="14.5" y1="14" x2="20" y2="14" />
							  <line x1="14.5" y1="18" x2="20" y2="18" />
							</svg>
						</span>
					</div>
					
					<div class="col">
						<div class="font-weight-medium">
							<?=$name?>
						</div>
						<div class="text-muted">
							<?=$owner?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row row-cards">
	<div class="col-12">
		<div class="card card-sm">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<textarea class="form-control textarea_text" name="example-textarea-input" rows="6" readonly style="min-height: 300px"><?=$text?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	
