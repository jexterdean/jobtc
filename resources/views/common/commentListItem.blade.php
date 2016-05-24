<div id="comment-item-{{$comment->comment_id}}" class="comment-item">
    <div class="media">
        <div class="media-left">
            <a href="#">
                @if($comment->user->photo !== '' && Auth::user()->user_id === $comment->user->user_id)
                <img class="comment-photo" src="{{url($comment->user->photo)}}" alt="Employee Photo">
                @elseif ($comment->user->photo === '' && Auth::user()->user_id === $comment->user->user_id)
                <img class="comment-photo" src="{{url('assets/user/avatar.png')}}" alt="Employee Photo">
                @elseif ($comment->applicant->photo !== '')
                <img class="comment-photo" src="{{url($comment->applicant->photo)}}" alt="Employee Photo">
                @else
                <img class="comment-photo" src="{{url('assets/user/avatar.png')}}" alt="Employee Photo">
                @endif
            </a>
            @if(isset($comment->user->name))
            <text class="media-heading">{{$comment->user->name}}</text>
            @else
            <text class="media-heading">{{$comment->applicant->name}}</text>
            @endif
        </div>
        <div class="media-body media-right">
            <p class="comment">{!!nl2br(e($comment->comment))!!}</p>
        </div>
    </div>
    <table class="comment-utilities pull-right">
        <tr>
            <td><a href="#" class="edit-comment"><i class="fa fa-pencil"></i></a></td>
            <td>&nbsp;</td>
            <td><a href="#" class="delete-comment"><i class="fa fa-times"></i></a></td>
        </tr>
    </table>
    <input class="comment_id" type="hidden" value="{{$comment->comment_id}}">
    <input class="applicant_id" type="hidden" value="{{$comment->applicant->id}}">
</div>
<!--div class="mini-space"></div-->

