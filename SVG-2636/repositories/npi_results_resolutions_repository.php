<?php

namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class NPIResultsResolutionsRepository extends BaseRepository
{
    protected $table = 'npi_results_resolutions';

    public function migrate()
    {
        $this->mysqliConnection->query("INSERT INTO `npi_results_resolutions` (
  `employees_audit_log_id`,
  `npi_results_data_id`,
  `resolution_type`,
  `resolution_note`,
  `created_at`)
SELECT
	employees_audit_log2.id as 'employees_audit_log_id',
	npi_results_data_temp.npi_results_data_id,
    'default' as 'resolution_type',
    credential_match_resolutions.note,
    credential_match_resolutions.created_at
FROM npi_results_data_temp
INNER JOIN credential_matches ON npi_results_data_temp.credential_match_id = credential_matches.id
INNER JOIN credential_match_resolutions ON credential_matches.id = credential_match_resolutions.credential_match_id
INNER JOIN (
	SELECT t1.id, t1.employee_id, t1.date_created
	FROM (
		SELECT employee_id, MAX(date_created) as date_created
		FROM employees_audit_log
		GROUP BY employee_id
	) AS t2
	INNER JOIN employees_audit_log as t1 ON t1.employee_id = t2.employee_id AND t1.date_created = t2.date_created
) AS employees_audit_log2 ON credential_matches.employee_id = employees_audit_log2.employee_id;");
    }

    public function migrateCount()
    {
        $query = $this->mysqliConnection->query("SELECT
	employees_audit_log2.id as 'employees_audit_log_id',
	npi_results_data_temp.npi_results_data_id,
    'default' as 'resolution_type',
    credential_match_resolutions.note,
    credential_match_resolutions.created_at
FROM npi_results_data_temp
INNER JOIN credential_matches ON npi_results_data_temp.credential_match_id = credential_matches.id
INNER JOIN credential_match_resolutions ON credential_matches.id = credential_match_resolutions.credential_match_id
INNER JOIN (
	SELECT t1.id, t1.employee_id, t1.date_created
	FROM (
		SELECT employee_id, MAX(date_created) as date_created
		FROM employees_audit_log
		GROUP BY employee_id
	) AS t2
	INNER JOIN employees_audit_log as t1 ON t1.employee_id = t2.employee_id AND t1.date_created = t2.date_created
) AS employees_audit_log2 ON credential_matches.employee_id = employees_audit_log2.employee_id;");

        $result = $query->fetch_all();

        return count($result);
    }

    public function checkData($items)
    {
        foreach ($items as $item) {
            $result = $this->mysqliConnection->query("SELECT COUNT(*) FROM `$this->table`
INNER JOIN `npi_results_data` ON `npi_results_data`.`id` = `$this->table`.`npi_results_data_id`
INNER JOIN `employees_audit_log` ON `employees_audit_log`.`id` = `$this->table`.`employees_audit_log_id`
INNER JOIN `credential_match_resolutions` ON `credential_match_resolutions`.`note` = `$this->table`.`resolution_note`
WHERE `$this->table`.`employees_audit_log_id` = " . $item['employees_audit_log_id'] . "
AND `$this->table`.`npi_results_data_id` = " . $item['npi_results_data_id'] . "
AND `$this->table`.`resolution_note` = '" . $item['resolution_note'] . "'
AND `$this->table`.`resolution_type` = 'default'
;");
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
