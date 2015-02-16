Ext.define('HotelApp.view.menu.Sidebar', {
    extend: 'Ext.tree.Panel',
    requires: [
        'HotelApp.reader.JSONReader'
    ],
    xtype: 'menuSidebar',
    frame: true,
    height: 400,
    width: 200,
    title: 'Menu',
    useArrows: true,
    hideHeaders: true,
    columns: [{
            xtype: 'treecolumn',
            dataIndex: 'name',
            flex: 1
        }],
    listeners: {
        itemclick: function (s, r) {
            //console.log(s);
            console.log(r);
            //alert(r.data.text);
            var tabs = Ext.getCmp('tabs');

            //gak ngerti kok tabs.items.items @_@
            var items = tabs.items.items;
            var ada = false;
            for(var i=0;i<items.length;i++){
                var item = items[i];
                if(item.title == r.data.name){
                    tabs.setActiveTab(i);
                    ada = true;
                    break;
                }
            }
            if(!ada){
                tabs.add({
                    title: r.data.name,
                    //iconCls: someicon,
                    closable: true,
                    items: [Ext.create('HotelApp.view.content.'+r.data.text+'.Index')]
                });
                tabs.setActiveTab(tabs.items.length -1);
            }
            
            // OR if you create the view as an Ext.tab.Tab which already contains the gridpanel
            //tabs.add(Ext.create('MyApp.view.MyTab'));
        }
    },
    store: new Ext.data.TreeStore({
        proxy: {
            type: 'ajax',
            url: baseUrl + 'backend/module/listMenu',
            reader: {
                xtype: 'jsonreader'
            }
        },
        root: {
            name: 'Menu'
        },
        folderSort: true,
        sorters: [{
                property: 'text',
                direction: 'ASC'
            }]
    })
});