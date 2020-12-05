<?php
/**
 * Gfont
 * @version 1.0.0
 * @author jambonbill
 */

namespace GC;

use Exception;
use GC\Gsim;


/**
 * Manage vector font
 */
class Gfont
{

    /**
     * The config object
     * @var null
     */
    private $conf=null;

    /**
     * Font name
     * @var string
     */
    private $name='';


    /**
     * Font author
     * @var string
     */
    private $author='';


    /**
     * Font data
     * @var array
     */
    private $chars=[];


    /**
     * Font width
     * (compress/expend)
     * @var integer
     */
    private $width=1;//1=>100% width


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
     * Sequence data for a given char(code)
     * @param  [type] $n [description]
     * @return [type]    [description]
     */
    public function char($n): array
    {
        if (is_string($n)) {
            $n=ord($n);
        }

        if (isset($this->chars[$n])) {
            //transform here
            $char=$this->chars[$n];
            foreach($char as $k=>$v){
                $char[$k][1]*=$this->width;//apply width (squeeze/compress/expend)
                //$char[$k][2]*=0.5;//height
            }
            //print_r($char);exit;
            return $char;
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