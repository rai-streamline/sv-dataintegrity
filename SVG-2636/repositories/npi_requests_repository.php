<?php

namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class NPIRequestsRepository extends BaseRepository
{
    protected $table = 'npi_requests';

    public function dropTemporaryTable()
    {
        $this->mysqliConnection->query("DROP TABLE npi_request_temp");
    }

    public function migrate()
    {
        $this->mysqliConnection->query("CREATE TABLE IF NOT EXISTS `npi_request_temp` (
  `npi_request_id` INT NOT NULL AUTO_INCREMENT,
  `npi` CHAR(10) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `check_employee_id` INT UNSIGNED NOT NULL,
  `scheduled_time_to_complete` DATETIME NULL DEFAULT NULL,
  `credential_match_id` INT NOT NULL,
  PRIMARY KEY (`npi_request_id`));");

        $this->mysqliConnection->query("INSERT INTO `npi_request_temp` (
  `npi`,
  `check_employee_id`,
  `created_at`,
  `scheduled_time_to_complete`,
  `credential_match_id`)
  SELECT
    credential_matches.credential_id,
    check_employees.id AS 'check_employee_id',
    check_employees.created_at,
    NULL as 'scheduled_time_to_complete',
    credential_matches.id
  FROM checks_credential_matches
  INNER JOIN credential_matches ON checks_credential_matches.credential_match_id = credential_matches.id
  INNER JOIN check_employees ON checks_credential_matches.check_id = check_employees.check_id
  WHERE credential_matches.registry IN ('NPPES', 'MOO')
  GROUP BY checks_credential_matches.check_id, checks_credential_matches.credential_match_id;");

        $this->mysqliConnection->query("INSERT INTO `npi_requests` (
  `id`,
  `npi`,
  `created_at`,
  `check_employee_id`,
  `scheduled_time_to_complete`)
  SELECT
    npi_request_temp.npi_request_id,
    npi_request_temp.npi,
    npi_request_temp.created_at,
    npi_request_temp.check_employee_id,
    npi_request_temp.scheduled_time_to_complete
  FROM npi_request_temp;");
    }

    public function checkData($items)
    {
        foreach ($items as $item) {
            $result = $this->mysqliConnection->query("SELECT * FROM `$this->table`
INNER JOIN `check_employees` ON `check_employees`.`id` = `$this->table`.`check_employee_id`
INNER JOIN `checks_credential_matches` ON `checks_credential_matches`.`check_id` = `check_employees`.`check_id`
INNER JOIN `credential_matches` ON `credential_matches`.`id` = `checks_credential_matches`.`credential_match_id`
WHERE 
`$this->table`.check_employee_id = '" . $item['check_employee_id'] . "' AND
`credential_matches`.`credential_id` = '" . $item['npi'] . "'; 
");

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