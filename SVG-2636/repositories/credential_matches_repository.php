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


    public function generateSampleIncrementalData()
    {
        $checkIDQuery = $this->mysqliConnection->query("SELECT `id` FROM `checks` ORDER BY `id` DESC LIMIT 1");
        $checkID = $checkIDQuery->fetch_array();
        $credentialMatchIDQuery = $this->mysqliConnection->query("SELECT `id` FROM `credential_matches` ORDER BY `id` DESC LIMIT 1");
        $credentialMatchIDQuery = $credentialMatchIDQuery->fetch_array();
        $this->mysqliConnection->query("INSERT INTO `" . $this->table . "`(`current`, `employee_id`, `registry`, `type`, `match`, `date_created`, `date_updated`, `date_resolved`, `credential_id`, `license_type_id`, `last_modified`, `expiry_date`, `match_is_valid`) VALUES
      (1, 9, 'vtmb', 1, '" . '{"first_name": "John", "middle_name": "Peter", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), '" . utf8_decode('İç1'). "', 'lt1', now(), '2020-01-01', 1),
      (1, 9, 'txmb', 1, '" . '{"first_name": "Peter", "middle_name": "Pied", "last_name": "Piper", "business_name": "Peter Pipers", "expiration_date": "2017-09-01", "image": "test"}' . "', now(), now(), now(), '" . utf8_decode('İç2'). "', 'lt2', now(), '2020-01-01', 1),
      (1, 9, 'vtmb', 1, '" . '{"first_name": "asdf", "middle_name": "dfg", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), 'cr3" . rand(2, 2000) . "', 'lt" . rand(2, 2000) . "', now(), '2020-01-01', 1),
      (1, 9, 'txmb', 1, '" . '{"first_name": "fghk", "middle_name": "nvbn", "last_name": "Piper", "business_name": "Peter Pipers", "expiration_date": "2017-09-01", "image": "test"}' . "', now(), now(), now(), 'cr4" . rand(2, 2000) . "', 'lt" . rand(2, 2000) . "', now(), '2020-01-01', 1),
      (1, 9, 'nyopmc', 1, '" . '{"first_name": "asdf", "middle_name": "dfg", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), 'cr5" . rand(2, 2000) . "', 'lt" . rand(2, 2000) . "', now(), '2020-01-01', 1),
      (1, 9, 'nppes', 1, '" . '{"first_name": "fghk", "middle_name": "nvbn", "last_name": "Piper", "business_name": "Peter Pipers", "expiration_date": "2017-09-01", "image": "test"}' . "', now(), now(), now(), 'cr6" . rand(2, 2000) . "', 'lt" . rand(2, 2000) . "', now(), '2020-01-01', 1),
      (1, 9, 'moo', 1, '" . '{"first_name": "asdf", "middle_name": "dfg", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), 'cr7" . rand(2, 2000) . "', 'lt" . rand(2, 2000) . "', now(), '2020-01-01', 1),
      (1, 9, 'txmb', 1, '" . '{"first_name": "fghk", "middle_name": "nvbn", "last_name": "Piper", "business_name": "Peter Pipers", "expiration_date": "2017-09-01", "image": "test"}' . "', now(), now(), now(), 'cr8" . rand(2, 2000) . "', 'lt" . rand(2, 2000) . "', now(), '2020-01-01', 1),
      (1, 19, 'njel', 1, '" . '{"first_name":" Daenerys", "middle_name": "Khaleesi", "last_name": "Targaryen", "business_name": "First of her name, breaker of chains...", "expiration_date": "2012-01-01", "image": "test"}' . "', now(), now(), now(), 'cr9', 'tr9', now(), '2020-01-01', 1),
      (1, 14, 'ilel', 1, '" . '{"first_name": "Michael", "middle_name": "Johnson", "last_name": "Jordan", "business_name": "Chicago Lakers", "expiration_date": "1990-01-01", "image": "test"}' . "', now(), now(), now(), '" . utf8_decode('İç5'). "', 'tr7', now(), '2020-01-01', 1),
      (1, 15, 'ctna', 1, '" . '{"first_name": "Tyrion", "middle_name": "Imp", "last_name": "Lannister", "business_name": "Hear Me Roar", "expiration_date": "1980-01-01", "image": "test"}' . "', now(), now(), now(), '" . utf8_decode('İç6'). "', 'tr8', now(), '2020-01-01', 1),
      (1, 17, 'flel', 1, '" . '{"first_name": "Lord", "middle_name": "Spider", "last_name": "Varys", "business_name": "Master of Whisperers", "expiration_date": "2007-01-01", "image": "test"}' . "', now(), now(), now(), '', '', now(), '2020-01-01', 1),
      (1, 17, 'mapp', 1, '" . '{"first_name": "Rhaeghar", "middle_name": "Dragon Prince", "last_name": "Targaryen", "business_name": "Ruby Ford", "expiration_date": "2006-01-01", "image": "test"}' . "', now(), now(), now(), '', 'tr10', now(), '2020-01-01', 1),
      (1, 10, 'nyopmc', 1, '" . '{"first_name": "", "middle_name": "Peter", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), 'invalid3', '" . utf8_decode('İç7'). "', now(), '2020-01-01', 1),
      (1, 11, 'txbn', 1, '" . '{"first_name": "John", "middle_name": "", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), '" . utf8_decode('ÆØÅ2'). "', 'tr4', now(), '2020-01-01', 1),
      (1, 12, 'ncmb', 1, '" . '{"first_name": "John", "middle_name": "Peter", "last_name": "", "business_name": "Johnny Does", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), '" . utf8_decode('ÆØÅ3'). "', 'tr5', now(), '2020-01-01', 1),
      (1, 12, 'nppes', 1, '" . '{"first_name": "John", "middle_name": "Peter", "last_name": "Doe", "business_name": "", "expiration_date": "2017-01-01", "image": "test"}' . "', now(), now(), now(), '" . utf8_decode('ÆØÅ4'). "', 'tr6', now(), '2020-01-01', 1),
      (1, 13, 'inmb', 1, '" . '{"first_name": "John", "middle_name": "Peter", "last_name": "Doe", "business_name": "Johnny Does", "expiration_date": "", "image": "test"}' . "', now(), now(), now(), '" . utf8_decode('ÆØÅ5'). "', 'tr7', now(), '2020-01-01', 1),
      (1, 19, 'moo', 1, '" . '{"first_name": "Hodor", "middle_name": "Hodor", "last_name": "Hodor", "business_name": "Hodor", "expiration_date": "Hodor", "image": "test"}' . "', now(), now(), now(), '" . utf8_decode('ÆØÅ6'). "', '', now(), '2020-01-01', 1)
      ") or die($this->mysqliConnection->error);
        $credentialMatchAfterInsertIDQuery = $this->mysqliConnection->query("SELECT `id` FROM `credential_matches` ORDER BY `id` DESC LIMIT 1");
        $credentialMatchAfterInsertIDQuery = $credentialMatchAfterInsertIDQuery->fetch_array();
        $totalInserted = $credentialMatchAfterInsertIDQuery['id'] - $credentialMatchIDQuery['id'];

        for ($x = 0; $x < $totalInserted; ++$x) {
            $this->mysqliConnection->query("INSERT INTO `checks_credential_matches` 
            (`check_id`, `credential_match_id`, `pending_update`)
            VALUES('" . rand(1, $checkID['id']) . "', '" . rand($credentialMatchIDQuery['id'],
                                                                $credentialMatchAfterInsertIDQuery['id'] ) . "', " .
                                           rand(0,1) .")");
        }
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
        $registry = ['vtmb', 'txmb', 'njel', 'nyopmc', 'ilel', 'ctna', 'flel', 'mapp', 'nyopmc', 'txbn', 'moo', 'ncmb', 'nppes', 'inmb'];
        $match = json_encode(
            [
                'first_name' => $this->randomizer->randomString(12),
                'middle_name' => $this->randomizer->randomString(4),
                'last_name' => $this->randomizer->randomString(6),
                'business_name' => $this->randomizer->randomString(15),
                'expiration_date' => $this->randomizer->randomDate(false, true),
                'image' => $this->randomizer->randomString(4),
                'certificate_status' => $this->randomizer->randomString(rand(30, 300)),
                'license_status_name' => $this->randomizer->randomString(rand(30, 300))
            ]
        );
        shuffle($registry);
        $this->mysqliConnection->query("INSERT INTO `credential_matches`
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
'" . rand(0, $recordCount) . "',
'" . $registry[0] . "',
" . rand(1, 5) . ",
'" . $match . "',
'" .  $this->randomizer->randomDate() . "',
'" .  $this->randomizer->randomDate() . "',
'" .  $this->randomizer->randomDate() . "',
'cr" . (rand(0, 1) == 1 ? utf8_decode('ÆØÅ6'): '') . rand(0, 99) . "',
'tr" . rand(0, 99) . "',
'" .  $this->randomizer->randomDate() . "',
'" .  $this->randomizer->randomDate(false, true) . "',
1);")or die($this->mysqliConnection->error);
    }

    //deletes data containing a creation date that we haven't reached yet
    public function deleteUnrealisticData()
    {
        $this->mysqliConnection->query("DELETE FROM `credential_matches` WHERE date_created = '00-00-00 00:00:00' OR date_created > NOW()");
    }
}
