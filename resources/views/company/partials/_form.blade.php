<div class="form-body">
    <div class="form-group">
        {!!  Form::label('company_name','Company Name',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','name',isset($companies->name) ? $companies->name : '',['class'
            => 'form-control', 'placeholder' => 'Enter Company Name', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('email','Email',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('email','email',isset($companies->email) ? $companies->email : '',['class' => 'form-control',
            'placeholder' => 'Enter Contact Email', 'tabindex' => '3']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('phone','Phone',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!! Form::input('text','phone',isset($companies->phone) ? $companies->phone : '',['class' => 'form-control',
            'placeholder' => 'Enter Contact Number', 'tabindex' => '4']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('address','Address',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('address',isset($companies->address) ? $companies->address : '',['size' => '30x3', 'class' =>
            'form-control', 'placeholder' => 'Enter Contact Address', 'tabindex' => '5']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('country_id','Country',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::select('country', $countries,( isset($companies->country) ?
            $companies->country : ''), ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '9'] )  !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            {!!  Form::submit(isset($buttonText) ? $buttonText : 'Add Company',['class' => 'btn btn-edit', 'tabindex'
            => '10'])  !!}
        </div>
    </div>
</div>
