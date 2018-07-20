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

    public function reset()
    {
        $this->mysqliConnection->query("DELETE FROM `$this->table` WHERE `id` > 191");
        $this->mysqliConnection->query("ALTER TABLE `$this->table` AUTO_INCREMENT = 192");
    }

    public function generateDummyData($recordCount)
    {
        $this->mysqliConnection->query("INSERT INTO $this->table(`check_id`, `employee_id`, message)VALUES
(
'" . rand(1, $recordCount) . "',
'" . rand(1, $recordCount + 81) . "',
'" . $this->randomizer->randomString(8, true, array(' ')). "'

);");
    }
}