<?php

namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class LicenseResultsImagesRepository extends BaseRepository
{
    protected $table = 'license_results_images';

    public function migrate()
    {
        $this->mysqliConnection->query("INSERT INTO `$this->table` (
  `license_results_data_id`,
  `image`,
  `image_hash`)
  SELECT
    res_tabl.license_results_data_id,
    res_tabl.image,
    SHA2(res_tabl.image, 256) as 'image_hash'
  FROM (
    SELECT
    `id` AS 'license_results_data_id',
    JSON_UNQUOTE(`raw_result`->'$.image') AS 'image'
    FROM license_results_data
    WHERE JSON_CONTAINS_PATH(`raw_result`, 'one', '$.image')
  ) AS res_tabl;") or die($this->mysqliConnection->error . ' file: ' . __FILE__ . ' line: ' . __LINE__);
    }

    public function checkData($items)
    {
        foreach ($items as $item) {
            $result = $this->mysqliConnection->query("SELECT count(*) FROM `license_results_data`
WHERE `id` = '" . $item['license_results_data_id'] . "'" );

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