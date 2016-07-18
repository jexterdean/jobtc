/*Add Projects*/
$('#my_projects').on('click', '#add-project', function (e) {
    e.stopImmediatePropagation();
    $(this).addClass('disabled');

    var url = public_path + 'addProjectForm';
    var project_container = $('.project_container');

    $.get(url, function (data) {
        project_container.append(data);
    });
});

$('#my_projects').on('click', '.save-project', function (e) {
    e.stopImmediatePropagation();
    var url = public_path + 'addProject';
    var project_container = $('.project_container');
    var company_id = $('.project_tab_options').find('.company_id').val();

    var data = {
        'project_title': $('input[name="project-title"]').val(),
        'company_id': company_id
    };

    $.post(url, data, function (data) {
        $('#add-project-form').remove();
        $('#add-project').removeClass('disabled');
        var project_count = project_container.find('.project-row').last().children().length;

        if (project_count === 1) {
            project_container.find('.project-row').last().append(data);
        } else {
            project_container.append('<div class="project-row row">' + data + '</div>');
        }


    });
});

$('#my_projects').on('click', '.cancel-project', function (e) {
    e.stopImmediatePropagation();
    $('#add-project').removeClass('disabled');
    $('#add-project-form').remove();
});

/*Subprojects Load on Demand*/
$('#my_projects').on('click', '.toggle-subprojects', function () {
    //var project_id = $(this).attr('id').split('-').pop();
    var project_id = $(this).find('.project_id').val();
    //var company_id = $(this).find('.company_id').val();
    var company_id = window.location.pathname.split('/').pop();

    var url = public_path + 'getSubprojects/' + project_id + '/' + company_id;

    if ($.trim($('#project-collapse-' + project_id).is(':empty'))) {
        $('#project-collapse-' + project_id).load(url, function () {
            $(this).find('.task-header').each(function () {
                var task_id = $(this).parent().attr('id').split('-').pop();
                var task_url = public_path + '/task/' + task_id;
                if ($.trim($('#load-task-assign-' + task_id).is(':empty'))) {
                    $('#load-task-assign-' + task_id).load(task_url, function () {
                        $('#project-' + project_id).removeClass('toggle-subprojects');
                    });
                }
            });
        });
    }
});