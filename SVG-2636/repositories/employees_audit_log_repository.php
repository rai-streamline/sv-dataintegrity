<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\Randomizer;

class EmployeesAuditLogRepository extends BaseRepository
{
    private $randomizer;
    protected $table = 'employees_audit_log';

    public function __construct(\Mysqli $mysqliConnection, \Colors $colors)
    {
        $this->randomizer = new Randomizer();
        parent::__construct($mysqliConnection, $colors);
    }

    public function generateDummyData($recordCount, $increment)
    {
        $this->mysqliConnection->query("INSERT INTO `$this->table`
(
`employee_id`,
`action`,
`employeelist_id`,
`custom_id`,
`facility_id`,
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
`created_by`,
`date_created`,
`last_modified_by`,
`terminated`)
VALUES
(
$increment,
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . rand(1, $recordCount) . "',
'" . rand(1, $recordCount) . "',
'" . rand(1, $recordCount) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . rand(1, $recordCount) . "',
'" . rand(1, $recordCount) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . rand(1, $recordCount) . "',
'" . rand(1, $recordCount) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . rand(1, $recordCount) . "',
'" . rand(1, $recordCount) . "',
'" . $this->randomizer->randomDate() . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(4, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . rand(1, $recordCount) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . $this->randomizer->randomString(12, false, array(' ')) . "',
'" . rand(1, $recordCount) . "',
'" . rand(1, $recordCount) . "',
CURRENT_TIMESTAMP,
'" . rand(0, 1) . "')") or die($this->mysqliConnection->error);
    }
}