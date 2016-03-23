<div class="form-body">
    <div class="form-group">
        {!! Form::label('slug','Slug',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','slug',isset($category->slug) ? $category->slug : '',
            ['class' => 'form-control', 'placeholder' => 'Slug', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('name','Company Name',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','name',isset($category->name) ? $category->name : '',
           ['class' => 'form-control', 'placeholder' => 'Name', 'tabindex' => '1']) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            {!!  Form::submit((isset($buttonText) ? $buttonText : 'Add Links'),['class' => 'btn green', 'tabindex' =>
            '9'])  !!}
        </div>
    </div>
</div>
