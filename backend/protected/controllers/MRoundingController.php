<?php

class MRoundingController extends GxController {
    public $baseName = "Rounding";
    public $actionName = "List Data";
    


    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'MRounding'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new MRounding;


        if (isset($_POST['MRounding'])) {
            $model->setAttributes($_POST['MRounding']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'MRounding');


        if (isset($_POST['MRounding'])) {
            $model->setAttributes($_POST['MRounding']);

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
        MRounding::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}