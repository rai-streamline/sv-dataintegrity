<?php
$mysqli = new mysqli('localhost', 'root', 'root', 'streamline_local');
$colors = new Colors();

echo "\r\n";
echo $colors->getColoredString('                                *** ', 'brown') . "AUTOMATED TEST for " .
    $colors->getColoredString('SVG-2636', 'green') .
    " migration script" . $colors->getColoredString(' *** ', 'brown') . "\r\n\r\n";
