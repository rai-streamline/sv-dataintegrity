<?php

namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class NPIResultsDataRepository extends BaseRepository
{
    protected $table = 'npi_results_data';

    public function dropTemporaryTable()
    {
        $this->mysqliConnection->query("DROP TABLE npi_results_data_temp");
    }

    public function migrate()
    {
        $this->mysqliConnection->query("CREATE TABLE IF NOT EXISTS `npi_results_data_temp` (
  `npi_results_data_id` INT NOT NULL AUTO_INCREMENT,
  `npi_request_id` INT,
  `credential_match_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`npi_results_data_id`));");

        $this->mysqliConnection->query("INSERT INTO `npi_results_data_temp` (
  `npi_request_id`,
  `credential_match_id`,
  `created_at`)
  SELECT
    `npi_request_id`,
    `credential_match_id`,
    `created_at`
  FROM npi_request_temp;");

        $this->mysqliConnection->query("INSERT INTO `npi_results_data` (
  `id`,
  `raw_result`,
  `first_name`,
  `middle_name`,
  `last_name`,
  `suffix`,
  `business_name`,
  `npi`,
  `opt_out`,
  `opt_out_start_date`,
  `opt_out_end_date`,
  `registry`,
  `response_status_code`,
  `created_at`,
  `last_retrieved_at`)
  SELECT
    npi_results_data_id,
    credential_matches.match,
    JSON_UNQUOTE(`match`->'$.first_name'),
    JSON_UNQUOTE(`match`->'$.middle_name'),
    JSON_UNQUOTE(`match`->'$.last_name'),
    JSON_UNQUOTE(`match`->'$.suffix'),
    JSON_UNQUOTE(`match`->'$.business_name'),
    credential_matches.credential_id,
    JSON_UNQUOTE(`match`->'$.opt_out_flag'),
    JSON_UNQUOTE(`match`->'$.start_date'),
    JSON_UNQUOTE(`match`->'$.end_date'),
    credential_matches.registry,
    credential_matches.type,
    credential_matches.date_created,
    credential_matches.last_modified
  FROM npi_results_data_temp
  INNER JOIN credential_matches ON npi_results_data_temp.credential_match_id = credential_matches.id
  WHERE npi_results_data_temp.npi_request_id IS NOT NULL;");
    }
}