<?php
namespace app\test\controller;

use app\test\model\Table;
use think\Controller;
use think\Request;

class Index extends Controller{

    public function index(){
        $tables = Table::getAllTables();
        $this->assign('tables',$tables);
        return $this->fetch();
    }

    public function doPost(){

        $tableName = Request::instance()->param('table_name','');
        $fields = Table::getTablesField($tableName);
        return json($fields);
//        $codeService = new CodeService();
//        echo $codeService->create($fields);
    }
}