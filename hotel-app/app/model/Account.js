Ext.define('HotelApp.model.Account', {
    extend: 'HotelApp.model.Base',
    fields: [
        'code', 'name', 'status', 'level', 'type', 'active'
    ],
    idProperty: 'accountid',
    proxy: {
        type: 'jsonp',
        url: baseUrl+'backend/mAccount/list',
        reader: {
            rootProperty: 'topics',
            totalProperty: 'totalCount'
        },
        // sends single sort as multi parameter
        simpleSortMode: true
    }
});