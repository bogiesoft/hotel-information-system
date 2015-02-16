<?php

class UserLevelController extends GxController {

    public $baseName = "User Level";
    public $actionName = "List Data";

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'UserLevel'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new UserLevel;


        if (isset($_POST['UserLevel'])) {
            $model->setAttributes($_POST['UserLevel']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'UserLevel');


        if (isset($_POST['UserLevel'])) {
            $model->setAttributes($_POST['UserLevel']);

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
        UserLevel::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionSave() {
        $id = $_POST['id'];
        $ha = UserModule::model()->deleteAllByAttributes(array("user_level_id" => $id));
        $q = $_POST['q'];
        $arr = explode(";;", $q);
        $num = 1;
        foreach ($arr as $elem) {
            $ha = new UserModule();
            $ha->user_level_id = $id;
            $ha->module_id = $elem;
            $ha->save();
            $num ++;
        }
    }
}
