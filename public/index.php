<?php
declare(strict_types = 1);

use App\Helpers\Config;

require_once __DIR__ . '/vendor/autoload.php';

set_exception_handler([new \App\Exception\ExceptionHandler(), 'handle']);

$application = new \App\Helpers\App();

echo $application->getServerTime()->format('Y-m-d H:i:s') . PHP_EOL;
echo $application->getLogPath() . PHP_EOL;
echo $application->getEnvironment() . PHP_EOL;
echo $application->isDebug() . PHP_EOL;
var_dump( $application->isRunningFromConsole());

