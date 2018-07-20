<?php
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\NPIRequestsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\ChecksCredentialMatchesRepository;

include "../base_repository.php";
include "../colors.php";
include "../header.php";
include "repositories/npi_requests_repository.php";
include "repositories/checks_credential_matches_repository.php";
include "../randomizer.php";

//define the target sql migration file to test here
$sqlFileName = '../../../etc/database/migrations/ctr/svg-2636-migrate_to_npi_requests.sql';
$showDataValidationDetails = false;

$NPIRequestsRepository = new NPIRequestsRepository($mysqli, $colors);
$checksCredentialMatchesRepository = new ChecksCredentialMatchesRepository($mysqli, $colors);


//performing count on target migration table first
echo $colors->getColoredString('INFO  ', 'blue') . "Executing script: " .
    $colors->getColoredString('migrate_to_npi_requests_1_npi_requests', 'cyan') . "\r\n";

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
$NPIRequestsRepository->migrate();
/**
 * Post-checks
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Doing post-count...\r\n";
$NPIRequestsCount = $NPIRequestsRepository->count();

if ($checksCredentialMatchesCount != $NPIRequestsCount) {
    echo $colors->getColoredString('ERROR ', 'red') . 'There is a discrepancy during count of ' .
        $colors->getColoredString(abs($checksCredentialMatchesCount - $NPIRequestsCount),
            'green') . " for table " .
        $colors->getColoredString('npi_requests', 'yellow') . "\r\n";
    exit;
}
/**
 * Data check
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Doing post-data check...\r\n\r\n";
$results = $NPIRequestsRepository->randomPick(5);
echo $colors->getColoredString('INFO  ', 'blue') . "Done\r\n\r\n";

$NPIRequestsRepository->checkData($results);

echo $colors->getColoredString('INFO  ', 'blue') . "Successful migration for " .
    $colors->getColoredString('npi_requests', 'yellow'). " table.\r\n\r\n";

echo $colors->getColoredString('INFO  ', 'blue') . "All tests ran successfully!\r\n";
