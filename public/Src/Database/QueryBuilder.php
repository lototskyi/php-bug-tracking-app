<?php
declare(strict_types = 1);

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exception\InvalidArgumentException;
use PDO, mysqli;

abstract class QueryBuilder
{
    protected PDO | mysqli $connection;
    protected string $table;
    protected $statement;
    protected $fields;
    protected $placeholders;
    protected $bindings;
    protected $operation = self::DML_TYPE_SELECT; //dml - SELECT, UPDATE, DELETE, INSERT

    const OPERATORS = ['=', '>=', '>', '<=', '<', '<>'];
    const PLACEHOLDER = '?';
    const COLUMNS = '*';
    const DML_TYPE_SELECT = 'SELECT';
    const DML_TYPE_INSERT = 'INSERT';
    const DML_TYPE_UPDATE = 'UPDATE';
    const DML_TYPE_DELETE = 'DELETE';

    use Query;

    public function __construct(DatabaseConnectionInterface $databaseConnection)
    {
        $this->connection = $databaseConnection->getConnection();
    }

    public function table(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function where(string $column, string | int $operator = self::OPERATORS[0], ?string $value = null): static
    {
        if (!in_array($operator, self::OPERATORS)) {
            if ($value === null) {
                $value = $operator;
                $operator = self::OPERATORS[0];
            } else {
                throw new InvalidArgumentException('Operator is not valid', ['operator' => $operator]);
            }
        }
        $this->parseWhere([$column => $value], $operator);
        return $this;
    }

    private function parseWhere(array $conditions, string $operator): static
    {
        foreach ($conditions as $column => $value) {
            $this->placeholders[] = sprintf('%s %s %s', $column, $operator, self::PLACEHOLDER);
            $this->bindings[] = $value;
        }
        return $this;
    }

    public function select(string $fields = self::COLUMNS): static
    {
        $this->operation = self::DML_TYPE_SELECT;
        $this->fields = $fields;
        return $this;
    }

    public function create(array $data)
    {
        $this->fields = '`' . implode('`, `', array_keys($data)) . '`';

        foreach($data as $value) {
            $this->placeholders[] = self::PLACEHOLDER;
            $this->bindings[] = $value;
        }

        $query = $this->prepare($this->getQuery(self::DML_TYPE_INSERT));
        $this->statement = $this->execute($query);

        return $this->lastInsertedId();
    }

    public function update(array $data): static
    {
        $this->fields = [];
        $this->operation = self::DML_TYPE_UPDATE;

        foreach ($data as $column => $value) {
            $this->fields[] = sprintf('%s%s%s', $column, self::OPERATORS[0], "'$value'");
        }

        return $this;
    }

    public function delete(): static
    {
        $this->operation = self::DML_TYPE_DELETE;
        return $this;
    }

    public function raw(string $query): static
    {
        $query = $this->prepare($query);
        $this->statement = $this->execute($query);
        return $this;
    }

    public function find(int $id)
    {
        return $this->where('id', $id)->runQuery()->first();
    }

    public function findOneBy(string $field, $value)
    {
        return $this->where($field, $value)->runQuery()->first();
    }

    public function first(): ?object
    {
        return $this->count() ? $this->get()[0] : null;
    }

    public function runQuery(): static
    {
        $query = $this->prepare($this->getQuery($this->operation));
        $this->statement = $this->execute($query);
        return $this;
    }

    public function rollback(): void
    {
        $this->connection->rollback();
    }

    abstract public function get();
    abstract public function count();
    abstract public function lastInsertedId(): int;
    abstract public function prepare($query);
    abstract public function execute($statement);
    abstract public function fetchInto($className);
    abstract public function beginTransaction();
    abstract public function affected(): int;

}