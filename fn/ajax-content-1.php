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

	$SQL = "SELECT Name, NewsDate FROM [NewsFactory].[dbo].[News] WHERE (id = '{$id}')";
	
	$row = $db->super_query($SQL);
	
	$name = $row["Name"];
	
	$date = $row["NewsDate"];
	
	//$date = $date->format('d.m.Y H:i:s');
		
	$date = langdate("j F Y", strtotime($date));
	
	$name = iconv("WINDOWS-1251", "UTF-8", $name);
	
	echo <<<HTML
	
        <div class="container-xl">
          <!-- Page title -->
          <div class="page-header d-print-none">
            <div class="row align-items-center">
              <div class="col">
			  
                <h2 class="page-title">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-indent-increase" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
					   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
					   <line x1="20" y1="6" x2="9" y2="6"></line>
					   <line x1="20" y1="12" x2="13" y2="12"></line>
					   <line x1="20" y1="18" x2="9" y2="18"></line>
					   <path d="M4 8l4 4l-4 4"></path>
					</svg>
					{$name}
                </h2>
				<div class="page-pretitle">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-event" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
					   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
					   <rect x="4" y="5" width="16" height="16" rx="2"></rect>
					   <line x1="16" y1="3" x2="16" y2="7"></line>
					   <line x1="8" y1="3" x2="8" y2="7"></line>
					   <line x1="4" y1="11" x2="20" y2="11"></line>
					   <rect x="8" y="15" width="2" height="2"></rect>
					</svg> 
					{$date}
                </div>
              </div>
              
            </div>
          </div>
        </div>
		
HTML;

	$SQL = "SELECT Id, Name, BLockType, Ready, Approve, CalcTime FROM Blocks WHERE (NewsId = '{$id}') AND (deleted = 0) ORDER BY Sort";
	
	$res = $db->query($SQL);	
	
	$blocks = "";
	
	$count = 0;
	
	while ($row = $db->get_row($res)) {
		
		$count++;
		
		$id = $row["Id"];

		$name = $row["Name"];
		
		$type = $row["BLockType"];
		
		$ready = $row["Ready"];
		
		$approve = $row["Approve"];
		
		$time = $row["CalcTime"];
		
		$time = date("H:i:s", mktime(0, 0, $time));
		
		if ($type == '3') $type = 'Студия';
		if ($type == '2') $type = 'Видео';
		if ($type == '1') $type = 'Сюжет';		

		$id = iconv("WINDOWS-1251", "UTF-8", $id);
		
		$name = iconv("WINDOWS-1251", "UTF-8", $name);
				
		if ($approve) $approve = 'success'; else $approve = 'warning';
		if ($ready) $ready = 'success'; else $ready = 'warning';
		
		
		$blocks .= <<<HTML
			<tr id="{$id}" class="news-block" style="cursor: pointer">
				<td width="20">{$count}</td>
				<td>{$name}</td>
				<td width="50">{$type}</td>
				<td width="50" class="text-center"><span class="badge bg-{$approve} me-1"></span></td>
				<td width="50" class="text-center"><span class="badge bg-{$ready} me-1"></span></td>				
				<td width="70">{$time}</td>
			</tr>
HTML;		
		
	}
	
	echo <<<HTML
		
        <div class="page-body">
			<div class="container-xl">
			
				<div class="col-12 mb-3">
					<div class="card">
						
						<table class="table card-table table-vcenter text-nowrap datatable">
							<thead>
								<tr>
								  <th width="40">#</th>
								  <th>Название</th>
								  <th width="40">Тип</th>
								  <th width="60" class="text-center">Одобрен</th>
								  <th width="60" class="text-center">Готов</th>
								  <th width="90">Хр</th>
								</tr>
							</thead>
						</table>
						
						
						<div class="table-responsive" style="min-height: 200px; max-height: 300px; overflow: auto; display:inline-block;">
							<table class="table card-table table-vcenter text-nowrap datatable table-hover">
								<tbody>
									{$blocks}
								</tbody>
							</table>
						</div>			
					</div>
				</div>
			</div>
			<div class="container-xl ajax-content-2">
			
			</div>
		</div>

HTML;

?>

<script>

	$(document).ready(function(){
		$('.news-block').click(function(event) {
			event.preventDefault();
			
			var id = $(this).attr("id");
			$(".card-table tr").removeClass("active");
			$(this).addClass("active");
			
			var URL = "ajax-content-2.php?rndval=" + getRandomInt();
			
			let Data = new FormData();
			Data.append('id', id);
			
			$.ajax({
				url: URL,
				type: "post",
				cache: false,
				data: Data,
				dataType: "html",
				contentType: false,
				processData: false,
				success: function(data) {
					$(".ajax-content-2").html(data);				
					
					/* SIZE */
					
					var window_h = $(window).height();
					var table_h = $(".table-responsive").height();
					var h = window_h;
					h = h - 260- table_h;
					$(".textarea_text").css("min-height", h + "px");
					
					/* END SIZE */
					
					
				},
				error: function(jqXHR, textStatus) {
					alert("Ошибка: " + textStatus);
				}
			});

		});
	});

</script>