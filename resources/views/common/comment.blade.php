<div class="col-md-6">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Comments</h3>
        </div>
        <div class="box-body">
            {!!  Form::open(['method' => 'POST','route' => ['comment.store'],'class' => 'comment-form'])  !!}
            {!!  Form::hidden('belongs_to',$belongs_to)  !!}
            {!!  Form::hidden('unique_id', $unique_id)  !!}
            <div class="form-group">
                {!!  Form::textarea('comment','',['size' => '30x3', 'class' => 'form-control', 'placeholder' => 'Enter
                Comment', 'tabindex' => '11']) !!}
            </div>
            <div class="form-group">
                {!!  Form::submit('Add',['class' => 'btn btn-primary', 'tabindex' => '12'])  !!}
            </div>
            {!!  Form::close()  !!}


            @if(count($comments))
                <br/>
                <div class="box box-success">
                    <div class="box-body chat" id="chat-box">
                        @foreach($comments as $comment)
                            <div class="item">
                                <img src="{{ \App\Helpers\Helper::getAvatar($comment->username) }}" alt="user image" class="online"/>
                                <p class="message">
                                    <a href="#" class="name">
                                        <small class="text-muted pull-right"><i
                                                    class="fa fa-clock-o"></i> {{ date("d M Y",strtotime($comment->created_at)) }}
                                        </small>
                                        {{ $comment->username }} writes
                                    </a>
                                    {{ $comment->comment}}

                                    @if(Auth::user()->is('admin') || Auth::user()->username == $comment->username)
                                        {!!  Form::open(array('route' => array('comment.destroy', $comment->comment_id)
                                        , 'method' => 'delete'))  !!}
                                        <button type="submit" class="btn btn-danger btn-xs"><i class="icon-trash"></i>
                                            Delete
                                        </button>
                                        {!!   Form::close() !!}
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
