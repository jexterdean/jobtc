/*Initialize socket transactions(for real time update)*/
//var socket = io('http://job.tc:3000');
var socket_port = '3000';
var base_url = 'https://' + window.location.host;
var site_name = base_url.split(':');
var socket_url = site_name[0] + ':' + site_name[1] + ':' + socket_port;

//Check the page and create it as a room
var page_url = window.location.pathname;
var absolute_path = page_url.split('/')[1];


var socket = io.connect(socket_url);
console.log(socket);
//Create room and join room for socket.io
//Create Room for that page only
socket.emit('create', page_url);

if (absolute_path === 'company') {
    
    var get_projects_url = public_path + 'getCompanyProjects/'+page_url.split('/').pop();
    
    $.get(get_projects_url,function(data){
        for(x in data) {
            var project_url = '/project/' +data[x]
            socket.emit('create', project_url);
        }
    });
}

/*
 * Receive applicant comments from the Server
 **/
socket.on('applicant-comment', function (msg) {
    console.log(msg);
    var applicant_id = $(msg).find('.applicant_id').val();

    var comment_id = $('.comment-list').find('.comment_id').eq(0).val();
    if (comment_id === 'undefined') {
        comment_id = 0;
    }

    if ($('.no-comment-notifier').length === 1) {
        $('.no-comment-notifier').remove();
    }
    //Update the comment list
    var new_comment_id = $(msg).find('.comment_id').val();
    if (new_comment_id !== comment_id) {
        $('#comment-list-' + applicant_id).prepend(msg);
        $('.comment-list').animate({scrollTop: 0}, 'slow');
    }
});

/*
 * Receive New Task list items from the Server
 **/
socket.on('add-task-list-item', function (msg) {
    //var subproject = $('#list_group_' + this.id);
    //console.log(msg.list_group_id);
    //console.log(msg.html);
    
    var subproject = $('#list_group_' + msg.list_group_id);
    
    subproject.html(msg.html);
});