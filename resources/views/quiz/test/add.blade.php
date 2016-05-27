{!! Form::open(array('files' => true, 'url' => 'quiz?p=test', 'method' => 'POST')) !!}
<div class="form-group">
    <div class="row">
        <label class="col-sm-3">Test Title</label>
        <div class="col-md-9">
            <input type="text" name="title" class="form-control" />
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label class="col-sm-3">Description</label>
        <div class="col-md-9">
            <textarea name="description" class="form-control"></textarea>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label class="col-sm-3">Test Introduction</label>
        <div class="col-md-9">
            <textarea name="start_message" class="form-control"></textarea>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label class="col-sm-3">Completion Message</label>
        <div class="col-md-9">
            <textarea name="completion_message" class="form-control"></textarea>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row hidden">
        <label class="col-sm-3">Test Photo</label>
        <div class="col-md-9">
            <input type="file" name="test_photo" class="form-control" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <input type="submit" name="submit" class="btn btn-submit" value="Save" />
        <input type="button" name="cancel" class="btn btn-delete" value="Cancel" data-dismiss="modal" />
    </div>
</div>
{!! Form::close() !!}