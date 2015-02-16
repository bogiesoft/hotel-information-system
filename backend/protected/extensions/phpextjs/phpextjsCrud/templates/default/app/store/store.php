Ext.define('HotelApp.store.<?php echo $this->modelClass; ?>s', {
    extend: 'Ext.data.Store',

    alias: 'store.<?php echo Utils::lowerFirst($this->modelClass); ?>',
    model: 'HotelApp.model.<?php echo $this->modelClass; ?>',

    pageSize: 50,
    remoteSort: true,
    sorters: [{
        property: 'name',
        direction: 'DESC'
    }]
});