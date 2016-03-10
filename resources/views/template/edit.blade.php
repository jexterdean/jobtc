@extends('layouts.default')

@section('content')


    <div class="col-md-12">
        <div class="box box-solid box-primary">
            <div class="box-header">
                <h3 class="box-title">Edit Email Templates</h3>
            </div>
            <div class="box-body">
                {{ Form::model($template,['method' => 'PATCH','route' => ['template.update',$template->template_id] ,'class' => 'form-horizontal client-form']) }}
                <div class="form-body">
                    <div class="form-group">
                        {{ Form::label('template_id','Select Template',['class' => 'col-md-2 control-label'])}}
                        <div class="col-md-10">
                            <select onchange="if (this.value) window.location.href=this.value" name="template_url"
                                    id="template_url" class="form-control input-xlarge select2me"
                                    placeholder="Select One" tabindex="1">
                                <option value="">Select One</option>
                                @foreach($templates as $template_list)
                                    <option value="{{ url('template/'.$template_list->template_id.'/edit') }}" {{ ($template->template_id == $template_list->template_id) ? 'selected' : '' }} >{{ $template_list->template_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('template_subject','Email Subject',['class' => 'col-md-2 control-label'])}}
                        <div class="col-md-10">
                            {{ Form::input('text','template_subject',isset($template->template_subject) ? $template->template_subject : '',['class' => 'form-control', 'placeholder' => 'Enter Email Subject', 'tabindex' => '2'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Template Content</label>
                        <div class="col-md-10">
                            <textarea name="template_content" class="textarea form-control" rows="6"
                                      tabindex="2">{{ $template->template_content }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            {{ Form::hidden('template_id',$template->template_id) }}
                            {{ Form::submit('Save',['class' => 'btn btn-primary pull-right', 'tabindex' => '3']) }}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
