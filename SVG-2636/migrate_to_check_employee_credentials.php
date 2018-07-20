<?php


use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CheckEmployeeCredentialsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseRequestParamsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\ChecksRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CredentialMatchesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\ChecksCredentialMatchesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseRequestsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseRegistriesCheckedRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseResultsDataRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseResultsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseResultsImagesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseResultsResolutionsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CredentialMatchResolutionsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\NPIResultsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\NPIRequestsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\NPIResultsDataRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\NPIResultsResolutionsRepository;

include "../base_repository.php";
include "repositories/check_employee_credentials_repository.php";
include "repositories/license_request_params_repository.php";
include "repositories/checks_credential_matches_repository.php";
include "repositories/checks_repository.php";
include "repositories/credential_matches_repository.php";
include "repositories/license_requests_repository.php";
include "repositories/license_registries_checked_repository.php";
include "repositories/license_results_data_repository.php";
include "repositories/license_results_repository.php";
include "repositories/license_results_images_repository.php";
include "repositories/license_results_resolutions_repository.php";
include "repositories/credential_match_resolutions_repository.php";
include "repositories/npi_requests_repository.php";
include "repositories/npi_results_data_repository.php";
include "repositories/npi_results_repository.php";
include "repositories/npi_results_resolutions_repository.php";
include "../colors.php";
include "../header.php";
include "../randomizer.php";

//define the target sql migration file to test here
$sqlFileName = '../../../etc/database/migrations/ctr/svg-2636-migrate_to_check_employee_credentials.sql';
$showDataValidationDetails = false;

$checkEmployeeCredentialsRepository = new CheckEmployeeCredentialsRepository($mysqli, $colors);
$checksCredentialMatchesRepository = new ChecksCredentialMatchesRepository($mysqli, $colors);
$checksRepository = new ChecksRepository($mysqli, $colors);
$credentialMatchesRepository = new CredentialMatchesRepository($mysqli, $colors);
$licenseRequestsRepository = new LicenseRequestsRepository($mysqli, $colors);
$licenseRequestParamsRepository = new LicenseRequestParamsRepository($mysqli, $colors);
$licenseRegistriesCheckedRepository = new LicenseRegistriesCheckedRepository($mysqli, $colors);
$licenseResultsDataRepository = new LicenseResultsDataRepository($mysqli, $colors);
$licenseResultsRepository= new LicenseResultsRepository($mysqli, $colors);
$licenseResultsImagesRepository = new LicenseResultsImagesRepository($mysqli, $colors);
$licenseResultsResolutionsRepository = new LicenseResultsResolutionsRepository($mysqli, $colors);
$credentialMatchResolutionsRepository = new CredentialMatchResolutionsRepository($mysqli, $colors);
$NPIResultsRepository = new NPIResultsRepository($mysqli, $colors);
$NPIResultsResolutionsRepository = new NPIResultsResolutionsRepository($mysqli, $colors);
$NPIRequestsRepository = new NPIRequestsRepository($mysqli, $colors);
$NPIResultsDataRepository = new NPIResultsDataRepository($mysqli, $colors);

/**
 * Doing cleanup first to remove any constraints
 */
$NPIResultsResolutionsRepository->truncate();
$NPIResultsRepository->truncate();
$NPIResultsDataRepository->truncate();
$NPIResultsDataRepository->dropTemporaryTable();
$NPIRequestsRepository->truncate();
$NPIRequestsRepository->dropTemporaryTable();
$licenseResultsResolutionsRepository->truncate();
$licenseResultsImagesRepository->truncate();
$licenseResultsRepository->dropTemporaryTable();
$licenseResultsRepository->truncate();
$licenseResultsDataRepository->truncate();
$licenseResultsDataRepository->dropTemporaryTable();
$licenseRegistriesCheckedRepository->truncate();
$licenseRequestsRepository->truncate();
$licenseRequestsRepository->dropTemporaryTable();
$licenseRequestParamsRepository->truncate();
$checkEmployeeCredentialsRepository->truncate();

//performing count on target migration table first
echo $colors->getColoredString('INFO  ', 'blue') . "Executing script: " .
    $colors->getColoredString('migrate_to_check_employee_credentials', 'cyan') . "\r\n";

/**
 * Pre-checks
 */
//performing count on target migration table first
echo $colors->getColoredString('INFO  ', 'blue') . "Performing before count on target table: " .
    $colors->getColoredString('check_employee_credentials', 'yellow') . "\r\n";
$target_table_count = $checkEmployeeCredentialsRepository->count();

echo $colors->getColoredString('INFO  ', 'blue') . "Found: " .
    $colors->getColoredString($target_table_count . ' record/s', 'green') . " on table " .
    $colors->getColoredString('check_employee_credentials', 'yellow') . "\r\n";

//empty target table if it has more than one record, as we will need a
// clean slate to validate count against source table
if (0 < $target_table_count) {
    echo $colors->getColoredString('WARN  ', 'yellow') . "Need to empty target table first\r\n";
    $checkEmployeeCredentialsRepository->truncate();
}

//now performing count on source table
echo $colors->getColoredString('INFO  ', 'blue') . "Performing count on source table: " .
    $colors->getColoredString('checks', 'yellow') . "\r\n";
$sourceTableCount = $checksRepository->count();

echo $colors->getColoredString('INFO  ', 'blue') . "Found: " .
    $colors->getColoredString($sourceTableCount . ' record/s', 'green') . " on table " .
    $colors->getColoredString('checks', 'yellow') . " and its join tables.\r\n";
