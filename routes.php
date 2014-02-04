<?php
set_include_path('.:lib:example');
function __autoload($class) {
	include $class . '.php';
}
include_once('config.php');
$exec_start = microtime(true);

$request = $_SERVER['SCRIPT_NAME'];
PnmLogger::debug("Request: " . print_r($_SERVER['REQUEST_URI'], true));

# Route the incoming requests to a handler
switch ($request) {
case '/authorize':
	$pnm = new ExampleAuthorizationCallbackHandler(SECRET, $_GET);
	break;
case '/confirm':
	$pnm = new ExampleConfirmationCallbackHandler(SECRET, $_GET);
	break;
default:
	return false;
}

# Handle request and output result
echo $pnm->handleRequest();

$exec_time = (microtime(true) - $exec_start);
PnmLogger::debug("Request took $exec_time seconds");
if ($exec_time >= 6) {
	PnmLogger::warn("Request took longer than 6 seconds!");
}

exit();
?>
