<div class="row">
    <div class="col-md-12">
        <?php echo "<?php "; ?> if(Yii::app()->user->hasFlash('success')):?>
            <div class="alert alert-success dark alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="fa fa-trophy pr10"></i>
                <?php echo "<?php "; ?> echo Yii::app()->user->getFlash('success'); ?>
            </div>
        <?php echo "<?php "; ?> endif; ?>
    </div>
    
    <div class="col-md-12">
        <div class="panel panel-info panel-border top">
            <div class="panel-heading">
                <div class="panel-title text-info fw700">
                    <span class="glyphicon glyphicon-tasks"></span>
                    <?php echo "<?php "; ?> echo $this->baseName; ?> Data
                    <div class="widget-menu pull-right mr10">
                        <a href="<?php echo "<?php" ?> echo Yii::app()->request->baseUrl.'/'.Yii::app()->controller->id.'/create' ?>" class="btn btn-sm btn-success">
                            <span class="fa fa-plus fs11 mr5"></span> Add New Data
                        </a>
                    </div>
                </div>
            </div>
            <div class="panel-body pn">
                <table class="table table-striped table-hover datatables" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <?php
                            $schema = $this->tableSchema;
                            foreach ($schema->columns as $key => $val) {
                                echo "<th>".Utils::splitCamel(Utils::toTitle($key))."</th>
                            ";
                            }
                            ?><th style="width: 200px">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo "<?php\n"; ?>
                        $dataArray = <?php echo $this->modelClass; ?>::model()->findAll();
                        foreach($dataArray as $dataElement){
                        ?>
                        <tr>
                            <?php
                            $schema = $this->tableSchema;
                            foreach ($schema->columns as $key => $val) {
                                echo "<td><?php echo \$dataElement->".$key.";?></td>
                            ";
                            }
                            ?><td style="text-align: center">
                                <a href="<?php echo "<?php" ?> echo Yii::app()->request->baseUrl.'/'.Yii::app()->controller->id.'/update/'.$dataElement->primaryKey; ?>" class="btn btn-success">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <a href="<?php echo "<?php" ?> echo Yii::app()->request->baseUrl.'/'.Yii::app()->controller->id.'/delete/'.$dataElement->primaryKey; ?>" class="btn btn-danger delete">
                                    <i class="fa fa-trash-o"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php echo "<?php\n"; ?>
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>