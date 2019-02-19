<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\Randomizer;

class EmployeesRepository extends BaseRepository
{
    private $randomizer;
    protected $table = 'employees';

    public function __construct(\Mysqli $mysqliConnection, \Colors $colors)
    {
        $this->randomizer = new Randomizer();
        parent::__construct($mysqliConnection, $colors);
    }

    public function count()
    {
        $query = $this->mysqliConnection->query("SELECT
    '' as first_name,
    '' as last_name,
    credential_matches.credential_id,
    employees.license_type_id
  FROM employees
    INNER JOIN check_employees ON employees.id = check_employees.employee_id
    INNER JOIN checks_credential_matches ON check_employees.check_id = checks_credential_matches.check_id
    INNER JOIN credential_matches ON checks_credential_matches.credential_match_id = credential_matches.id
  WHERE credential_matches.registry NOT IN ('NPPES', 'MOO', 'NYOPMC')
  GROUP BY credential_matches.credential_id;") or die ($this->mysqliConnection->error);
        $result = $query->fetch_all();

        return count($result);
    }

    public function deleteEmployees()
    {
        $this->mysqliConnection->query("TRUNCATE `$this->table`");    }

    public function generateDummyData()
    {
        $this->mysqliConnection->query("INSERT INTO `$this->table`
(
`employeelist_id`,
`custom_id`,
`facility_id`,
`terminated`,
`date_of_termination`,
`date_termination_entered`,
`termination_note`,
`first_name`,
`middle_name`,
`last_name`,
`alt_first_name`,
`alt_middle_name`,
`alt_last_name`,
`alt_first_name_2`,
`alt_middle_name_2`,
`alt_last_name_2`,
`alt_first_name_3`,
`alt_middle_name_3`,
`alt_last_name_3`,
`alt_first_name_4`,
`alt_middle_name_4`,
`alt_last_name_4`,
`alt_first_name_5`,
`alt_middle_name_5`,
`alt_last_name_5`,
`maiden_name`,
`alt_maiden_name_1`,
`alt_maiden_name_2`,
`business`,
`alt_business1`,
`alt_business2`,
`npi`,
`certification_number`,
`certification_state`,
`license_type_id`,
`alt_certification_number`,
`alt_certification_state`,
`alt_license_type_id`,
`upin`,
`date_of_birth`,
`social_security_num`,
`ssn_hash`,
`ssn_last_four`,
`address1`,
`address2`,
`city`,
`state`,
`zip`,
`alt_address1_1`,
`alt_address2_1`,
`alt_city_1`,
`alt_state_1`,
`alt_zip_1`,
`notes`,
`date_last_checked`,
`last_check_had_matches`,
`caches_invalid`,
`created_by`,
`date_created`,
`date_modified`,
`last_modified_by`)

VALUES
(
'" . rand(1, 15) . "',
'" . $this->randomizer->randomString(5, true, array('-')) . "',
'" . $this->randomizer->randomString(3, true) . "',
'" . rand(0, 1) . "',
'" . $this->randomizer->randomDate(true) . "',
'" . $this->randomizer->randomDate(true) . "',
'" . $this->randomizer->randomString(50, true, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(1, true, array(' ')) . "',
'" . $this->randomizer->randomString(15, false, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(2, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomString(8, true, array(' ')) . "',
'" . $this->randomizer->randomDate(true, true) . "',
'" . $this->randomizer->randomString(8, true, array('-')) . "',
'" . $this->randomizer->randomString(8, true, array('-')) . "',
'" . $this->randomizer->randomString(4, true) . "',
'" . $this->randomizer->randomString(25, true, array(' ')) . "',
'" . $this->randomizer->randomString(15, true, array(' ')) . "',
'" . $this->randomizer->randomString(15, true, array(' ')) . "',
'" . $this->randomizer->randomString(2) . "',
'" . rand(900, 4000) . "',
'" . $this->randomizer->randomString(15, true, array(' ')) . "',
'" . $this->randomizer->randomString(15, true, array(' ')) . "',
'" . $this->randomizer->randomString(10, true, array(' ')) . "',
'" . $this->randomizer->randomString(2) . "',
'" . rand(900, 4000) . "',
'" . $this->randomizer->randomString(50, true, array(' ')) . "',
'" . $this->randomizer->randomDate(true) . "',
'-1',
1,
31,
'" . $this->randomizer->randomDate() . "',
CURRENT_TIMESTAMP,
" . rand(1, 31) . ")
")or die($this->mysqliConnection->error);
    }
}