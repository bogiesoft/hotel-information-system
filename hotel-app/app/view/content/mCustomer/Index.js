Ext.define('HotelApp.view.content.mCustomer.Index', {
    extend: 'Ext.grid.Panel',
    requires: [
        'Ext.data.*',
        'Ext.grid.*',
        'Ext.util.*',
        'Ext.toolbar.Paging',
        'HotelApp.ResourceManager',
        'HotelApp.component.Button',
        'HotelApp.model.Customer',
        'HotelApp.view.content.mCustomer.Form',
        'HotelApp.view.content.mCustomer.Controller'
    ],
    xtype: 'paging-grid',
    
    controller: 'mCustomer',
    
    frame: true,
    fullscreen: true,
    title: 'Customer Data',
    loadMask: true,
    tbar: [
        {
            xtype: 'hotelButton',
            icon: HotelApp.ResourceManager.addImage,
            text: 'Add New Data',
            handler: 'onAddClick',
        },
        {
            xtype: 'hotelButton',
            icon: HotelApp.ResourceManager.editImage,
            text: 'Edit Data',
            bind: {
                disabled: '{!customerGrid.selection}'
            },
            handler: 'onEditClick',
        },
        {
            xtype: 'hotelButton',
            icon: HotelApp.ResourceManager.deleteImage,
            text: 'Delete Data',
            bind: {
                disabled: '{!customerGrid.selection}'
            },
            handler: 'onDeleteClick',
        }
    ],
    initComponent: function () {
        // create the Data Store
        var store = Ext.create('HotelApp.store.Customers');

        Ext.apply(this, {
            id:"customerGrid",
            reference: 'customerGrid',
            width: "100%",
            height: 400,
            store: store,
            plugins: [],
            viewConfig: {
                trackOver: false,
                stripeRows: true
            },
            // grid columns
            columns: [
                {dataIndex: 'id', hidden: true},
                {
                    text: "Code",
                    dataIndex: 'code',
                    width: 100,
                    sortable: true
                }, {
                    text: "Name",
                    dataIndex: 'name',
                    flex: 1,
                    sortable: false
                }, {
                    text: "City",
                    dataIndex: 'city',
                    width: 100,
                    sortable: true
                }, {
                    text: "Phone",
                    dataIndex: 'phone_1',
                    width: 100,
                    sortable: true
                }, {
                    text: "Phone 2",
                    dataIndex: 'phone_2',
                    width: 100,
                    sortable: true
                }],
            // paging bar on the bottom
            bbar: Ext.create('Ext.PagingToolbar', {
                store: store,
                displayInfo: true,
                displayMsg: 'Displaying topics {0} - {1} of {2}',
                emptyMsg: "No topics to display",
                items: ['-']
            })
        });
        this.callParent();
    },
    afterRender: function () {
        this.callParent(arguments);
        this.getStore().loadPage(1);
    }
});