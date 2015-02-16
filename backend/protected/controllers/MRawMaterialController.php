<?php

class MRawMaterialController extends GxController {
    public $baseName = "Raw Material";
    public $actionName = "List Data";
    


    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'MRawMaterial'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new MRawMaterial;


        if (isset($_POST['MRawMaterial'])) {
            $model->setAttributes($_POST['MRawMaterial']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'MRawMaterial');


        if (isset($_POST['MRawMaterial'])) {
            $model->setAttributes($_POST['MRawMaterial']);

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
        MRawMaterial::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}