if (0 >= $sourceTableCount) {
    //source table has no records on it, we need to populate it first
    echo $colors->getColoredString('WARN  ', 'yellow') .
        "Source table needs to be populated, Populating...\r\n";
    $checksRepository->generateSampleData();
}



//now performing counts on join tables

//first join table: credential_matches
echo $colors->getColoredString('INFO  ', 'blue') . "Performing count on join table: " .
    $colors->getColoredString('credential_matches', 'yellow') . "\r\n";
$joinTableCount = $credentialMatchesRepository->count();

echo $colors->getColoredString('INFO  ', 'blue') . "Found: " .
    $colors->getColoredString($joinTableCount . ' record/s', 'green') . " on table " .
    $colors->getColoredString('credential_matches', 'yellow') . "\r\n";
//join table has no records on it, we need to populate it first
if (0 >= $joinTableCount) {
    echo $colors->getColoredString('WARN  ', 'yellow') . "Join table " .
        $colors->getColoredString('credential_matches', 'yellow') .
        " needs to be populated. Populating...\r\n";
    $credentialMatchesRepository->generateSampleData();
}

//second join table: checks_credential_matches
echo $colors->getColoredString('INFO  ', 'blue') . "Performing count on join table: " .
    $colors->getColoredString('checks_credential_matches', 'yellow') . "\r\n";
$joinTableCount = $checksCredentialMatchesRepository->count();
echo $colors->getColoredString('INFO  ', 'blue') . "Found: " .
    $colors->getColoredString($joinTableCount . ' record/s', 'green') . " on table " .
    $colors->getColoredString('checks_credential_matches', 'yellow') . "\r\n";
//join table has no records on it, we need to populate it first
if (0 >= $joinTableCount) {
    echo $colors->getColoredString('WARN  ', 'yellow') . "Join table " .
        $colors->getColoredString('checks_credential_matches', 'yellow') .
        " needs to be populated. Populating...\r\n";
    $checksCredentialMatchesRepository->generateSampleData();
}


//now performing count on credential match resolutions table
echo $colors->getColoredString('INFO  ', 'blue') . "Performing count on source table: " .
    $colors->getColoredString('credential_match_resolutions', 'yellow') . "\r\n";
$credentialMatchResolutionsCount = $credentialMatchResolutionsRepository->count();

echo $colors->getColoredString('INFO  ', 'blue') . "Found: " .
    $colors->getColoredString($credentialMatchResolutionsCount . ' record/s', 'green') . " on table " .
    $colors->getColoredString('credential_match_resolutions', 'yellow') . " and its join tables.\r\n";
if (0 >= $credentialMatchResolutionsCount) {
    /**
     * Populate credential_resolutions table first first
     */
    echo $colors->getColoredString('INFO  ', 'blue') . "Populating " .
        $colors->getColoredString('credential_match_resolutions', 'yellow'). " table...\r\n";
    $credentialMatchResolutionsRepository->generateSampleData();
}

/**
 * Once everything is prepared, run the migration script
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Now running the migration script...\r\n";
$script = file_get_contents($sqlFileName);
$script = preg_replace('/--.*/', '', $script);
$mysqli->query($script) or die($mysqli->error);


echo $colors->getColoredString('INFO  ', 'blue') . "Script successfully executed.\r\n";

/**
 * Post-checks
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Preparing results...\r\n";

//after count(check_employee_credentials)
echo $colors->getColoredString('INFO  ', 'blue') . "Performing after count on target table: " .
    $colors->getColoredString('check_employee_credentials', 'yellow') . "\r\n";
$target_table_count = $checkEmployeeCredentialsRepository->count();

echo $colors->getColoredString('INFO  ', 'blue') . "Successfully imported " .
    $colors->getColoredString($target_table_count . ' record/s', 'green') . " on table " .
    $colors->getColoredString('check_employee_credentials', 'yellow') . "\r\n";
$sourceTableCount = $checkEmployeeCredentialsRepository->countForMigration();
//count comparison
if ($sourceTableCount != $target_table_count) {
    echo $colors->getColoredString('ERROR ', 'red') . "Data count failed. A discrepancy of " .
        $colors->getColoredString(abs($target_table_count - $sourceTableCount), 'red') .
        " was found.\r\n";
    exit;
} else {
    echo $colors->getColoredString('INFO  ', 'blue') .
        "Data count successful. See import count.\r\n";
}

//Todo: do random data comparison
echo $colors->getColoredString('INFO  ', 'blue') . "Now performing data comparison...\r\n";
$results = $checkEmployeeCredentialsRepository->randomPick();

if ($showDataValidationDetails) {
    foreach ($results as $result) {
        $match = $checksRepository->validate($result[1], $result[2], $result[3]);
        if (0 >= $mysqli->field_count) {
            echo $colors->getColoredString('ERROR ', 'red') .
                "Data did not match. See data below.\r\n";
            var_dump($result);
            exit;
        } else {
            echo $colors->getColoredString('INFO  ', 'blue') . "Random data match successful.\r\n";
            echo $colors->getColoredString('INFO  ', 'blue') . "Imported data:\r\n";
            var_dump($result);
            echo $colors->getColoredString('INFO  ', 'blue') . "Source data:\r\n";
            var_dump($match->fetch_assoc());
        }
    }
} else {
    echo $colors->getColoredString('INFO  ', 'blue') . "Random data match successful.\r\n";
    echo $colors->getColoredString('INFO  ', 'blue') .
        "Set " . $colors->getColoredString('showDataValidationDetails', 'green') .
        " variable to " . $colors->getColoredString('true', 'green') .
        " if you wish to show actual data comparison.\r\n";
}

$mysqli->close();