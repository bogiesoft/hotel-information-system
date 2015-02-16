Ext.define('HotelApp.component.Window', {
    extend: 'Ext.window.Window',
    xtype: 'hotelWindow',
    
    id: 'hotelWindow',
    layout: 'fit',
    height: 500,
    width: 600,
    title: 'Window',
    scrollable: true,
    modal: true,
    bodyPadding: 10,
    html: "",
    constrain: true,
    closable: true
});