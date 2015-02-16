<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass; ?> {
    public $baseName = "<?php echo Utils::splitCamel($this->modelClass) ?>";
    public $actionName = "List Data";
    

<?php 
	$authpath = 'ext.giix-core.giixCrud.templates.default.auth.';
	Yii::app()->controller->renderPartial($authpath . $this->authtype);
?>

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id, '<?php echo $this->modelClass; ?>'),
        ));
    }

    public function actionCreate() {
        $this->actionName = "New Data";
        $model = new <?php echo $this->modelClass; ?>;

<?php if ($this->enable_ajax_validation): ?>
		$this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass)?>-form');
<?php endif; ?>

        if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
            $model->setAttributes($_POST['<?php echo $this->modelClass; ?>']);
<?php if ($this->hasManyManyRelation($this->modelClass)): ?>
                $relatedData = <?php echo $this->generateGetPostRelatedData($this->modelClass, 4); ?>;
<?php endif; ?>

<?php if ($this->hasManyManyRelation($this->modelClass)): ?>
            if ($model->saveWithRelated($relatedData)) {
<?php else: ?>
            if ($model->save()) {
<?php endif; ?>
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array( 'model' => $model));
    }

    public function actionUpdate($id) {
        $this->actionName = "Update Data";
        $model = $this->loadModel($id, '<?php echo $this->modelClass; ?>');

<?php if ($this->enable_ajax_validation): ?>
        $this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass)?>-form');
<?php endif; ?>

        if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
            $model->setAttributes($_POST['<?php echo $this->modelClass; ?>']);
<?php if ($this->hasManyManyRelation($this->modelClass)): ?>
            $relatedData = <?php echo $this->generateGetPostRelatedData($this->modelClass, 4); ?>;
<?php endif; ?>

<?php if ($this->hasManyManyRelation($this->modelClass)): ?>
            if ($model->saveWithRelated($relatedData)) {
<?php else: ?>
            if ($model->save()) {
<?php endif; ?>
                Yii::app()->user->setFlash('success', "Data saved!");
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionDelete($id) {
        <?php echo $this->modelClass; ?>::model()->deleteByPk($id);
        Yii::app()->user->setFlash('success', "Data deleted.");
        $this->redirect(array('index'));
    }

    public function actionIndex() {
        $this->render('index');
    }
}