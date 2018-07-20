<?php
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\NPIResultsDataRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\ChecksCredentialMatchesRepository;

include "../base_repository.php";
include "../colors.php";
include "../header.php";
include "repositories/npi_results_data_repository.php";
include "repositories/checks_credential_matches_repository.php";
include "../randomizer.php";

//define the target sql migration file to test here
$sqlFileName = '../../../etc/database/migrations/ctr/svg-2636-migrate_to_npi_requests.sql';
$showDataValidationDetails = false;

$NPIResultsDataRepository = new NPIResultsDataRepository($mysqli, $colors);
$checksCredentialMatchesRepository = new ChecksCredentialMatchesRepository($mysqli, $colors);

//performing count on target migration table first
echo $colors->getColoredString('INFO  ', 'blue') . "Executing script: " .
    $colors->getColoredString('migrate_to_npi_requests_2_npi_results_data', 'cyan') . "\r\n";

/**
 * Pre-count checks
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Doing pre-count for source table: " .
    $colors->getColoredString('checks_credential_matches', 'yellow'). "\r\n";
$checksCredentialMatchesCount = $checksCredentialMatchesRepository->countForNPIRequestsRepository();
echo $colors->getColoredString('INFO  ', 'blue') . "Found " .
    $colors->getColoredString($checksCredentialMatchesCount, 'green').
    " records for migration.\r\n";

/**
 * Migration Script
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Now running migration script...\r\n";
$NPIResultsDataRepository->migrate();
/**
 * Post-checks
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Doing post-count...\r\n";
$NPIResultsDataCount = $NPIResultsDataRepository->count();

if ($checksCredentialMatchesCount != $NPIResultsDataCount) {
    echo $colors->getColoredString('ERROR ', 'red') . 'There is a discrepancy during count of ' .
        $colors->getColoredString(abs($checksCredentialMatchesCount - $NPIResultsDataCount),
            'green') . " for table " .
        $colors->getColoredString('npi_results_data', 'yellow') . "\r\n";
    exit;
}

echo $colors->getColoredString('INFO  ', 'blue') . "Successful migration for " .
    $colors->getColoredString('npi_results_data', 'yellow'). " table.\r\n\r\n";

echo $colors->getColoredString('INFO  ', 'blue') . "All tests ran successfully!\r\n";
