<?php
use Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories\CredentialMatchesRepository;

include "../base_repository.php";
include "repositories/credential_matches_repository.php";
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
$credentialMatchesRepository = new CredentialMatchesRepository($mysqli, $colors);

$mysqli->query('SET autocommit=0;');
$mysqli->query('SET unique_checks=0;');
$mysqli->query('SET foreign_key_checks=0;');

$credentialMatchesRepository->generateSampleIncrementalData();

$mysqli->query('COMMIT');
$mysqli->query('SET autocommit=1;');
$mysqli->query('SET unique_checks=1;');
$mysqli->query('SET foreign_key_checks=1;');

echo $colors->getColoredString('INFO  ', 'blue') .
    "Dummy data generated successfully.\r\n";