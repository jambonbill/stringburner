<?php
/**
 * Gcode stringulator
 * @version 1.0.0
 * @author jambonbill
 */

namespace PSP;

use Exception;
use PSP\Gsim;

class Gcode
{

    private $DEBUG=false;

    private $conf=null;

    public function __construct(string $path)
    {
        $this->loadJson($path);
    }


    /**
     * Load Parameters from JSON
     * @param  string $path [description]
     * @return [type]       [description]
     */
    public function loadJson(string $path)
    {
        if (!is_file($path)) {
            throw new Exception("File not found", 1);
        }

        $txt=file_get_contents($path);
        $json=json_decode($txt);
        $err=json_last_error();

        if ($err) {
            throw new Exception("JSON EROR:".json_last_error(), 1);
        }

        // Here we must make sure that `conf` hold all the necessary values

        $this->conf=$json;

        /*
        if ($this->conf->string) {
            $this->toString();
        }
        */
        return $this;
    }


    /**
     * Return GCODE string for a given Charcode
     * @param  float  $x   [description]
     * @param  float  $y   [description]
     * @param  int    $chr [description]
     * @return [type]      [description]
     */
    public function vectorCode(float $x,float $y, string $chr)
    {
        //echo "vectorCode($chr)\n";
        $sim=new Gsim($x, $y, $this->conf);
        $sim->goto($x,$y);
        $sim->comment("Letter ".$chr);

        switch($chr){

            case " ":
                $sim->comment("[Space]");
                break;


            case "/":
                $sim->burn()
                    ->move(0.8,1);
                    break;


            case "\\":
                $sim->move(0.8,0)->burn()
                    ->move(-0.8,1);
                    break;


            case "+":
                $sim->move(0.4,0)
                    ->move(0,0.2)->burn()
                    ->move(0,0.6)->stop()
                    ->move(-0.4,-0.4)->burn()
                    ->move(0.8,0);
                    break;


            case "-":
                $sim->move(0,0.5)->burn()
                    ->move(0.8,0);
                    break;

            case ".":
                $sim->move(0.4,0)->burn()
                    ->move(0.2,0)
                    ->move(0,0.2)
                    ->move(-0.2,0)
                    ->move(0,-0.2);
                    break;


            case "=":
                $sim->move(0,0.3)->burn()
                    ->move(0.8,0)->stop()
                    ->move(0,0.3)->burn()
                    ->move(-0.8,0);
                    break;


            case "A":
                $sim->burn()
                    ->move(0.4,1)
                    ->move(0.4,-1)->stop()
                    ->move(-0.15,0.25)->burn()
                    ->move(-0.6,0);
                    break;

            case "B":
                $sim->burn()
                    ->move(0.6,0)
                    ->move(0.2,0.2)
                    ->move(0,0.3)

                    ->move(-0.7,0)
                    ->move(0.7,0)

                    ->move(0,0.3)
                    ->move(-0.2,0.2)
                    ->move(-0.6,0)->stop()
                    ->move(0.2,0)->burn()
                    ->move(0,-1);
                    break;

            case "C"://67
                $sim->move(0.75,0)->burn()
                    ->move(-0.75,0)
                    ->move(0,1)
                    ->move(0.75,0);
                    break;


            case "D":
                $sim->burn()
                    ->move(0.6,0)
                    ->move(0.2,0.2)
                    ->move(0,0.6)
                    ->move(-0.2,0.2)
                    ->move(-0.6,0)->stop()
                    ->move(0.2,0)->burn()
                    ->move(0,-1);
                    break;

            case "E"://69
                $sim->move(0.8,1)->burn()
                    ->move(-0.8,0)
                    ->move(0,-1)
                    ->move(0.8,0)->stop()
                    ->move(-0.8,0.5)
                    ->burn()->move(0.6,0);
                    break;


            case "F"://70
                $sim->burn()
                    ->move(0,0.5)
                    ->move(0.5,0)
                    ->move(-0.5,0)
                    ->move(0,0.5)
                    ->move(0.75,0);
                    break;



            case "G"://71
                $sim->move(0.8,1)//start from top right
                    ->burn()
                    ->move(-0.8,0)
                    ->move(0,-1)
                    ->move(0.8,0)
                    ->move(0,0.5)
                    ->move(-0.4,0);
                    break;

            case "H"://72
                $sim->burn()
                    ->move(0,1)
                    ->move(0,-0.5)
                    ->move(0.75,0)
                    ->move(0,0.5)
                    ->move(0,-1);
                    break;

            case "I"://73
                $sim->burn()
                    ->move(0.75,0)
                    ->move(-0.75/2,0)
                    ->move(0,1)
                    ->move(-0.75/2,0)
                    ->move(0.75,0);
                    break;



            case "J"://74://J
                $sim->burn()
                    ->move(0.4,0.1)
                    ->move(0,0.9)
                    ->move(-0.4,0)
                    ->move(0.8,0);
                break;

            case "K":
                $sim->burn()
                    ->move(0,1)->stop()
                    ->move(0.8,0)->burn()
                    ->move(-0.8,-0.5)
                    ->move(0.8,-0.5);

            case "L":
                $sim->move(0.75,0)->burn()
                    ->move(-0.75,0)
                    ->move(0,1);
                    break;

            case "M"://78
                $sim->burn()
                    ->move(0,1)
                    ->move(0.4,-0.5)
                    ->move(0.4,0.5)
                    ->move(0,-1);
                    break;

            case "N"://78
                $sim->burn()
                    ->move(0,1)
                    ->move(0.75,-1)
                    ->move(0,1);
                    break;

            case "O"://79
                $sim->burn()
                    ->move(0,1)
                    ->move(0.75,0)
                    ->move(0,-1)
                    ->move(-0.75,0);
                    break;

            case "P"://80
                $sim->burn()
                    ->move(0,1)
                    ->move(0.75,0)
                    ->move(0,-0.5)
                    ->move(-0.75,0);
                    break;

            case "Q":
                $sim->burn()
                ->move(0,1)
                ->move(0.8,0)
                ->move(0,-0.5)
                ->move(-0.4,-0.5)
                ->move(-0.4,0)->stop()//back to origin
                ->move(0.4,0.5)->burn()
                ->move(0.4,-0.5);
                break;

            case "R"://R
                $sim->burn()
                    ->move(0,1)
                    ->move(0.75,0)
                    ->move(0,-0.5)
                    ->move(-0.75,0)
                    ->move(0.75,-0.5);
                    break;

            case "S":
                $sim->burn()
                    ->move(0.75,0)
                    ->move(0,0.5)
                    ->move(-0.75,0)
                    ->move(0,0.5)
                    ->move(0.75,0);
                    break;

            case "T":
                $sim->move(0.75/2,0)->burn()
                    ->move(0,1)
                    ->move(-0.75/2,0)
                    ->move(0.75,0);
                    break;

            case "U":
                $sim->move(0,1)->burn()
                    ->move(0,-1)
                    ->move(0.75,0)
                    ->move(0,1);
                    break;

            case "V":
                $sim->move(0,1)->burn()//move up first
                    ->move(0.4,-1)
                    ->move(0.4,1);
                    break;

            case "W":
                $sim->move(0,1)->burn()//move up first
                    ->move(0,-1)
                    ->move(0.4,0.5)
                    ->move(0.4,-0.5)
                    ->move(0,1);
                    break;


            case "X":
                $sim->burn()
                    ->move(0.8,1)->stop()
                    ->move(-0.8,0)->burn()
                    ->move(0.8,-1);
                    break;
                break;

            case "Y":
                $sim->move(0.4,0)->burn()
                    ->move(0,0.5)
                    ->move(-0.4,0.5)->stop()
                    ->move(0.4,-0.5)->burn()
                    ->move(0.4,0.5);
                break;

            case "Z":
                $sim->move(0.8,0)->burn()
                    ->move(-0.8,0)
                    ->move(0.8,1)
                    ->move(-0.8,0);
                break;

            case "0":
                $sim->burn()
                    ->move(0.8,0)
                    ->move(0,1)
                    ->move(-0.8,0)
                    ->move(0,-1)
                    ->move(0.8,1);
                    break;

            case "1":
                $sim->burn()->move(0.8,0)->stop()
                    ->move(-0.4,0)->burn()
                    ->move(0,1)
                    ->move(-0.4,-0.25);
                    break;

            case "2":
                $sim->move(0.8,0)->burn()
                    ->move(-0.8,0)
                    ->move(0.8,0.5)
                    ->move(0,0.5)
                    ->move(-0.8,0)
                    ->move(0,-0.1);
                    break;

            case "3":
                $sim->burn()
                    ->move(0.8,0)
                    ->move(0,1)
                    ->move(-0.8,0)->stop()
                    ->move(0,-0.5)->burn()
                    ->move(0.8,0);
                    break;

            case "4":
                $sim->move(0.8,0)->burn()
                    ->move(0,1)->stop()
                    ->move(-0.8,0)->burn()
                    ->move(0,-0.5)
                    ->move(0.8,0);
                    break;

            case "5":
                $sim->burn()
                    ->move(0.8,0)
                    ->move(0,0.5)
                    ->move(-0.8,0)
                    ->move(0,0.5)
                    ->move(0.8,0);
                    break;

            case "6":
                $sim->move(0,0.5)->burn()
                    ->move(0.8,0)
                    ->move(0,-0.5)
                    ->move(-0.8,0)
                    ->move(0,1)
                    ->move(0.8,0);
                    break;

            case "7":
                $sim->burn()
                    ->move(0.8,1)
                    ->move(-0.8,0)->stop()
                    ->move(0,-0.5)->burn()
                    ->move(0.8,0);
                    break;

            case "8":
                $sim->burn()
                    ->move(0.8,0)
                    ->move(0,1)
                    ->move(-0.8,0)
                    ->move(0,-1)->stop()
                    ->move(0,0.5)->burn()
                    ->move(0.8,0);
                    break;

            case "9":
                $sim->burn()
                    ->move(0.8,0)
                    ->move(0,1)
                    ->move(-0.8,0)
                    ->move(0,-0.5)
                    ->move(0.8,0);
                    break;


            default:
                echo "Error: Chr $chr not defined\n";
        }

        $sim->stop();

        return $sim->gcode();
    }


