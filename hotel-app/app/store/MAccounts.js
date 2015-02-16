Ext.define('HotelApp.store.MAccounts', {
    extend: 'Ext.data.Store',

    alias: 'store.mAccount',
    model: 'HotelApp.model.MAccount',

    pageSize: 50,
    remoteSort: true,
    sorters: [{
        property: 'name',
        direction: 'DESC'
    }]
});