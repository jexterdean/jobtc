<div class="form-body">
    <div class="form-group">
        {!!  Form::label('event_title','Event Title',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','event_title',isset($event->event_title) ? $event->event_title : '',['class' =>
            'form-control', 'placeholder' => 'Enter Event Title', 'tabindex' => '1']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('event_description','Event Description',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('event_description',isset($event->event_description) ? \App\Helpers\Helper::br2nl
            ($event->event_description) : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => 'Enter Event Description', 'tabindex' => '2']) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('start_date','Start Date',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            {!!  Form::input('text','start_date',isset($event->start_date) ? date("d-m-Y",strtotime($event->start_date)
            ) : '', ['class' => 'form-control form-control-inline input-medium date-picker datepicker',
             ]) !!}
        </div>
    </div>
    <div class="form-group">
        {!!  Form::label('end_date','End Date',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            {!!  Form::input('text','end_date',isset($event->end_date) ? date("d-m-Y",strtotime($event->end_date)) :
            '', ['class' => 'form-control form-control-inline input-medium date-picker', 'placeholder' => 'Enter Start Date', 'tabindex' => '4', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true'])  !!}
        </div>
    </div>
    @role('admin')
        <div class="form-group">
            <label class='col-md-3 control-label'>Make Public
            </label>
            <div class="col-md-9">
                {!!  Form::checkbox('public', '1', false, ['class' => 'minimal', 'id' => 'minimal', 'tabindex' => '5']) !!}
            </div>
        </div>
    @endrole
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            {!!  Form::submit(isset($buttonText) ? $buttonText : 'Save',['class' => 'btn btn-edit', 'tabindex' =>
            '6']) !!}
        </div>
    </div>
</div>