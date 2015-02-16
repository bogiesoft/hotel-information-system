<div class="grid_8" style="margin-bottom: 10px">
    <a href="<?php echo "<?php" ?> echo Yii::app()->request->baseUrl.'/'.Yii::app()->controller->id.'/create' ?>" class="btn btn-success">
        <i class="icol-add"></i> Tambah Data
    </a>
</div>

<div class="mws-panel grid_8 mws-collapsible">
    <div class="mws-panel-header">
        <span><?php echo $this->modelClass; ?></span>
    </div>
    <div class="mws-panel-inner-wrap">
        <div class="mws-panel-body no-padding">
            <table class="mws-datatable mws-table">
                <thead>
                    <tr>
                        <?php
                        $schema = $this->tableSchema;
                        foreach ($schema->columns as $key => $val) {
                            echo "<th>".$key."</th>
                        ";
                        }
                        ?>
                        <th style="width: 200px">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo "<?php" ?>
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
                        ?>
                        <td style="text-align: center">
                            <a href="<?php echo "<?php" ?> echo Yii::app()->request->baseUrl.'/'.Yii::app()->controller->id.'/update/'.$dataElement->primaryKey; ?>" class="btn btn-success">
                                <i class="icol-pencil"></i> Edit
                            </a>
                            <a href="<?php echo "<?php" ?> echo Yii::app()->request->baseUrl.'/'.Yii::app()->controller->id.'/delete/'.$dataElement->primaryKey; ?>" class="btn btn-danger delete">
                                <i class="icol-delete"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php echo "<?php" ?>
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <script>
            $(".mws-datatable").dataTable();
            $(".mws-datatable-fn").dataTable({
                sPaginationType: "full_numbers"
            });
            $( document ).on( "click", "a.delete", function() {
                if(confirm("Apakah Anda yakin menghapus data ini ?")){
                    return true;
                }
                return false;
            });
        </script>
    </div>
</div>