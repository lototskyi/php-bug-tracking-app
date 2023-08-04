<?php
declare(strict_types = 1);

namespace App\Exception;

use App\Helpers\App;
use Throwable;
use ErrorException;

class ExceptionHandler
{

    public function handle(Throwable $exception): void
    {
        $application = new App();

        if ($application->isDebug()) {
            var_dump($exception);
        } else {
            echo "This should not have happened, please try again.";
        }

        exit;
    }

    public function convertWarningsAndNoticesToExceptions($severity, $message, $file, $line): void
    {
        throw new ErrorException($message, $severity, $severity, $file, $line);
    }
}