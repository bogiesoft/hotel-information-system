<?php

class MMaritalStatusController extends GxController {
    public $baseName = "Marital Status";
    public $actionName = "List Data";
    


    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'MMaritalStatus'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new MMaritalStatus;


        if (isset($_POST['MMaritalStatus'])) {
            $model->setAttributes($_POST['MMaritalStatus']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'MMaritalStatus');


        if (isset($_POST['MMaritalStatus'])) {
            $model->setAttributes($_POST['MMaritalStatus']);

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
        MMaritalStatus::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}