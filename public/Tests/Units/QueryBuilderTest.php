<?php
declare(strict_types = 1);

namespace Tests\Units;

use App\Database\PDOConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{

    private $querybuilder;

    public function setUp(): void
    {
        $this->querybuilder = new PDOQueryBuilder(
            (new PDOConnection(
                array_merge(Config::get('database', 'pdo'),
                ['db_name' => 'bug-report-testing'])
            ))->connect()
        );
        parent::setUp();
    }

    public function testItCanCreateRecords()
    {
        $data = [
            'report_type' => 'Report Type 1',
            'message' => 'This is a dummy message',
            'email' => 'support@test.com',
            'link' => 'https://link.com',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $id = $this->querybuilder->table('reports')->create($data);
        self::assertNotNull($id);
    }

    public function testItCanPerformRawQuery()
    {
        $result = $this->querybuilder->raw("SELECT * FROM reports;")->get();
        self::assertNotNull($result);
    }

    public function testItCanPerformSelectQuery()
    {
        $result = $this->querybuilder
            ->table('reports')
            ->select('*')
            ->where('id', 1)
            ->runQuery()
            ->first();

        self::assertNotNull($result);
        self::assertSame(1, $result->id);
    }

    public function testItCanPerformSelectQueryWithMultipleWhereClause()
    {
        $result = $this->querybuilder
            ->table('reports')
            ->select('*')
            ->where('id', 1)
            ->where('report_type', '=', 'Report Type 1')
            ->runQuery()
            ->first();

        self::assertNotNull($result);
        self::assertSame(1, (int) $result->id);
        self::assertSame('Report Type 1', $result->report_type);
    }
}