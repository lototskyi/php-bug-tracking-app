<?php
declare(strict_types = 1);

namespace Tests\Units;

use App\Database\MySQLiConnection;
use App\Database\MySQLiQueryBuilder;
use App\Database\PDOConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use App\Helpers\DbQueryBuilderFactory;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{

    private $querybuilder;

    public function setUp(): void
    {
        $this->querybuilder = DbQueryBuilderFactory::make(
            'database',
            'pdo',
            ['db_name' => 'bug-report-testing']
        );

        $this->querybuilder->getConnection()->beginTransaction();

        parent::setUp();
    }

    public function testItCanCreateRecords()
    {
        $id = $this->insertIntoTable();
        self::assertNotNull($id);
        return $id;
    }

    public function testItCanPerformRawQuery()
    {
        $result = $this->querybuilder->raw("SELECT * FROM reports;")->get();
        self::assertNotNull($result);
    }

    public function testItCanPerformSelectQuery()
    {
        $id = $this->insertIntoTable();
        $result = $this->querybuilder
            ->table('reports')
            ->select('*')
            ->where('id', $id)
            ->runQuery()
            ->first();

        self::assertNotNull($result);
        self::assertSame((int) $id, $result->id);
    }

    public function testItCanPerformSelectQueryWithMultipleWhereClause()
    {
        $id = $this->insertIntoTable();
        $result = $this->querybuilder
            ->table('reports')
            ->select('*')
            ->where('id', $id)
            ->where('report_type', '=', 'Report Type 1')
            ->runQuery()
            ->first();

        self::assertNotNull($result);
        self::assertSame((int) $id, $result->id);
        self::assertSame('Report Type 1', $result->report_type);
    }

    public function tearDown(): void
    {
        $this->querybuilder->getConnection()->rollback();
        parent::tearDown();
    }

    public function insertIntoTable()
    {
        $data = [
            'report_type' => 'Report Type 1',
            'message' => 'This is a dummy message',
            'email' => 'support@test.com',
            'link' => 'https://link.com',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $id = $this->querybuilder->table('reports')->create($data);
        return $id;
    }
}