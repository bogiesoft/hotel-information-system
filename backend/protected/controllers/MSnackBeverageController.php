<?php

class MSnackBeverageController extends GxController {
    public $baseName = "Snack Beverage";
    public $actionName = "List Data";
    


    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'MSnackBeverage'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new MSnackBeverage;


        if (isset($_POST['MSnackBeverage'])) {
            $model->setAttributes($_POST['MSnackBeverage']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'MSnackBeverage');


        if (isset($_POST['MSnackBeverage'])) {
            $model->setAttributes($_POST['MSnackBeverage']);

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
        MSnackBeverage::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}