<?php

require __DIR__."/../vendor/autoload.php";


$gcode=new GC\Gcode("../conf/default.json");
$gcode->debug(true);
$gcode->loadFont("../font/square.json");

echo $gcode->conf()."\n";
//$gcode->text("<JAMBONBILL@GMAIL.COM>\n<JAMBONBILL@GMAIL.COM>\n<JAMBONBILL@GMAIL.COM>");

$text=file_get_contents('poem.txt');
$gcode->text("@JAMBON");

//echo $gcode->toString();

$gcode->toFile("/tmp/test.gcode");

echo "ok\n";