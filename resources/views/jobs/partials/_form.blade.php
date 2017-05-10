<div class="form-body">
    <div class="form-group">
        <div class="col-md-12">
            <input class="form-control title" name="title" placeholder="Title" type="text" value="" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            {{--*/ $user_id = Illuminate\Support\Facades\Auth::user()->user_id /*--}}
            {{--*/ $companies = App\Models\Profile::with('company')->where('user_id', $user_id)->get() /*--}}
            {{--*/ $clients = [] /*--}}

            @if(count($companies) > 0)
                @foreach($companies as $company)
                    {{--*/ $clients = [ $company->company->id =>  $company->company->name] /*--}}
                @endforeach
            @endif
            {!! Form::select('company_id', $clients, isset($project->company_id) ?
            $project->client_id : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select Company', 'tabindex' =>'2'] )  !!}
        </div>
    </div>
    <div class="form-group">
        <div class="fileUpload btn btn-edit btn-shadow btn-sm">
            <span>Upload Logo</span>
            <input class="upload" name="photo" type="file" value="" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <textarea id="description" class="form-control description" name="description"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="pull-right">
            {!!  Form::submit((isset($buttonText) ? $buttonText : 'Add Job'),['class' => 'btn btn-edit btn-shadow', 'tabindex' =>
            '9'])  !!}
        </div>
    </div>
</div>
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