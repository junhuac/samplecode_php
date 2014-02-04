<?php
set_include_path('.:lib:example');
function __autoload($class) {
	include $class . '.php';
}

$secret = 'd33af5664496dc4d';
$pnm = new ExampleConfirmationCallbackHandler($secret, $_GET);
echo $pnm->handleRequest();
?>