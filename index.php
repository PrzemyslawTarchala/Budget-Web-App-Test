<?php

declare(strict_types=1);

namespace Layer_1;

require_once("src/controller.php");
require_once("src/request.php");

// $request = [
// 	'get' => $_GET,
// 	'post' => $_POST,
// 	'server' => $_SERVER
// ];

$request = new Request($_GET, $_POST, $_SERVER);
$controller = new Controller($request);
$controller->run();