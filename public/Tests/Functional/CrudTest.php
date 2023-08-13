<?php
declare(strict_types=1);

namespace Tests\Functional;

use App\Database\QueryBuilder;
use App\Entity\BugReport;
use App\Helpers\DbQueryBuilderFactory;
use App\Helpers\HttpClient;
use App\Repository\BugReportRepository;
use PHPUnit\Framework\TestCase;

class CrudTest extends TestCase
{
    private BugReportRepository $repository;
    private QueryBuilder $querybuilder;
    private HttpClient $client;

    public function setUp(): void
    {
        $this->querybuilder = DbQueryBuilderFactory::make(
            'database',
            'pdo',
            ['db_name' => 'bug-report-testing']
        );

        $this->repository = new BugReportRepository($this->querybuilder);

        $this->client = new HttpClient;
        parent::setUp();
    }

    public function testItCanCreateReportUsingPostRequest()
    {
        $postData = $this->getPostData(['add' => true]);
        $response = json_decode(
            $this->client->post("http://nginx/Src/add.php", $postData),
            true
        );

        self::assertEquals(200, $response['statusCode']);

        $result = $this->repository->findBy([
            ['report_type', '=', 'Audio'],
            ['link', '=', 'http://example.com'],
            ['email', '=', 'test@example.com']
        ]);

        /** @var BugReport $bugReport */
        $bugReport = $result[0] ?? [];

        self::assertInstanceOf(BugReport::class, $bugReport);
        self::assertSame('test@example.com', $bugReport->getEmail());
        self::assertSame('Audio', $bugReport->getReportType());
        self::assertSame('http://example.com', $bugReport->getLink());

        return $bugReport;
    }

    /**
     * @depends testItCanCreateReportUsingPostRequest
     */
    public function testItCanUpdateReportUsingPostRequest(BugReport $bugReport)
    {
        $postData = $this->getPostData([
            'update' => true,
            'message' => 'The video on PHP OOP has audio issues, please check and fix it',
            'link' => 'http://updated.com',
            'reportId' => $bugReport->getId()
        ]);
        $response = json_decode(
            $this->client->post("http://nginx/Src/update.php", $postData),
            true
        );

        self::assertEquals(200, $response['statusCode']);

        $result = $this->repository->find($bugReport->getId());

        self::assertInstanceOf(BugReport::class, $result);
        self::assertSame('The video on PHP OOP has audio issues, please check and fix it', $result->getMessage());
        self::assertSame('http://updated.com', $result->getLink());

        return $result;
    }

    /**
     * @depends testItCanUpdateReportUsingPostRequest
     */
    public function testItCanDeleteReportUsingPostRequest(BugReport $bugReport)
    {
        $postData = [
            'delete' => true,
            'reportId' => $bugReport->getId()
        ];
        $response = json_decode(
            $this->client->post("http://nginx/Src/delete.php", $postData),
            true
        );

        self::assertEquals(200, $response['statusCode']);

        $result = $this->repository->find($bugReport->getId());

        self::assertNull($result);
    }

    private function getPostData(array $options = []): array
    {
        return array_merge([
            'reportType' => 'Audio',
            'message' => 'The video on xxx has audio issues, please check and fix it',
            'email' => 'test@example.com',
            'link' => 'http://example.com',
        ], $options);
    }

}