<?php
declare(strict_types = 1);

require_once __DIR__ . '/vendor/autoload.php';

$application = new \App\Helpers\App();

echo $application->getServerTime()->format('Y-m-d H:i:s') . PHP_EOL;
echo $application->getLogPath() . PHP_EOL;
echo $application->getEnvironment() . PHP_EOL;
echo $application->isDebug() . PHP_EOL;
var_dump( $application->isRunningFromConsole());

