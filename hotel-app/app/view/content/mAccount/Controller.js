Ext.define('HotelApp.view.content.mAccount.Controller', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.mAccount',
    
    requires: [
        'Ext.window.Window',
        'HotelApp.view.content.mAccount.Form'
    ],
    
    getSelectedRowData: function(){
        var grid = Ext.getCmp('mAccountGrid');
        var selection = grid.getSelectionModel();
        for (var i = 0; i < grid.store.getCount(); i++) {
            if (selection.isSelected(i)) {
                var data = (grid.store.getAt(i));
                return data;
            }
        }
        return null;
    },
    
    createDialog: function(record) {
        //var view = this.getView();

        this.isEdit = !!record;
        
        this.dialog = Ext.create('HotelApp.view.content.mAccount.Form', {
            viewModel: {
                data: {
                    title: record ? 'Edit: ' + record.get('name') : 'Add MAccount'
                },
                // If we are passed a record, a copy of it will be created in the newly spawned session.
                // Otherwise, create a new phantom MAccount in the child.
                links: {
                    theMAccount: record || {
                        type: 'MAccount',
                        create: true
                    }
                }
            },
            controller: this,
        });

        this.dialog.show();
    },
    
    onAddClick: function(button){
        this.createDialog(null);
    },
    
    onEditClick: function (button) {
        this.createDialog(this.getSelectedRowData());
    },
    
    onDeleteClick: function (button) {
        Ext.MessageBox.confirm('Information', 'Are you sure you want delete this data?', function(btn, text){
            if(btn == "yes"){
                this.getSelectedRowData().erase();
            }
        }, this);
    },
    
    onSaveClick: function () {
        // Save the changes pending in the dialog's child session back to the
        // parent session.
        var dialog = this.dialog,
            form = this.lookupReference('form'),
            isEdit = this.isEdit,
            id;

        if (form.isValid()) {
            var data = dialog.getViewModel().getData();
            
            //remove dialog
            this.onCancelClick();
            data.theMAccount.save({
                callback : function(record, operation) {
                    if (operation.wasSuccessful()) {
                        //reload the grid
                        var grid = Ext.getCmp('mAccountGrid');
                        grid.store.loadPage(1);
                    } else {
                        Ext.Msg.alert('Warning', 'Data cannot be saved.');
                    }
                }
            });
        }else{
            Ext.MessageBox.alert('Information', 'Changes saved successfully.');
        }
    },

    onCancelClick: function () {
        this.dialog = Ext.destroy(this.dialog);
    },
});