<?php
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CredentialMatchesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseRequestParamsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\ChecksCredentialMatchesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseRequestsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\EmployeesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CheckEmployeesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\ChecksRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CheckEmployeeCredentialsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseRegistriesCheckedRepository;

include '../base_repository.php';
include 'repositories/credential_matches_repository.php';
include 'repositories/license_request_params_repository.php';
include 'repositories/license_requests_repository.php';
include 'repositories/checks_credential_matches_repository.php';
include 'repositories/employees_repository.php';
include 'repositories/check_employees_repository.php';
include 'repositories/checks_repository.php';
include 'repositories/check_employee_credentials_repository.php';
include 'repositories/license_registries_checked_repository.php';
include '../colors.php';
include '../header.php';
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
$checksRepository = new ChecksRepository($mysqli, $colors);
$checkEmployeeCredentialsRepository = new CheckEmployeeCredentialsRepository($mysqli, $colors);
$licenseRegistriesCheckedRepository = new LicenseRegistriesCheckedRepository($mysqli, $colors);

//performing count on target migration table first
echo $colors->getColoredString('INFO  ', 'blue') . "Executing script: " .
    $colors->getColoredString('migrate_to_license_requests_2_license_requests', 'cyan') . "\r\n";

/**
 * Pre-count checks
 */
echo $colors->getColoredString('INFO  ', 'blue') . 'Doing pre-count for source table: ' .
    $colors->getColoredString('checks_credential_matches_repository', 'yellow'). "\r\n";
$licenseRequestsMigrationCount = $checksCredentialMatchesRepository->countForLicenseRequestsMigration();
echo $colors->getColoredString('INFO  ', 'blue') . 'Found ' .
    $colors->getColoredString($licenseRequestsMigrationCount, 'green').
    " records for migration.\r\n";

/**
 * Migration Script
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Now running migration script...\r\n";
$licenseRequestsRepository->migrate();

/**
 * Post-checks
 */
echo $colors->getColoredString('INFO  ', 'blue') . "Doing post-count...\r\n";

$licenseRequestsCount = $licenseRequestsRepository->count();
if ($licenseRequestsMigrationCount != $licenseRequestsCount) {
    echo $colors->getColoredString('ERROR ', 'red') . 'There is a discrepancy during count of' .
        $colors->getColoredString(abs($licenseRequestsMigrationCount - $licenseRequestsCount),
            'green') . 'for table ' .
        $colors->getColoredString('license_requests', 'yellow') . "\r\n";
    exit;
}
echo $colors->getColoredString('INFO  ', 'blue') . 'Post count for target table ' .
    $colors->getColoredString('license_requests', 'yellow'). " successful\r\n";

$licenseRegistriesCheckedCount = $licenseRegistriesCheckedRepository->count();
if ($licenseRequestsMigrationCount != $licenseRegistriesCheckedCount) {
    echo $colors->getColoredString('ERROR ', 'red') . 'There is a discrepancy during count of ' .
        $colors->getColoredString(abs($licenseRequestsMigrationCount - $licenseRegistriesCheckedCount),
            'green') . ' for table ' .
        $colors->getColoredString('license_registries_checked', 'yellow') . "\r\n";
    exit;
}

echo $colors->getColoredString('INFO  ', 'blue') . 'Post count for target table ' .
    $colors->getColoredString('license_registries_checked', 'yellow'). " successful\r\n";

//random data validation

//license_requests table
$licenseRequests = $licenseRequestsRepository->randomPick(5, true);
echo $colors->getColoredString('INFO  ', 'blue') . 'Running data validation for table ' .
    $colors->getColoredString('license_requests', 'yellow'). "...\r\n";
foreach ($licenseRequests as $licenseRequest) {

//    echo $colors->getColoredString('INFO  ', 'blue') . 'Validating ID ' .
//        $colors->getColoredString($licenseRequest['id'], 'green'). "...\r\n";

    $licenseRequestParams = $licenseRequestParamsRepository->fetch($licenseRequest['license_request_param_id']);
    if (0 >= count($licenseRequestParams)) {
        echo $colors->getColoredString('ERROR ', 'red') . 'Record missing for field ' .
            $colors->getColoredString('license_request_param_id',
                'green') . 'with reference ID of ' .
            $colors->getColoredString($licenseRequest['license_request_param_id'],
                'green') . 'for source table ' .
            $colors->getColoredString('license_request_params', 'yellow') . "\r\n";
        exit;
    }

    $checkEmployeeCredentials = $checkEmployeeCredentialsRepository->fetch($licenseRequest['check_employee_credential_id']);
    if (0 >= count($checkEmployeeCredentials)) {
        echo $colors->getColoredString('ERROR ', 'red') . 'Record missing for field ' .
            $colors->getColoredString('check_employee_credential_id',
                'green') . 'with reference ID of ' .
            $colors->getColoredString($licenseRequest['check_employee_credential_id'],
                'green') . 'for source table ' .
            $colors->getColoredString('check_employee_credentials', 'yellow') . "\r\n";
        exit;
    }

//    echo $colors->getColoredString('INFO  ', 'blue') . 'Successful record validation for ID ' .
//        $colors->getColoredString($licenseRequest['id'], 'green'). ".\r\n";
}


//license_registries_checked table
$licenseRegistriesChecked = $licenseRegistriesCheckedRepository->randomPick(5, true);
echo $colors->getColoredString('INFO  ', 'blue') . 'Running data validation for table ' .
    $colors->getColoredString('license_registries_checked', 'yellow'). "...\r\n";
foreach ($licenseRegistriesChecked as $licenseRegistryChecked) {

//    echo $colors->getColoredString('INFO  ', 'blue') . 'Validating ID ' .
//        $colors->getColoredString($licenseRegistryChecked['id'], 'green'). "...\r\n";

    $licenseRequests= $licenseRequestsRepository->fetch($licenseRegistryChecked['license_request_id']);
    if (0 >= count($licenseRequests)) {
        echo $colors->getColoredString('ERROR ', 'red') . 'Record missing for field ' .
            $colors->getColoredString('license_request_id',
                'green') . 'with reference ID of ' .
            $colors->getColoredString($licenseRegistryChecked['license_request_id'],
                'green') . 'for source table ' .
            $colors->getColoredString('license_requests', 'yellow') . "\r\n";
        exit;
    }

//    echo $colors->getColoredString('INFO  ', 'blue') . 'Successful record validation for ID ' .
//        $colors->getColoredString($licenseRegistryChecked['id'], 'green'). ".\r\n";
}

echo $colors->getColoredString('INFO  ', 'blue') . 'Done.';

echo $colors->getColoredString('INFO  ', 'blue') . "Successful migration for " .
    $colors->getColoredString('license_requests', 'yellow'). " table.\r\n";