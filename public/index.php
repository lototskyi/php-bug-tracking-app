<?php
declare(strict_types = 1);

use App\Helpers\Config;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Src/Exception/exception.php';

$logger = new \App\Logger\Logger();
$logger->log(\App\Logger\LogLevel::EMERGENCY, 'There is an emergency!', ['exception' => 'exception occurred']);

$logger->info('User account created successfully!', ['id' => 5]);