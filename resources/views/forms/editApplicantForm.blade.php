<form actions="" method="post" class="edit-applicant-form form-horizontal" role="form" novalidate="novalidate">
    <div class="box-body">
        <div class="box-content">
            <div class="form-group">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            @if($applicant->photo === '' || $applicant->photo === NULL)
                            <img class="edit-applicant-photo" src="{{url('assets/user/default-avatar.jpg')}}" />
                            @else
                            <img class="edit-applicant-photo" src="{{url($applicant->photo)}}"/>
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
                {!!  Form::input('text','name',$applicant->name,['class' => 'form-control', 'placeholder' =>
                'Name']) !!}
            </div>
            <div class="form-group">
                {!!  Form::input('email','email',$applicant->email,['class' => 'form-control', 'placeholder'
                => 'Email']) !!}
            </div>
            <div class="form-group">
                {!!  Form::input('text','phone',$applicant->phone,['class' => 'form-control', 'placeholder'
                => 'Phone']) !!}
            </div>
            <div class="form-group">
                {!!  Form::input('text','skype',$applicant->skype,['class' => 'form-control', 'placeholder'
                => 'Skype']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('Resume') !!}
                {!!  Form::input('file','resume','') !!}    
            </div>
        </div>
    </div>
</form>