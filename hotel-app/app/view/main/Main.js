/**
 * This class is the main view for the application. It is specified in app.js as the
 * "autoCreateViewport" property. That setting automatically applies the "viewport"
 * plugin to promote that instance of this class to the body element.
 *
 * TODO - Replace this content of this view to suite the needs of your application.
 */


Ext.define('HotelApp.view.main.Main', {
    extend: 'Ext.container.Container',
    requires: [
        'HotelApp.view.main.MainController',
        'HotelApp.view.main.MainModel',
        'HotelApp.view.menu.Sidebar'
    ],
    xtype: 'app-main',
    controller: 'main',
    viewModel: {
        type: 'main'
    },
    layout: {
        type: 'border'
    },
    items: [{
            region: 'west',
            xtype: 'menuSidebar',
        }, {
            id: 'tabs',
            region: 'center',
            xtype: 'tabpanel',
            items: [
                {
                    glyph: 72,
                    title: 'Home',
                    html: '',
                    items: [Ext.create('HotelApp.view.main.Home')]
                }
            ]
        }]
});
