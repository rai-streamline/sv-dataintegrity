#!/usr/bin php
<?php
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CredentialMatchesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseRequestParamsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\ChecksCredentialMatchesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseRequestsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\EmployeesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CheckEmployeesRepository;

include "../base_repository.php";
include "repositories/credential_matches_repository.php";
include "repositories/license_request_params_repository.php";
include "repositories/license_requests_repository.php";
include "repositories/checks_credential_matches_repository.php";
include "repositories/employees_repository.php";
include "repositories/check_employees_repository.php";
include "../colors.php";
include "../header.php";
include "../randomizer.php";

//define the target sql migration file to test here
$sqlFileName = '../../../etc/database/migrations/ctr/svg-2636-migrate_to_license_requests.sql';
$showDataValidationDetails = false;

$credentialMatchesRepository = new CredentialMatchesRepository($mysqli, $colors);
$licenseRequestParamsRepository = new LicenseRequestParamsRepository($mysqli, $colors);
$checksCredentialMatchesRepository = new ChecksCredentialMatchesRepository($mysqli, $colors);
$licenseRequestsRepository = new LicenseRequestsRepository($mysqli, $colors);
$employeesRepository = new EmployeesRepository($mysqli, $colors);
$checkEmployeesRepository = new CheckEmployeesRepository($mysqli, $colors);

//performing count on target migration table first
echo $colors->getColoredString('INFO  ', 'blue') . "Executing script: " .
    $colors->getColoredString('migrate_to_license_requests_1_license_request_params', 'cyan') . "\r\n";

/**
 * Now running the migration script
 */
echo $colors->getColoredString('INFO  ', 'blue') .
    "Importing data to " . $colors->getColoredString('license_request_params', 'yellow') .
    " table...\r\n";
$licenseRequestParamsRepository->migrate();

/**
 * Post-checks
 */
//now doing manual validation
echo $colors->getColoredString('INFO  ', 'blue') .
    "Running data validation...\r\n";

//doing count comparison on source and destination table
$sourceCount = $employeesRepository->count();
$destinationCount = $licenseRequestParamsRepository->count();
if ($sourceCount != $destinationCount) {
    echo $colors->getColoredString('ERROR ', 'red') . "Data count failed. A discrepancy of " .
        $colors->getColoredString(abs($sourceCount - $destinationCount), 'red') .
        " was found.\r\n";
    echo $colors->getColoredString('INFO  ', 'blue') .
        "Source Count: $sourceCount\r\n";
    echo $colors->getColoredString('INFO  ', 'blue') .
        "Destination Count: $destinationCount\r\n";
    exit;
}
echo $colors->getColoredString('INFO  ', 'blue') .
    "Initial data count successful. Found " . $colors->getColoredString($destinationCount, 'green') .
    " record/s\r\n";

//check if there are records inserted with invalid registry inserted
if (0 < $licenseRequestParamsRepository->checkInvalidInsertedRegistries()) {
    echo $colors->getColoredString('ERROR ', 'red') . 'There were invalid entries ' .
    'that got inserted. Make sure that the following registry values are not being processed: ' .
    $colors->getColoredString("'NPPES', 'MOO', 'NYOPMC'", 'green') . "\r\n";
    exit;
}
echo $colors->getColoredString('INFO  ', 'blue') .
    "Registry value check successful.\r\n";

if (0 < $licenseRequestParamsRepository->checkNullEntries()) {
    echo $colors->getColoredString('WARN  ', 'red') . 'There are NULL entries found ' .
    'on the following fields: ' .
        $colors->getColoredString("license_number, license_type_id", 'green') . "\r\n";
}
else {
    echo $colors->getColoredString('INFO  ', 'blue') .
        "Null value check successful.\r\n";
}

//proceeding with random data validation
$finalCount = array();
//start with employees, check if employee has id on join table
$employees = $employeesRepository->randomPick(5, true);
foreach ($employees as $employee) {
    $checkEmployeeResult = $checkEmployeesRepository->find($employee['id']);

    if (0 < count($checkEmployeeResult)) {

        foreach ($checkEmployeeResult as $checkEmployee) {
            if ($showDataValidationDetails) {
                echo $colors->getColoredString('INFO  ', 'blue') . "Employee ID " .
                    $colors->getColoredString($employee['id'], 'green') .
                    " found on " . $colors->getColoredString('check_employees', 'yellow') .
                    " table.\r\n";
            }

            hasChecksCredentialsMatches($checkEmployee[1]);
        }
    }
    else {
        if ($showDataValidationDetails) {
            echo $colors->getColoredString('WARN  ', 'red') . "Data not processed. Employee ID " .
                $colors->getColoredString($employee['id'], 'green') .
                " not found under " . $colors->getColoredString('check_employees', 'yellow') .
                " table.\r\n";
        }
    }
}

function hasChecksCredentialsMatches($checkID)
{
    global $checksCredentialMatchesRepository, $colors, $showDataValidationDetails;

    $result = $checksCredentialMatchesRepository->findByCheckID($checkID);

    if (0 < count($result)) {
        if ($showDataValidationDetails) {
            echo $colors->getColoredString(' --> INFO  ', 'blue') . "Check ID " .
                $colors->getColoredString($checkID, 'green') .
                " found on " . $colors->getColoredString('checks_credential_matches', 'yellow') .
                " table.\r\n";
        }
        hasCredentialsMatches($result['credential_match_id']);
    }
    else {
        if ($showDataValidationDetails) {
            echo $colors->getColoredString(' --> WARN  ', 'red') . "Data not processed. Check ID " .
                $colors->getColoredString($checkID, 'green') .
                " not found under " . $colors->getColoredString('checks_credential_matches', 'yellow') .
                " table.\r\n";
        }
    }
}

function hasCredentialsMatches($credentialMatchID)
{
    global $credentialMatchesRepository, $colors, $finalCount, $showDataValidationDetails;

    $result = $credentialMatchesRepository->findByCredentialMatchID($credentialMatchID);

    if (0 < count($result)) {
        if ($showDataValidationDetails) {
            echo $colors->getColoredString(' ----> INFO  ', 'blue') . "Credential Match ID " .
                $colors->getColoredString($credentialMatchID, 'green') .
                " found on " . $colors->getColoredString('credential_matches', 'yellow') .
                " table.\r\n";
        }

        $finalCount[] = $credentialMatchID;
    }
    else {
        if ($showDataValidationDetails) {
            echo $colors->getColoredString(' ----> WARN  ', 'red') .
                "Data not processed. Credential Match ID " .
                $colors->getColoredString($credentialMatchID, 'green') .
                " not found under " . $colors->getColoredString('credential_matches', 'yellow') .
                " table.\r\n";
        }

    }
}
if (!$showDataValidationDetails) {
    echo $colors->getColoredString('INFO  ', 'blue') .
        "Set " . $colors->getColoredString('showDataValidationDetails', 'green') .
        " variable to " . $colors->getColoredString('true', 'green') .
        " if you wish to show actual data comparison.\r\n";
}
$finalCount = array_unique($finalCount);
echo $colors->getColoredString('INFO  ', 'blue') . "Total count after validation: " .
    $colors->getColoredString(count($finalCount), 'green') .
    " successfully migrated.\r\n";