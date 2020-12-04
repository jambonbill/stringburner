<?php
/**
 * Gfont
 * @version 1.0.0
 * @author jambonbill
 */

namespace PSP;

use Exception;
use PSP\Gsim;


/**
 * Manage vector font (monospace)
 */
class Gfont
{

    private $conf=null;//

    private $author='';//Font author
    private $name='';//Font name
    private $chars=[];//Font data

    public function __construct(string $path)
    {
        if ($path) {
            $this->load($path);
        }
    }

    /**
     * Load a JSON font
     * @param  string $path [description]
     * @return [type]       [description]
     */
    public function load(string $path)
    {
        //Load a FONT
        if (!is_file($path)) {
            throw new Exception("$path not found", 1);
        }

        $txt=file_get_contents($path);
        $json=json_decode($txt);
        $err=json_last_error();

        if ($err) {
            throw new Exception("JSON EROR:".json_last_error(), 1);
        }

        $font=(array)$json;
        $keys=array_keys($font);
        //print_r($keys);
        //
        if ($font['name']) {
            $this->name=$font['name'];
        }

        if ($font['author']) {
            $this->author=$font['author'];
        }

        $this->chars=[];
        foreach($keys as $key){
            if (strlen($key)===1) {
                //print_r($font[$key]);exit;
                $this->setChar($key, $font[$key]);
            }
        }

        return $this;
    }


    /**
     * define a Font Character
     * @param [type] $chr  [description]
     * @param [type] $data [description]
     */
    public function setChar(string $chr, $data)
    {
        if (strlen($chr)!==1) {
            throw new Exception("Error Processing CHR", 1);
        }

        //TODO watch data format
        $char=[];//a sequence of [burn|x|y]

        if (is_string($data)) {

            $steps=explode("|",$data);

            foreach ($steps as $step) {
                $x=explode(",", $step);
                if(count($x)!=3)continue;
                //$bits count must be 3
                $bits=array(3);
                $bits[0]=+$x[0];
                $bits[1]=+$x[1];
                $bits[2]=+$x[2];
                $char[]=$bits;
            }

        } else if (is_array($data)) {
            throw new Exception("Not implemented", 1);

        }
        $this->chars[ord($chr)]=$char;
    }


    /**
     * Sequence data for a given char
     * @param  [type] $n [description]
     * @return [type]    [description]
     */
    public function char($n): array
    {
        if (is_string($n)) {
            $n=ord($n);
        }

        if (isset($this->chars[$n])) {
            return $this->chars[$n];
        }else{
            echo "Warning: Font Char #$n not found;\n";
        }
        return [];
    }


    /**
     * Return list of chars
     * @return [type] [description]
     */
    public function list(): array
    {
        $list=[];
        for($i=0;$i<256;$i++){
            if(isset($this->chars[$i])){
                $list[]=$i;
            }
        }
        return $list;
    }


    /**
     * Return Gcode for the given char at given x/y position
     * (was)function vectorCode
     * @param  [type] $x   [description]
     * @param  [type] $y   [description]
     * @param  string $chr [description]
     * @return [type]      [description]
     */
    /*
    public function gcode(float $x, float $y, string $chr)
    {
        $sim=new Gsim($this->conf);
        $sim->goto($x,$y);
        $sim->comment("Letter ".$chr);
    }
    */

    /**
     * return Font as Json
     * @return [type] [description]
     */
    public function toJson()
    {
        $out=[];
        $out['name']=$this->name;
        $out['author']=$this->author;
        $out['chars']=$this->chars;
        return json_encode($out, JSON_PRETTY_PRINT);
    }

    public function __toString()
    {
        return $this->name;
    }

}