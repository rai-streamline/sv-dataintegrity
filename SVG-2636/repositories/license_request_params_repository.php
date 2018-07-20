<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class LicenseRequestParamsRepository extends BaseRepository
{
    protected $table = 'license_request_params';

    public function migrate()
    {
        $this->mysqliConnection->query("INSERT INTO $this->table (
  `first_name`,
  `last_name`,
  `license_number`,
  `license_type_id`)
  SELECT
    '' as first_name,
    '' as last_name,
    credential_matches.credential_id,
    employees.license_type_id
  FROM employees
    INNER JOIN check_employees ON employees.id = check_employees.employee_id
    INNER JOIN checks_credential_matches ON check_employees.check_id = checks_credential_matches.check_id
    INNER JOIN credential_matches ON checks_credential_matches.credential_match_id = credential_matches.id
  WHERE credential_matches.registry NOT IN ('NPPES', 'MOO', 'NYOPMC')
  GROUP BY credential_matches.credential_id;") or die($this->mysqliConnection->error);
    }

    public function checkInvalidInsertedRegistries()
    {
        $query = $this->mysqliConnection->query("SELECT count(*) FROM `$this->table` WHERE
                  `license_number` LIKE 'invalid%'");
        $result = $query->fetch_row();

        return $result[0];
    }

    public function checkNullEntries()
    {
        $query = $this->mysqliConnection->query("SELECT count(*) FROM `$this->table` WHERE
                  `license_number` LIKE '' OR `license_type_id` LIKE ''");
        $result = $query->fetch_row();

        return $result[0];
    }
}