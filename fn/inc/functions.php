<?php

if (!defined("DIONA")) {
	header("HTTP/1.1 403 Forbidden");
	header("Location: /");
	die("ERROR");
}


$langdate = array (
	'January'		=>	"января",
	'February'		=>	"февраля",
	'March'			=>	"марта",
	'April'			=>	"апреля",
	'May'			=>	"мая",
	'June'			=>	"июня",
	'July'			=>	"июля",
	'August'		=>	"августа",
	'September'		=>	"сентября",
	'October'		=>	"октября",
	'November'		=>	"ноября",
	'December'		=>	"декабря",
	'Jan'		=>	"янв",
	'Feb'		=>	"фев",
	'Mar'		=>	"мар",
	'Apr'		=>	"апр",
	'Jun'		=>	"июн",
	'Jul'		=>	"июл",
	'Aug'		=>	"авг",
	'Sep'		=>	"сен",
	'Oct'		=>	"окт",
	'Nov'		=>	"ноя",
	'Dec'		=>	"дек",
	'Sunday'	=>	"Воскресенье",
	'Monday'	=>	"Понедельник",
	'Tuesday'	=>	"Вторник",
	'Wednesday'	=>	"Среда",
	'Thursday'	=>	"Четверг",
	'Friday'	=>	"Пятница",
	'Saturday'	=>	"Суббота",
	'Sun'	=>	"Вс",
	'Mon'	=>	"Пн",
	'Tue'	=>	"Вт",
	'Wed'	=>	"Ср",
	'Thu'	=>	"Чт",
	'Fri'	=>	"Пт",
	'Sat'	=>	"Сб",
);


function langdate($format, $stamp) {

	global $langdate;
	
	return strtr( @date( $format, $stamp ), $langdate );

}

function cleanpath($path) {
	$path = trim(str_replace(chr(0), '', (string)$path));
	$path = str_replace(array('/', '\\'), '/', $path);
	$parts = array_filter(explode('/', $path), 'strlen');
	$absolutes = array();
	foreach ($parts as $part) {
		if ('.' == $part) continue;
		if ('..' == $part) {
			array_pop($absolutes);
		} else {
			$absolutes[] = totranslit($part, false, false);
		}
	}

	return implode('/', $absolutes);
}

function totranslit($var, $lower = true, $pu = true) {
	global $langtranslit;
	
	if ( is_array($var) ) return "";

	$var = str_replace(chr(0), '', $var);
	
	$var = trim( strip_tags( $var ) );
	$var = preg_replace( "/\s+/u", "-", $var );
	$var = str_replace( "/", "-", $var );
	
	if (is_array($langtranslit) AND count($langtranslit) ) {
		$var = strtr($var, $langtranslit);
	}

	if ( $pu ) $var = preg_replace( "/[^a-z0-9\_\-.]+/mi", "", $var );
	else $var = preg_replace( "/[^a-z0-9\_\-]+/mi", "", $var );

	$var = preg_replace( '#[\-]+#i', '-', $var );
	$var = preg_replace( '#[.]+#i', '.', $var );

	if ( $lower ) $var = strtolower( $var );

	$var = str_ireplace( ".php", "", $var );
	$var = str_ireplace( ".php", ".ppp", $var );

	if( strlen( $var ) > 200 ) {
		
		$var = substr( $var, 0, 200 );
		
		if( ($temp_max = strrpos( $var, '-' )) ) $var = substr( $var, 0, $temp_max );
	
	}
	
	return $var;
}

function checkLoginFromForm($lo,$pa){
	global $db;
	
	$SQL = "SELECT COUNT(*) AS count FROM Users WHERE (UserID = '$lo') AND (pass = '$pa')";
	
	$row = $db->super_query($SQL);
	$count = $row["count"];
	
	if ($count == 1){
		setcookie("pr_l", $lo, 0);
		setcookie("pr_p", $pa, 0);
		return true;
	}else{
		return false;
	}
	
}

function checkLoginFromCookie(){
	global $db;
	
	$lo = @$_COOKIE["pr_l"];
	$lo = $db->safesql($lo);
	
	$pa = @$_COOKIE["pr_p"];
	$pa = $db->safesql($pa);
	
	$SQL = "SELECT COUNT(*) AS count FROM Users WHERE (UserID = '$lo') AND (pass = '$pa')";
	$row = $db->super_query($SQL);
	$count = $row["count"];
	if ($count == 1){
		return true;
	}else{
		return false;
	}	
}

function showLoginForm($msg = ""){
	global $db;
	if ($msg == "") $msg = <<<HTML
	<div class="alert alert-info" role="alert">
		<div class="text-muted">Для входа введите пароль</div>
	</div>
HTML;

	$logins = "";

	$res = $db->query("SELECT UserID, UserName FROM Users WHERE (deleted = 0) ORDER BY UserName");
	
	while ($row = $db->get_row($res)) {
		
		$id = iconv("WINDOWS-1251", "UTF-8", $row['UserID']);
		
		$user = iconv("WINDOWS-1251", "UTF-8", $row['UserName']);
		
		$logins .= "<option value='{$id}'>{$user}</option>";
		
	}
	
	$tpl = new template;
	$tpl->dir = "template";
	$tpl->load_template("login.tpl");
	$tpl->set("{msg}", $msg);
	$tpl->set("{logins}", $logins);
	$tpl->compile('content');
	echo $tpl->result['content'];
	$tpl->global_clear();
}

function showNewsDates(){

	global $db;

	$items = "";

	$SQL = "SELECT TOp 300 id, Name, NewsDate FROM News WHERE (Deleted = 0) ORDER BY NewsDate DESC";

	$res = $db->query($SQL);
	
	while ($row = $db->get_row($res)) {
		
		$id = iconv("WINDOWS-1251", "UTF-8", $row['id']);
		
		$name = iconv("WINDOWS-1251", "UTF-8", $row['Name']);
		
		$date = $row['NewsDate'];
		
		$date2 = langdate("d.m.Y", strtotime($date));
		$date3 = langdate("j F Y, H:i", strtotime($date));
		
		if ($date2 == Date("d.m.Y")){
			$highlight = <<<HTML
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brightness-up" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="#2fb344" fill="none" stroke-linecap="round" stroke-linejoin="round">
				  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
				  <circle cx="12" cy="12" r="3" />
				  <line x1="12" y1="5" x2="12" y2="3" />
				  <line x1="17" y1="7" x2="18.4" y2="5.6" />
				  <line x1="19" y1="12" x2="21" y2="12" />
				  <line x1="17" y1="17" x2="18.4" y2="18.4" />
				  <line x1="12" y1="19" x2="12" y2="21" />
				  <line x1="7" y1="17" x2="5.6" y2="18.4" />
				  <line x1="6" y1="12" x2="4" y2="12" />
				  <line x1="7" y1="7" x2="5.6" y2="5.6" />
				</svg>			
HTML;
		}else{
			$highlight = "";
		}

		$items .=<<<HTML
			<li class="nav-item">
				<a class="nav-link" href="#" id="{$id}" >
					{$highlight}
					<span class="nav-link-title">
						$name <br />
						$date2 
					</span>
				</a>
			</li>
HTML;
		
	}	

	return $items;

}

?>