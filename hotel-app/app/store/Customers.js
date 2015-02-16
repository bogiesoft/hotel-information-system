Ext.define('HotelApp.store.Customers', {
    extend: 'Ext.data.Store',

    alias: 'store.customer',
    model: 'HotelApp.model.Customer',

    pageSize: 50,
    remoteSort: true,
    sorters: [{
        property: 'name',
        direction: 'DESC'
    }]
});