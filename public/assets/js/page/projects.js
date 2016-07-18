/*Briefcases Load on Demand*/
$('#accordion').on('click', '.toggle-briefcase', function () {
    //var project_id = $(this).attr('id').split('-').pop();
    var task_id = $(this).find('.task_id').val();
    //var company_id = $(this).find('.company_id').val();
    var company_id = $(this).find('.company_id').val();

    var task_url = public_path + '/task/' + task_id;

    $('#load-task-assign-' + task_id).load(task_url, function () {
        $('#task-' + task_id).removeClass('toggle-briefcase');
    });
});

