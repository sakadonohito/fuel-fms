<?php
/**
 * For FileMaker
 *
 * @package    Fuel
 * @version    1.0
 * @author     sakadonohito oosawa
 * @license    MIT License
 * @copyright  2013 sakadonohito
 * @link       
 */

require_once APPPATH.'vendor/FX/FX.php';
require_once APPPATH.'vendor/FX/FX_Error.php';

use Fuel\Core;

class Model_Fms_Fmserver extends \Model_Crud
{

	protected static $_primary_key = '-recid';

    /**
     * FileMkaerServer Object
     */
    public static $fms = null;

    public static function getFmServer($lay){
        $conn = new Model_Fms_Connection($lay);
        static::$fms = $conn->fms;
    }

    /*
     * FileMaker Record Object
     */
    public $fm = null;

    /**
     * FileMkaerServer Connection
     */

    /**
     * FileMkaerServer Access Info
     */
//    public  static $fms_info = array('host'=>'192.168.1.29',
//                              'port'=>'80',
//                              'scheme'=>'http',
//                              'version'=>'FMPro8',
//                              'db'=>'tm_web',
//                              'lay'=>'Book_List',
//                              'user'=>'web_user',
//                              'pass'=>'web_user');

    /**
     * Set FileMakerServer Access Info
     * @param array fms info
     * @return void
     */
//    public static function setFmsInfo($info = array()) {
//        foreach( $info as $key => $value) {
//            static::$fms_info[$key] = $value;
//        }
//        return null;
//    }

    /**
     * Create FileMakerServer Access Object
     * @param none
     * @return FileMakerServer Access Object
     */
//    public static function createFmServer() {
//        $fx = new FX(static::$fms_info['host'],static::$fms_info['port'],static::$fms_info['version'],static::$fms_info['scheme']);
//        $fx->SetDBData(static::$fms_info['db'],static::$fms_info['lay']);
//        $fx->SetDBUserPass(static::$fms_info['user'],static::$fms_info['pass']);
//        $fx->SetCharacterEncoding('utf8');
//        $fx->SetDataParamsEncoding('utf8');
//
//        static::$fms = $fx;
//        return null;
//    }

    /**
     * Get ValueList defined in FileMaker
     * @param String ValueListName
     * @return array
     */
//    public function getValueList($vlName) {
//        $fmMetaData = $this->fms->FMView(true,'full',true);
//        if($fmMetaData instanceof FX_Error){
//            return array('error'=>'エラーが発生しました。');
//        }
//
//        if(empty($fmMetaData)){
//            return array('error'=>'読み込みに失敗しました。');
//        }else{
//            $temp = $fmMetaData['valueLists'];
//        }
//        return $temp[$vlName];
//    }

    /**
     * Util data change array to html option list
     * @param array value list ,String default
     * @return String html option list
     */
//    public function changeArrayToOptionList($array=array(),$default=''){
//        $options = '';
//        foreach($array as $key => $value){
//            $decValue = mb_convert_encoding($value,'UTF-8','HTML-ENTITIES');
//            if($default == $decValue){
//                $options .= "<option value='${decValue}' selected>${decValue}</option>\n";
//            }else{
//                $options .= "<option value='${decValue}'>${decValue}</option>\n";
//            }
//        }
//        return $options;
//    }

	/**
	 * Finds a record
	 *
	 * @param   number  $value  The primary key value to find
	 * @return  record object
	 */
	public static function find_by_pk($value)
	{
		return static::find_one_by(array(static::primary_key() => $value));
	}

	/**
	 * Finds a record
	 *
	 * @param   array  $cond
	 * @return  record object
	 */
	public static function find_one_by($column, $value = null, $operator = 'eq')
	{
//		$config = array(
//			'limit' => 1,
//		);

//		if (is_array($column) or ($column instanceof \Closure))
//		{
//			$config['where'] = $column;
//		}
//		else
//		{
//			$config['where'] = array(array($column, $operator, $value));
//		}
        $config = $column;

		$result = static::find($config);

		if ($result !== null)
		{
			return reset($result);
		}

		return null;
	}

