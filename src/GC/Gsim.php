<?php
/**
 * Gsim
 * @version 1.0.0
 * @author jambonbill
 */

namespace GC;

use Exception;
use GC\Gconf;


/**
 * A mini class to help drawing shapes with gcode
 */
class Gsim
{

    /**
     * Config object
     * @var null
     */
    private $conf=null;


    /**
     * Current x pos
     * @var integer
     */
    private $x=0;//mm


    /**
     * Current Y pos
     * @var integer
     */
    private $y=0;//global scaling


    /**
     * List of steps
     * @var array
     */
    private $list=[];



    public function __construct(Gconf $conf)
    {
        $this->conf=$conf;
    }


    /**
     * Reset list of instructions
     * @return [type] [description]
     */
    public function reset()
    {
        $this->list=[];
        return $this;
    }


    /**
     * Load a sequence
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function load(array $data)
    {
        $this->reset();
        foreach($data as $step){
            $step[0];//burn(yes/no)
            $step[1];//addx
            $step[2];//addy
        }
    }


    /**
     * Add gcode comment
     * @param  string $str [description]
     * @return [type]      [description]
     */
    public function comment(string $str)
    {
        $this->list[]=';'.trim($str);
        return $this;
    }


    /**
     * Go to coords, fast
     * @param  int    $x [description]
     * @param  int    $y [description]
     * @return [type]    [description]
     */
    public function goto(int $x,int $y){
        //set absolute position
        $this->x=$x*$this->conf->scale();
        $this->y=$y*$this->conf->scale();
        //g.push('G0 X'+x+' Y'+y);//Rapid  move
        $this->list[]='G0 X' . $this->x . ' Y' . $this->y . ' ;go fast';//Rapid move to
        return $this;
    }


    /**
     * Start Burn
     * @return [type] [description]
     */
    public function burn()
    {
        $this->list[]='F' . $this->conf->feed() . ' S' . $this->conf->spindle() . "    ;burn";
        return $this;
    }


    /**
     * Stop burn
     * @return [type] [description]
     */
    public function stop()
    {
        $this->list[]='M3 S0   ;stop';
        $this->list[]='';
        return $this;
    }


    /**
     * Move to, at work speed
     * @param  [type] $x [description]
     * @param  [type] $y [description]
     * @return [type]    [description]
     */
    public function move($x, $y)
    {
        $this->x+=$x*$this->conf->size()*$this->conf->scale();
        $this->y+=$y*$this->conf->size()*$this->conf->scale();

        $this->list[]='G1 X' . round($this->x,4) . ' Y' . round($this->y,4);//go to (at work speed1)
        return $this;
    }


    /**
     * perform a sequence of actions
     * @return [type] [description]
     */
    public function run(array $data)
    {
        $burnin=false;
        foreach($data as $step){

            if ($step[0]&&!$burnin) {
                $this->burn();
                $burnin=true;
            }

            if (!$step[0]) {
                $this->stop();
                $burnin=false;
            }
            // Move to
            $this->move($step[1], $step[2]);
        }

        //always stop burn
        $this->stop();

        return $this;
    }


    /**
     * Return gcode string
     * @return [type] [description]
     */
    public function gcode()
    {
        $this->list[]='';//empty line
        return implode("\n", $this->list);
    }

}