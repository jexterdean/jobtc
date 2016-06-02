{!! Form::open(array('files' => true, 'url' => 'quiz?p=test', 'method' => 'POST')) !!}
<div class="form-group">
    <div class="row">
        <label class="col-sm-3 text-right">Test Title</label>
        <div class="col-md-9">
            <input type="text" name="title" class="form-control" />
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label class="col-sm-3 text-right">Description</label>
        <div class="col-md-9">
            <textarea name="description" class="form-control"></textarea>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label class="col-sm-3 text-right">Test Introduction</label>
        <div class="col-md-9">
            <textarea name="start_message" class="form-control"></textarea>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label class="col-sm-3 text-right">Completion Message</label>
        <div class="col-md-9">
            <textarea name="completion_message" class="form-control"></textarea>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row hidden">
        <label class="col-sm-3 text-right">Test Photo</label>
        <div class="col-md-9">
            <input type="file" name="test_photo" class="form-control" />
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label class="col-sm-3 text-right">Default Question Time</label>
        <div class="col-md-9">
            <input type="text" name="default_time" class="time-form form-control" style="width: 100px;" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <input type="submit" name="submit" class="btn btn-submit btn-shadow" value="Save" />
        <input type="button" name="cancel" class="btn btn-delete btn-shadow" value="Cancel" data-dismiss="modal" />
    </div>
</div>
{!! Form::close() !!}

<script>
    $(function(e){
        $('.time-form').inputmask("59:59", {
            placeholder: '0',
            definitions: {
                '5': {
                    validator: "[0-5]",
                    cardinality: 1
                }
            }
        });
    });
</script>