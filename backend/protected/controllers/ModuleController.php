<?php

class ModuleController extends Controller {

    public $baseName = "Module";
    public $actionName = "List Data";
    
    public function actionListMenu(){
        $children = array();
        foreach(Module::model()->findAll("parent_id = 0") as $module){
            $child = array();
            $child["id"] = $module->id;
            $child["name"] = $module->name;
            if($module->controller == "#"){
                $child["expanded"] = FALSE;
            }else{
                $child["leaf"] = TRUE;
                $child["qtip"] = $module->name;
                $child["text"] = $module->controller;
            }
            
            foreach(Module::model()->findAll("parent_id = ".$module->id) as $module2){
                $child2 = array();
                $child2["id"] = $module2->id;
                $child2["name"] = $module2->name;
                $child2["leaf"] = TRUE;
                $child2["qtip"] = $module2->name;
                $child2["text"] = $module2->controller;

                $child["children"][] = $child2;
            }
            
            $children[] = $child;
        }
        
        $output = array("name"=>"test", "success"=>TRUE, "expanded"=>TRUE);
        $output["children"] = $children;
        
        echo json_encode($output);
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'Module'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new Module;


        if (isset($_POST['Module'])) {
            $model->setAttributes($_POST['Module']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'Module');


        if (isset($_POST['Module'])) {
            $model->setAttributes($_POST['Module']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionDelete($id) {
        Module::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionSave() {
        $q = $_POST['q'];
        $arr = explode(";;", $q);
        $num = 1;
        foreach ($arr as $elem) {
            $arrElem = explode("||", $elem);
            $mod = Module::model()->findByPk($arrElem[0]);
            $mod->name = $arrElem[1];
            $mod->controller = $arrElem[2];
            $mod->icon = $arrElem[3];
            $mod->parent_id = $arrElem[4];
            $mod->order = $num;
            $mod->save();
            $num ++;
        }
    }

}
