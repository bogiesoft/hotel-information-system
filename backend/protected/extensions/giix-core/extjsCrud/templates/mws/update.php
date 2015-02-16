<div class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
    	<span>Update <?php echo $this->modelClass; ?></span>
    </div>
    <div class="mws-panel-body no-padding">
    	<?php echo "<?php\n"; ?>
		$this->renderPartial('_form', array(
				'model' => $model));
		<?php echo '?>'; ?>
    </div>    	
</div>