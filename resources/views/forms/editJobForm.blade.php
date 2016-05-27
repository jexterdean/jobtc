<form actions="" method="POST" class="edit-job-form form-horizontal" role="form" novalidate="novalidate">
    {!! csrf_field() !!}
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="title">Title</label>
            <div class="col-md-10">
                <input class="form-control title" name="title" type="text" value="{{$job->title}}" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="description">Description</label>
            <div class="col-md-10">
                <textarea id="description" class="form-control description" name="description">{{htmlspecialchars_decode($job->description)}}</textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="photo">Photo</label>
            <div class="col-md-10">
                @if(isset($job->photo))
                <img class="profile-pic" src="{{url($job->photo)}}"/>
                @endif
                <input class="form-control" name="photo" type="file" value="" />
            </div>
        </div>
    </div>
</form>
@section('js_footer')
@parent
<script>
$.fn.modal.Constructor.prototype.enforceFocus = function() {
    $( document )
        .off( 'focusin.bs.modal' ) // guard against infinite focus loop
        .on( 'focusin.bs.modal', $.proxy( function( e ) {
            if (
                this.$element[ 0 ] !== e.target && !this.$element.has( e.target ).length
                // CKEditor compatibility fix start.
                && !$( e.target ).closest( '.cke_dialog, .cke' ).length
                // CKEditor compatibility fix end.
            ) {
                this.$element.trigger( 'focus' );
            }
        }, this ) );
};

CKEDITOR.replace('description');
</script>    
@stop