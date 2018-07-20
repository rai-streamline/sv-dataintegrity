<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use \Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class ChecksCredentialMatchesRepository extends BaseRepository
{
    protected $table = 'checks_credential_matches';

    public function generateSampleData()
    {
        $this->mysqliConnection->query("INSERT INTO `$this->table`(`check_id`, `credential_match_id`, `pending_update`) VALUES
      (22, 1, 1),
      (23, 1, 1),
      (24, 2, 1),
      (25, 3, 1),
      (26, 3, 1),
      (27, 3, 1),
      (38, 11, 1),
      (39, 3, 1),
      (40, 13, 1);") or die($this->mysqliConnection->error);
    }

    public function findByCheckID($checkID)
    {
        $query = $this->mysqliConnection->query("SELECT * FROM `$this->table` WHERE `check_id` = " . $checkID);
        $result = $query->fetch_assoc();

        return $result;
    }

    public function countForLicenseRequestsMigration()
    {
        $query = $this->mysqliConnection->query("SELECT * FROM checks_credential_matches
  INNER JOIN credential_matches ON checks_credential_matches.credential_match_id = credential_matches.id
  INNER JOIN license_request_params ON credential_matches.credential_id = license_request_params.license_number
  INNER JOIN check_employee_credentials ON checks_credential_matches.check_id = check_employee_credentials.check_id
  WHERE credential_matches.registry NOT IN ('NPPES', 'MOO', 'NYOPMC')
  GROUP BY checks_credential_matches.check_id, checks_credential_matches.credential_match_id;");
        $result = $query->fetch_all();

        return count($result);
    }

    public function countForNPIRequestsRepository()
    {
        $query = $this->mysqliConnection->query("SELECT
    count(*)
  FROM checks_credential_matches
  INNER JOIN credential_matches ON checks_credential_matches.credential_match_id = credential_matches.id
  INNER JOIN check_employee_credentials ON checks_credential_matches.check_id = check_employee_credentials.check_id
  WHERE credential_matches.registry IN ('NPPES', 'MOO')
  GROUP BY checks_credential_matches.check_id, checks_credential_matches.credential_match_id;");

        $result = $query->fetch_all();

        return count($result);
    }

    public function generateDummyData($recordCount, $increment)
    {
        $this->mysqliConnection->query("INSERT INTO `$this->table`(`check_id`, `credential_match_id`, `pending_update`) VALUES
      (" . rand(1, $recordCount) . ", " . $increment . ", 1)") or die($this->mysqliConnection->error);
    }
}