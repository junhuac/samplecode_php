<?php

set_include_path('.:lib:example');
function __autoload($class) {
	include $class . '.php';
}

$pnm = new ExampleAuthorizationCallbackHandler('d33af5664496dc4d', $_GET);

header('Content-type: application/xml');

echo $pnm->handleRequest();

exit();
?>