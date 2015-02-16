Ext.define('HotelApp.view.content.mAccount.Index', {
    extend: 'Ext.grid.Panel',
    requires: [
        'Ext.data.*',
        'Ext.grid.*',
        'Ext.util.*',
        'Ext.toolbar.Paging',
        'HotelApp.ResourceManager',
        'HotelApp.component.Button',
        'HotelApp.model.MAccount',
        'HotelApp.view.content.mAccount.Form',
        'HotelApp.view.content.mAccount.Controller'
    ],
    xtype: 'mAccountPagingGrid',
    
    controller: 'mAccount',
    
    frame: true,
    fullscreen: true,
    title: 'MAccount Data',
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
                disabled: '{!mAccountGrid.selection}'
            },
            handler: 'onEditClick',
        },
        {
            xtype: 'hotelButton',
            icon: HotelApp.ResourceManager.deleteImage,
            text: 'Delete Data',
            bind: {
                disabled: '{!mAccountGrid.selection}'
            },
            handler: 'onDeleteClick',
        }
    ],
    initComponent: function () {
        // create the Data Store
        var store = Ext.create('HotelApp.store.MAccounts');

        Ext.apply(this, {
            id:"mAccountGrid",
            reference: 'mAccountGrid',
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
                {dataIndex: 'id', hidden: true}
                ,{
                    text: "Code",
                    dataIndex: 'code',
                    width: 100,
                    sortable: true
                }
                ,{
                    text: "Name",
                    dataIndex: 'name',
                    width: 100,
                    sortable: true
                }
                ,{
                    text: "Status",
                    dataIndex: 'status',
                    width: 100,
                    sortable: true
                }
                ,{
                    text: "Level",
                    dataIndex: 'level',
                    width: 100,
                    sortable: true
                }
                ,{
                    text: "Type",
                    dataIndex: 'type',
                    width: 100,
                    sortable: true
                }
                ,{
                    text: "Active",
                    dataIndex: 'active',
                    width: 100,
                    sortable: true
                }
                                ],
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