<?php
/**
 * StringBurner Manager : File management and Web UI Methods
 * @version 1.0.0
 * @author jambonbill
 */

namespace GC;

use Exception;

class SBManager
{

    private $pathFonts='../../font';
    private $pathProfiles='../../conf';

    public function __construct()
    {
        //make sure paths exist
    }

    public function getFonts()
    {
        $files=glob($this->pathFonts . "/*.json");
        return $files;
    }

    public function getProfiles()
    {
        $files=glob($this->pathProfiles . "/*.json");
        return $files;
    }
    
}
