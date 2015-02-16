Ext.define('HotelApp.view.content.<?php echo Utils::lowerFirst($this->modelClass); ?>.Form', {
    extend: 'Ext.window.Window',
    xtype: '<?php echo Utils::lowerFirst($this->modelClass); ?>Form',

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
        items: [<?php
        $array  = array();
        foreach ($this->tableSchema->columns as $name => $column) {
            if($name == "id") continue;
            $array[] = "{
            xtype: 'textfield',
            fieldLabel: '".Utils::toTitle($name)."',
            reference: '".$name."',
            msgTarget: 'side',
            bind: '{the".$this->modelClass.".".$name."}'
        }";
        }
        
        echo implode(", ", $array);
        
        ?>]
    },

    buttons: [{
        text: 'Save',
        handler: 'onSaveClick'
    }, {
        text: 'Cancel',
        handler: 'onCancelClick'
    }]
});