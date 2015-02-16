<?php

class ExtController extends Controller {

    public $modelName = "MCustomer";

    public function actionList() {
        $page = $_GET['page'];
        $start = $_GET['start'];
        $limit = $_GET['limit'];
        $sort = $_GET['sort'];
        $dir = $_GET['dir'];
        $callback = $_GET['callback'];
        $id = $_GET['id'];

        $extraMessage = "";
        $errors = NULL;

        //select mode
        if (!isset($start)) {
            $start = 0;
        }

        if (!isset($limit)) {
            $limit = 1;
        }

        if (!isset($sort)) {
            $sort = "id";
            $dir = "ASC";
        }

        if (isset($id)) {
            $hasil = CActiveRecord::model($this->modelName)->findAll("id = '{$id}' ORDER BY {$sort} {$dir} LIMIT {$start},{$limit}");
        } else {
            $hasil = CActiveRecord::model($this->modelName)->findAll("1 ORDER BY {$sort} {$dir} LIMIT {$start},{$limit}");
        }

        $output = array();
        $output["totalCount"] = CActiveRecord::model($this->modelName)->count();
        $output["detail"] = $hasil;
        $output["extraMessage"] = $extraMessage;
        $output["errors"] = $errors;

        echo $callback . "(" . CJSON::encode($output) . ")";
    }

    public function actionCreate() {
        $callback = $_GET['callback'];
        $records = $_GET['records'];
        $records = json_decode($records);
        $cls = $this->modelName;
        
        $errors = NULL;
        $extraMessage = "";
        
        $model = new $cls();
        foreach ($records as $key => $val) {
            $extraMessage .= "{$key} {$val}\n";
            $model->$key = $val;
        }
        if (!$model->save()) {
            $errors = $model->errors;
        }

        
        

        $output = array();
        $output["totalCount"] = CActiveRecord::model($this->modelName)->count();
        $output["detail"] = array();
        $output["extraMessage"] = $extraMessage;
        $output["errors"] = $errors;

        echo $callback . "(" . CJSON::encode($output) . ")";
    }
    
    public function actionUpdate() {
        $callback = $_GET['callback'];
        $records = $_GET['records'];

        $records = json_decode($records);
        $model = CActiveRecord::model($this->modelName)->findByPk($records->id);
        
        foreach ($records as $key => $val) {
            $extraMessage .= "{$key} {$val}\n";
            $model->$key = $val;
        }
        if (!$model->save()) {
            $errors = $model->errors;
        }

        $extraMessage = "";
        $errors = NULL;

        $output = array();
        $output["totalCount"] = CActiveRecord::model($this->modelName)->count();
        $output["detail"] = array();
        $output["extraMessage"] = $extraMessage;
        $output["errors"] = $errors;

        echo $callback . "(" . CJSON::encode($output) . ")";
    }

    public function actionDelete() {
        $records = $_GET['records'];
        $callback = $_GET['callback'];

        $records = json_decode($records);
        CActiveRecord::model($this->modelName)->deleteByPk($records->id);
        
        $extraMessage = "";
        $errors = NULL;

        $output = array();
        $output["totalCount"] = CActiveRecord::model($this->modelName)->count();
        $output["detail"] = array();
        $output["extraMessage"] = $extraMessage;
        $output["errors"] = $errors;

        echo $callback . "(" . CJSON::encode($output) . ")";
    }
}
