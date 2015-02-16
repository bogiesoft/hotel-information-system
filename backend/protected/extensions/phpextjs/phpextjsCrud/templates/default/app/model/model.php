<?php
$fields = array();
foreach ($this->tableSchema->columns as $name => $column) {
    if($name == "id") continue;
    $fields[] = "'".$name."'";
}
?>Ext.define('HotelApp.model.<?php echo $this->modelClass; ?>', {
    extend: 'HotelApp.model.Base',
    fields: [
        <?php echo implode(", ", $fields); ?>
    ],
    idProperty: 'id',
    
    proxy: {
        type: 'jsonp',
        //url: baseUrl+'backend/<?php echo Utils::lowerFirst($this->modelClass); ?>/list',
        api: {
            read: baseUrl+'backend/<?php echo Utils::lowerFirst($this->modelClass); ?>/list',
            create: baseUrl+'backend/<?php echo Utils::lowerFirst($this->modelClass); ?>/create',
            update: baseUrl+'backend/<?php echo Utils::lowerFirst($this->modelClass); ?>/update',
            destroy: baseUrl+'backend/<?php echo Utils::lowerFirst($this->modelClass); ?>/delete'
        },
        reader: {
            rootProperty: 'detail',
            totalProperty: 'totalCount'
        },
        // sends single sort as multi parameter
        simpleSortMode: true
    }
});