<?php

namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class LicenseRequestsRepository extends BaseRepository
{
    protected $table = 'license_requests';

    public function migrate()
    {
        $this->mysqliConnection->query("        
CREATE TABLE IF NOT EXISTS `license_request_temp` (
  `license_request_id` INT NOT NULL AUTO_INCREMENT,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `license_request_param_id` INT NULL DEFAULT NULL,
  `check_employee_credential_id` INT UNSIGNED NOT NULL,
  `scheduled_time_to_complete` DATETIME NULL DEFAULT NULL,
  `credential_match_id` INT NOT NULL,
  `registry` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`license_request_id`));

") or die($this->mysqliConnection->error);

        $this->mysqliConnection->query("INSERT INTO `license_request_temp` (
  `license_request_param_id`,
  `check_employee_credential_id`,
  `scheduled_time_to_complete`,
  `created_at`,
  `credential_match_id`,
  `registry`)
  SELECT
    license_request_params.id,
    check_employee_credentials.id AS 'check_employee_credential_id',
    NULL as 'scheduled_time_to_complete',
    check_employee_credentials.created_at,
    credential_matches.id,
    credential_matches.registry
  FROM checks_credential_matches
  INNER JOIN credential_matches ON checks_credential_matches.credential_match_id = credential_matches.id
  INNER JOIN license_request_params ON credential_matches.credential_id = license_request_params.license_number
  INNER JOIN check_employee_credentials ON checks_credential_matches.check_id = check_employee_credentials.check_id
  WHERE credential_matches.registry NOT IN ('NPPES', 'MOO', 'NYOPMC')
  GROUP BY checks_credential_matches.check_id, checks_credential_matches.credential_match_id;

") or die($this->mysqliConnection->error);

        $this->mysqliConnection->query("INSERT INTO `$this->table` (
  `id`,
  `license_request_param_id`,
  `check_employee_credential_id`,
  `scheduled_time_to_complete`,
  `created_at`)
  SELECT
    license_request_temp.license_request_id,
    license_request_temp.license_request_param_id,
    license_request_temp.check_employee_credential_id,
    license_request_temp.scheduled_time_to_complete,
    license_request_temp.created_at
  FROM license_request_temp;") or die($this->mysqliConnection->error);

        $this->mysqliConnection->query("

INSERT INTO `license_registries_checked` (
  `license_request_id`,
  `registry`)
  SELECT
    license_request_temp.license_request_id,
    license_request_temp.registry
  FROM license_request_temp;") or die($this->mysqliConnection->error);
    }

    public function dropTemporaryTable()
    {
        $this->mysqliConnection->query('DROP TABLE `license_request_temp`');
    }
}