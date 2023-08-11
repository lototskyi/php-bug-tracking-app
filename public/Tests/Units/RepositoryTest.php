<?php
declare(strict_types=1);

namespace Tests\Units;

use App\Database\QueryBuilder;
use App\Entity\BugReport;
use App\Helpers\DbQueryBuilderFactory;
use App\Repository\BugReportRepository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{

    private QueryBuilder $querybuilder;
    private BugReportRepository $bugReportRepository;

    public function setUp(): void
    {
        $this->querybuilder = DbQueryBuilderFactory::make(
            'database',
            'pdo',
            ['db_name' => 'bug-report-testing']
        );

        $this->querybuilder->beginTransaction();
        $this->bugReportRepository = new BugReportRepository($this->querybuilder);

        parent::setUp();
    }

    public function testItCanCreateRecordWithEntity()
    {
        $newBugReport = $this->createBugReport();

        self::assertInstanceOf(BugReport::class, $newBugReport);
        //self::assertNotNull($newBugReport->getId());
        self::assertSame('Type 2', $newBugReport->getReportType());
        self::assertSame('https://testing-link.com', $newBugReport->getLink());
        self::assertSame('This is a dummy message', $newBugReport->getMessage());
        self::assertSame('email@test.com', $newBugReport->getEmail());
    }

    public function testItCanUpdateAGivenEntity()
    {
        $newBugReport = $this->createBugReport();

        $bugReport = $this->bugReportRepository->find($newBugReport->getId());
        $bugReport
            ->setMessage('this is from update method')
            ->setLink('https://newlink.com/image.png');
        $updatedReport = $this->bugReportRepository->update($bugReport);

        self::assertSame('https://newlink.com/image.png', $updatedReport->getLink());
        self::assertSame('this is from update method', $updatedReport->getMessage());
    }

    public function testItCanDeleteAGivenEntity()
    {
        $newBugReport = $this->createBugReport();
        $this->bugReportRepository->delete($newBugReport);
        $bugReport = $this->bugReportRepository->find($newBugReport->getId());
        self::assertNull($bugReport);
    }

    public function testItCanFindByCriteria()
    {
        $newBugReport = $this->createBugReport();
        $report = $this->bugReportRepository->findBy([
            ['report_type', '=', 'Type 2'],
            ['email', '=', 'email@test.com'],
        ]);
        self::assertIsArray($report);

        /**  @var BugReport $bugReport */
        $bugReport = $report[0];
        self::assertSame('Type 2', $bugReport->getReportType());
        self::assertSame('email@test.com', $bugReport->getEmail());
    }

    private function createBugReport(): BugReport
    {
        $bugReport = new BugReport();
        $bugReport->setReportType('Type 2')
            ->setLink('https://testing-link.com')
            ->setMessage('This is a dummy message')
            ->setEmail('email@test.com');

        return $this->bugReportRepository->create($bugReport);
    }

    public function tearDown(): void
    {
        $this->querybuilder->rollback();
        parent::tearDown();
    }
}