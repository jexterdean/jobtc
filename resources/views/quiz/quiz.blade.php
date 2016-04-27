<div class="row">
    <div class="col-md-4">
        <div class="box box-solid box-success">
            <div class="box-header">
                <h3 class="box-title">Test Library</h3>
                <div class="box-tools pull-right">
                    <a href="{{ url('quiz') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="box-body">
                @foreach($test as $v)
                <div class="media">
                    <div class="media-left">
                        {!! HTML::image('/storage/img/test/' . $v->test_photo, '', array('style' => 'width: 64px;max-width: 64px!important;')) !!}
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">{{ $v->title }}</h4>
                        <strong>Number of Question:</strong> {{ $v->num_question }}<br />

                        <a href="{{ url('quiz/' . $v->id) }}" class="btn btn-default">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ url('quiz/' . $v->id . '/edit') }}" class="btn btn-default">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <button class="btn btn-danger test-delete-btn" id="{{ $v->id }}">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-8">
        @include('quiz.' . $page)
    </div>
</div>

<div class="modal fade deleteModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete Test</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger yes-btn">Delete</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@section('js_footer')
@parent
<script>
    $(function(e){
        //region Question
        var boxCounter = 1;
        var qBox = $('.question-default');
        var qBoxHtml =  $('<div class="box box-solid box-info box-question" />')
            .append(qBox.clone().removeClass('question-default'))
            .html();
        var qAHtml = $('.question-answer-1').html();
        $(document).on('click', '.add-question-btn', function(e){
            $('.box-question .box-body').collapse('hide');

            boxCounter ++;

            var thisQBox = $(this).closest('.box-question');
            var thisBox = qBoxHtml;
            thisBox = thisBox.replace(/(\[[0-9]+\])/g, "[" + boxCounter + "]");
            thisBox = thisBox.replace('hidden', "");
            thisBox = thisBox.replace(/(disabled="disabled")/g, "");

            if(thisQBox.length == 0){
                $('.question-area').append(thisBox);
            }
            else{
                thisQBox.after(thisBox);
            }
        });
        $(document).on('click', '.remove-question-btn', function(e){
            $(this).closest('.box-question').remove();
        });
        $(document).on('click', '.collapse-btn', function(e){
            $(this).closest('.box-header').next('.box-body').collapse('toggle');
        });
        $(document).on('change', '.question-type-dp', function(e){
            var showThisQArea = $(this).val();
            var qArea = $(this).closest('.box-body');
            qArea.find('.question-type-area')
                .css('display', 'none')
                .find('.q-form')
                .attr('disabled', 'disabled');
            qArea.find('.question-type-area[data-type="' + showThisQArea + '"]')
                .css('display', 'block')
                .find('.q-form')
                .removeAttr('disabled');
        });
        $(document).on('click', '.add-choice-btn', function(e){
            var thisChoice = '<div class="row">' + qAHtml + '</div>';
            $(this).parent().before(thisChoice);
        });
        $(document).on('click', '.remove-choice-btn', function(e){
            $(this).closest('.row').remove();
        });

        var delete_btn = $('.test-delete-btn');
        var yes_btn = $('.yes-btn');
        var deleteModal = $('.deleteModal');
        delete_btn.click(function(e){
            var thisId = this.id;
            var thisUrl = '{{ URL::to('quiz') }}/' + thisId;

            deleteModal.modal('show');
            yes_btn.click(function(e){
                deleteModal.modal('hide');
                waitingDialog.show('Pleas wait...');
                $.ajax({
                    url: thisUrl,
                    method: "DELETE",
                    success: function(doc) {
                        location.reload();
                    }
                });
            });
        });
        //endregion
    });
</script>
@stop