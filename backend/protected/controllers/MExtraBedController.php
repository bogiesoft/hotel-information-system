<?php

class MExtraBedController extends GxController {
    public $baseName = "Extra Bed";
    public $actionName = "List Data";
    


    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'MExtraBed'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new MExtraBed;


        if (isset($_POST['MExtraBed'])) {
            $model->setAttributes($_POST['MExtraBed']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'MExtraBed');


        if (isset($_POST['MExtraBed'])) {
            $model->setAttributes($_POST['MExtraBed']);

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
        MExtraBed::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}