<?php
set_include_path('.:lib:example');
function __autoload($class) {
	include $class . '.php';
}
include_once('config.php');

$exec_start = microtime(true);
PnmLogger::debug("Request: authorize");

$pnm = new ExampleAuthorizationCallbackHandler(SECRET, $_GET);

# Handle request and output result
echo $pnm->handleRequest();

$exec_time = (microtime(true) - $exec_start);
PnmLogger::debug("Request took $exec_time seconds");
if ($exec_time >= 6) {
	PnmLogger::warn("Request took longer than 6 seconds!");
}

?>
