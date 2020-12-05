<?php

require __DIR__."/../vendor/autoload.php";


$font=new GC\Gfont("../font/vectrex.json");


//echo $font->gcode(0,0,"A");
$font->char("A");

$list=$font->list();
echo "Defined chars:";print_r($list);

$data=$font->char("D");
print_r($data);

echo "\nok\n";