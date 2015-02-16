<?php

class MGiroCheckResultController extends GxController {
    public $baseName = "Giro Check Result";
    public $actionName = "List Data";
    


    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'MGiroCheckResult'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new MGiroCheckResult;


        if (isset($_POST['MGiroCheckResult'])) {
            $model->setAttributes($_POST['MGiroCheckResult']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'MGiroCheckResult');


        if (isset($_POST['MGiroCheckResult'])) {
            $model->setAttributes($_POST['MGiroCheckResult']);

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
        MGiroCheckResult::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}