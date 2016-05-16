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
            Password', 'tabindex' => '5']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!   Form::label('email','Email',['class' => 'col-md-3 control-label'])!!}
        <div class="col-md-9">
            {!!  Form::input('email','email',isset($user->email) ? $user->email : '',['class' => 'form-control',
            'placeholder' => 'Email', 'tabindex' => '8']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('phone','Phone',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','phone',isset($user->phone) ? $user->phone : '',['class' => 'form-control',
            'placeholder' => 'Contact Number', 'tabindex' => '9']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('photo','Photo',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('file','photo',isset($user->photo) ? $user->photo: '',['class' => 'form-control',
            'placeholder' => 'Photo', 'tabindex' => '9']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            {!!  Form::submit(isset($buttonText) ? $buttonText : 'Add User',['class' => 'btn green', 'tabindex' =>
            '10'])  !!}
        </div>
    </div>
</div>
