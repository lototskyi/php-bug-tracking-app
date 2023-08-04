<?php
declare(strict_types = 1);

use App\Helpers\Config;

require_once __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/Src/Exception/exception.php';

$application = new \App\Helpers\App();

new mysqli('dsdd', 'dsds', 'dsds', 'ddd');
exit;

echo $application->getServerTime()->format('Y-m-d H:i:s') . PHP_EOL;
echo $application->getLogPath() . PHP_EOL;
echo $application->getEnvironment() . PHP_EOL;
echo $application->isDebug() . PHP_EOL;
var_dump( $application->isRunningFromConsole());

