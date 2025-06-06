<?php

require 'vendor/autoload.php';
use MaxMind\Db\Reader;

function loadGeo(string $ipAddress): ?array
{
        $databaseFile = 'GeoLite2-City.mmdb';
        $reader = new Reader($databaseFile);
        $results = $reader->get($ipAddress);
        $reader->close();

        return $results;
}

// Set top-level variables for HTML
$site_title = 'ip.cmc.pub';
$site_owner_name = 'Christian Cleberg';
$site_owner_url = 'https://cmc.pub';
$source_repository = 'https://github.com/ccleberg/ifconfig.php';

// Extract geolocation
$geo = loadGeo($_SERVER['REMOTE_ADDR']);

// Create array of values
$user = array(
        'ip' 	         => $_SERVER['REMOTE_ADDR'],
	'host' 	         => (isset($_SERVER['REMOTE_ADDR']) ? gethostbyaddr($_SERVER['REMOTE_ADDR']) : ""),
	'port' 	         => $_SERVER['REMOTE_PORT'],
	'ua' 	         => $_SERVER['HTTP_USER_AGENT'],
	'lang' 	         => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
	'mime' 	         => $_SERVER['HTTP_ACCEPT'],
	'encoding'       => $_SERVER['HTTP_ACCEPT_ENCODING'],
	'charset'        => $_SERVER['HTTP_ACCEPT_CHARSET'],
	'connection'     => $_SERVER['HTTP_CONNECTION'],
	'cache'          => $_SERVER['HTTP_CACHE_CONTROL'],
	'cookie'         => $_SERVER['HTTP_COOKIE'],
	'referer'        => $_SERVER['HTTP_REFERER'],
	'real_ip'        => $_SERVER['HTTP_X_REAL_IP'],
	'fwd_ip'         => $_SERVER['HTTP_X_FORWARDED_FOR'],
	'fwd_host'       => (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? gethostbyaddr($_SERVER['HTTP_X_FORWARDED_FOR']) : ""),
	'dnt' 	         => $_SERVER['HTTP_DNT'],
        'continent_code' => $geo['continent']['code'],
        'continent_name' => $geo['continent']['names']['en'],
        'country_code'   => $geo['country']['geoname_id'],
        'country_iso'    => $geo['country']['iso_code'],
        'country_name'   => $geo['country']['names']['en'],
        'time_zone'      => $geo['location']['time_zone']
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
		  body { overflow-wrap: break-word; }
			pre { padding: 0.5rem; border: 1px solid black; }
			p,a { font-family: ui-monospace, 'Cascadia Code', 'Source Code Pro', Menlo, Consolas, 'DejaVu Sans Mono', monospace; }
			small, small > a { font-size: 9pt; }
			@media (prefers-color-scheme: dark) {
				body { background-color: #000; color: #ccc; }
				a, a:visited { color: #0f0; }
				pre { border-color: #ccc; }
			}
		</style>
	</head>
	<body>
	<nav>
		<a href="/">html</a> &middot;
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

	echo <<<EOD
	<pre><code># Curl Commands:
	curl -L -X GET https://ip.cmc.pub
	curl -L -X GET https://ip.cmc.pub/\?q\=text
	curl -L -X GET https://ip.cmc.pub/\?q\=xml
	curl -L -X GET https://ip.cmc.pub/\?q\=ip
	curl -L -X GET https://ip.cmc.pub/\?q\=ua
	</code></pre>
	EOD;

	$date = date("Y");
	echo <<<EOD
	<br>
	<p><small>Copyright &copy; {$date} <a href="{$site_owner_url}">{$site_owner_name}</a>
	<br><a target="_blank" rel="noreferrer" href="{$source_repository}">Source Code</a></small></p>
	</body>
	</html>
	EOD;
}
die();
?>
