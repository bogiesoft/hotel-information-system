Ext.define('HotelApp.model.Customer', {
    extend: 'HotelApp.model.Base',
    fields: [
        'code', 'name', 'contact_person', 'address', 'city', 'province', 'post_code', 'nation', 'description', 'phone_1', 'phone_2', 'fax'
    ],
    idProperty: 'id',
    
    proxy: {
        type: 'jsonp',
        //url: baseUrl+'backend/mCustomer/list',
        api: {
            read: baseUrl+'backend/mCustomer/list',
            create: baseUrl+'backend/mCustomer/create',
            update: baseUrl+'backend/mCustomer/update',
            destroy: baseUrl+'backend/mCustomer/delete'
        },
        reader: {
            rootProperty: 'detail',
            totalProperty: 'totalCount'
        },
        // sends single sort as multi parameter
        simpleSortMode: true
    }
});