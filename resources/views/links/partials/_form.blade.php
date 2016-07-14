<div class="form-body">
    <div class="form-group">
        <div class="col-md-12">
            {!!  Form::input('text','title',isset($link->title) ? $link->title : '',
            ['class' => 'form-control', 'placeholder' => 'Title', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            {!!  Form::input('text','url',isset($link->url) ? $link->url : '',
            ['class' => 'form-control', 'placeholder' => 'Url', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            {!!  Form::select('category_id', $categories, isset($link->category_id) ?
            $link->category_id : '', ['class' => 'form-control input-xlarge select2me category', 'placeholder' => 'Select Category', 'tabindex' =>'2'] )  !!}
        </div>
        <div class="col-sm-6">
            {!!  Form::input('text','new_category','',
                        ['class' => 'form-control category-name', 'placeholder' => 'Add New Category', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            {!!   Form::textarea('descriptions',isset($link->descriptions) ? $link->descriptions : '',['class' =>
            'form-control', 'placeholder' => 'Descriptions', 'tabindex' => '3'])!!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            {!!  Form::input('text','tags',isset($link->tags) ? $link->tags: '', ['class' => 'form-control form-control-inline ',
            'placeholder' => 'Tags', 'tabindex' => '4',
            ])  !!}
        </div>
    </div>
    <div class="row">
        <div class="pull-right">
            {!!  Form::submit((isset($buttonText) ? $buttonText : 'Add Link'),['class' => 'btn btn-submit btn-shadow', 'tabindex' =>
            '9'])  !!}
        </div>
    </div>
</div>
