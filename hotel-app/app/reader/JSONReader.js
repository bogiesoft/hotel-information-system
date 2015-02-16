Ext.define('HotelApp.reader.JSONReader', {
    extend: 'Ext.data.reader.Json',
    //alias: 'reader.treereader',
    xtype: "jsonreader",
    getResponseData: function( response ) {
        var result = Ext.decode(response.responseText);
        return result.results.data;
    }
})