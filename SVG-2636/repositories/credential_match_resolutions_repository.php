<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\Randomizer;

class CredentialMatchResolutionsRepository extends BaseRepository
{
    protected $table = 'credential_match_resolutions';
    private $randomizer;

    public function __construct(\Mysqli $mysqliConnection, \Colors $colors)
    {
        $this->randomizer = new Randomizer();
        parent::__construct($mysqliConnection, $colors);
    }

    public function generateSampleData()
    {
        $this->mysqliConnection->query("INSERT INTO `" . $this->table . "`(`credential_match_id`, `note`, `created_at`) VALUES
      (1, 'test1', NOW()),
      (2, 'test2', NOW()),
      (3, 'test3', NOW()),
      (4, 'test4', NOW()),
      (5, 'test5', NOW()),
      (6, 'test6', NOW()),
      (7, 'test7', NOW()),
      (8, 'test8', NOW()),
      (9, 'test9', NOW()),
      (10, 'test10', NOW()),
      (11, 'test11', NOW()),
      (12, 'test12', NOW()),
      (13, 'test13', NOW());
      ") or die($this->mysqliConnection->error);
    }
    public function generateDummyData($recordCount)
    {
        $this->mysqliConnection->query("INSERT INTO `" . $this->table . "`(`credential_match_id`, `note`, `created_at`) VALUES
      (" . rand(1, $recordCount) . ", '" . $this->randomizer->randomString(5) . "', NOW())") or die($this->mysqliConnection->error);
    }
}