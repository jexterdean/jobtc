/* 
 * Companies Page scripts
 */

//For Dragging employees to projects
$('.list-group').sortable({
    dropOnEmpty: true,
    connectWith: ".list-group",
    handle: '.drag-handle',
    receive: function (event, ui) {

        //Remove warning that no employee is assigned.
        $(this).find('li:contains("No Employees assigned to this project.")').remove();

    },
    update: function (event, ui) {

    }
});

