<form id="add-employee-form" class="add-employee-form">
    <div class="row">
        <div class="col-md-12">
            <input class="form-control" name="employee-name" placeholder="Name" value="" />
            <br />
            <input class="form-control" name="employee-email" placeholder="Email" value="" />
            <br />
            <input type="password" class="form-control" name="employee-password" placeholder="Password" value="" />
            <br />
            <div class="center-block">
                <label class="radio-inline"><input id="existing-position-tab" checked="checked" name="position-tab" type="radio" value="" data-target="#existing-position">Existing Position</label>
                <label class="radio-inline"><input id="new-position-tab" name="position-tab" type="radio" value="" data-target="#new-position">New Position</label>
            </div>
            <br />
            <div class="tab-content">
                <div id="existing-position" class="tab-pane active">
                    <select name="role_id" class='form-control input-xlarge select2me' placeholder="Select Position">
                        @foreach($positions as $position)
                        <option value="{{$position->id}}">{{$position->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div id="new-position" class="tab-pane">
                    <input class="form-control" name="position" placeholder="New Position" value="" />
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $('input[name="position-tab"]').click(function () {
        $(this).tab('show');
    });
</script>
