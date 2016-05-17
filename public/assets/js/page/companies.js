/* 
 * Companies Page scripts
 */

//For Dragging employees to projects
$('.list-group').sortable({
    dropOnEmpty: true,
    connectWith: ".list-group",
    handle: '.drag-handle',
    remove: function (event, ui) {
        //Don't remove item when dropped to the project list group
        $(this).append($(ui.item).clone());
    },
    receive: function (event, ui) {

        project_id = $(this).siblings().val();
        list_group_user_id = $(ui.item).attr('id');
        user_id = list_group_user_id.split('-').pop();

        var identicalItemCount = $("#project-" + project_id + ' .list-group').children('li:contains(' + ui.item.text() + ')').length;

        //If a duplicate, remove it
        if (identicalItemCount > 1) {
            $("#project-" + project_id + ' .list-group').children('li:contains(' + ui.item.text() + ')').first().remove();
        }

        //Show unassign button
        $(ui.item).find('.unassign-member').removeClass('hidden');

        //Get other list group item's team and
        team_id = $(ui.item).parent().find('.team_id').val();

        //Assign it to the this list group item's input
        $(ui.item).find('.team_id').val(team_id);

        //Create Team if 
        url = public_path + 'createTeam/';
        data = {
            'project_id': project_id,
            'user_id': user_id
        };

        $.post(url, data);

        //Remove warning that no employee is assigned.
        $(this).find('li:contains("No Employees assigned to this project.")').remove();


    },
    update: function (event, ui) {

    }
});


/*Unassign Team Member from project*/
$('.list-group').on('click', '.unassign-member', function () {

    var list_item = $(this).parent().parent().parent().remove();

    //Remove the element immediately
    list_item.remove();

    var user_id = $(this).parent().find('.user_id').val();
    var team_id = $(this).parent().find('.team_id').val();

    data = {
        'team_id': team_id,
        'user_id': user_id
    };

    url = public_path + 'unassignTeamMember/';

    $.post(url, data);
});



function removeDuplicates(listName, newItem) {
    var dupl = false;
    $("#" + listName + " > li").each(function () {
        if ($(this)[0] !== newItem[0]) {
            if ($(this).html() === newItem.html()) {
                dupl = true;
            }
        }
    });

    return !dupl;
}