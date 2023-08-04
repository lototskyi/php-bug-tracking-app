<?php
set_error_handler([new \App\Exception\ExceptionHandler(), 'convertWarningsAndNoticesToExceptions']);
set_exception_handler([new \App\Exception\ExceptionHandler(), 'handle']);