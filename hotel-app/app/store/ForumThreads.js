Ext.define('HotelApp.store.ForumThreads', {
    extend: 'Ext.data.Store',

    alias: 'store.forumthreads',
    model: 'HotelApp.model.ForumThread',

    pageSize: 50,
    remoteSort: true,
    sorters: [{
        property: 'name',
        direction: 'DESC'
    }]
});