<div class="form-body">
    <div class="form-group">
        {!!  Form::label('company_id','Company Name',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::select('company_id', $companies, isset($user->company_id) ?
            $user->client_id : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '1'] )  !!}
        </div>
    </div>
    <div class="form-group">
        {!!   Form::label('role_id','User role',['class' => 'col-md-3 control-label'])!!}
        <div class="col-md-9">
            {!!  Form::select('role_id', $roles, isset($user->role_id) ? $user->role_id :
            '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '2'] ) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('name','Name',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','name',isset($user->name) ? $user->name : '',['class' => 'form-control',
            'placeholder' => 'Name', 'tabindex' => '3']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!   Form::label('password','Password',['class' => 'col-md-3 control-label'])!!}
        <div class="col-md-9">
            {!!  Form::input('password','password','',['class' => 'form-control', 'placeholder' => 'Enter
            Password', 'tabindex' => '4']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!   Form::label('email','Email',['class' => 'col-md-3 control-label'])!!}
        <div class="col-md-9">
            {!!  Form::input('email','email',isset($user->email) ? $user->email : '',['class' => 'form-control',
            'placeholder' => 'Email', 'tabindex' => '5']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('phone','Phone',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','phone',isset($user->phone) ? $user->phone : '',['class' => 'form-control',
            'placeholder' => 'Contact Number', 'tabindex' => '6']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('photo','Photo',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('file','photo',isset($user->photo) ? $user->photo: '',['class' => 'form-control',
            'placeholder' => 'Photo', 'tabindex' => '7']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('address_1','Address 1',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('address_1',isset($user->address_1) ? $user->address_1 : '',['size' => '30x3', 'class' =>
            'form-control', 'placeholder' => 'Enter Address 1', 'tabindex' => '8']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('address_2','Address 2',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('address_2',isset($user->address_2) ? $user->address_2 : '',['size' => '30x3', 'class' =>
            'form-control', 'placeholder' => 'Enter Address 1', 'tabindex' => '9']) !!}
        </div>
    </div>
    
    <div class="form-group">
        {!!  Form::label('zipcode','Zipcode',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!! Form::input('text','zipcode',isset($user->zipcode) ? $user->zipcode: '',['class' => 'form-control',
            'placeholder' => 'Enter Zipcode', 'tabindex' => '10']) !!}
        </div>
    </div>
    
    <div class="form-group">
        {!!  Form::label('country_id','Country',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::select('country_id', $countries,( isset($user->country_id) ?
            $user->country_id : ''), ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '11'] )  !!}
        </div>
    </div>
    
    <div class="form-group">
        {!!  Form::label('skype','Skype',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','skype',isset($user->skype) ? $user->skype: '',['class' => 'form-control',
            'placeholder' => 'Enter Skype', 'tabindex' => '12']) !!}
        </div>
    </div>
    
    <div class="form-group">
        {!!  Form::label('facebook','Facebook',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','facebook',isset($user->facebook) ? $user->facebook: '',['class' => 'form-control',
            'placeholder' => 'Enter Facebook', 'tabindex' => '13']) !!}
        </div>
    </div>
    
    <div class="form-group">
        {!!  Form::label('linkedin','LinkedIn',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','linkedin',isset($user->linkedin) ? $user->linkedin: '',['class' => 'form-control',
            'placeholder' => 'Enter LinkedIn', 'tabindex' => '14']) !!}
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            {!!  Form::submit(isset($buttonText) ? $buttonText : 'Add User',['class' => 'btn green', 'tabindex' =>
            '15'])  !!}
        </div>
    </div>
</div>
