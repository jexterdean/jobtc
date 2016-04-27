<div class="form-body">
    <div class="form-group">
        {!!  Form::label('project_type','Project Type',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::select('project_type', [
                'Standard' => 'Standard',
                'Hiring Assessment' => 'Hiring Assessment',
                'Software Development' => 'Software Development'
            ], isset($project->project_type) ? $project->project_type : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select Project Type', 'tabindex' => '7'] )  !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('project_title','Project Title',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','project_title',isset($project->project_title) ? $project->project_title : '',
            ['class' => 'form-control', 'placeholder' => 'Project Title', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('project_description','Description',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('project_description',isset($project->project_description) ?
            $project->project_description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => 'Project Description', 'tabindex' => '6']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('client_id','Company',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::select('client_id', $clients, isset($project->client_id) ?
            $project->client_id : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select Company Name', 'tabindex' =>'2'] )  !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('account','Account',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            {!!  Form::input('text','account',isset($project->account) ?
            $project->account : '', ['class' => 'form-control form-control-inline input-medium', 'placeholder' => 'Account', 'tabindex' => '4'])  !!}
        </div>
        {!!  Form::label('currency','Currency',['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-3">
            {!!  Form::select('currency', [
                'USD' => 'USD',
                'EUR' => 'EUR',
                'GBP' => 'GBP',
                'PHP' => 'PHP'
            ], isset($project->currency) ? $project->currency : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select Currency', 'tabindex' => '7'] )  !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('reverence','Reverence',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('reverence',isset($project->reverence) ?
            $project->reverence : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => 'Reverence', 'tabindex' => '6']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('start_date','Start',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            {!!  Form::input('text','start_date',isset($project->start_date) ? date("d-m-Y",strtotime
            ($project->start_date)) : '', ['class' => 'form-control form-control-inline input-medium date-picker', 'placeholder' => 'Start', 'tabindex' => '4', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true'])  !!}
        </div>
        {!!  Form::label('deadline','Deadline',['class' => 'col-md-3 control-label'])  !!}
        <div class="col-md-3">
            {!!  Form::input('text','deadline',isset($project->deadline) ? date("d-m-Y",strtotime($project->deadline))
            : '', ['class' => 'form-control form-control-inline input-medium date-picker', 'placeholder' => 'Deadline', 'tabindex' => '5', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true'])  !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('rate_type','Rate Type',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            {!!  Form::select('rate_type', [
                'fixed' => 'Fixed',
                'hourly' => 'Hourly'
            ], isset($project->rate_type) ? $project->rate_type : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select Rate', 'tabindex' => '7'] )  !!}
        </div>
        {!!  Form::label('rate_value','Hourly Rate',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            {!! Form::input('text','rate_value',isset($project->rate_value) ? $project->rate_value : '',['class' =>
            'form-control', 'placeholder' => 'Hourly Rate', 'tabindex' => '8']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            {!!  Form::submit((isset($buttonText) ? $buttonText : 'Add Project'),['class' => 'btn btn-success btn-shadow', 'tabindex' =>
            '9'])  !!}
        </div>
    </div>
</div>
