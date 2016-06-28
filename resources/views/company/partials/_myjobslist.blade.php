<div class="mini-space"></div>
<div class="row">
    <div class="col-md-12">
        <ul id="my_jobs_tabs" class="nav nav-tabs">
            <li class="my_jobs_tab active"><a data-toggle="pill" href="#my_jobs_list">My Jobs</a></li>
            <li class="shared_jobs_tab"><a data-toggle="pill" href="#shared_jobs_list">Shared Jobs</a></li>
            <li><a class="share_jobs_tab" data-toggle="pill" href="#share_jobs">Share Jobs</a></li>
        </ul>
        <div class="tab-content">
            <div id="my_jobs_list" class="tab-pane fade in active">
                <div class="job_container">
                    @foreach($my_jobs->chunk(2) as $chunk)
                    <div class="job-row row">
                        @foreach($chunk as $job)
                        <div class="col-md-6">
                            <div  class="box box-default">
                                <div class="box-container">
                                    <div class="box-header">
                                        <h3 class="box-title">
                                            <a target="_blank" href="{{url('job/'.$job->id)}}">{{$job->title}}</a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                <div class="mini-space"></div>
                <div class="job_tab_options">
                    <a href="#" id="add-job" class="btn btn-shadow btn-default add-job">
                        <i class="fa fa-plus"></i> 
                        <strong>New Job</strong>
                    </a>
                    <input class="company_id" type="hidden" value="{{$company_id}}"/>
                </div>
                <div class="mini-space"></div>
            </div>
            <div id="shared_jobs_list" class="tab-pane fade in">
                @foreach($shared_jobs->chunk(2) as $chunk)
                <div class="job-row row">
                    @foreach($chunk as $job)
                    <div class="col-md-6">
                        <div  class="box box-default">
                            <div class="box-container">
                                <div class="box-header">
                                    <h3 class="box-title">
                                        <a target="_blank" href="{{url('job/'.$job->id)}}">{{$job->title}}</a>
                                    </h3>
                                    <div class="pull-right">
                                        <div class="row">
                                            <label>Shared by {{$job->user->name}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            <div id="share_jobs" class="tab-pane fade in">
                <!--Load the content with AJAX when the user clicks on tab-->
            </div>
        </div>
    </div>
</div>
<script>

    $('#my_jobs_tabs').one('click', '.share_jobs_tab', function () {
        var url = public_path + 'getShareJobsTab/' + company_id;
        if ($.trim($('#share_jobs').is(':empty'))) {
            $('#share_jobs').load(url, function () {
                shareJobsScripts();
            });
        }
    });



    /*Add Job*/
    $('#my_jobs_list').on('click', '#add-job', function (e) {
        e.stopImmediatePropagation();

        $(this).addClass('disabled');

        var url = public_path + 'addJobFormCompany';
        var job_container = $('.job_container');

        $.get(url, function (data) {
            job_container.append(data);
        });

    });

    $('#my_jobs_list').on('click', '.save-job', function (e) {
        e.stopImmediatePropagation();
        var url = public_path + 'addJobCompany';
        var job_container = $('.job_container');
        var company_id = $('.job_tab_options').find('.company_id').val();

        var data = {
            'job_title': $('input[name="job-title"]').val(),
            'company_id': company_id
        };

        $.post(url, data, function (data) {
            $('#add-job-form').remove();
            $('#add-job').removeClass('disabled');
            var job_count = job_container.find('.job-row').last().children().length;

            if (job_count === 1) {
                job_container.find('.job-row').last().append(data);
            } else {
                job_container.append('<div class="job-row row">' + data + '</div>');
            }


        });
    });

    $('#my_jobs_list').on('click', '.cancel-job', function (e) {
        e.stopImmediatePropagation();
        $('#add-job').removeClass('disabled');
        $('#add-job-form').remove();
    });
    
    function shareJobsScripts() {

    $('.job-list-group').sortable({
        dropOnEmpty: true,
        connectWith: ".job-list-group",
        handle: '.drag-handle',
        remove: function (event, ui) {
            //Don't remove item when dropped to the project list group
            $(this).append($(ui.item).clone());
        },
        receive: function (event, ui) {

            job_id = $(ui.item).find('.job_id').val();
            list_group_id = $(ui.item).parent().attr('id');
            console.log(list_group_id);
            var user_id;
            var company_id;
            var share_url;
            var share_data;

            if (list_group_id.split('-')[0] === 'user') {
                user_id = list_group_id.split('-').pop();

                var identicalItemCount = $("#user-" + user_id + ' .list-group').children('li:contains(' + ui.item.find('.job_id').val() + ')').length;

                //If a duplicate, remove it
                if (identicalItemCount > 1) {
                    $("#user-" + user_id + ' .list-group').children('li:contains(' + ui.item.find('.job_id').val() + ')').remove();
                }

                //Show unassign button
                $(ui.item).find('.unshare-job').removeClass('hidden');

                //Assign Test to Job
                share_url = public_path + 'shareJobToUser';
                share_data = {
                    'user_id': user_id,
                    'job_id': job_id
                };

                $.post(share_url, share_data, function (data) {
                    //Assign the applicant id to the this list group item's input
                    //$(ui.item).find('.job_id').val(data);
                    //$(ui.item).find('.employee-list').html(data);
                });

            } else {
                company_id = list_group_id.split('-').pop();

                var identicalItemCount = $("#company-" + company_id + ' .list-group').children('li:contains(' + ui.item.find('.job_id').val() + ')').length;

                //If a duplicate, remove it
                if (identicalItemCount > 1) {
                    $("#company-" + company_id + ' .list-group').children('li:contains(' + ui.item.find('.job_id').val() + ')').remove();
                }

                //Show unassign button
                $(ui.item).find('.unshare-job').removeClass('hidden');

                $(ui.item).find('.company_id').val(company_id);

                //Share Job to a Company
                share_url = public_path + 'shareJobToCompany';
                share_data = {
                    'company_id': company_id,
                    'job_id': job_id
                };

                $.post(share_url, share_data, function (data) {
                    //Assign the applicant id to the this list group item's input
                    //$(ui.item).find('.job_id').val(data);
                    //$(ui.item).find('.employee-list').html(data);
                    //$(ui.item).find('.employee-list').html(data);
                    $(ui.item).find('.toggle-employees').attr('id', 'shared-company-item-' + data);
                    $(ui.item).find('.toggle-employees').attr('href', '#employee-collapse-' + data);
                    $(ui.item).find('.employee-list').attr('id', 'employee-collapse-' + data);
                });
            }



            //Remove warning that no employee is assigned.
            $(this).find('li:contains("Drag a test here to make it available for all applicants in this job posting.")').remove();

        },
        update: function (event, ui) {

        }
    });

    /*Unshare Job from User*/
    $('.job-list-group').on('click', '.unshare-job', function () {
        var list_item = $(this).parent().parent().parent();
        var list_group = list_item.parent().attr('id').split('-')[0];

        var job_id = $(this).find('.job_id').val();
        var user_id;
        var company_id;

        //Remove the element immediately 
        list_item.remove();

        if (list_group === 'user') {
            user_id = $(this).find('.user_id').val();

            data = {
                'user_id': user_id,
                'job_id': job_id
            };

            url = public_path + 'unshareJobFromUser';
            $.post(url, data);

        } else {
            company_id = $(this).find('.company_id').val();
            console.log(company_id);
            data = {
                'company_id': company_id,
                'job_id': job_id
            };

            url = public_path + 'unshareJobFromCompany';
            $.post(url, data);
        }
    });

    /*Employees per Company Load on Demand*/
    $('.job-list-group').on('click', '.toggle-employees', function () {
        var shared_company_job_id = $(this).attr('id').split('-').pop();
        var job_id = $(this).parent().parent().parent().attr('id').split('-').pop();
        var company_id = $(this).parent().parent().parent().parent().attr('id').split('-').pop();

        console.log('shared_company_job_id: ' + shared_company_job_id);
        console.log('company_id: ' + company_id);

        var url = public_path + 'getEmployees/' + company_id + '/' + job_id;

        if ($.trim($('#employee-collapse-' + shared_company_job_id).is(':empty'))) {
            $('#employee-collapse-' + shared_company_job_id).load(url, function () {

            });
        }
    });

    $('.job-list-group').on('click', '.job-permission', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var user_id = $(this).children('.user_id').val();
        var job_id = $(this).children('.job_id').val();
        var company_id = $(this).children('.company_id').val();


        var assign_html = '<i class="fa fa-check" aria-hidden="true"></i>';
        assign_html += '<input class="user_id" type="hidden" value="' + user_id + '"/>';
        assign_html += '<input class="job_id" type="hidden" value="' + job_id + '"/>';
        assign_html += '<input class="company_id" type="hidden" value="' + company_id + '"/>';

        var unassign_html = '<i class="fa fa-plus" aria-hidden="true"></i>';
        unassign_html += '<input class="user_id" type="hidden" value="' + user_id + '"/>';
        unassign_html += '<input class="job_id" type="hidden" value="' + job_id + '"/>';
        unassign_html += '<input class="company_id" type="hidden" value="' + company_id + '"/>';

        /*Assign the Task List to this user*/
        if ($(this).hasClass('bg-gray')) {
            $(this).switchClass('bg-gray', 'bg-green', function () {
                $(this).html(assign_html);
                shareToCompanyEmployee(user_id, company_id, job_id);
            });
        }
        /*Unassign the Task List from this user*/
        if ($(this).hasClass('bg-green')) {
            $(this).switchClass('bg-green', 'bg-gray', function () {
                $(this).html(unassign_html);
                unshareFromCompanyEmployee(user_id, company_id, job_id);
            });
        }
    });

}
    
    
</script>