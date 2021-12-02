<?php

/**************************************************

	Вывод списка блоков по результатам поиска
	
**************************************************/

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
set_time_limit(0);

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

	$date1 = @$db->safesql($_REQUEST["date1"]);
	$date2 = @$db->safesql($_REQUEST["date2"]);
	$text = @$db->safesql($_REQUEST["text"]);
	
	if (!$date1) die("ERROR");
	if (!$date2) die("ERROR");
	if (!$text) die("ERROR");
	
/***************************/

	$date1 = date_format(date_create_from_format('Y-m-d', $date1), 'Y-d-m');
	$date2 = date_format(date_create_from_format('Y-m-d', $date2), 'Y-d-m');
	
	$SQL = "SELECT id, NewsDate FROM [NewsFactory].[dbo].[News] where (NewsDate >= (cast('{$date1} 00:00:00' as datetime))) and (NewsDate <= (cast('{$date2} 23:59:00' as datetime))) order by NewsDate desc";

	$res = $db->query($SQL);	
	
	$blocks = "";
	
	$count = 0;
	
	$text = iconv("UTF-8", "WINDOWS-1251", $text);
	
	$records = $db->num_rows();
	
	$current_record = 1;
	
	session_start(['read_and_close'  => true]);
	
	$filename = "sessions/" . session_id();
	
	while ($row = $db->get_row($res)) {
		
		$percent = round($current_record / $records * 100);
		$current_record++;

		file_put_contents($filename, $percent);
		
		$id = $row["id"];
		$NewsDate = $row["NewsDate"];
			
		$SQL = "SELECT Id, Name, BLockType, Ready, Approve, CalcTime FROM [NewsFactory].[dbo].[Blocks] where deleted=0 AND NewsId = '{$id}' AND (Name Like ('%{$text}%') OR BlockText Like ('%{$text}%')) ORDER BY Sort";
		
		$res2 = $db->query($SQL);		
		
		while ($row2 = $db->get_row($res2)) {

			$count++;
			
			$id = $row2["Id"];

			$name = $row2["Name"];
			
			$type = $row2["BLockType"];
			
			$ready = $row2["Ready"];
			
			$approve = $row2["Approve"];
			
			$time = $row2["CalcTime"];
			
			$time = date("H:i:s", mktime(0, 0, $time));
			
			if ($type == '3') $type = 'Студия';
			if ($type == '2') $type = 'Видео';
			if ($type == '1') $type = 'Сюжет';		

			$id = iconv("WINDOWS-1251", "UTF-8", $id);
			
			$name = iconv("WINDOWS-1251", "UTF-8", $name);
					
			if ($approve) $approve = 'success'; else $approve = 'warning';
			if ($ready) $ready = 'success'; else $ready = 'warning';
			
			$NewsDate = date("d.m.Y", strtotime($NewsDate));
			
			$blocks .= <<<HTML
				<tr id="{$id}" class="news-block" style="cursor: pointer">
					<td width="40">{$count}</td>
					<td>{$name}</td>
					<td width="80">{$NewsDate}</td>
					<td width="60">{$type}</td>
					<td width="60" class="text-center"><span class="badge bg-{$approve} me-1"></span></td>
					<td width="60" class="text-center"><span class="badge bg-{$ready} me-1"></span></td>				
					<td width="90">{$time}</td>
				</tr>
HTML;

		}
		
	}
	
	if ($count == 0){
		die('<div class="page-body">
			<div class="container"><div class="alert alert-danger" role="alert"><h4 class="alert-title">Ничего не найдено</h4><div class="text-muted">Попробуйте изменить условия поиска</div></div></div></div>');
	}	
	
	echo <<<HTML
		
        <div class="page-body">
			<div class="container-xl">
			
				<div class="col-12 mb-0">
					<div class="card">
						
						<table class="table card-table table-vcenter text-nowrap datatable">
							<thead>
								<tr>
								  <th width="40">#</th>
								  <th>Название</th>
								  <th width="80" class="text-center">Дата</th>
								  <th width="60" class="text-center">Тип</th>
								  <th width="60" class="text-center">Одобрен</th>
								  <th width="60" class="text-center">Готов</th>
								  <th width="110">Хр</th>
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
			<div class="container-xl ajax-content-5">
			
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
					$(".ajax-content-5").html(data);				
					
					/* SIZE */
					
					var window_h = $(window).height();
					var table_h = $(".table-responsive").height();
					var h = window_h;
					h = h - 310- table_h;
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