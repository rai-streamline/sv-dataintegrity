<?php
include "../../colors.php";
include "../../header.php";
echo $colors->getColoredString("INFO ", "blue");
echo "Doing count on " . $colors->getColoredString("license_request_params", "yellow") . " table. \r\n";
$result = $mysqli->query("select * from license_request_params where license_number in (select credential_id from credential_matches);");

$totalLicenseRequestsForMigration = count($result->fetch_all());

$result = $mysqli->query("SELECT count(*) from license_request_params");
$licenseRequestParams = $result->fetch_array();

$totalLicenseRequestParamsMigrated = $licenseRequestParams[0];
if ($totalLicenseRequestParamsMigrated != $totalLicenseRequestsForMigration) {
    echo $colors->getColoredString("ERR  ", "red");
    echo "Found discrepancy of " . $colors->getColoredString(abs($totalLicenseRequestParamsMigrated - $totalLicenseRequestsForMigration), "yellow") . " for source and target data. \r\n";
}
echo $colors->getColoredString("INFO ", "blue");
echo "Found " . $colors->getColoredString($totalLicenseRequestParamsMigrated, "yellow") . " records successfully migrated \r\n";

$result = $mysqli->query('SELECT
    \'\' as first_name,
    \'\' as last_name,
    credential_matches.credential_id,
    employees.license_type_id
  FROM employees
    INNER JOIN credential_matches ON credential_matches.employee_id = employees.id
  WHERE credential_matches.registry NOT IN (\'nppes\', \'moo\', \'nyopmc\')
   AND credential_matches.credential_id IN(SELECT `license_number` FROM `license_request_params`)
  AND credential_matches.`current` = 1 group by credential_matches.credential_id, employees.license_type_id');

$totalLicenseRequestsFromCredentialMatches = count($result->fetch_all());

echo $colors->getColoredString("INFO ", "blue");
echo "with " . $colors->getColoredString(abs($totalLicenseRequestsFromCredentialMatches - $totalLicenseRequestsForMigration), "yellow") . " records updated \r\n";

echo $colors->getColoredString("INFO ", "blue");
echo "Doing count on " . $colors->getColoredString("npi_requests", "yellow") . " table. \r\n";
$result = $mysqli->query("SELECT
    credential_matches.credential_id,
    check_employees.id AS 'check_employee_id',
    NULL as 'scheduled_time_to_complete',
    credential_matches.id
  FROM checks_credential_matches
  INNER JOIN credential_matches ON checks_credential_matches.credential_match_id = credential_matches.id
  INNER JOIN check_employees ON checks_credential_matches.check_id = check_employees.check_id
  WHERE credential_matches.registry IN ('nppes', 'moo', 'mooa')
  GROUP BY credential_matches.credential_id
  ");

$totalNPIRequestsForMigration = count($result->fetch_all());

$result = $mysqli->query("SELECT count(*) from npi_requests");
$NPIRequestParams = $result->fetch_array();

$totalNPIRequestParamsMigrated = $NPIRequestParams[0];

if ($totalNPIRequestsForMigration == $totalNPIRequestParamsMigrated) {
    echo $colors->getColoredString("INFO ", "blue");
    echo "Found " . $colors->getColoredString($totalNPIRequestParamsMigrated, "yellow") . " records successfully migrated. \r\n";
}
else {
    echo $colors->getColoredString("ERR  ", "red");
    echo "Found discrepancy of " . $colors->getColoredString(abs($totalNPIRequestParamsMigrated - $totalNPIRequestsForMigration), "yellow") . " for source and target data. \r\n";
}

$query = $mysqli->query("SELECT count(*) as total_credential_matches from credential_matches");
$result = $query->fetch_assoc();
echo $colors->getColoredString("INFO ", "blue");
echo "Total count for " . $colors->getColoredString('credential_matches', "yellow")  . " table(write this down somewhere): " .
    $colors->getColoredString($result['total_credential_matches'], "green") . "\r\n";
