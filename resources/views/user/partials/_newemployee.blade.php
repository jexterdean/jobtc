<div class="col-md-6 employee-column">
    <div class="row">
        <div class="col-md-9">
            <a class="profile-toggle" data-toggle="collapse" href="#profile-collapse-{{$profile->user->user_id}}">
                <i class="pull-left" aria-hidden="true">
                    @if($profile->user->photo === '' || $profile->user->photo === NULL)
                    <img class="employee-photo" src="{{url('assets/user/default-avatar.jpg')}}" />
                    @else
                    <img class="employee-photo" src="{{url($profile->user->photo)}}"/>
                    @endif
                </i>
                <div class="employee-details">
                    <div class="name">{{$profile->user->name}}</div>
                    <div class="email">{{$profile->user->email}}</div>
                    <div class="phone">{{$profile->user->phone}}</div>
                    <input class="company_id" type="hidden" value="{{$company_id}}"/>
                </div>
            </a>
        </div>
    </div>
</div>