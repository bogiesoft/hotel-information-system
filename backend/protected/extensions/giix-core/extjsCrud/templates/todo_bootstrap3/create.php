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
);\n?>";
?>

<section class="panel"> 
	<header class="panel-heading">
		<span class="h4">New <?php echo '<?php'; ?> echo GxHtml::encode($model->label()); ?></span>
	</header> 
	<div class="panel-body"> 
		<?php echo "<?php\n"; ?>
		$this->renderPartial('_form', array(
				'model' => $model,
				'buttons' => 'create'));
		<?php echo '?>'; ?>
	</div> 
</section>