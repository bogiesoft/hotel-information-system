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
	'htmlOptions' => array("class" => "form-horizontal"),
));
<?php echo '?>'; ?>

		<div class="form-group">
			<div class="col-sm-4 col-sm-offset-2"> 
				<?php echo "<?php echo Yii::t('app', 'Fields with'); ?> <span class=\"required\">*</span> <?php echo Yii::t('app', 'are required'); ?>"; ?>.
				<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>
			</div>
		</div>

<?php foreach ($this->tableSchema->columns as $column): ?>
<?php if (!$column->autoIncrement): ?>
		<div class="form-group">
			<?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column, array("class"=>"col-sm-2 control-label")) . "; ?>\n"; ?>
			<div class="col-sm-10">
				<?php echo "<?php " . $this->generateActiveField($this->modelClass, $column, array("class"=>"form-control")) . "; ?>\n"; ?>
				<?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
			</div>
		</div>
		<div class="line line-dashed line-lg pull-in"></div>
<?php endif; ?>
<?php endforeach; ?>

<?php foreach ($this->getRelations($this->modelClass) as $relation): ?>
<?php if ($relation[1] == GxActiveRecord::HAS_MANY || $relation[1] == GxActiveRecord::MANY_MANY): ?>
		<label><?php echo '<?php'; ?> echo GxHtml::encode($model->getRelationLabel('<?php echo $relation[0]; ?>')); ?></label>
		<?php echo '<?php ' . $this->generateActiveRelationField($this->modelClass, $relation) . "; ?>\n"; ?>
<?php endif; ?>
<?php endforeach; ?>

		<div class="form-group">
			<div class="col-sm-4 col-sm-offset-2"> 
				<button type="submit" class="btn btn-success">Simpan</button> 
				<button type="reset" class="btn btn-warning">Reset</button> 
				<a href="<?php echo "<?php " ?> echo Yii::app()->request->baseUrl; <?php echo "?>" ?>/<?php echo "<?php echo Yii::app()->controller->id; ?>" ?>" class="btn btn-danger">Kembali</a> 
			</div>
		</div>

<?php echo "<?php
\$this->endWidget();
?>\n"; ?>