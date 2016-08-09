<div class="form-body">
    <div class="form-group">
        <div class="col-md-12">
            {!!  Form::input('text','title','',
            ['class' => 'form-control', 'placeholder' => 'Title', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            {!!  Form::input('text','url','',
            ['class' => 'form-control', 'placeholder' => 'Url', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            {!!  Form::select('category_id', $categories, '', ['class' => 'form-control input-xlarge select2me category', 'placeholder' => 'Select Category', 'tabindex' =>'2'] )  !!}
        </div>
        <div class="col-sm-6">
            {!!  Form::input('text','new_category','',
                        ['class' => 'form-control category-name', 'placeholder' => 'Add New Category', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            {!!   Form::textarea('descriptions', '',['class' =>
            'form-control', 'placeholder' => 'Descriptions', 'tabindex' => '3'])!!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            {!!  Form::input('text','tags', '', ['class' => 'form-control form-control-inline ',
            'placeholder' => 'Tags', 'tabindex' => '4',
            ])  !!}
        </div>
    </div>
    <div class="row">
        <div class="pull-right">
            {!!  Form::submit('Add Link',['class' => 'btn btn-submit btn-shadow add-link-btn', 'tabindex' =>
            '9'])  !!}
        </div>
    </div>
</div>
