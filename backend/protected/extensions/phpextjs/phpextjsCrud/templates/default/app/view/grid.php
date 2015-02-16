Ext.define('HotelApp.view.content.<?php echo Utils::lowerFirst($this->modelClass); ?>.Index', {
    extend: 'Ext.grid.Panel',
    requires: [
        'Ext.data.*',
        'Ext.grid.*',
        'Ext.util.*',
        'Ext.toolbar.Paging',
        'HotelApp.ResourceManager',
        'HotelApp.component.Button',
        'HotelApp.model.<?php echo $this->modelClass; ?>',
        'HotelApp.view.content.<?php echo Utils::lowerFirst($this->modelClass); ?>.Form',
        'HotelApp.view.content.<?php echo Utils::lowerFirst($this->modelClass); ?>.Controller'
    ],
    xtype: '<?php echo Utils::lowerFirst($this->modelClass); ?>PagingGrid',
    
    controller: '<?php echo Utils::lowerFirst($this->modelClass); ?>',
    
    frame: true,
    fullscreen: true,
    title: '<?php echo $this->modelClass; ?> Data',
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
                disabled: '{!<?php echo Utils::lowerFirst($this->modelClass); ?>Grid.selection}'
            },
            handler: 'onEditClick',
        },
        {
            xtype: 'hotelButton',
            icon: HotelApp.ResourceManager.deleteImage,
            text: 'Delete Data',
            bind: {
                disabled: '{!<?php echo Utils::lowerFirst($this->modelClass); ?>Grid.selection}'
            },
            handler: 'onDeleteClick',
        }
    ],
    initComponent: function () {
        // create the Data Store
        var store = Ext.create('HotelApp.store.<?php echo $this->modelClass; ?>s');

        Ext.apply(this, {
            id:"<?php echo Utils::lowerFirst($this->modelClass); ?>Grid",
            reference: '<?php echo Utils::lowerFirst($this->modelClass); ?>Grid',
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
                <?php foreach ($this->tableSchema->columns as $name => $column) { 
                    if($name == "id")continue; 
                    ?>,{
                    text: "<?php echo Utils::toTitle($name); ?>",
                    dataIndex: '<?php echo $name; ?>',
                    width: 100,
                    sortable: true
                }
                <?php } ?>
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