<?php

namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class LicenseResultsDataRepository extends BaseRepository
{
    protected $table = 'license_results_data';

    public function migrate()
    {
        $this->mysqliConnection->query("
CREATE TABLE IF NOT EXISTS `license_results_data_temp` (
  `license_results_data_id` INT NOT NULL AUTO_INCREMENT,
  `license_request_id` INT,
  `credential_match_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`license_results_data_id`));
") or die($this->mysqliConnection->error . ' file: ' . __FILE__ . ' line: ' . __LINE__);


        $this->mysqliConnection->query("INSERT INTO license_results_data_temp (
  `license_request_id`,
  `credential_match_id`,
  `created_at`)
  SELECT
    `license_request_id`,
    `credential_match_id`,
    `created_at`
  FROM license_request_temp;
;") or die($this->mysqliConnection->error . ' file: ' . __FILE__ . ' line: ' . __LINE__);

        $this->mysqliConnection->query("
INSERT INTO `license_results_data` (
  `id`,
  `raw_result`,
  `first_name`,
  `middle_name`,
  `last_name`,
  `business_name`,
  `license_number`,
  `expiration_date`,
  `registry`,
  `status_text`,
  `response_status_code`,
  `created_at`,
  `last_retrieved_at`)
  SELECT
    license_results_data_id,
    credential_matches.match,
    JSON_UNQUOTE(`match`->'$.first_name'),
    JSON_UNQUOTE(`match`->'$.middle_name'),
    JSON_UNQUOTE(`match`->'$.last_name'),
    JSON_UNQUOTE(`match`->'$.business_name'),
    credential_matches.credential_id,
    JSON_UNQUOTE(`match`->'$.expiration_date'),
    credential_matches.registry,
    credential_matches.status,
    credential_matches.type,
    credential_matches.date_created,
    credential_matches.last_modified
  FROM license_results_data_temp
  INNER JOIN credential_matches ON license_results_data_temp.credential_match_id = credential_matches.id
  WHERE license_results_data_temp.license_request_id IS NOT NULL;") or die($this->mysqliConnection->error . ' file: ' . __FILE__ . ' line: ' . __LINE__);
    }

    public function dropTemporaryTable()
    {
        $this->mysqliConnection->query('DROP TABLE `license_results_data_temp`');
    }

    public function countForLicenseResultsMigration()
    {
        $query = $this->mysqliConnection->query("SELECT
    count(*)
  FROM license_results_data_temp
  WHERE license_results_data_temp.license_request_id IS NOT NULL");

        $result = $query->fetch_row();

        return $result[0];
    }

    public function migrateUpdateImageField()
    {
        $this->mysqliConnection->query("UPDATE license_results_data
SET raw_result = JSON_REMOVE(raw_result, '$.image')
WHERE JSON_CONTAINS_PATH(`raw_result`, 'one', '$.image');
")or die($this->mysqliConnection->error);
    }

    public function countForLicenseResultsResolutionsMigration()
    {
        $query = $this->mysqliConnection->query("SELECT
  count(*)
FROM license_results_data_temp
INNER JOIN credential_matches ON license_results_data_temp.credential_match_id = credential_matches.id
INNER JOIN credential_match_resolutions ON credential_matches.id = credential_match_resolutions.credential_match_id
INNER JOIN (
  SELECT t1.id, t1.employee_id, t1.date_created
  FROM (
    SELECT employee_id, MAX(date_created) as date_created
    FROM employees_audit_log
    GROUP BY employee_id
  ) AS t2
  INNER JOIN employees_audit_log as t1 ON t1.employee_id = t2.employee_id AND t1.date_created = t2.date_created
) AS employees_audit_log2 ON credential_matches.employee_id = employees_audit_log2.employee_id;") or die($this->mysqliConnection->error);

        $result = $query->fetch_row();

        return $result[0];
    }

    public function validateIfImageFieldIsStillPresent()
    {
        $query = $this->mysqliConnection->query("SELECT count(*) FROM license_results_data
WHERE JSON_CONTAINS_PATH(`raw_result`, 'one', '$.image');
")or die($this->mysqliConnection->error);
        $result = $query->fetch_row();

        return $result[0];
    }

    public function checkData($items)
    {
        foreach ($items as $item) {
            $result = $this->mysqliConnection->query("SELECT count(*) FROM `credential_matches`
WHERE JSON_UNQUOTE(`match`->'$.first_name') = '" . $item['first_name'] . "' AND
JSON_UNQUOTE(`match`->'$.middle_name') = '" . $item['middle_name'] . "' AND
JSON_UNQUOTE(`match`->'$.last_name') = '" . $item['last_name'] . "' AND
JSON_UNQUOTE(`match`->'$.business_name') = '" . $item['business_name'] . "' AND
JSON_UNQUOTE(`match`->'$.expiration_date') = '" . $item['expiration_date'] . "' AND
    `credential_id` = '" . $item['license_number'] . "' AND
    `registry` = '" . $item['registry'] . "'" );

            $count = $result->fetch_row();
            if (0 >= $count[0]) {
                echo $this->colors->getColoredString('ERROR ', 'red') . 'Record not found. See row details below: ';
                var_dump($item);

                echo $this->colors->getColoredString('npi_results_resolutions', 'yellow') . "\r\n";
                exit;
            }

        }
    }
}