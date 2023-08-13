<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Repository\BugReportRepository;
use App\Helpers\DbQueryBuilderFactory;
use App\Logger\Logger;
use App\Exception\BadRequestException;

if (isset($_POST, $_POST['delete'])) {
    $reportId = $_POST['reportId'];

    $logger = new Logger;

    try {
        $queryBuilder = DbQueryBuilderFactory::make(
            'database',
            'pdo',
            ['db_name' => 'bug-report-testing']
        );
        $repository = new BugReportRepository($queryBuilder);

        $bugReport = $repository->find((int) $reportId);

        $repository->delete($bugReport);

    } catch(Throwable $exception) {
        $logger->critical($exception->getMessage(), $_POST);
        throw new BadRequestException($exception->getMessage(), $_POST, 400);
    }

    $logger->info(
        'new bug report deleted',
        ['id' => $bugReport->getId(), 'type' => $bugReport->getReportType()]
    );

    //$bugReports = $repository->findAll();
}