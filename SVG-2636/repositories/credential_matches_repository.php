<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\Randomizer;

class CredentialMatchesRepository extends BaseRepository
{
    protected $table = 'credential_matches';
    private $randomizer;

    public function __construct(\Mysqli $mysqliConnection, \Colors $colors)
    {
        $this->randomizer = new Randomizer();
        parent::__construct($mysqliConnection, $colors);
    }


    public function generateSampleData()
    {
        $this->mysqliConnection->query("INSERT INTO `" . $this->table . "`(`id`, `current`, `employee_id`, `registry`, `type`, `match`, `date_created`, `date_updated`, `date_resolved`, `credential_id`, `license_type_id`, `last_modified`, `expiry_date`, `match_is_valid`) VALUES
      (1, 1, 9, 'vtmb', 1, '" . '{"first_name": "John", "middle_name": "Peter", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), 'cr1', 'lt1', now(), '2020-01-01', 1),
      (2, 1, 9, 'txmb', 1, '" . '{"first_name": "Peter", "middle_name": "Pied", "last_name": "Piper", "business_name": "Peter Pipers", "expiration_date": "2017-09-01", "image": "test"}' . "', now(), now(), now(), 'cr2', 'lt2', now(), '2020-01-01', 1),
      (3, 1, 19, 'njel', 1, '" . '{"first_name":" Daenerys", "middle_name": "Khaleesi", "last_name": "Targaryen", "business_name": "First of her name, breaker of chains...", "expiration_date": "2012-01-01", "image": "test"}' . "', now(), now(), now(), 'cr9', 'tr9', now(), '2020-01-01', 1),
      (4, 1, 14, 'ilel', 1, '" . '{"first_name": "Michael", "middle_name": "Johnson", "last_name": "Jordan", "business_name": "Chicago Lakers", "expiration_date": "1990-01-01", "image": "test"}' . "', now(), now(), now(), 'cr6', 'tr7', now(), '2020-01-01', 1),
      (5, 1, 15, 'ctna', 1, '" . '{"first_name": "Tyrion", "middle_name": "Imp", "last_name": "Lannister", "business_name": "Hear Me Roar", "expiration_date": "1980-01-01", "image": "test"}' . "', now(), now(), now(), 'cr7', 'tr8', now(), '2020-01-01', 1),
      (6, 1, 17, 'flel', 1, '" . '{"first_name": "Lord", "middle_name": "Spider", "last_name": "Varys", "business_name": "Master of Whisperers", "expiration_date": "2007-01-01", "image": "test"}' . "', now(), now(), now(), '', '', now(), '2020-01-01', 1),
      (7, 1, 17, 'mapp', 1, '" . '{"first_name": "Rhaeghar", "middle_name": "Dragon Prince", "last_name": "Targaryen", "business_name": "Ruby Ford", "expiration_date": "2006-01-01", "image": "test"}' . "', now(), now(), now(), '', 'tr10', now(), '2020-01-01', 1),
      (8, 1, 10, 'NYOPMC', 1, '" . '{"first_name": "", "middle_name": "Peter", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), 'invalid3', 'tr3', now(), '2020-01-01', 1),
      (9, 1, 11, 'txbn', 1, '" . '{"first_name": "John", "middle_name": "", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), 'cr4', 'tr4', now(), '2020-01-01', 1),
      (10, 1, 12, 'ncmb', 1, '" . '{"first_name": "John", "middle_name": "Peter", "last_name": "", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), 'cr4', 'tr5', now(), '2020-01-01', 1),
      (11, 1, 12, 'NPPES', 1, '" . '{"first_name": "John", "middle_name": "Peter", "last_name": "Doe", "business_name": "", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), 'invalid3', 'tr6', now(), '2020-01-01', 1),
      (12, 1, 13, 'inmb', 1, '" . '{"first_name": "John", "middle_name": "Peter", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "", "image": "test"}' . "', now(), now(), now(), 'cr6', 'tr7', now(), '2020-01-01', 1),
      (13, 1, 19, 'MOO', 1, '" . '{"first_name": "Hodor", "middle_name": "Hodor", "last_name": "Hodor", "business_name": "Hodor", "expiration_date": "Hodor", "image": "test"}' . "', now(), now(), now(), 'invalid3', '', now(), '2020-01-01', 1)
      ") or die($this->mysqliConnection->error);
    }

    public function countExpectedLicenseRequestParamsToBeMigrated()
    {
        $query = $this->mysqliConnection->query("SELECT *
  FROM credential_matches
  WHERE credential_matches.registry NOT IN ('NPPES', 'MOO', 'NYOPMC')
  GROUP BY credential_matches.credential_id");

        $result = $query->fetch_all();

        return count($result);
    }

    public function findByCredentialMatchID($credentialMatchID)
    {
        $query = $this->mysqliConnection->query("SELECT * FROM `$this->table` WHERE `id` = " . $credentialMatchID
            . " AND `registry` NOT IN ('NPPES', 'MOO', 'NYOPMC')");
        $result = $query->fetch_assoc();

        return $result;
    }

    public function generateDummyData($recordCount)
    {
        $registry = array("vtmb", "txmb", "njel", "ilel", "ctna", "flel", "mapp", "NYOPMC", "txbn", "MOO", "ncmb", "NPPES", "inmb");
        $match = '{"first_name": "' . $this->randomizer->randomString(12) . '", "middle_name": "' . $this->randomizer->randomString(4) . '", "last_name": "' . $this->randomizer->randomString(6) . '", "business_name": "' . $this->randomizer->randomString(15) . '", "expiration_date": "' . $this->randomizer->randomDate(false, true) . '", "image": "' . $this->randomizer->randomString(4) . '"}';
        shuffle($registry);
        $this->mysqliConnection->query("INSERT INTO `streamline_local`.`credential_matches`
(
`current`,
`employee_id`,
`registry`,
`type`,
`match`,
`date_created`,
`date_updated`,
`date_resolved`,
`credential_id`,
`license_type_id`,
`last_modified`,
`expiry_date`,
`match_is_valid`)
VALUES
(
'" . rand(0, 1) . "',
'" . rand(0, $recordCount + 81) . "',
'" . $registry[0] . "',
1,
'" . $match . "',
'" .  $this->randomizer->randomDate() . "',
'" .  $this->randomizer->randomDate() . "',
'" .  $this->randomizer->randomDate() . "',
'cr" . rand(0, 99) . "',
'tr" . rand(0, 99) . "',
'" .  $this->randomizer->randomDate() . "',
'" .  $this->randomizer->randomDate(false, true) . "',
1);")or die($this->mysqliConnection->error);
    }
}