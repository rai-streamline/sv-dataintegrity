<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636\Repositories;

use \Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;
use Com\StreamlineVerify\Tests\DataIntegrity\Randomizer;

class CheckEmployeesRepository extends BaseRepository
{
    protected $table = 'check_employees';
    private $randomizer;

    public function __construct(\Mysqli $mysqliConnection, \Colors $colors)
    {
        $this->randomizer = new Randomizer();
        parent::__construct($mysqliConnection, $colors);
    }

    public function find($employeeID)
    {
        $query = $this->mysqliConnection->query("SELECT * FROM `$this->table` WHERE employee_id = " . $employeeID);
        $result = $query->fetch_all();

        return $result;
    }
    public function generateDummyData($recordCount)
    {
        $this->mysqliConnection->query("INSERT INTO $this->table(`check_id`, `employee_id`, message)VALUES
(
'" . rand(1, $recordCount) . "',
'" . rand(1, $recordCount) . "',
'" . $this->randomizer->randomString(8, true, array(' ')). "'

);");
    }
}