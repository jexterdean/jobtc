@foreach($authority_levels as $level)
<label>{{$level->name}}</label>
<ul class="org-chart list-group">
    @foreach($company_users as $profile)
    @if($profile->role_id === $level->id)
    <li class="list-group-item">
        {{$profile->user->name}}
    </li>
    @endif
    @endforeach
</ul>
@endforeach