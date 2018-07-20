<?php
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseResultsResolutionsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseResultsDataRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CredentialMatchResolutionsRepository;

include "../base_repository.php";
include "../colors.php";
include "../header.php";
include "repositories/license_results_resolutions_repository.php";
include "repositories/license_results_data_repository.php";
include "repositories/credential_match_resolutions_repository.php";
include "../randomizer.php";

//define the target sql migration file to test here
$sqlFileName = '../../../etc/database/migrations/ctr/svg-2636-migrate_to_license_requests.sql';
$showDataValidationDetails = false;

$licenseResultsResolutionsRepository = new LicenseResultsResolutionsRepository($mysqli, $colors);
$licenseResultsDataRepository = new LicenseResultsDataRepository($mysqli, $colors);
$credentialMatchResolutionsRepository = new CredentialMatchResolutionsRepository($mysqli, $colors);

//performing count on target migration table first
echo $colors->getColoredString('INFO  ', 'blue') . "Executing script: " .
    $colors->getColoredString('migrate_to_license_requests_7_license_results_resolutions', 'cyan') . "\r\n";



/**
 * Pre-count checks
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Doing pre-count for source table: " .
    $colors->getColoredString('check_credential_matches', 'yellow'). "\r\n";
$licenseResultsDataCount = $licenseResultsDataRepository->countForLicenseResultsResolutionsMigration();
echo $colors->getColoredString('INFO  ', 'blue') . "Found " .
    $colors->getColoredString($licenseResultsDataCount, 'green').
    " records for migration.\r\n";

/**
 * Migration Script
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Now running migration script...\r\n";
$licenseResultsResolutionsRepository->migrate();
/**
 * Post-checks
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Doing post-count...\r\n";
$licenseResultsResolutionsCount = $licenseResultsResolutionsRepository->count();

if ($licenseResultsResolutionsCount != $licenseResultsDataCount) {
    echo $colors->getColoredString('ERROR ', 'red') . 'There is a discrepancy during count of' .
        $colors->getColoredString(abs($licenseResultsResolutionsCount - $licenseResultsDataCount),
            'green') . "for table " .
        $colors->getColoredString('license_results_images', 'yellow') . "\r\n";
    exit;
}

echo $colors->getColoredString('INFO  ', 'blue') . "Successful migration for " .
    $colors->getColoredString('license_results_resolutions', 'yellow'). " table.\r\n\r\n";

echo $colors->getColoredString('INFO  ', 'blue') . "All tests ran successfully!\r\n";
