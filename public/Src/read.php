<?php
declare(strict_types=1);

use App\Helpers\DbQueryBuilderFactory;
use App\Repository\BugReportRepository;

$queryBuilder = DbQueryBuilderFactory::make(
    'database',
    'pdo',
    ['db_name' => 'bug-report-testing']
);

$repository = new BugReportRepository($queryBuilder);

$bugReports = $repository->findAll();