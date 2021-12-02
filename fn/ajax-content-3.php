<?php

/*******************************

	Вывод окна поиска
	
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

/***************************/

echo <<<HTML
	
        <div class="container-xl">
          <!-- Page title -->
          <div class="page-header d-print-none">
            <div class="row align-items-center">
              <div class="col">
			  
                <h2 class="page-title">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="15" cy="15" r="4"></circle><path d="M18.5 18.5l2.5 2.5"></path><path d="M4 6h16"></path><path d="M4 12h4"></path><path d="M4 18h4"></path></svg>
					Поиск по материалам
                </h2>
				<div class="page-pretitle2">
					
					<div class="row">
						<div class="col-auto pt-2">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-event" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><rect x="4" y="5" width="16" height="16" rx="2"></rect><line x1="16" y1="3" x2="16" y2="7"></line><line x1="8" y1="3" x2="8" y2="7"></line><line x1="4" y1="11" x2="20" y2="11"></line><rect x="8" y="15" width="2" height="2"></rect></svg> 
						</div>
						<div class="col-auto col-lg-5">
							<label for="text" class="visually-hidden">Текст для поиска</label>
							<input type="text" class="form-control" id="text" placeholder="Текст для поиска">
						</div>
						<div class="col-auto pt-2">
							Диапазон дат для поиска: С
						</div>
						<div class="col-auto">
							<label for="date1" class="visually-hidden">Дата</label>
							<input type="date" class="form-control" id="date1">
						</div>
						<div class="col-auto pt-2">
							ПО
						</div>
						<div class="col-auto">
							<label for="date2" class="visually-hidden">Дата</label>
							<input type="date" class="form-control" id="date2">
						</div>
						<div class="col-auto">
							<button type="submit" class="btn btn-primary do-search">Искать</button>
						</div>
					</div>
                </div>
              </div>
              
            </div>
          </div>
        </div>
		
HTML;

echo <<<HTML

<div class="page-wrapper ajax-content-4">
		
	<div class="jumbotron d-flex align-items-center min-vh-100">
		<div class="container text-center">
			
			<img src="./template/images/logo.png" height="128" alt="Завод Новостей">

		</div>
	</div>

</div>

HTML;

?>

<script>

	function ajax_progress() {
		
		$.ajax({
			type: 'GET',
			cache: false,
			dataType: "json",
			contentType: false,
			processData: false,
			url: '/fn/ajax-content-4-progress.php?rndval=' + getRandomInt(),
			success: function(data) {
				$('.progress-bar').width(data.progress + "%");
			}
		});
		return false;
	}

	$(document).ready(function(){
		$('.do-search').click(function(event) {
			event.preventDefault();
			
			var date1 = $("#date1").val();
			var date2 = $("#date2").val();
			var text = $("#text").val();
			
			if (date1 == ""){ alert('Укажите начальную дату'); return false;}
			if (date2 == ""){ alert('Укажите конечную дату'); return false;}
			if (text == ""){ alert('Укажите текст для поиска'); return false;}
			
			var URL = "ajax-content-4.php?rndval=" + getRandomInt();
			
			let Data = new FormData();
			Data.append('date1', date1);
			Data.append('date2', date2);
			Data.append('text', text);
			
			$(".ajax-content-4").html('<div class="jumbotron d-flex align-items-center h-auto"><div class="container text-center"><div class="progress progress-sm"><div class="progress-bar bg-orange"></div></div></div></div>');
			
			var myVar = setInterval(function() {
				ajax_progress();
			}, 500);
						
			$.ajax({
				url: URL,
				type: "post",
				cache: false,
				data: Data,
				dataType: "html",
				contentType: false,
				processData: false,
				success: function(data) {
					clearInterval(myVar);
					$(".ajax-content-4").html(data);					
				},
				error: function(jqXHR, textStatus) {
					alert("Ошибка: " + textStatus);
				}
			});

		});
	});

</script>