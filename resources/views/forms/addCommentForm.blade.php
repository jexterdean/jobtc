<form class="add-comment-form form-horizontal">
   {!! csrf_field() !!}
    <div class="media">
        <div class="media-left">
            <a href="#">
                @if($user_info->photo !== '')
                <img class="employee-photo" src="{{url($user_info->photo)}}" alt="Employee Photo">
                @else
                <img class="employee-photo" src="{{url('assets/user/avatar.png')}}" alt="Employee Photo">
                @endif
            </a>
            <text class="media-heading">{{$user_info->name}}</text>
        </div>
        <div class="media-body media-right">
            <textarea placeholder="Add a comment for this applicant" class="form-control comment-textarea" name="comment"></textarea>
        </div>
        <button class="btn btn-primary pull-right submit-comment">Submit</button>
    </div>
    <div>
        <label>Email Comment</label>    
        <input class="email-comment" type="checkbox" value="email"/>
    </div>
    <input name="applicant_id" type="hidden" value="{{$applicant->id}}"/>
    <input name="job_id" type="hidden" value="{{$applicant->job_id}}"/>
    <input name="profile_id" type="hidden" value="{{$user_info->profile->id or '0'}}"/>
</form>
