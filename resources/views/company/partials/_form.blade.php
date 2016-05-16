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
            'placeholder' => 'Enter Contact Email', 'tabindex' => '2']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('phone','Phone',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!! Form::input('text','phone',isset($companies->phone) ? $companies->phone : '',['class' => 'form-control',
            'placeholder' => 'Enter Contact Number', 'tabindex' => '3']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('number_of_employees','No. Of Employees',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            <select class="form-control input-xlarge select2me" name="number_of_employees" placeholder="Select One" tabindex="4">
                <option>1 employee</option>
                <option>more than 5</option>
                <option>more than 10</option>
                <option>more than 20</option>
                <option>more than 50</option>
                <option>more than 100</option>
                <option>more than 200</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('address_1','Address 1',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('address_1',isset($companies->address_1) ? $companies->address_1 : '',['size' => '30x3', 'class' =>
            'form-control', 'placeholder' => 'Enter Address 1', 'tabindex' => '5']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('address_2','Address 2',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('address_2',isset($companies->address_2) ? $companies->address_2 : '',['size' => '30x3', 'class' =>
            'form-control', 'placeholder' => 'Enter Address 2', 'tabindex' => '6']) !!}
        </div>
    </div>
    
    <div class="form-group">
        {!!  Form::label('province','Province',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!! Form::input('text','province',isset($companies->province) ? $companies->province: '',['class' => 'form-control',
            'placeholder' => 'Enter Province', 'tabindex' => '7']) !!}
        </div>
    </div>
    
    <div class="form-group">
        {!!  Form::label('zipcode','Zipcode',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!! Form::input('text','zipcode',isset($companies->zipcode) ? $companies->zipcode: '',['class' => 'form-control',
            'placeholder' => 'Enter Zipcode', 'tabindex' => '8']) !!}
        </div>
    </div>
    
    <div class="form-group">
        {!!  Form::label('website','Website',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!! Form::input('text','website',isset($companies->website) ? $companies->website: '',['class' => 'form-control',
            'placeholder' => 'Enter Website', 'tabindex' => '9']) !!}
        </div>
    </div>
    
    <div class="form-group">
        {!!  Form::label('country_id','Country',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::select('country_id', $countries,( isset($companies->country_id) ?
            $companies->country_id : ''), ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '10'] )  !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            {!!  Form::submit(isset($buttonText) ? $buttonText : 'Add Company',['class' => 'btn btn-edit', 'tabindex'
            => '11'])  !!}
        </div>
    </div>
</div>
