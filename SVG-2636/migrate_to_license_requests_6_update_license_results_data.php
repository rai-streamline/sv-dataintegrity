<?php
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseResultsDataRepository;

include "../base_repository.php";
include "../colors.php";
include "../header.php";
include "repositories/license_results_images_repository.php";
include "repositories/license_results_data_repository.php";
include "../randomizer.php";

//define the target sql migration file to test here
$sqlFileName = '../../../etc/database/migrations/ctr/svg-2636-migrate_to_license_requests.sql';
$showDataValidationDetails = false;

$licenseResultsDataRepository = new LicenseResultsDataRepository($mysqli, $colors);

//performing count on target migration table first
echo $colors->getColoredString('INFO  ', 'blue') . "Executing script: " .
    $colors->getColoredString('migrate_to_license_requests_6_update_license_results_data', 'cyan') . "\r\n";

/**
 * Migration Script
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Now running migration script...\r\n";
$licenseResultsDataRepository->migrateUpdateImageField();

/**
 * Data check
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Validating if image field is still present " .
    "on raw data...\r\n";
if (0 < $licenseResultsDataRepository->validateIfImageFieldIsStillPresent()) {
    echo $colors->getColoredString('ERROR ', 'red') . 'Still found records with image field ' .
        " on raw data not removed on table " .
        $colors->getColoredString('license_results_data', 'yellow') . "\r\n";
    exit;
}

echo $colors->getColoredString('INFO  ', 'blue') . "Successful migration for " .
    $colors->getColoredString('license_results_data', 'yellow'). " table.\r\n";
