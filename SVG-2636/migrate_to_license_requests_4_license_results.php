<?php
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseResultsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseResultsDataRepository;

include "../base_repository.php";
include "../colors.php";
include "../header.php";
include "repositories/license_results_repository.php";
include "repositories/license_results_data_repository.php";
include "../randomizer.php";

//define the target sql migration file to test here
$sqlFileName = '../../../etc/database/migrations/ctr/svg-2636-migrate_to_license_requests.sql';
$showDataValidationDetails = false;

$licenseResultsRepository = new LicenseResultsRepository($mysqli, $colors);
$licenseResultsDataRepository = new LicenseResultsDataRepository($mysqli, $colors);

//performing count on target migration table first
echo $colors->getColoredString('INFO  ', 'blue') . "Executing script: " .
    $colors->getColoredString('migrate_to_license_requests_4_license_results', 'cyan') . "\r\n";

/**
 * Pre-count checks
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Doing pre-count for source table: " .
    $colors->getColoredString('check_credential_matches', 'yellow'). "\r\n";
$licenseResultsDataCount = $licenseResultsDataRepository->countForLicenseResultsMigration();
echo $colors->getColoredString('INFO  ', 'blue') . "Found " .
    $colors->getColoredString($licenseResultsDataCount, 'green').
    " records for migration.\r\n";

/**
 * Migration Script
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Now running migration script...\r\n";
$licenseResultsRepository->migrate();
/**
 * Post-checks
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Doing post-count...\r\n";
$licenseResults = $licenseResultsRepository->count();

if ($licenseResults != $licenseResultsDataCount) {
    echo $colors->getColoredString('ERROR ', 'red') . 'There is a discrepancy during count of' .
        $colors->getColoredString(abs($licenseResults - $licenseResultsDataCount),
            'green') . "for table " .
        $colors->getColoredString('license_results', 'yellow') . "\r\n";
    exit;
}
/**
 * Data check
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Doing post-data check...\r\n\r\n";
$results = $licenseResultsRepository->randomPick(5);
echo $colors->getColoredString('INFO  ', 'blue') . "Done\r\n\r\n";

$licenseResultsRepository->checkData($results);

echo $colors->getColoredString('INFO  ', 'blue') . "Successful migration for " .
    $colors->getColoredString('license_results', 'yellow'). " table.\r\n";
$licenseResults = $licenseResultsRepository->count();