
var _gbl_draft_socket = null;

function connect_draft_socket(draft) {
    _gbl_draft_socket = io(':3000', {query: 'draft='+draft});
    send_draft_update();

    _gbl_draft_socket.on('update', function(msg){
        console.log('received update from the server '+msg);
    });
}

function send_draft_update() {
    if(typeof _gbl_draft_socket !== 'undefined' && _gbl_draft_socket) {
        _gbl_draft_socket.emit('update', 'maci');
    }
}