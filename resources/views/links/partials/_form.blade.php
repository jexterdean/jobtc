<div class="form-body">
    <div class="form-group">
        {!! Form::label('title','Title',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','title',isset($link->title) ? $link->title : '',
            ['class' => 'form-control', 'placeholder' => 'Title', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('url','Url',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','url',isset($link->url) ? $link->url : '',
            ['class' => 'form-control', 'placeholder' => 'Url', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('category_id','Category',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::select('category_id', $categories, isset($link->category_id) ?
            $link->category_id : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select Category', 'tabindex' =>'2'] )  !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('descriptions','Descriptions',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!   Form::textarea('descriptions',isset($link->descriptions) ? $link->descriptions : '',['class' =>
            'form-control', 'placeholder' => 'Descriptions', 'tabindex' => '3'])!!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('tags','Tags',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            {!!  Form::input('text','tags',isset($link->tags) ? $link->tags: '', ['class' => 'form-control form-control-inline ',
            'placeholder' => 'Tags', 'tabindex' => '4',
            ])  !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('comments','Comments',['class' => 'col-md-3 control-label'])  !!}
        <div class="col-md-9">
            {!!  Form::textarea('comments',isset($link->comments)?$link->comments: '',
            ['class' => 'form-control ', 'placeholder' => 'Comments',
             'tabindex' => '5'])  !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            {!!  Form::submit((isset($buttonText) ? $buttonText : 'Add Links'),['class' => 'btn green', 'tabindex' =>
            '9'])  !!}
        </div>
    </div>
</div>
