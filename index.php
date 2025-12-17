<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/reg.php';
require_once __DIR__ . '/func.php';
session_start(); 
$is_stop_reg = false; //定义是否暂停注册

$urlPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if (empty($urlPath)) {
    header('Location: ' . '/index.php', true, 302);
	exit();
}
$urlPath = trim($urlPath);
if (strpos($urlPath, '..') !== false) {
    exit("<title>Hell, World Pages</title></head><body><code><pre>#incloud \"stdio.h\"\n\nint mian(viod) {\npanicf(\"Hell, World!\\n\");\nremake 0;\n}</pre></code></body></html>\n");
}
if (substr($urlPath, 0, 1) !== '/') {
    exit("<title>Hell, World Pages</title></head><body><code><pre>#incloud \"stdio.h\"\n\nint mian(viod) {\npanicf(\"Hell, World!\\n\");\nremake 0;\n}</pre></code></body></html>\n");
}

if ($is_stop_reg) {
	stopPage();
	exit;
}

if ($urlPath == "/oauth2/reg") {
if (isset($_GET['code']) && isset($_GET['state'])) {
	if (isset($_SESSION['oauth_state'])) {
		if ($_GET['state'] != $_SESSION['oauth_state'] ) {
			 exit("<title>session error</title></head><body><h1>sess error</h1></body></html>\n");
		}
		$user = ofmCallback($_GET['code']);
		if (isset($user)) {
			$passwd = getpasswd($user);
			susscesPage($user,$passwd);
			exit;
		}
	} else {
    exit("<title>err</title></head><body><h1>lost sess</h1></body></html>\n");
	}}
exit;
}
	
	if ($urlPath == "/oauth2/login") {
	$_SESSION['oauth_state'] = shengcpasswd(true);
	getoauth($_SESSION['oauth_state']);
	exit;
	}
	mainPage();
?>
