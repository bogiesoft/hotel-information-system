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
	<div class="mws-form-inline">
		<div class="mws-form-row">
			<?php echo "<?php echo Yii::t('app', 'Input bertanda '); ?> <span class=\"required\">*</span> <?php echo Yii::t('app', 'harus diisi'); ?>"; ?>.
			<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>
		</div>

<?php foreach ($this->tableSchema->columns as $column): ?>
<?php if (!$column->autoIncrement): ?>
		<div class="mws-form-row">
			<div class="mws-form-label"><?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>"; ?></div>
			<div class="mws-form-item">
				<?php echo "<?php " . $this->generateActiveField($this->modelClass, $column, array("class"=>"small")) . "; ?>\n"; ?>
				<?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
			</div>
		</div>
<?php endif; ?>
<?php endforeach; ?>


		<div class="mws-form-row">
            <div class="mws-form-label"></div>
			<div class="mws-form-item">
                <button type="submit" class="btn btn-primary">
                    <i class="icol-disk"></i> Simpan
                </button>
                <button onclick="history.back();return false" class="btn btn-warning">
                    <i class="icol-arrow-left"></i> Kembali
                </button>
            </div>
		</div>
	</div>
<?php echo "<?php
\$this->endWidget();
?>\n"; ?>