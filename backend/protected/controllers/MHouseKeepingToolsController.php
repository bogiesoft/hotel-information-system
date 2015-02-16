<?php

class MHouseKeepingToolsController extends GxController {
    public $baseName = "House Keeping Tools";
    public $actionName = "List Data";
    


    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, 'MHouseKeepingTools'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new MHouseKeepingTools;


        if (isset($_POST['MHouseKeepingTools'])) {
            $model->setAttributes($_POST['MHouseKeepingTools']);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, 'MHouseKeepingTools');


        if (isset($_POST['MHouseKeepingTools'])) {
            $model->setAttributes($_POST['MHouseKeepingTools']);

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
        MHouseKeepingTools::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}