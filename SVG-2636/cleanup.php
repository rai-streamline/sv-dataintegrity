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
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\LicenseRequestParamsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\NPIResultsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\NPIRequestsRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\NPIResultsDataRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\NPIResultsResolutionsRepository;

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
include "repositories/license_request_params_repository.php";
include "../colors.php";
include "../header.php";
include "../randomizer.php";

//define the target sql migration file to test here
$sqlFileName = '../../../etc/database/migrations/ctr/svg-2636-migrate_to_check_employee_credentials.sql';
$showDataValidationDetails = false;

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
$licenseRequestParamsRepository = new LicenseRequestParamsRepository($mysqli, $colors);

/**
 * Doing cleanup first to remove any constraints
 */
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

echo $colors->getColoredString('INFO  ', 'blue') .
    "Done.\r\n";