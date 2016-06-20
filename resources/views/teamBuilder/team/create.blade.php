{!! Form::open(array('url' => 'teamBuilder?p=team')) !!}
<div class="form-group">
    <label>Team Name:</label>
    <input type="text" name="title" class="form-control" />
</div>
<div class="form-group pull-right">
    <button type="submit" name="submit" class="btn btn-submit">Add</button>
    <button type="button" class="btn btn-delete" data-dismiss="modal">Close</button>
</div>
<br style="clear: both;" />
{!! Form::close() !!}