	/**
	 * Finds all records where the given column matches the given value using
	 * the given operator ('=' by default).  Optionally limited and offset.
	 *
	 * @param   string  $column    The column to search
	 * @param   mixed   $value     The value to find
	 * @param   string  $operator  The operator to search with
	 * @param   int     $limit     Number of records to return
	 * @param   int     $offset    What record to start at
	 * @return  null|object  Null if not found or an array of Model object
	 */
	public static function find_by($column = null, $value = null, $operator = 'eq', $limit = null, $offset = 0)
	{
//		$config = array(
//			'limit' => $limit,
//			'offset' => $offset,
//		);

//		if ($column !== null)
//		{
//			if (is_array($column) or ($column instanceof \Closure))
//			{
//				$config['where'] = $column;
//			}
//			else
//			{
//				$config['where'] = array(array($column, $operator, $value));
//			}
//		}
        $config = $column;
		return static::find($config);
	}

	/**
	 * Finds all records in the table.
	 *
	 * @return  record object
	 */
	public static function find_all($limit=null,$offset=0)
	{
		return static::find(null,null);
	}

	/**
	 * Finds records.
	 *
	 * @param    array     $array     array containing query settings
	 * @return   array            an array containing models or null if none are found
	 */
	public static function find($config=array(),$key = null)
	{

        if(null === $config && null === $key ){
            //find_all
            $rs = static::$fms->DoFXAction('show_all',true,false,'full');
        }else{
	        foreach($config as $key=>$value){
	            //script->'-script'
	            //課題：検索条件をどうするか
	            static::$fms->AddDBParam($key,$value);
	        }
            $rs = static::$fms->DoFXAction('perform_find',true,false,'full');
        }

        if($rs instanceof FX_Error){
            return array('error'=>'エラーが発生しました。');
        }

        if(empty($rs)){
            return array('error'=>'データが見つかりませんでした。');            
        }else{
            return $rs;
        }
	}

    public function set(array $data = array()){
        foreach($data as $key => $value){
                $this->fm[$key] = $value;
        }
        return $this;
    }

	/**
	 * Saves the object includeing primary_key -> update other -> new
	 *
	 * @param   array $array model
	 * @return  array record object
	 */
	public function save($validate = true)
	{
        if(null == $this->fm){
            return null;
        }

        foreach($this->fm as $key=>$value){
            static::$fms->AddDBParam($key,$value);
        }

        if(!isset($this->fm[static::primary_key()])){
            $result = static::$fms->DoFXAction('new',true,false,'object');
        }else{
            $result = static::$fms->DoFXAction('update',true,false,'object');
        }

        if($result instanceof FX_Error){
            return array('error'=>'エラーが発生しました。');
        }
        if(empty($result)){
            return array('error'=>'書き込みに失敗しました。');
        }else{
            return array_shift($result);
        }
	}

	/**
	 * Deletes this record and freezes the object
	 *
	 * @return  mixed  Rows affected
	 */
	public function delete()
	{
        $id = (isset($this->fm[static::primary_key()]))? $this->fm[static::primary_key()] : null;
        if(null == $id){
            return "削除対象データが見つかりませんでした。";
        }

        static::$fms->AddDBParam(static::primary_key(),$id);
        $result = static::$fms->DoFXAction('delete',true,false,'object');

        if($result instanceof FX_Error){
            return array('error'=>'エラーが発生しました。');
        }
		return $result;
	}

    public static function addSortParam(array $params = null){
        if(static::$fms == null){
            return null;
        }
        foreach($params as $param){
            static::$fms->AddSortParam($param['field'],$param['ad'],$param['order']);
        }
    }

    public static function addSkip($skip){
        if(static::$fms == null){
            return null;
        }
        static::$fms->FMSkipRecords($skip);
    }

}
