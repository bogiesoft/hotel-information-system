<?php

class MBillingController extends GxController {
    public $baseName = "Billing";
    public $actionName = "List Data";
    


    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'MBilling'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new MBilling;


        if (isset($_POST['MBilling'])) {
            $model->setAttributes($_POST['MBilling']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'MBilling');


        if (isset($_POST['MBilling'])) {
            $model->setAttributes($_POST['MBilling']);

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
        MBilling::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}