    public function header()
    {
        $str ='%'."\n";
        $str =';made with jambonbill/stringburner'."\n";
        $str =';git...'."\n";

        $str.='G90'."\n";//; DONT_PANIC2

        $str.='G17 ;Plane select. XY (default)'."\n";

            //;G21 - to use millimeters for length units.
            //G21         ; Set units to mm
            //G90         ; Absolute positioning
        $str.='G21 ; Set units to mm'."\n";

        $str.='G91 Z0'."\n";//meh?
        $str.='G90 ; Absolute positioning'."\n";
            //; CUT SKETCH

        $str.='; SCALE is '.$this->conf->scale."\n";

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

    public function toString(): string
    {

        $str=$this->header();

        $conf=$this->conf;

        //make gcode for each letters
        for($i=0; $i<strlen($this->conf->string); $i++){
            $x=$i;
            $y=0;
            $px=($x*$conf->size)+($x*$conf->spacing)*$conf->scale;
            $py=($y*$conf->size)+($y*$conf->spacing)*$conf->scale;

            $str.=$this->vectorCode($px,$py, $conf->string[$i]);
        }

        $str.=$this->footer();
        return $str;
    }

    public function toFile(string $path)
    {
        if(!$path){
            throw new Exception("Error Processing Request", 1);
        }

        $gcode=$this->toString();

        $f=fopen($path,"w+");
        fwrite($f, $gcode);
        fclose($f);
    }

}