	
	<div class="form-body">
		<div class="form-group">
			{{ Form::label('client_id','Company Name',['class' => 'col-md-3 control-label'])}}
			<div class="col-md-9">
			{{ Form::select('client_id', [null=>'Please Select'] + $clients, isset($user->client_id) ? $user->client_id : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '1'] ) }}
			</div>
		</div>
		<div class="form-group">
			{{ Form::label('role_id','User role',['class' => 'col-md-3 control-label'])}}
			<div class="col-md-9">
			{{ Form::select('role_id', [null => 'Please select'] + $roles, isset($user->role_id) ? $user->role_id : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '2'] ) }}
			</div>
		</div>
		<div class="form-group">
			{{ Form::label('name','Name',['class' => 'col-md-3 control-label'])}}
			<div class="col-md-9">
				{{ Form::input('text','name',isset($user->name) ? $user->name : '',['class' => 'form-control', 'placeholder' => 'Enter Name', 'tabindex' => '3'])}}
			</div>
		</div>
		@if (!isset($user->username))
		<div class="form-group">
			{{ Form::label('username','Username',['class' => 'col-md-3 control-label'])}}
			<div class="col-md-9">
				{{ Form::input('text','username',isset($user->username) ? $user->username : '',['class' => 'form-control', 'placeholder' => 'Enter Username', 'tabindex' => '4'])}}
			</div>
		</div>
		<div class="form-group">
			{{ Form::label('password','Password',['class' => 'col-md-3 control-label'])}}
			<div class="col-md-9">
				{{ Form::input('password','password','',['class' => 'form-control', 'placeholder' => 'Enter Password', 'tabindex' => '5'])}}
			</div>
		</div>
		@else
		<div class="form-group">
			<div class="col-md-3">
					<label>{{ Form::checkbox('user_status','Ban', ($user->user_status === 'Ban') ? true : false ,['class' => 'control-label', 'tabindex' => '6'])}} Ban??</label>
			</div>
			<div class="col-md-9">
				{{ Form::input('text','user_status_detail','',['class' => 'form-control', 'placeholder' => 'Enter Ban Reason', 'tabindex' => '7'])}}
			</div>
		</div>
		@endif
		<div class="form-group">
			{{ Form::label('email','Email',['class' => 'col-md-3 control-label'])}}
			<div class="col-md-9">
				{{ Form::input('email','email',isset($user->email) ? $user->email : '',['class' => 'form-control', 'placeholder' => 'Enter Email', 'tabindex' => '8'])}}
			</div>
		</div>
		<div class="form-group">
			{{ Form::label('phone','Phone',['class' => 'col-md-3 control-label'])}}
			<div class="col-md-9">
				{{ Form::input('text','phone',isset($user->phone) ? $user->phone : '',['class' => 'form-control', 'placeholder' => 'Enter Contact Number', 'tabindex' => '9'])}}
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-3 col-md-9">
				{{ Form::submit(isset($buttonText) ? $buttonText : 'Add User',['class' => 'btn green', 'tabindex' => '10']) }}
			</div>
		</div>
	</div>
