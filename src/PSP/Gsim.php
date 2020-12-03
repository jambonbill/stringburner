<?php
/**
 * Gsim
 * @version 1.0.0
 * @author jambonbill
 */

namespace PSP;

use Exception;

/**
 * A mini class to help drawing shapes with gcode
 */
class Gsim
{

    private $x=0;//mm
    private $y=0;//global scaling
    private $list=[];
    private $conf=null;

    public function __construct($x, $y, $conf)
    {
        $this->x=$x;
        $this->y=$y;
        $this->conf=$conf;
        //print_r($this->conf);
    }

    public function reset()
    {
        $this->list=[];
    }

    public function comment(string $str)
    {
        $this->list[]=';'.trim($str);
        return $this;
    }

    public function goto(int $x,int $y){
        //set absolute position
        $this->x=$x*$this->conf->scale;
        $this->y=$y*$this->conf->scale;
        //g.push('G0 X'+x+' Y'+y);//Rapid  move
        $this->list[]='G0 X' . $this->x . ' Y' . $this->y . ' ;go fast';//Rapid move to
        //this.list.push('');//Rapid move to
        return $this;
    }

    public function burn()
    {
        $this->list[]='F' . $this->conf->feed . ' S' . $this->conf->spindle . "    ;burn";
        return $this;
    }

    public function stop(){
        $this->list[]='M3 S0   ;stop';
        $this->list[]='';
        return $this;
    }

    public function move($x, $y)
    {
        $this->x+=$x*$this->conf->size*$this->conf->scale;
        $this->y+=$y*$this->conf->size*$this->conf->scale;

        $this->list[]='G1 X' . round($this->x,4) . ' Y' . round($this->y,4);//go to (at work speed1)
        return $this;
    }

    /*
    public function mvx($x){
        $this->x+=$x*_setup.size*_setup.scale;
        $this.list.push('G1 X'+this.x+' Y'+this.y);//go to (at work speed1)
    },

    public function mvy(y){
        $this.y+=y*_setup.size*_setup.scale;
        $this.list.push('G1 X'+this.x+' Y'+this.y);//go to (at work speed1)
    },
    */

    public function gcode()
    {
        //return the gcode
        $this->list[]='';//empty line
        return implode("\n", $this->list);
    }

}