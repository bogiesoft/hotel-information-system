<?php

class MAssetAgingController extends GxController {
    public $baseName = "Asset Aging";
    public $actionName = "List Data";
    


    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'MAssetAging'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new MAssetAging;


        if (isset($_POST['MAssetAging'])) {
            $model->setAttributes($_POST['MAssetAging']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'MAssetAging');


        if (isset($_POST['MAssetAging'])) {
            $model->setAttributes($_POST['MAssetAging']);

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
        MAssetAging::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}