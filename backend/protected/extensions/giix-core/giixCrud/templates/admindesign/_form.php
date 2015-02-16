<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
$ajax = ($this->enable_ajax_validation) ? 'true' : 'false'; ?>

<?php echo '<?php '; ?>
$form = $this->beginWidget('GxActiveForm', array(
	'id' => '<?php echo $this->class2id($this->modelClass); ?>-form',
	'enableAjaxValidation' => <?php echo $ajax; ?>,
	'htmlOptions' => array('class'=>'form-horizontal', 'role'=>'form')
));
<?php echo '?>'; ?>
	
    <div class="form-group">
        <div class="col-lg-9 col-lg-offset-3 text-warning dark">
            <?php echo "<?php echo Yii::t('app', 'Input with asterisk '); ?> <span class=\"required\">*</span> <?php echo Yii::t('app', 'must be filled'); ?>"; ?>.
        </div>
        <div class="col-lg-9 col-lg-offset-3 text-danger dark">
            <?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>
        </div>
    </div>

<?php foreach ($this->tableSchema->columns as $column): ?>
<?php if (!$column->autoIncrement): ?>
            
    <div class="form-group">
        <label for="inputStandard" class="col-lg-3 control-label">
            <?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>"; ?>
        </label>
        <div class="col-lg-9">
            <?php echo "<?php " . $this->generateActiveField($this->modelClass, $column, array("class"=>"form-control")) . "; ?>\n"; ?>
            <?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
        </div>
    </div>
<?php endif; ?>
<?php endforeach; ?>

    <div class="form-group">
        <div class="col-lg-9 col-lg-offset-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Save
            </button>
            <a href="<?php echo "<?php" ?> echo Yii::app()->request->baseUrl."/".Yii::app()->controller->id; ?>" class="btn btn-warning">
                <i class="fa fa-chevron-left"></i> Back
            </a>
        </div>
    </div>
    
<?php echo "<?php
\$this->endWidget();
?>\n"; ?>