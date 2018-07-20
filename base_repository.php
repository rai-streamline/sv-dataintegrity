<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity;

class BaseRepository
{
    protected $mysqliConnection;
    protected $colors;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct(\Mysqli $mysqliConnection, \Colors $colors)
    {
        $this->mysqliConnection = $mysqliConnection;
        $this->colors = $colors;
    }

    public function truncate()
    {
        //we can't actually truncate as table has foreign keys
        $this->mysqliConnection->query('DELETE FROM `' . $this->table . '`')
        or die($this->colors->getColoredString('ERROR ', 'red') .
            $this->mysqliConnection->error . __FILE__ . ' ' . __LINE__ . "\r\n\r\n");
        $this->mysqliConnection->query('ALTER TABLE `' . $this->table . '` AUTO_INCREMENT = 1')
        or die($this->colors->getColoredString('ERROR ', 'red') .
            $this->mysqliConnection->error . __FILE__ . ' ' . __LINE__ . "\r\n\r\n");
    }

    public function randomPick($items = 5, $fullPick = false) {
        if (is_int($items)) {
            if ($fullPick) {
                $query = $this->mysqliConnection->query('SELECT * FROM `' . $this->table . '`');
            }
            else {
                $query = $this->mysqliConnection->query('SELECT * FROM `' . $this->table .
                    '` order by RAND() LIMIT ' . $items);
            }


            return $query->fetch_all(MYSQLI_BOTH);
        }

        die('value passed on randomizer is not an integer');
    }

    public function count()
    {
        $query = $this->mysqliConnection->query('SELECT count(*) FROM `' . $this->table . '`')
        or die($this->colors->getColoredString('ERROR ', 'red') .
            $this->mysqliConnection->error . "\r\n\r\n");
        $result = $query->fetch_row();

        return $result[0];
    }

    public function fetch($id)
    {
        $query = $this->mysqliConnection->query("SELECT * FROM `$this->table` 
WHERE `$this->primaryKey` = $id");

        return $query->fetch_assoc();
    }

}