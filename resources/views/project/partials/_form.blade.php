<div class="form-body">
    <div class="form-group">
        <div class="col-md-12">
            {!!  Form::select('project_type', [
                'Standard' => 'Standard',
                'Hiring Assessment' => 'Hiring Assessment',
                'Software Development' => 'Software Development',
                'Coding' => 'Coding'
            ], isset($project->project_type) ? $project->project_type : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select Project Type', 'tabindex' => '7'] )  !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            {!!  Form::input('text','project_title',isset($project->project_title) ? $project->project_title : '',
            ['class' => 'form-control', 'placeholder' => 'Project Title', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            {!!  Form::textarea('project_description',isset($project->project_description) ?
            $project->project_description : '',['rows' => '3','class' => 'form-control', 'placeholder' => 'Project Description', 'tabindex' => '6']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <?php
            //change code because causes error on other pages
            $clients = App\Models\Company::orderBy('name', 'asc')->lists('name', 'id');
            ?>
            {!! Form::select('company_id', $clients, isset($project->company_id) ?
            $project->client_id : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select Company', 'tabindex' =>'2'] )  !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            {!!  Form::input('text','start_date',isset($project->start_date) ? date("d-m-Y",strtotime
            ($project->start_date)) : '', ['class' => 'form-control form-control-inline input-medium date-picker', 'placeholder' => 'Start', 'tabindex' => '4', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true'])  !!}
        </div>
        <div class="col-md-6">
            {!!  Form::input('text','deadline',isset($project->deadline) ? date("d-m-Y",strtotime($project->deadline))
            : '', ['class' => 'form-control form-control-inline input-medium date-picker', 'placeholder' => 'Deadline', 'tabindex' => '5', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true'])  !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-2">
            {!! Form::input('text','rate_value',isset($project->rate_value) ? $project->rate_value : '',['class' =>
            'form-control', 'placeholder' => 'Rate', 'tabindex' => '8']) !!}
        </div>
        <div class="col-md-3">
            {!!  Form::select('currency', [
                'USD' => 'USD',
                'EUR' => 'EUR',
                'GBP' => 'GBP',
                'PHP' => 'PHP'
            ], isset($project->currency) ? $project->currency : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select Currency', 'tabindex' => '7'] )  !!}
        </div>
        <div class="col-md-3">
            {!!  Form::select('rate_type', [
                'fixed' => 'Fixed',
                'hourly' => 'Hourly'
            ], isset($project->rate_type) ? $project->rate_type : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select Rate', 'tabindex' => '7'] )  !!}
        </div>
        <div class="col-md-4">
            {!!  Form::input('text','account',isset($project->account) ? $project->account : '', ['class' => 'form-control form-control-inline input-medium', 'placeholder' => 'Account', 'tabindex' => '4'])  !!}
        </div>
    </div>
    <div class="row">
        <div class="pull-right">
            {!!  Form::submit((isset($buttonText) ? $buttonText : 'Add Project'),['class' => 'btn btn-edit btn-shadow', 'tabindex' =>
            '9'])  !!}
        </div>
    </div>
</div>
