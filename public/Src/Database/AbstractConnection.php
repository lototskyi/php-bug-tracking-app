<?php

namespace App\Database;

use App\Exception\MissingArgumentException;

abstract class AbstractConnection
{

    protected $connection;

    const REQUIRED_CONNECTION_KEYS = [];

    public function __construct(protected array $credentials)
    {
        if (!$this->credentialsHaveRequiredKeys($this->credentials)) {
            throw new MissingArgumentException(
                sprintf(
                    'Database connection credentials are not mapped correctly, required keys: %s',
                    implode(',', static::REQUIRED_CONNECTION_KEYS)
                )
            );
        }
    }

    private function credentialsHaveRequiredKeys(array $credentials): bool
    {
        $matches = array_intersect_key(static::REQUIRED_CONNECTION_KEYS, array_keys($credentials));
        return count($matches) === count(static::REQUIRED_CONNECTION_KEYS);
    }

    abstract protected function parseCredentials(array $credentials): array;

}