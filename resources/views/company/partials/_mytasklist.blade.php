@foreach($projects->chunk(2) as $chunk)
<div class="row">
    @foreach($chunk as $project)
    <div class="col-md-6">
        <div  class="box box-default">
            <div class="box-container">
                <div class="box-header toggle-subprojects" id="project-{{$project->project_id}}" data-toggle="collapse" data-target="#project-collapse-{{ $project->project_id }}">
                    <h3 class="box-title">{{$project->project_title}}</h3>
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

