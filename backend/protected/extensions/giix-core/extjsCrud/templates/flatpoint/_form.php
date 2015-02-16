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
	'htmlOptions' => array('class'=>'mws-form')
));
<?php echo '?>'; ?>
		<div class="form_row">
			<?php echo "<?php echo Yii::t('app', 'Fields with'); ?> <span class=\"required\">*</span> <?php echo Yii::t('app', 'are required'); ?>"; ?>.
			<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>
		</div>

<?php foreach ($this->tableSchema->columns as $column): ?>
<?php if (!$column->autoIncrement): ?>
		<div class="form_row">
			<div class="field_name"><?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>"; ?></div>
			<div class="field">
				<?php echo "<?php " . $this->generateActiveField($this->modelClass, $column) . "; ?>\n"; ?>
				<?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
			</div>
		</div>
<?php endif; ?>
<?php endforeach; ?>

<?php foreach ($this->getRelations($this->modelClass) as $relation): ?>
<?php if ($relation[1] == GxActiveRecord::HAS_MANY || $relation[1] == GxActiveRecord::MANY_MANY): ?>
		<label><?php echo '<?php'; ?> echo GxHtml::encode($model->getRelationLabel('<?php echo $relation[0]; ?>')); ?></label>
		<?php echo '<?php ' . $this->generateActiveRelationField($this->modelClass, $relation) . "; ?>\n"; ?>
<?php endif; ?>
<?php endforeach; ?>

		<div class="form_row">
			<div class="field">
				<input type="submit" value="Save" class="btn blue">
			</div>
		</div>
<?php echo "<?php
\$this->endWidget();
?>\n"; ?>