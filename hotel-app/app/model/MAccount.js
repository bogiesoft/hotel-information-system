Ext.define('HotelApp.model.MAccount', {
    extend: 'HotelApp.model.Base',
    fields: [
        'code', 'name', 'status', 'level', 'type', 'active'],
    idProperty: 'id',
    proxy: {
        type: 'jsonp',
        //url: baseUrl+'backend/mAccount/list',
        api: {
            read: baseUrl + 'backend/mAccount/list',
            create: baseUrl + 'backend/mAccount/create',
            update: baseUrl + 'backend/mAccount/update',
            destroy: baseUrl + 'backend/mAccount/delete'
        },
        reader: {
            rootProperty: 'detail',
            totalProperty: 'totalCount'
        },
        // sends single sort as multi parameter
        simpleSortMode: true
    }
});