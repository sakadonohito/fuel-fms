<?php

require_once APPPATH.'vendor/FX/FX.php';
require_once APPPATH.'vendor/FX/FX_Error.php';

use Fuel\Core;

class Model_Fms_Connection {

    public $fms = null;

    public function __construct($lay){
        \Config::load('db',true);
        $confName = \Config::get('db.active');
        $fmsConf = \Config::get("db.{$confName}");

        $this->fms = new FX($fmsConf['host'],$fmsConf['port'],'FMPro8',$fmsConf['scheme']);
        $this->fms->SetDBData($fmsConf['database'],$lay);
        $this->fms->SetDBUserPass($fmsConf['user'],$fmsConf['pass']);
        $this->fms->SetCharacterEncoding('utf8');
        $this->fms->SetDataParamsEncoding('utf8');
    }

    public function getValueList($vlName) {
        $fmMetaData = $this->fms->FMView(true,'full',true);
        if($fmMetaData instanceof FX_Error){
            return array('error'=>'エラーが発生しました。');
        }

        if(empty($fmMetaData)){
            return array('error'=>'読み込みに失敗しました。');
        }else{
            $temp = $fmMetaData['valueLists'];
        }
        return $temp[$vlName];
    }

    public function changeArrayToOptionList($array=array(),$default=''){
        $options = '';
        foreach($array as $key => $value){
            $decValue = mb_convert_encoding($value,'UTF-8','HTML-ENTITIES');
            if($default == $decValue){
                $options .= "<option value='${decValue}' selected>${decValue}</option>\n";
            }else{
                $options .= "<option value='${decValue}'>${decValue}</option>\n";
            }
        }
        return $options;
    }

}
?>