<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#existing_tab" role="tab" data-toggle="tab">Existing</a></li>
        <li role="presentation"><a href="#duplicate_tab" role="tab" data-toggle="tab">Duplicate</a></li>
        <li role="presentation"><a href="#create_tab" role="tab" data-toggle="tab">Create</a></li>
    </ul>
    <br />
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="existing_tab">
            <div id="userSearch"></div>
        </div>
        <div role="tabpanel" class="tab-pane" id="duplicate_tab">

        </div>
        <div role="tabpanel" class="tab-pane" id="create_tab">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" />
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Role:</label>
                        <?php
                        echo Form::select('role_id', $role, '', array('class' => 'form-control'));
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="text" name="password" max="4" class="form-control" value="{{ str_random(4) }}" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Company:</label>
                        <?php
                        echo Form::select('company_id', array(), '', array('class' => 'form-control'));
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Account:</label>
                        <?php
                        echo Form::select('account_id', array(), '', array('class' => 'form-control'));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(e){
        $('#userSearch').magicSuggest({
            allowFreeEntries: false,
            method: 'get',
            data: '{{ URL::to('teamBuilderUserJson') }}',
            renderer: function(data){
                console.log(data);
                return '<div style="padding: 5px; overflow:hidden;">' +
                    '<div style="float: left;">{!! HTML::image("assets/user/avatar.png") !!}</div>' +
                    '<div style="float: left; margin-left: 5px">' +
                        '<div style="font-weight: bold; color: #333; font-size: 10px; line-height: 11px">' + data.name + '</div>' +
                        '<div style="color: #999; font-size: 9px">' + data.email + '</div>' +
                    '</div>' +
                '</div><div style="clear:both;"></div>'; // make sure we have closed our dom stuff
            }
        });
    });
</script>