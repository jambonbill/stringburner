<?php

require __DIR__."/../vendor/autoload.php";


$conf=new GC\Gconf("../conf/default.json");

$sim=new GC\Gsim($conf);
$font=new GC\Gfont("../font/vectrex.json");

$sim->goto(10,10);

echo $sim->gcode();

//echo $gcode->toString();
//$gcode->toFile("/tmp/test.gcode");

echo "ok\n";