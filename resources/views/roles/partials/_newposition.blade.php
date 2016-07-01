<div class="col-md-6">
    <div  class="box box-default">
        <div class="box-container">
            <div class="box-header" id="position-{{$position->id}}" data-toggle="collapse" data-target="#position-collapse-{{$position->id}}">
                <h3 class="box-title">{{$position->name}}</h3>
            </div>
            <div class="box-body">
                <div id="position-collapse-{{$position->id}}" class="box-content collapse">
                    <ul class="list-group">
                        @foreach($employees as $employee)
                        @if($employee->role_id === $position->id)
                        <li class="list-group-item">
                            {{$employee->user->name}}
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div><!--Box Container-->
            </div>
        </div>
    </div>
</div>