<?php
/**
 * Gcode stringulator
 * @version 1.0.0
 * @author jambonbill
 */

namespace GC;

use Exception;

use GC\Gconf;//config entity
use GC\Gfont;//vector font
use GC\Gsim; //simulator

class Gcode
{

    private $DEBUG=false;

    private $conf=null;
    private $font=null;

    private $text='';//the text string we want to burn

    public function __construct(string $configpath)
    {
        $this->conf=new Gconf($configpath);

    }


    /**
     * Return config entity
     * @return [type] [description]
     */
    public function conf()
    {
        return $this->conf;
    }

    /**
     * Load Parameters from JSON
     * @param  string $path [description]
     * @return [type]       [description]
     */

    public function loadFont(string $path)
    {
        if (!is_file($path)) {
            throw new Exception("File not found", 1);
        }

        //$this->font->load($path);
        $this->font=new Gfont($path);
        return $this;
    }


    /**
     * [debug description]
     * @return [type] [description]
     */
    public function debug(bool $bool)
    {
        $this->DEBUG=$bool;
    }


    /**
     * Return Gfont
     * @return [type] [description]
     */
    public function font()
    {
        return $this->font;
    }


    /**
     * Set Text
     * @return [type] [description]
     */
    public function text(string $str)
    {
        $this->text=$str;
    }





    /**
     * The gcode header
     * @return [type] [description]
     */
    public function header()
    {
        $str ='%'."\n";
        $str.=';made with jambonbill/stringburner'."\n";
        $str.=';https://github.com/jambonbill/stringburner'."\n";

        $str.='G90'."\n";//; DONT_PANIC2

        $str.='G17 ;Plane select. XY (default)'."\n";

            //;G21 - to use millimeters for length units.
            //G21         ; Set units to mm
            //G90         ; Absolute positioning
        $str.='G21 ; Set units to mm'."\n";

        $str.='G91 Z0'."\n";//meh?
        $str.='G90 ; Absolute positioning'."\n";
            //; CUT SKETCH

        $str.='; SCALE is '.$this->conf->scale()."\n";

        //;Tx - prepare to change to tool x.
        $str.='T1'."\n";

        //;LASER ON
        $str.='M3'."\n";
        return $str;
    }


    /**
     * End of Gcode string
     * @return [type] [description]
     */
    public function footer()
    {
        $str="\n";
        $str.='M3 S0'."\n";//laser off
        $str.='M5'."\n";//?

        $str.='G0 X0 Y0    ;GO HOME'."\n";//Rapid home
        //?
        $str.='G91 G0 X0 Y0'."\n";
        $str.='%';
        return $str;
    }


    /**
     * Generate Gcode string
     * @return [type] [description]
     */
    public function make(): string
    {
        $str=$this->header();

        $rows=explode("\n", $this->text);

        foreach($rows as $line=>$row)
        {
            for ($i=0; $i<strlen($row); $i++) {

                $chr=$row[$i];

                $y=-$line;

                $px=($i*$this->conf->size())+($i*$this->conf->spacing())*$this->conf->scale();
                $py=($y*$this->conf->size())+($y*$this->conf->spacing())*$this->conf->scale();

                $sim=new Gsim($this->conf);
                $sim->goto($px, $py);
                $sim->comment("Letter #$chr");
                $sim->run($this->font()->char($chr));
                //$conf->string[$i]
                //$str.=$this->vectorCode($px,$py, $conf->string[$i]);
                $str.=$sim->gcode();
            }
        }




        $str.=$this->footer();
        return $str;
    }


    /**
     * Generate gcode and save to file
     * @param  string $path [description]
     * @return [type]       [description]
     */
    public function toFile(string $path)
    {
        if (!$path) {
            throw new Exception("Error Processing Request", 1);
        }

        $gcode=$this->make();

        $f=fopen($path,"w+");
        fwrite($f, $gcode);
        fclose($f);
    }

}