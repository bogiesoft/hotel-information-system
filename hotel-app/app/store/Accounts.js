Ext.define('HotelApp.store.Accounts', {
    extend: 'Ext.data.Store',

    alias: 'store.accounts',
    model: 'HotelApp.model.Account',

    pageSize: 50,
    remoteSort: true,
    sorters: [{
        property: 'code',
        direction: 'ASC'
    }]
});