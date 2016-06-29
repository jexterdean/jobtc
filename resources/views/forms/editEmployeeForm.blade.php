<form actions="" method="post" class="edit-employee-form form-horizontal" role="form" novalidate="novalidate">
    <div class="box-body">
        <div class="box-content">
            <div class="form-group">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            @if($profile->user->photo === '' || $profile->user->photo === NULL)
                            <img class="edit-employee-photo" src="{{url('assets/user/default-avatar.jpg')}}" />
                            @else
                            <img class="edit-employee-photo" src="{{url($profile->user->photo)}}"/>
                            @endif
                        </a>
                    </div>
                    <div class="media-body">
                        <br />
                        <br />
                        <br />
                        <br />
                        {!!  Form::input('file','photo','') !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!!  Form::input('text','name',$profile->user->name,['class' => 'form-control', 'placeholder' =>
                'Name']) !!}
            </div>
            <div class="form-group">
                {!!  Form::input('email','email',$profile->user->email,['class' => 'form-control', 'placeholder'
                => 'Email']) !!}
            </div>
            <div class="form-group">
                {!!  Form::input('text','phone',$profile->user->phone,['class' => 'form-control', 'placeholder'
                => 'Phone']) !!}
            </div>

            <div class="form-group">
                {!!  Form::input('text','address_1',$profile->user->address_1,['class' => 'form-control', 'placeholder'
                => 'Address 1']) !!}
            </div>
            <div class="form-group">
                {!!  Form::input('text','address_2',$profile->user->address_2,['class' => 'form-control', 'placeholder'
                => 'Address 2']) !!}
            </div>
            <div class="form-group">
                {!!  Form::input('text','zipcode',$profile->user->zipcode,['class' => 'form-control', 'placeholder'
                => 'Enter Phone']) !!}
            </div>
            <div class="form-group">
                <select name="country_id" class='form-control input-xlarge select2me' placeholder="Select Country">
                    @foreach($countries as $country)
                    @if($country->country_id == $profile->user->country_id)
                    <option selected="selected" value='{{$country->country_id}}'>{{$country->country_name}}</option>
                    @else
                    <option value='{{$country->country_id}}'>{{$country->country_name}}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                {!!  Form::input('text','skype',$profile->user->skype,['class' => 'form-control', 'placeholder'
                => 'Skype']) !!}
            </div>
            <div class="form-group">
                {!!  Form::input('text','facebook',$profile->user->facebook,['class' => 'form-control', 'placeholder'
                => 'Facebook']) !!}
            </div>
            <div class="form-group">
                {!!  Form::input('text','linkedin',$profile->user->linkedin,['class' => 'form-control', 'placeholder'
                => 'Linkedin']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('Resume') !!}
            {!!  Form::input('file','resume','') !!}    
            </div>
        </div>
    </div>
</form>