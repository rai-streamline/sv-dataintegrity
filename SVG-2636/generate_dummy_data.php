<?php
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CheckEmployeeCredentialsRepository;
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
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\EmployeesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseRequestParamsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CheckEmployeesRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\GenerateDummyDataUtility;

include "../base_repository.php";
include "repositories/check_employee_credentials_repository.php";
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
include "repositories/employees_repository.php";
include "repositories/license_request_params_repository.php";
include "repositories/check_employees_repository.php";
include "../randomizer.php";
include "../colors.php";
include "../header.php";
include "generate_dummy_data_utility.php";

echo $colors->getColoredString('                              *** Dummy Data Generator for CTR Migration ***  ', 'red') . "\r\n";
echo $colors->getColoredString('WARN  ', 'yellow') .
    "Running this script remove records from the database in preparation for migration. Are you sure you want to do this?  Type " .
    $colors->getColoredString("'yes'", 'green') . " to continue: ";

$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim($line) != 'yes') {
    echo $colors->getColoredString('ERROR ', 'red') . "ABORTING!\r\n\r\n";
    exit;
}
fclose($handle);
echo $colors->getColoredString('INFO  ', 'blue') . "Input number of records to generate: ";
$handle = fopen("php://stdin", "r");
$recordCount = (int) trim(fgets($handle));
if (!is_int($recordCount)) {
    echo $recordCount;
    echo $colors->getColoredString('ERROR ', 'red') . "ABORTING!\r\n\r\n";
    exit;
}
fclose($handle);

$checkEmployeeCredentialsRepository = new CheckEmployeeCredentialsRepository($mysqli, $colors);
$checksCredentialMatchesRepository = new ChecksCredentialMatchesRepository($mysqli, $colors);
$checksRepository = new ChecksRepository($mysqli, $colors);
$credentialMatchesRepository = new CredentialMatchesRepository($mysqli, $colors);
$licenseRequestsRepository = new LicenseRequestsRepository($mysqli, $colors);
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
$employeesRepository = new EmployeesRepository($mysqli, $colors);
$licenseRequestParamsRepository = new LicenseRequestParamsRepository($mysqli, $colors);
$checkEmployeesRepository = new CheckEmployeesRepository($mysqli, $colors);

/**
 * Doing cleanup first to remove any constraints
 */
echo $colors->getColoredString('INFO  ', 'blue') .
    "Deleting data on affected tables...\r\n";
$NPIResultsResolutionsRepository->truncate();
$NPIResultsRepository->truncate();
$NPIResultsDataRepository->truncate();
$NPIResultsDataRepository->dropTemporaryTable();
$NPIRequestsRepository->truncate();
$NPIRequestsRepository->dropTemporaryTable();
$credentialMatchResolutionsRepository->truncate();
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
$checksCredentialMatchesRepository->truncate();
$credentialMatchesRepository->truncate();
$checkEmployeesRepository->reset();
$checksRepository->truncate();
$employeesRepository->deleteEmployees();


echo $colors->getColoredString('INFO  ', 'blue') .
    "Done.\r\n";

$generateDummyDataUtility = new GenerateDummyDataUtility($colors, $recordCount);
$generateDummyDataUtility->generate($employeesRepository, 'employees');
$generateDummyDataUtility->generate($checksRepository, 'checks');
$generateDummyDataUtility->generate($checkEmployeesRepository, 'check_employees');
$generateDummyDataUtility->generate($credentialMatchesRepository, 'credential_matches');
$generateDummyDataUtility->generate($credentialMatchResolutionsRepository, 'credential_match_resolutions');
$generateDummyDataUtility->generate($checksCredentialMatchesRepository, 'checks_credential_matches');

echo $colors->getColoredString('INFO  ', 'blue') .
    "Dummy data generated successfully.\r\n";