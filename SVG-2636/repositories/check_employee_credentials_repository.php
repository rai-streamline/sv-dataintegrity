<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use \Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class CheckEmployeeCredentialsRepository extends BaseRepository
{
    protected $table = 'check_employee_credentials';

    public function countForMigration()
    {
        $query = $this->mysqliConnection->query("SELECT
    checks.id,
    credential_matches.employee_id,
    checks.date_created
  FROM checks
  INNER JOIN checks_credential_matches ON checks.id = checks_credential_matches.check_id
  INNER JOIN credential_matches ON checks_credential_matches.credential_match_id = credential_matches.id
  GROUP BY checks_credential_matches.check_id, credential_matches.employee_id");

        $result = $query->fetch_all();
        return count($result);
    }
}