<?php
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
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\EmployeesAuditLogRepository;

use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\GenerateDummyDataUtility;


include "../base_repository.php";
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
include "repositories/employees_audit_log_repository.php";

include "../randomizer.php";
include "../colors.php";
include "../header.php";
include "generate_dummy_data_utility.php";

echo $colors->getColoredString('                           RUNNING THIS SCRIPT WILL CLEANUP THE TARGET TABLES FOR MIGRATION!!!',
        'red') . "\r\n\r\n";

echo $colors->getColoredString('                                   *** Dummy Data Generator for CTR Migration ***  ', 'red') . "\r\n";
echo $colors->getColoredString('WARN  ', 'yellow') .
    "Running this script will remove records from the database as part of the migration dryrun. Are you sure you want to do this?  Type " .
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
$employeesAuditLogRepository = new EmployeesAuditLogRepository($mysqli, $colors);

$mysqli->query('SET autocommit=0;');
$mysqli->query('SET unique_checks=0;');
$mysqli->query('SET foreign_key_checks=0;');
/**
 * Doing cleanup first to remove any constraints
 */


echo $colors->getColoredString('INFO  ', 'blue') .
    "Deleting data on affected tables...\r\n";

$NPIResultsResolutionsRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on results_resolutions.\r\n";

$NPIResultsRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on results.\r\n";

$NPIResultsDataRepository->truncate();
$NPIResultsDataRepository->dropTemporaryTable();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on results_data...\r\n";

$NPIRequestsRepository->truncate();
$NPIRequestsRepository->dropTemporaryTable();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on npi_requests...\r\n";

$credentialMatchResolutionsRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on credential_match_resolutions...\r\n";

$licenseResultsResolutionsRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on license_results_resolutions...\r\n";

$licenseResultsImagesRepository->truncate();
$licenseResultsRepository->dropTemporaryTable();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on license_results_images...\r\n";
$licenseResultsRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on license_results...\r\n";
$licenseResultsDataRepository->truncate();
$licenseResultsDataRepository->dropTemporaryTable();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on license_results_data...\r\n";

$licenseRegistriesCheckedRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on license_registries_checked...\r\n";

$licenseRequestsRepository->truncate();
$licenseRequestsRepository->dropTemporaryTable();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on license_requests...\r\n";

$licenseRequestParamsRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on license_request_params...\r\n";
$checksCredentialMatchesRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on employee_matches...\r\n";

$credentialMatchesRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on credential_matches...\r\n";

$checkEmployeesRepository->truncate();
$checksRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on checks...\r\n";

$employeesAuditLogRepository->truncate();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on employees_audit_logs...\r\n";

$employeesRepository->deleteEmployees();
echo $colors->getColoredString('INFO  ', 'blue') .
    "Done on employees...\r\n";

echo $colors->getColoredString('INFO  ', 'blue') .
    "Done.\r\n";

$mysqli->query("TRUNCATE `migration_logs`");

$mysqli->query("TRUNCATE `npi_results_resolutions`");
$mysqli->query("TRUNCATE `npi_results`");
$mysqli->query("TRUNCATE `npi_results_data`");
$mysqli->query("TRUNCATE `npi_requests`");

$mysqli->query("TRUNCATE `license_results_resolutions`");
$mysqli->query("TRUNCATE `license_results_images`");
$mysqli->query("TRUNCATE `license_results`");
$mysqli->query("TRUNCATE `license_results_data`");
$mysqli->query("TRUNCATE `license_registries_checked`");
$mysqli->query("TRUNCATE `license_requests`");
$mysqli->query("TRUNCATE `license_request_params`");

$generateDummyDataUtility = new GenerateDummyDataUtility($colors, $recordCount);
$generateDummyDataUtility->generate($employeesRepository, 'employees');
$generateDummyDataUtility->generate($checksRepository, 'checks');
$generateDummyDataUtility->generate($checkEmployeesRepository, 'check_employees');
$generateDummyDataUtility->generate($credentialMatchesRepository, 'credential_matches');
$credentialMatchesRepository->deleteUnrealisticData();
$generateDummyDataUtility->generate($credentialMatchResolutionsRepository, 'credential_match_resolutions');
$generateDummyDataUtility->generate($checksCredentialMatchesRepository, 'checks_credential_matches');
$generateDummyDataUtility->generate($employeesAuditLogRepository, 'employees_audit_log');

$mysqli->query('COMMIT');
$mysqli->query('SET autocommit=1;');
$mysqli->query('SET unique_checks=1;');
$mysqli->query('SET foreign_key_checks=1;');

echo $colors->getColoredString('INFO  ', 'blue') .
    "Dummy data generated successfully.\r\n";
