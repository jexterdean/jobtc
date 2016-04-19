{!! Form::open(array('url' => 'teamBuilder?p=team')) !!}
<div class="form-group">
    <label>Team Name:</label>
    <input type="text" name="title" class="form-control" />
</div>
<div class="form-group">
    <label>Project:</label>
    <?php
    echo Form::select('project_id', $project, '', array('class' => 'form-control'));
    ?>
</div>
<div class="form-group pull-right">
    <button type="submit" name="submit" class="btn btn-success">Add</button>
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
<br style="clear: both;" />
{!! Form::close() !!}