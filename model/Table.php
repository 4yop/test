<?php
/**
 * Date: 2018/12/1
 * Time: 16:05
 */

namespace app\test\model;


use think\Db;
use think\Model;

class Table extends Model
{
    /**获取所有表
     * @return string
     */
    public static function getAllTables(){
        $tables = Db::query('SHOW TABLES');
        $options = '';
        $prefix = config('database.prefix');
        foreach ($tables as $k=>$v){
            $options.="<option>{$v['Tables_in_xiaofang']}</option>";
        }
        return $options;
    }

    /**获取所有表字段
     * @param string $tableName
     * @return array
     */
    public static function getTablesField($tableName=''){
        $table = Db::getTableInfo($tableName);

        $fields = $table['fields'];
        $types = $table['type'];
        $comments = self::gettabelComment($tableName);
        $len = count($fields);
        $data = [];

        for($i=0;$i<$len;$i++){
            $field = $fields[$i];
            $type = $types[$field];
            $attr = self::tabelAttr($type);
            $data[$field] = [
                'comment' => $comments[$field],
                'type' => $attr['type'],
                'length' => $attr['length'],
                'other' => $attr['other'],
            ];
        }


        return $data;
    }

    /**获取注释
     * @param string $tableName
     * @return array
     */
    public static function gettabelComment($tableName=''){
        $comments = Db :: query("SELECT COLUMN_NAME as field,column_comment as comment FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$tableName}'");
        $commentArr = [];
        foreach ($comments as $k=>$v){

            $commentArr[$v['field']] = !empty($v['comment']) ? $v['comment'] : $v['field'];

        }
        return $commentArr;
    }

    /**获取字段类型 长度
     * @param $type
     * @return array
     */
    public static function tabelAttr($type){
        $left = stripos($type,'(');
        $text = [];
        if($left){
            $text['type'] = substr($type,0,$left);

            $right = stripos($type,')');

            $text['length'] = substr($type,$left+1,$right-$left-1);

            $rightText = substr($type,$right+1,strlen($type)-$right);

            $text['other'] = $rightText == false?'':trim($rightText);

        }else{
            $text['type'] = $type;
            $text['length'] = 0;
            $left = stripos($type,' ');
            $rightText = substr($type,$left,strlen($type)-$left-1);
            $text['other'] = $rightText == false?'':trim($rightText);
        }

        return $text;
    }

    public static function createCode(){

    }
}