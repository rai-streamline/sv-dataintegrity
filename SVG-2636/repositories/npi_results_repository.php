<?php

namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class NPIResultsRepository extends BaseRepository
{
    protected $table = 'npi_results';

    public function migrate()
    {
        $this->mysqliConnection->query("INSERT INTO `npi_results` (
  `created_at`,
  `npi_results_data_id`,
  `npi_request_id`)
  SELECT
    `created_at`,
    `npi_results_data_id`,
    `npi_request_id`
  FROM npi_results_data_temp
  WHERE npi_results_data_temp.npi_request_id IS NOT NULL;");
    }

    public function checkData($items)
    {
        foreach ($items as $item) {
            $result = $this->mysqliConnection->query("SELECT COUNT(*) FROM `$this->table`
INNER JOIN `npi_results_data` ON `npi_results_data`.`id` = `$this->table`.`npi_results_data_id`
INNER JOIN `npi_requests` ON `npi_requests`.`id` = `$this->table`.`npi_request_id`
WHERE
`$this->table`.`npi_results_data_id` = '" . $item['npi_results_data_id']. "' AND
`$this->table`.`npi_request_id` = '" . $item['npi_request_id']. "'
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