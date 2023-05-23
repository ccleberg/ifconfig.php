<?php

$site_title = 'ip.cleberg.net';
$site_owner_name = 'Christian Cleberg';
$site_owner_url = 'https://cleberg.net';
$source_repository = 'https://git.sr.ht/~cmc/ifconfig.php';

// Create array of values
$user = array(
	'ip' 		 => $_SERVER['REMOTE_ADDR'],
	'host' 		 => (isset($_SERVER['REMOTE_ADDR']) ? gethostbyaddr($_SERVER['REMOTE_ADDR']) : ""),
	'port' 		 => $_SERVER['REMOTE_PORT'],
	'ua' 		 => $_SERVER['HTTP_USER_AGENT'],
	'lang' 		 => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
	'mime' 		 => $_SERVER['HTTP_ACCEPT'],
	'encoding' 	 => $_SERVER['HTTP_ACCEPT_ENCODING'],
	'charset' 	 => $_SERVER['HTTP_ACCEPT_CHARSET'],
	'connection' => $_SERVER['HTTP_CONNECTION'],
	'cache' 	 => $_SERVER['HTTP_CACHE_CONTROL'],
	'cookie' 	 => $_SERVER['HTTP_COOKIE'],
	'referer' 	 => $_SERVER['HTTP_REFERER'],
	'real_ip' 	 => $_SERVER['HTTP_X_REAL_IP'],
	'fwd_ip' 	 => $_SERVER['HTTP_X_FORWARDED_FOR'],
	'fwd_host' 	 => (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? gethostbyaddr($_SERVER['HTTP_X_FORWARDED_FOR']) : ""),
	'dnt' 		 => $_SERVER['HTTP_DNT']
	);

// Check request (ex. ifconfig.php?q=ip)
$query=trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($_GET['q'])))))); 

// Return single value on request & die
if (isset($query) && array_key_exists($query, $user)) {
	empty($user[$query]) ? die() : die($user[$query]."\n");
}
// Return full output in one of supported formats (html, text, xml, json. default: html)
elseif (isset($query) && (($query=="text") || ($query=="all"))) {
	header('Content-Type: text/plain');
	foreach($user as $key => $value) {
		echo $key.": ".$value."\n";
	}
	die();
} elseif (isset($query) && ($query=="xml")) {
	header('Content-Type: text/xml');
	// Function for SimpleXML creation
	function array_to_xml(array $arr, SimpleXMLElement $xml)
	{
    	foreach ($arr as $k => $v) {
        	is_array($v)
            	? array_to_xml($v, $xml->addChild($k))
            	: $xml->addChild($k, $v);
    	}
    	return $xml;
	}
	echo array_to_xml($user, new SimpleXMLElement('<info/>'))->asXML();
} elseif (isset($query) && ($query=="json")) {
	header('Content-Type: application/json');
	die(json_encode($user));
} else {
	header('Content-Type: text/html');
	echo <<<EOD
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>{$site_title}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
		<style>
			p,a {
				font-family: ui-monospace, 'Cascadia Code', 'Source Code Pro', Menlo, Consolas, 'DejaVu Sans Mono', monospace;
				line-height: 1em;
			}
			.small, .small > a {
				font-size: 9pt;
				color: #777;
				text-align: center;
				text-decoration: none;
			}
		</style>
	</head>
	<body>
	<nav>
		<a href="/">home</a> &middot;
		<a href="/?q=text">text</a> &middot;
		<a href="/?q=xml">xml</a> &middot;
		<a href="/?q=ip">ip</a> &middot;
		<a href="/?q=ua">ua</a>
	</nav>
	<hr>
	EOD;

	foreach($user as $key => $value) {
		echo '	<p id="'.$key.'">'.$key.': '.$value.'</p>'."\n";
	}

	$date = date("Y");
	echo <<<EOD
	<br>
	<p class="small">Copyright &copy; {$date} <a href="{$site_owner_url}">{$site_owner_name}</a>.<br/>
	<a target="_blank" rel="noreferrer" href="{$source_repository}">Source Code</a></p>
	</body>
	</html>
	EOD;
}
die();
?>
