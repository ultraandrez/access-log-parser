<?php

use AccessLogAnalyser\App\Controllers\LogFileController;

require_once './vendor/autoload.php';
ini_set('display_errors', 1);

try {
	$response = (new LogFileController())->fileStatistic($_SERVER['argv'][1]);
} catch (Throwable $e) {
	$response['error'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT); 