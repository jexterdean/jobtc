<label class='center-block taskgroup-title'>Employees</label>
<ul class="employee-list-group list-group">
    @if($employees->count() > 0)
    @foreach($employees as $employee)
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-9">
                <a id="employee-{{$employee->user_id}}" class="toggle-subprojects" data-toggle="collapse" href="#employee-collapse-{{$employee->user_id}}">
                    <i class="pull-left fa fa-chevron-down" aria-hidden="true"></i>
                    {{$employee->user->name}}
                    <input class="project_id" type="hidden" value="{{$project_id}}"/>
                </a>
            </div>
        </div>
        <div class="row">
            <div id="employee-collapse-{{$employee->user_id}}" class="task-list-container collapse">
                
            </div>                            
        </div>
    </li>
    @endforeach
    @else
    <li class="list-group-item">
        No Employees Available for Sharing.
    </li>
    @endif
</ul>