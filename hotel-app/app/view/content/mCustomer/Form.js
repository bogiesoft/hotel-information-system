Ext.define('HotelApp.view.content.mCustomer.Form', {
    extend: 'Ext.window.Window',
    xtype: 'customerForm',

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
            fieldLabel: 'Name',
            reference: 'name',
            msgTarget: 'side',
            bind: '{theCustomer.name}'
        }, {
            xtype: 'textfield',
            fieldLabel: 'Phone',
            reference: 'phone',
            msgTarget: 'side',
            bind: '{theCustomer.phone_1}'
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


/*Ext.define('HotelApp.view.content.mCustomer.Form', {
    extend: 'Ext.form.Panel',
    xtype: 'form-customer',
    
    
    frame: false,
    bodyPadding: 10,
    scrollable:true,
    width: 355,

    fieldDefaults: {
        labelAlign: 'right',
        labelWidth: 115,
        msgTarget: 'side'
    },
    
    items: [{
        xtype: 'fieldset',
        title: 'User Info',
        defaultType: 'textfield',
        defaults: {
            anchor: '100%'
        },
        
        items: [
            { allowBlank:false, fieldLabel: 'User ID', name: 'user', emptyText: 'user id' },
            { allowBlank:false, fieldLabel: 'Password', name: 'pass', emptyText: 'password', inputType: 'password' },
            { allowBlank:false, fieldLabel: 'Verify', name: 'pass', emptyText: 'password', inputType: 'password' }
        ]
    }, {
        xtype: 'fieldset',
        title: 'Contact Information',
        
        defaultType: 'textfield',
        defaults: {
            anchor: '100%'
        },
        
        items: [{
            fieldLabel: 'First Name',
            emptyText: 'First Name',
            name: 'first'
        }, {
            fieldLabel: 'Last Name',
            emptyText: 'Last Name',
            name: 'last'
        }, {
            fieldLabel: 'Company',
            name: 'company'
        }, {
            fieldLabel: 'Email',
            name: 'email',
            vtype: 'email'
        }, 
        //
        {
            xtype: 'combobox',
            fieldLabel: 'State',
            name: 'state',
            store: {
                type: 'states'
            },
            valueField: 'abbr',
            displayField: 'state',
            typeAhead: true,
            queryMode: 'local',
            emptyText: 'Select a state...'
        }
        //
        , {
            xtype: 'datefield',
            fieldLabel: 'Date of Birth',
            name: 'dob',
            allowBlank: false,
            maxValue: new Date()
        }]
    }],

    buttons: [{
        text: 'Register',
        disabled: true,
        formBind: true
    }]
});*/