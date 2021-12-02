<?php

###########################################################################################################################
#   Класс для работы с MSSQL                                                                                              #
#   2021.11.30   nafanja@nafanja.ru                                                                                       #
#                                                                                                                         #
# Подключение:                                                                                                            #
# $db = new db;                                                                                                           #
# $db->connect(DBUSER(string), DBPASS(string), DBNAME(string), DBSERVER(string), SHOWERROR(bool), DATESASSTRING(bool));   #
#                                                                                                                         #
# Запрос:                                                                                                                 #
# $res = $db->query($SQL);                                                                                                #
#                                                                                                                         #
# Получение значений:                                                                                                     #
# while ($row = $db->get_row($res)) {                                                                                     #
#    $val = $row["FIELD_NAME"];                                                                                           #
# }                                                                                                                       #
#                                                                                                                         #
###########################################################################################################################

if (!defined("DIONA")) {
	header("HTTP/1.1 403 Forbidden");
	header("Location: /");
	die("ERROR");
}

class db {
	
	var $db_id = false;
	var $show_error = false;
	var $query_id = false;
	
	function connect($db_user, $db_pass, $db_name, $db_location, $show_error = true, $string_dates = true)	{
		
		$this->show_error = $show_error;
		
		$connectionInfo = array( 
			"Database" => $db_name,
			"UID" => $db_user,
			"PWD" => $db_pass,
			"ReturnDatesAsStrings" => $string_dates);
		
		$this->db_id = sqlsrv_connect( $db_location, $connectionInfo);
		
		if(!$this->db_id) {
			if($show_error) {
				$this->display_error(sqlsrv_errors());
			} else {
				return false;
			}
		}
		
		if ($this->show_error){
			sqlsrv_configure ("WarningsReturnAsErrors", true);
		}

		return true;
	}
	
	function close(){
		
		@sqlsrv_close($this->db_id);
		
	}
	
	function version(){
		
		$server_info = sqlsrv_server_info($this->db_id);
		if($server_info){
			return $server_info["SQLServerVersion"];
		} else {
			display_error(sqlsrv_errors());
		}
		
	}
	
	function safesql($var){
		
		$var = stripslashes(htmlspecialchars($var, ENT_QUOTES, 'UTF-8'));
		
		return $var;
	}	
	
	function query($query){
		
		$params = array();
	
		$options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		
		$this->query_id = sqlsrv_query($this->db_id, $query, $params, $options);
			
		if (!$this->query_id)
			if ($this->show_error)
				$this->display_error(sqlsrv_errors(), $query);	
			
		return $this->query_id;
		
	}
	
	
	function get_row($query_id = ''){
		
		if ($query_id == '') $query_id = $this->query_id;
		
		return sqlsrv_fetch_array($query_id, SQLSRV_FETCH_ASSOC);
		
	}
	
	function super_query($query){
		
		$this->query($query);
		
		$data = $this->get_row();
		
		$this->free();
		
		return $data;

	}	
	
	function num_rows($query_id = ''){
		
		if ($query_id == '') $query_id = $this->query_id;
		
		return sqlsrv_num_rows($query_id);
		
	}
	

	function free($query_id = ''){
		
		if ($query_id == '') $query_id = $this->query_id;
		
		@mysqli_free_result($query_id);
		
	}	
	
	
	function display_error($error, $query = "") {
		if($query) $query = preg_replace("/([0-9a-f]){32}/", "********************************", $query);

		$query = htmlspecialchars($query, ENT_QUOTES);

		$trace = debug_backtrace();
		
		$message = "";
		
		for ($i = 0; $i <= count($error) - 1; $i++){
			$error[$i]["message"] = iconv("WINDOWS-1251", "UTF-8", $error[$i]["message"]);
			$error[$i]["message"] = htmlspecialchars($error[$i]["message"], ENT_QUOTES, "UTF-8");
			$message .= "<span>Код:</span> " . $error[$i]["code"] . ".&nbsp;&nbsp;&nbsp;<span>Сообщение:</span> " . $error[$i]["message"] . "<br>";
		}
		
		$level = 0;
		if ($trace[1]['function'] == "query" ) $level = 1;
		if (@$trace[2]['function'] == "super_query" ) $level = 2;

		//$trace[$level]['file'] = str_replace(CDIR, "", $trace[$level]['file']);

		echo <<<HTML
<!doctype html>
<html lang="en">
<head>

<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
<meta http-equiv="X-UA-Compatible" content="ie=edge"/>
<title>Ошибка запроса</title>

<style type="text/css">
<!--
body {
	font-family: Arial, Tahoma, Helvetica, sans-serif;
	background-color: #f4f6fa;
	color: #000000;
}
.window {
	background-color: #fff;
	border: 1px solid #D9D9D9;
	-moz-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3); -webkit-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3); box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
}

.title {
	background-color: #e34b07;
	background-image: -moz-linear-gradient(top, #e34b07, #ac3805);
	background-image: -ms-linear-gradient(top, #e34b07, #ac3805);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#e34b07), to(#ac3805));
	background-image: -webkit-linear-gradient(top, #e34b07, #ac3805);
	background-image: -o-linear-gradient(top, #e34b07, #ac3805);
	background-image: linear-gradient(top, #e34b07, #ac3805);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e34b07', endColorstr='#ac3805',GradientType=0 ); 	
	color: #fff;
	padding: 10px 20px;
	font-size: 1.3em;
}

.body {
	font-size: .9em;
}

.body span {
	color: #d63939;
}

.title svg {
	float: left;
	margin-right: 10px;
}

.box {
	margin: 10px;
	padding: 4px;
	background-color: #fdfdfd;
	border: 1px solid #e6e7e9;
}
-->
</style>
</head>
<body>
	<div class="window">
		<div class="title">
			<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 9v2m0 4v.01"></path><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"></path></svg>
			SQL Error
		</div>
		<div class="body">
			<div class="box"><span>Ошибка в файле:</span> {$trace[$level]['file']}</b> в строке {$trace[$level]['line']}</div>
			<div class="box">Ответ сервера:<p>{$message}</p></div>
			<div class="box"><span>Запрос SQL:</span><p>{$query}</p></div>
		</div>
	</div>

</body>
</html>
HTML;
		
		die();
		
	}
	
}



?>