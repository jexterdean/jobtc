<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Edit link</h4>
</div>
<div class="modal-body">
    @role('admin')
        {!! Form::model($link,['method' => 'PATCH','route' => ['links.update',$link->id] ,'class' =>
        'form-horizontal link-form'])  !!}
        @include('links/partials/_form', ['buttonText' => 'Update Links'] )
        {!!  Form::close()  !!}
    @else
        <div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong>You dont have to perform this action!!</strong>
        </div>
    @endrole
</div>
<script>
    $(function () {
//        Validate.init();
    });
</script>