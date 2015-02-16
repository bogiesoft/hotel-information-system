<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n
\$this->breadcrumbs = array(
	\$model->label(2) => array('index'),
	Yii::t('app', 'Create'),
);\n
?>";
?>

<div class="statistic clearfix">
	<div class="current_page float_left">
		<span><i class="icon-reorder"></i> <?php echo '<?php'; ?> echo GxHtml::encode($model->label()); ?></span>
	</div>
</div>

<div class="row-fluid">
	<div class="span6">
		<div class="well green">
			<div class="well-header">
				<h5>Form</h5>
			</div>

			<div class="well-content no-search">
				<?php echo "<?php\n"; ?>
				$this->renderPartial('_form', array(
						'model' => $model));
				<?php echo '?>'; ?>
			</div>
		</div>
	</div>
</div>