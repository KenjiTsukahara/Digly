<?php
 
	require_once('join/config.php');
	require_once('join/fbconfig.php');
	require_once('join/codebird.php');
	 
	session_start();
	 
	$_SESSION = array();
	
	if (isset($_COOKIE[session_name()])) {
	    setcookie(session_name(), '', time()-604800, '/');
	}
	
	 
	session_destroy();
	 
	header('Location: '.SITE_URL);