<?php
namespace Com\StreamlineVerify\Tests\DataIntegrity\SVG2636;

use Com\StreamlineVerify\Tests\DataIntegrity\BaseRepository;

class GenerateDummyDataUtility
{
    private $colors;
    private $recordCount;

    public function __construct(\Colors $colors, $recordCount = "")
    {
        $this->colors = $colors;
        $this->recordCount = $recordCount;
    }

    function generate(BaseRepository $repository, $table)
    {

        echo $this->colors->getColoredString('INFO  ', 'blue') .
            "Generating dummy data for " . $this->colors->getColoredString($table, 'yellow') .
            " table...\r\n";
        for ($x = 0; $x < $this->recordCount; ++$x) {
            $repository->generateDummyData($this->recordCount , $x + 1);
            $this->progress($x);
        }

        echo "\r\n";
        echo $this->colors->getColoredString('INFO  ', 'blue') .
            "Done.\r\n";
    }

    private function progress($item) {
        if ($item % 10 == 0) {
            echo '.';
        }
    }
}