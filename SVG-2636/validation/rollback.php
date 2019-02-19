<?php
include "../../colors.php";
include "../../header.php";
echo $colors->getColoredString("INFO ", "blue");
echo "Doing count on " . $colors->getColoredString("credential_matches", "yellow") . " table. \r\n";
$query = $mysqli->query("SELECT * FROM `credential_matches`");
$result = count($query->fetch_all());
echo $colors->getColoredString("INFO ", "blue");
echo "There are 30 records updated to be pulled for rollback. Subtract \r
     the current total credential_matches table after rollback vs the dummy data inserted on the credential matches table.\r\n";
echo $colors->getColoredString("INFO ", "blue");
echo "Total count of data after rollback: " . $colors->getColoredString($result, "green") . "\r\n";
