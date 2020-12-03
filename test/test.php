<?php

require __DIR__."/../vendor/autoload.php";


$gcode=new PSP\Gcode("../json/test.json");

echo $gcode->toString();
//$gcode->toFile("/tmp/test.gcode");

echo "ok\n";