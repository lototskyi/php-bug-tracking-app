<?php

namespace App\Exception;

use Exception;

abstract class BaseException extends Exception
{
    public function __construct(
        string $message = "",
        protected array $data = [],
        int $code = 0,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }

    public function setData(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function getExtraData(): array
    {
        if (count($this->data) === 0) {
            return $this->data;
        }

        return json_decode(json_encode($this->data));
    }
}