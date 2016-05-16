/* 
 * Companies Page scripts
 */

//For Dragging employees to projects
$('.list-group').sortable({
    dropOnEmpty: true,
    connectWith: ".list-group",
    handle: '.drag-handle',
    receive: function (event, ui) {
        
        project_id = $(this).siblings().val();
        list_group_user_id = $(ui.item).attr('id');
        user_id = list_group_user_id.split('-').pop();
        
        
        url = public_path + 'createTeam/';
        data = {
            'project_id' : project_id,
            'user_id' : user_id
        };
        
        $.post(url,data);
        //Remove warning that no employee is assigned.
        $(this).find('li:contains("No Employees assigned to this project.")').remove();

    },
    update: function (event, ui) {

    }
});

