Ext.define('HotelApp.view.content.mAccount.Form', {
    extend: 'Ext.window.Window',
    xtype: 'mAccountForm',

    bind: {
        title: '{title}'
    },
    layout: 'fit',
    modal: true,
    width: 500,
    height: 430,
    closable: true,
    constrain: true,

    items: {
        xtype: 'form',
        reference: 'form',
        bodyPadding: 10,
        border: false,
        // use the Model's validations for displaying form errors
        modelValidation: true,
        layout: {
            type: 'vbox',
            align: 'stretch'
        },
        items: [{
            xtype: 'textfield',
            fieldLabel: 'Code',
            reference: 'code',
            msgTarget: 'side',
            bind: '{theMAccount.code}'
        }, {
            xtype: 'textfield',
            fieldLabel: 'Name',
            reference: 'name',
            msgTarget: 'side',
            bind: '{theMAccount.name}'
        }, {
            xtype: 'textfield',
            fieldLabel: 'Status',
            reference: 'status',
            msgTarget: 'side',
            bind: '{theMAccount.status}'
        }, {
            xtype: 'textfield',
            fieldLabel: 'Level',
            reference: 'level',
            msgTarget: 'side',
            bind: '{theMAccount.level}'
        }, {
            xtype: 'textfield',
            fieldLabel: 'Type',
            reference: 'type',
            msgTarget: 'side',
            bind: '{theMAccount.type}'
        }, {
            xtype: 'textfield',
            fieldLabel: 'Active',
            reference: 'active',
            msgTarget: 'side',
            bind: '{theMAccount.active}'
        }]
    },

    buttons: [{
        text: 'Save',
        handler: 'onSaveClick'
    }, {
        text: 'Cancel',
        handler: 'onCancelClick'
    }]
});