<?php
set_include_path('.:lib:example');
function __autoload($class) {
	include $class . '.php';
}

$secret = 'd33af5664496dc4d';
$request = $_SERVER['SCRIPT_NAME'];

PnmLogger::debug("Request: " . print_r($_SERVER['REQUEST_URI'], true));

# Route the incoming requests to a handler
switch ($request) {
case '/authorize':
	$pnm = new ExampleAuthorizationCallbackHandler($secret, $_GET);
	break;
case '/confirm':
	$pnm = new ExampleConfirmationCallbackHandler($secret, $_GET);
	break;
default: 
	return false;
}

# Handle request and output result
echo $pnm->handleRequest();

exit();
?>