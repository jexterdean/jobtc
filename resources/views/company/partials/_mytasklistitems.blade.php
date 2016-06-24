@if($tasks->count() > 0)
@foreach($tasks as $val)
<div class="modal fade" id="edit_task_{{ $val->task_id }}" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('task/edit', ['task'=> $val] )
        </div>
    </div>
</div>
<div id="collapse-container-{{ $val->task_id }}" class="panel task-list">
    <div class="panel-heading task-header" data-target="#collapse-{{ $val->task_id }}" role="tab" id="headingOne" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="panel-title task-list-header">{{ $val->task_title }}</h4>
            </div>
            <div class="col-xs-6">
                <div class="btn-group pull-right">
                    <a href="#" data-toggle='modal' data-target='#edit_task_{{ $val->task_id }}' class="edit-tasklist"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#" class="drag-handle move-tasklist"><i class="fa fa-arrows" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div id="collapse-{{ $val->task_id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
            <div class="panel-content">
                <div id="load-task-assign-{{ $val->task_id }}" class="load-task-assign" data-url="{{ url('task/' . $val->task_id ) }}" style="margin-top: -10px;"></div>
            </div>
        </div>
    </div>
</div>
@endforeach
@else
<div class="panel task-list empty-notifier">No Briefcases available.</div>
@endif

<br />
<div class="project-options row">
    <div class="col-md-12">
        <div class="pull-left">
            <a href="#" id="add-briefcase" class="btn btn-shadow btn-default add-briefcase">
                <i class="fa fa-plus"></i> 
                <strong>New Briefcase</strong>
            </a>
            <input class="project_id" type="hidden" value="{{$project_id}}"/>
        </div>
        @if($project_owner === Auth::user('user_id')->user_id)
        <div class="pull-right">
            <a href="#" class="btn-delete btn-shadow btn delete-project">
                <i class="fa fa-times"></i> 
                Delete
            </a>
            <a href="{{url('project/'.$project_id.'/edit')}}" class="btn-edit btn-shadow btn edit-project" data-toggle="modal" data-target="#edit_project_form">
                <i class="fa fa-pencil" aria-hidden="true"></i> 
                Edit
            </a>
            <input class="project_id" type="hidden" value="{{$project_id}}"/>
        </div>
        @endif
    </div>
</div>

<!--Put this into a single javascript file and call it-->
<script>

    /*Project Options*/
    $('.project-options').on('click', '.delete-project', function (e) {
        //e.stopImmediatePropagation();
        var r = confirm("Are you sure you want to delete this project?");
        if (r == true) {
            console.log('Deleted');
            var url = public_path + 'deleteProject';
            var project_id = $(this).siblings('.project_id').val();

            var data = {
                'project_id': project_id
            };

            $.post(url, data, function () {
                $('#project-' + project_id).remove();
                $('#project-collapse-' + project_id).remove();
            });
        }
    });

    $('.project-options').on('click', '.add-briefcase', function (e) {
        e.stopImmediatePropagation();
        $(this).addClass('disabled');

        var url = public_path + 'addBriefcaseForm';
        var project_id = $(this).siblings('.project_id').val();
        var project_container = $('#project-collapse-' + project_id + ' .task-list').last();

        $.get(url, function (data) {
            project_container.append(data);
        });


        $('#project-collapse-' + project_id).on('click', '.save-briefcase', function (e) {
            e.stopImmediatePropagation();
            var save_url = public_path + 'addBriefcase';
            var briefcase_data = {
                'title': $('input[name="briefcase-title"]').val(),
                'project_id': project_id
            };
            $.post(save_url, briefcase_data, function (data) {
                $('#project-collapse-' + project_id + ' #add-briefcase-form').remove();
                $('#project-collapse-' + project_id + ' #add-briefcase').removeClass('disabled');
                $(data).insertAfter(project_container);

                var task_id = $(data).attr('id').split('_').pop();
                var task_url = public_path + 'task/' + task_id;

                $('#load-task-assign-' + task_id).load(task_url);
                $('#project-collapse-' + project_id + ' .empty-notifier').last().remove();
            });
        });

        $('#project-collapse-' + project_id).on('click', '.cancel-briefcase', function () {
            $('#project-collapse-' + project_id + ' #add-briefcase').removeClass('disabled');
            $('#project-collapse-' + project_id + ' #add-briefcase-form').remove();
        });
    });


</script>
