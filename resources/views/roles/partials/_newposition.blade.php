<div class="col-md-6">
    <div  class="box box-default">
        <div class="box-container">
            <div class="box-header" id="position-{{$position->id}}" data-toggle="collapse" data-target="#position-collapse-{{$position->id}}">
                <h3 class="box-title">{{$position->name}}</h3>
            </div>
            <div class="box-body">
                <div id="position-collapse-{{$position->id}}" class="box-content collapse">
                    @include('company.partials._positionpermissions')
                </div><!--Box Container-->
            </div>
        </div>
    </div>
</div>