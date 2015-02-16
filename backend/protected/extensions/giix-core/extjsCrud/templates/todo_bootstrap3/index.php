<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n
\$this->breadcrumbs = array(
	{$this->modelClass}::label(2),
	Yii::t('app', 'Index'),
);\n";
?>

$this->menu = array(
	array('label'=>Yii::t('app', 'Create') . ' ' . <?php echo $this->modelClass; ?>::label(), 'url' => array('create')),
	array('label'=>Yii::t('app', 'Manage') . ' ' . <?php echo $this->modelClass; ?>::label(2), 'url' => array('admin')),
);
?>

<h1><?php echo '<?php'; ?> echo GxHtml::encode(<?php echo $this->modelClass; ?>::label(2)); ?></h1>

<div class="wrapper bg-light pull-in b-b font-bold" style="margin-bottom: 18px"> 
	<a href="<?php echo "<?php echo Yii::app()->request->baseUrl; ?>/".$this->controller."/create" ?>" class="btn btn-primary"><i class="icon-plus"></i> Tambah Data</a> 
</div>

<?php echo "<?php"; ?> $this->widget('ext.giix-core.widgets.DataTablesWidget', array(
	'dataProvider'=>$dataProvider,
)); <?php '?>'; ?>