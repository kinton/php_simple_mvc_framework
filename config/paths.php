<?php
	$protocol = '';
	if (isset($_SERVER['HTTPS']))
	    $protocol = $_SERVER['HTTPS'];
	else
	    $protocol = '';
	if (($protocol) && ($protocol != 'off')) $protocol = 'https:';
	else $protocol = 'http:';

	define('URL', $protocol.'//'.$_SERVER['HTTP_HOST'].'/');
	define('URL_NO_SLASHES', $_SERVER['HTTP_HOST']);
?>
