<?php
//Generate and force download GCode file

require "../../vendor/autoload.php";

//print_r($_POST);



$gcode=new GC\Gcode("../../conf/default.json");
$gcode->debug(true);
$gcode->loadFont("../../font/square.json");

//echo $gcode->conf()."\n";
//$gcode->text("<JAMBONBILL@GMAIL.COM>\n<JAMBONBILL@GMAIL.COM>\n<JAMBONBILL@GMAIL.COM>");

$gcode->text("@JAMBON");

//echo $gcode->toString();

//$gcode->toFile("/tmp/test.gcode");
header ('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.'download.gc');
echo $gcode->toString();
