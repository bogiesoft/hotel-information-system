<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info panel-border top">
            <div class="panel-heading">
                <div class="panel-title text-info fw700">
                    <span class="glyphicon glyphicon-pencil"></span>
                    Update <?php echo "<?php "; ?> echo $this->baseName; ?> Data
                </div>
            </div>
            <div class="panel-body">
                <?php echo "<?php\n"; ?>
                $this->renderPartial('_form', array('model' => $model));
                <?php echo "?>\n"; ?>
            </div>
        </div>
    </div>
</div>