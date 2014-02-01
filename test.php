<?php
include 'callbacks.php';

$pnm = new PaynearmeCallbacks('secrets');

header('Content-type: application/xml');
echo $pnm->authorize($_GET);

?>