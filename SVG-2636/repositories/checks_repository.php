<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\Randomizer;

class ChecksRepository extends BaseRepository
{
    protected $table = 'checks';
    private $randomizer;

    public function __construct(\Mysqli $mysqliConnection, \Colors $colors)
    {
        $this->randomizer = new Randomizer();
        parent::__construct($mysqliConnection, $colors);
    }

    public function generateSampleData()
    {
        $this->mysqliConnection->query("/**/
INSERT INTO `$this->table` (`id`,`account_id`,`checked_by_user_id`,`date_created`) VALUES 
(22,13,31,'2016-11-04 01:01:01'), 
 (23,13,31,'2017-01-22 01:01:02'), 
  (24,13,31,'2017-02-04 01:01:03'),
    (25,13,31,'2017-02-15 01:01:04'),
      (26,13,31,'2017-02-17 01:01:05'),
        (27,13,31,'2017-03-04 01:01:06'),
          (28,13,31,'2017-02-04 01:02:01'),
            (29,13,31,'2017-03-04 01:02:02'),
              (30,13,31,'2017-04-04 01:03:01'),
                (31,13,31,'2017-01-04 01:04:01'),
                  (32,13,31,'2017-02-04 01:04:02'),
                    (33,13,31,'2017-03-04 01:04:03'),
                      (34,13,31,'2017-04-04 01:05:01'),
                        (35,13,31,'2017-01-04 01:06:01'),
                          (36,13,31,'2017-03-04 01:07:01'),
                            (37,13,31,'2017-03-15 01:08:01'),
                              (38,13,31,'2017-03-10 01:09:01'),
                                (39,13,31,'2017-03-12 01:09:02'),
                                  (40,13,31,'2017-03-17 01:09:03'),
                                    (41,13,31,'2017-03-22 01:09:04'),
                                      (42,13,31,'2017-03-26 01:09:05');
");
    }

    public function count()
    {
        $query = $this->mysqliConnection->query('select count(*) from `' . $this->table . '` INNER JOIN checks_credential_matches ON checks.id = checks_credential_matches.check_id
  INNER JOIN credential_matches ON checks_credential_matches.credential_match_id = credential_matches.id');
        $result = $query->fetch_row();

        return $result[0];
    }

    public function validate($id, $employeeID, $dateCreated)
    {
        $query = 'SELECT
    checks.id,
    credential_matches.employee_id,
    checks.date_created
  FROM `' . $this->table . '`
  INNER JOIN checks_credential_matches ON checks.id = checks_credential_matches.check_id
  INNER JOIN credential_matches ON checks_credential_matches.credential_match_id = credential_matches.id
  WHERE checks.id = "' . $id . '"
  AND credential_matches.employee_id = "' . $employeeID . '"
  AND checks.date_created = "' . $dateCreated . '"';

        return $this->mysqliConnection->query($query);
    }

    public function generateDummyData()
    {
        $this->mysqliConnection->query("/**/
INSERT INTO $this->table
(
`account_id`,
`checked_by_user_id`,
`date_created`)
VALUES(
'" . rand(12, 16) . "',
'" . rand(31, 41) . "',
'" . $this->randomizer->randomDate() . "')") or die($this->mysqliConnection->error);
    }
}