<div class="form-body">
    <div class="form-group">
        {{ Form::label('project_title','Project Title',['class' => 'col-md-3 control-label'])}}
        <div class="col-md-9">
            {{ Form::input('text','project_title',isset($project->project_title) ? $project->project_title : '',['class' => 'form-control', 'placeholder' => 'Enter Project Title', 'tabindex' => '1'])}}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('client_id','Company Name',['class' => 'col-md-3 control-label'])}}
        <div class="col-md-9">
            {{ Form::select('client_id', [null=>'Please Select'] + $clients, isset($project->client_id) ? $project->client_id : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' =>'2'] ) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('ref_no','Reference No',['class' => 'col-md-3 control-label'])}}
        <div class="col-md-9">
            {{ Form::input('text','ref_no',isset($project->ref_no) ? $project->ref_no : '',['class' => 'form-control', 'placeholder' => 'Enter Reference No', 'tabindex' => '3'])}}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('start_date','Start Date',['class' => 'col-md-3 control-label'])}}
        <div class="col-md-3">
            {{ Form::input('text','start_date',isset($project->start_date) ? date("d-m-Y",strtotime($project->start_date)) : '', ['class' => 'form-control form-control-inline input-medium date-picker', 'placeholder' => 'Enter Start Date', 'tabindex' => '4', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true']) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('deadline','Deadline Date',['class' => 'col-md-3 control-label'])}}
        <div class="col-md-3">
            {{ Form::input('text','deadline',isset($project->deadline) ? date("d-m-Y",strtotime($project->deadline)) : '', ['class' => 'form-control form-control-inline input-medium date-picker', 'placeholder' => 'Enter Due Date', 'tabindex' => '5', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true']) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('project_description','Description',['class' => 'col-md-3 control-label'])}}
        <div class="col-md-9">
            {{ Form::textarea('project_description',isset($project->project_description) ? $project->project_description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => 'Enter Project Description', 'tabindex' => '6'])}}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('rate_type','Rate Type',['class' => 'col-md-3 control-label'])}}
        <div class="col-md-9">
            {{ Form::select('rate_type', [
                null => 'Please select',
                'fixed' => 'Fixed',
                'hourly' => 'Hourly'
            ], isset($project->rate_type) ? $project->rate_type : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '7'] ) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('rate_value','Rate Value',['class' => 'col-md-3 control-label'])}}
        <div class="col-md-9">
            {{ Form::input('text','rate_value',isset($project->rate_value) ? $project->rate_value : '',['class' => 'form-control', 'placeholder' => 'Enter Rate', 'tabindex' => '8'])}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            {{ Form::submit(isset($buttonText) ? $buttonText : 'Add Project',['class' => 'btn green', 'tabindex' => '9']) }}
        </div>
    </div>
</div>
