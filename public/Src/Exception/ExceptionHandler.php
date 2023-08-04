<?php

namespace App\Exception;

use App\Helpers\App;
use Throwable;

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
}