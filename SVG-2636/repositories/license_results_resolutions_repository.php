<?php

namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class LicenseResultsResolutionsRepository extends BaseRepository
{
    protected $table = 'license_results_resolutions';

    public function migrate()
    {
        $this->mysqliConnection->query("INSERT INTO `$this->table` (
  `employees_audit_log_id`,
  `license_results_data_id`,
  `resolution_type`,
  `resolution_note`,
  `created_at`)
SELECT
  employees_audit_log2.id as 'employees_audit_log_id',
  license_results_data_temp.license_results_data_id,
  'default' as 'resolution_type',
  credential_match_resolutions.note,
  credential_match_resolutions.created_at
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
) AS employees_audit_log2 ON credential_matches.employee_id = employees_audit_log2.employee_id;")or die($this->mysqliConnection->error);
    }
}