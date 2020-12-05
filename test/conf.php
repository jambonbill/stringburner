<?php

require __DIR__."/../vendor/autoload.php";


$gcode=new GC\Gcode("../conf/default.json");
$gcode->debug(true);

echo $gcode->conf();


echo "ok\n";