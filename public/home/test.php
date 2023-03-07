<?php
//Tests

require "../../vendor/autoload.php";

$Man=new GC\SBManager();


$fonts=$Man->getFonts();
print_r($fonts);

$profiles=$Man->getProfiles();
print_r($profiles);

