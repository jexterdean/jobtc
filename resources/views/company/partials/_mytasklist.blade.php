@foreach($projects->chunk(2) as $chunk)
<div class="row">
    @foreach($chunk as $project)
    <div class="col-md-6">
        <div  class="box box-default">
            <div class="box-container">
                <div class="box-header toggle-subprojects" id="project-{{$project->project_id}}" data-toggle="collapse" data-target="#project-collapse-{{ $project->project_id }}">
                    <h3 class="box-title">{{$project->project_title}}</h3>
                     <div class="pull-right">
                        @if(intval($project->company_id) !== intval($company_id))
                        <label>Shared by {{$project->company->name}}</label>
                        @endif
                        @if(Auth::user('user')->user_id !== $project->user_id)
                        <label>Shared by {{$project->user->name}}</label>
                        @endif
                    </div>
                    <input class="project_id" type="hidden" value="{{$project->project_id}}"/>
                    <input class="company_id" type="hidden" value="{{$project->company_id}}"/>
                </div>
                <div class="box-body">
                    <div id="project-collapse-{{ $project->project_id }}" class="box-content collapse">
                      
                    </div><!--Box Container-->
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endforeach

