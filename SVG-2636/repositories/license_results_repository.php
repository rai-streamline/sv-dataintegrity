<?php

namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class LicenseResultsRepository extends BaseRepository
{
    protected $table = 'license_results';

    public function migrate()
    {
        $this->mysqliConnection->query("
INSERT INTO $this->table (
  `created_at`,
  `license_results_data_id`,
  `license_request_id`)
  SELECT
    `created_at`,
    `license_results_data_id`,
    `license_request_id`
  FROM license_results_data_temp
  WHERE license_results_data_temp.license_request_id IS NOT NULL;")or die($this->mysqliConnection->error);
    }

    public function dropTemporaryTable()
    {
        $this->mysqliConnection->query("DROP TABLE license_results_temp");
    }

    public function checkData($items)
    {
        foreach ($items as $item) {
            $result = $this->mysqliConnection->query("SELECT COUNT(*) FROM `$this->table`
INNER JOIN `license_results_data` ON `license_results_data`.`id` = `$this->table`.`license_results_data_id`
INNER JOIN `license_requests` ON `license_requests`.`id` = `$this->table`.`license_request_id`
WHERE
`$this->table`.`license_results_data_id` = '" . $item['license_results_data_id']. "' AND
`$this->table`.`license_request_id` = '" . $item['license_request_id']. "'
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