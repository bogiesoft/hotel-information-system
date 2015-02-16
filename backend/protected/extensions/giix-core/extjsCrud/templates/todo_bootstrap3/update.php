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
	GxHtml::valueEx(\$model) => array('view', 'id' => GxActiveRecord::extractPkValue(\$model, true)),
	Yii::t('app', 'Update'),
);\n?>";
?>

<section class="panel"> 
	<header class="panel-heading">
		<span class="h4">Edit <?php echo '<?php'; ?> echo GxHtml::encode($model->label()); ?></span>
	</header> 
	<div class="panel-body"> 
		<?php echo "<?php\n"; ?>
		$this->renderPartial('_form', array(
				'model' => $model));
		<?php echo '?>'; ?>
	</div> 
</section>