<?php
declare(strict_types=1);

namespace App\Database;

use App\Exception\InvalidArgumentException;

class MySQLiQueryBuilder extends QueryBuilder
{

    private $resultSet;
    private $results;

    const PARAM_TYPE_INT = 'i';
    const PARAM_TYPE_STRING = 's';
    const PARAM_TYPE_DOUBLE = 'd';

    public function get()
    {
        $rows = [];
        if (!$this->resultSet) {
            $this->resultSet = $this->statement->get_result();
            if ($this->resultSet) {
                while ($obj = $this->resultSet->fetch_object()) {
                    $rows[] = $obj;
                }
            }
            $this->results = $rows;
        }
        return $this->results;
    }

    public function count(): int | bool
    {
        if (!$this->resultSet) {
            $this->get();
        }
        return $this->resultSet ? $this->resultSet->num_rows : false;
    }

    public function lastInsertedId(): int
    {
        return $this->connection->insert_id;
    }

    public function prepare($query)
    {
        return $this->connection->prepare($query);
    }

    public function execute($statement)
    {
        if (!$statement) {
            throw new InvalidArgumentException('MySQLi statement is false');
        }

        if ($this->bindings) {
            $bindings = $this->parseBindings($this->bindings);
            $reflectionObj = new \ReflectionClass('mysqli_stmt');
            $method = $reflectionObj->getMethod('bind_param');
            $method->invokeArgs($statement, $bindings);
        }
        $statement->execute();
        $this->bindings = [];
        $this->placeholders = [];

        return $statement;
    }

    private function parseBindings(array $params): array
    {
        $bindings = [];

        $count = count($params);

        if ($count === 0) {
            return $params;
        }

        $bindingTypes = $this->parseBindingTypes();
        $bindings[] = &$bindingTypes;

        for ($i = 0; $i < $count; $i++) {
            $bindings[] = &$params[$i];
        }

        return $bindings;
    }

    private function parseBindingTypes(): string
    {
        $bindingTypes = [];

        foreach($this->bindings as $binding) {
            if (is_int($binding)) {
                $bindingTypes[] = self::PARAM_TYPE_INT;
            }

            if (is_string($binding)) {
                $bindingTypes[] = self::PARAM_TYPE_STRING;
            }

            if (is_float($binding)) {
                $bindingTypes[] = self::PARAM_TYPE_DOUBLE;
            }
        }

        return implode('', $bindingTypes);
    }

    public function fetchInto($className)
    {
        $rows = [];
        $this->resultSet = $this->statement->getResult();

        while ($obj = $this->resultSet->fetch_object($className)) {
            $rows[] = $obj;
        }

        $this->results = $rows;
    }

    public function beginTransaction()
    {
        $this->connection->begin_transaction();
    }

    public function affected(): int
    {
        $this->statement->store_result();
        return $this->statement->affected_rows;
    }
}