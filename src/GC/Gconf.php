<?php
/**
 * Gconf
 * @version 1.0.0
 * @author jambonbill
 */

namespace GC;

use Exception;

/**
 * Config entity
 */
class Gconf
{


    /**
     * Font size
     * @var integer
     */
    private $size=8;


    /**
     * Global Scale
     * @var integer
     */
    private $scale=1;


    /**
     * Character spacing
     * @var integer
     */
    private $spacing=1;


    /**
     * Work speed
     * @var integer
     */
    private $feed=100;


    /**
     * Burn Power
     * @var integer
     */
    private $spindle=50;


    public function __construct(string $path)
    {
        $this->load($path);
    }


    /**
     * Load config file
     * @param  string $path [description]
     * @return [type]       [description]
     */
    public function load(string $path)
    {
        if (!is_file($path)) {
            throw new Exception("config $path not found", 1);
        }

        $txt=file_get_contents($path);
        $json=json_decode($txt);
        $err=json_last_error();

        if ($err) {
            throw new Exception("JSON EROR:".json_last_error(), 1);
        }

        $this->size=$json->size;
        $this->scale=$json->scale;
        $this->spacing=$json->spacing;
        $this->feed=$json->feed;
        $this->spindle=$json->spindle;

        return $this;
    }


    /**
     * [size description]
     * @return [type] [description]
     */
    public function size()
    {
        return $this->size;
    }


    /**
     * [scale description]
     * @return [type] [description]
     */
    public function scale()
    {
        return $this->scale;
    }


    /**
     * [spacing description]
     * @return [type] [description]
     */
    public function spacing()
    {
        return $this->spacing;
    }


    /**
     * [feed description]
     * @return [type] [description]
     */
    public function feed()
    {
        return $this->feed;
    }


    /**
     * [spindle description]
     * @return [type] [description]
     */
    public function spindle()
    {
        return $this->spindle;
    }


    function __toString()
    {
        $dat=[];
        $dat['size']=$this->size;
        $dat['scale']=$this->scale;
        $dat['spacing']=$this->spacing;
        $dat['feed']=$this->feed;
        $dat['spindle']=$this->spindle;
        return json_encode($dat, JSON_PRETTY_PRINT);
    }
}