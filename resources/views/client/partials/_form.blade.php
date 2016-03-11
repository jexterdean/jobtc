<div class="form-body">
    <div class="form-group">
        {!!  Form::label('company_name','Company Name',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','company_name',isset($client->company_name) ? $client->company_name : '',['class'
            => 'form-control', 'placeholder' => 'Enter Company Name', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('contact_person','Contact Person',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','contact_person',isset($client->contact_person) ? $client->contact_person : '',
            ['class' => 'form-control', 'placeholder' => 'Enter Contact Person Name', 'tabindex' => '2']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('email','Email',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('email','email',isset($client->email) ? $client->email : '',['class' => 'form-control',
            'placeholder' => 'Enter Contact Email', 'tabindex' => '3']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('phone','Phone',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!! Form::input('text','phone',isset($client->phone) ? $client->phone : '',['class' => 'form-control',
            'placeholder' => 'Enter Contact Number', 'tabindex' => '4']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('address','Address',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('address',isset($client->address) ? $client->address : '',['size' => '30x3', 'class' =>
            'form-control', 'placeholder' => 'Enter Contact Address', 'tabindex' => '5']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('zipcode','Zip Code',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','zipcode',isset($client->zipcode) ? $client->zipcode : '',['class' =>
            'form-control', 'placeholder' => 'Enter Zip Code', 'tabindex' => '6']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('city','City',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','city',isset($client->city) ? $client->city : '',['class' => 'form-control',
            'placeholder' => 'Enter City', 'tabindex' => '7']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('state','State',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!! Form::input('text','state',isset($client->state) ? $client->state : '',['class' => 'form-control',
            'placeholder' => 'Enter State', 'tabindex' => '8']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('country_id','Country',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::select('country_id', [null=>'Please Select'] + $countries, isset($client->country_id) ?
            $client->country_id : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '9'] )  !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            {!!  Form::submit(isset($buttonText) ? $buttonText : 'Add Client',['class' => 'btn btn-primary', 'tabindex'
            => '10'])  !!}
        </div>
    </div>
</div>
