/**
 * The main application class. An instance of this class is created by app.js when it calls
 * Ext.application(). This is the ideal place to handle application launch and initialization
 * details.
 */

var baseUrl = "http://localhost/hotelextjs/";

Ext.define('HotelApp.Application', {
    extend: 'Ext.app.Application',
    name: 'HotelApp',
    
    requires: [
        'HotelApp.ResourceManager'
    ],

    stores: [
        // TODO: add global / shared stores here
    ],
    
    launch: function () {
        HotelApp.ResourceManager.addImage = baseUrl + "resources/images/icons/add.png";
        HotelApp.ResourceManager.editImage = baseUrl + "resources/images/icons/pencil.png";
        HotelApp.ResourceManager.deleteImage = baseUrl + "resources/images/icons/delete.png";
    }
});
