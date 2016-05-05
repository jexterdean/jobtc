<div class="row">
    <div class="col-md-8">
        @foreach($team as $v)
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title" style="width: 80%;" data-target="#member-{{ $v->id }}" data-toggle="collapse">{{ $v->title }}</h3>
                    <div class="box-tools pull-right">
                        <a href="#" class="btn btn-submit btn-add-member" id="{{ $v->id }}" data-toggle="modal" data-target="#add_member">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="box-body collapse" id="member-{{ $v->id }}">
                    <div class="box-content">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="row">
                                    @foreach($v->member as $m)
                                    <div class="col-sm-6">
                                        <div class="media">
                                            <div class="media-left">
                                                {!! HTML::image('/assets/user/avatar.png', '', array('style' => 'width: 64px;max-width: 64px!important;')) !!}
                                            </div>
                                            <div class="media-body">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <h3 class="media-heading">{{ $m->name }}</h3>
                                                        {{ $m->email }}
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <button class="btn btn-delete" data-type="member" id="{{ $m->id }}">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="box box-default">
                                    <div class="box-container">
                                        <div class="box-header">
                                            <h3 class="box-title" style="width: 70%;">Projects</h3>
                                            <div class="box-tools pull-right">
                                                <a href="#" class="btn btn-submit add-project-btn" id="{{ $v->id }}">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <div class="box-content">
                                                @foreach($v->projects as $p)
                                                <div class="row">
                                                    <div class="col-sm-8">{{ $p->project_title }}</div>
                                                    <div class="col-sm-2">
                                                        <button class="btn btn-delete" data-type="project" id="{{ $p->project_id }}">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-md-4">
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title" style="width: 80%;" data-toggle="collapse" data-target="#team-library">Teams</h3>
                    <div class="box-tools pull-right">
                        <a href="#" class="btn btn-submit" data-toggle="modal" data-target="#create_team">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="box-body collapse in" id="team-library">
                    <div class="box-content">
                        @foreach($team as $v)
                        <div class="row">
                            <div class="col-sm-10">{{ $v->title }}</div>
                            <div class="col-sm-2">
                                <button class="btn btn-delete" data-type="team" id="{{ $v->id }}">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create_team">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Create Team</h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_member">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Member</h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_project">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Project</h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

@section('js_footer')
@parent
<script>
    $(function(e){
        var create_team = $('#create_team');
        create_team.on('show.bs.modal', function(e){
            $.ajax({
                url: '{{ URL::to("/teamBuilder/create?p=team") }}',
                success: function(doc) {
                    create_team.find('.modal-body').html(doc);
                }
            });
        });

        var add_member_btn = $('.btn-add-member');
        var add_member = $('#add_member');
        add_member_btn.click(function(e){
            var thisId = this.id;
            var thisUrl = '{{ URL::to("/teamBuilder/create?p=member") }}&id=' + thisId;
            $.ajax({
                url: thisUrl,
                success: function(doc) {
                    add_member.modal('show');
                    add_member.find('.modal-body').html(doc);
                }
            });
        });

        var add_project_btn = $('.add-project-btn');
        var add_project = $('#add_project');
        add_project_btn.click(function(e){
            var thisId = this.id;
            var thisUrl = '{{ URL::to("/teamBuilder/create?p=project") }}&id=' + thisId;
            $.ajax({
                url: thisUrl,
                success: function(doc) {
                    add_project.modal('show');
                    add_project.find('.modal-body').html(doc);
                }
            });
        });

        var btn_delete = $('.btn-delete');
        btn_delete.click(function(e){
            var thisId = this.id;
            var type = $(this).data('type');
            var thisUrl = '{{ URL::to('teamBuilder') }}/' + thisId + '?p=' + type;

            waitingDialog.show('Pleas wait...');
            $.ajax({
                url: thisUrl,
                method: "DELETE",
                success: function(doc) {
                    location.reload();
                }
            });
        });
    });
</script>
@stop