<?php

class MUnitConversionController extends GxController {
    public $baseName = "Unit Conversion";
    public $actionName = "List Data";
    


    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'MUnitConversion'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new MUnitConversion;


        if (isset($_POST['MUnitConversion'])) {
            $model->setAttributes($_POST['MUnitConversion']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'MUnitConversion');


        if (isset($_POST['MUnitConversion'])) {
            $model->setAttributes($_POST['MUnitConversion']);

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
        MUnitConversion::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}