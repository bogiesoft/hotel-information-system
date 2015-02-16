Ext.define('HotelApp.component.Button', {
    extend: 'Ext.button.Button',
    xtype: 'hotelButton',
    
    requires: [
        'HotelApp.ResourceManager'
    ],
    
    icon: HotelApp.ResourceManager.editImage,
    text: 'Button',
    scale: 'medium',
    iconAlign: 'right',
});