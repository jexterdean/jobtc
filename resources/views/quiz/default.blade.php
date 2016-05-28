@extends('layouts.default')

@section('content')
@parent
<div class="modal fade test-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
@include('quiz.' . $page)
<style>
    .test-modal textarea:not(.active){
        height: 39px!important;
        transition: height 0.25s ease-in;
    }
    .test-modal textarea.active{
        height: 200px!important;
        transition: height 0.25s ease-in;
    }
</style>
@stop

@section('js_footer')
@parent
<script>
    $(function(e){
        var testModal = $('.test-modal');

        $(document).on('click', '.trigger-links', function(e){
            e.preventDefault();

            var link = e.currentTarget.href;
            var title = $(this).data('title');
            var method = $(this).data('method');
            $.ajax({
                method: method ? method : 'get',
                url: link,
                success: function(data) {
                    testModal.find('.modal-title').html(title);
                    testModal.find('.modal-body').html(data);
                    testModal.modal('show');
                }
            });
        });

        //region Test Sort
        var t = $('.test-group');
        t.sortable({
            revert: "invalid",
            connectWith: ".test-group",
            handle: '.drag-test',
            stop: function (event, ui) {
                var sortId = [];
                t.find('.test-list').each(function(e){
                    sortId.push($(this).data('id'));
                });

                var url = public_path + '/testSort';
                $.post(url, { id: sortId });
            }
        });
        //endregion

        //region Test Delete
        var test_delete_btn = $('.test-delete-btn');
        test_delete_btn.click(function(e){
            var thisId = this.id;
            var thisTest = $(this).closest('.test-list');
            $.ajax({
                url: '{{ URL::to('quiz') }}/' + thisId + '?t=1',
                method: "DELETE",
                success: function(doc) {
                    thisTest.remove();
                }
            });
        });
        //endregion

        //region Quest Sort
        var q = $('.question-group');
        q.sortable({
            revert: "invalid",
            connectWith: ".question-group",
            handle: '.drag-question',
            stop: function (event, ui) {
                var sortId = [];
                $(this).find('.question-list').each(function(e){
                    sortId.push($(this).data('id'));
                });

                var url = public_path + '/questionSort';
                $.post(url, { id: sortId });
            }
        });
        //endregion

        //region Question Highlight
        $('.question-collapse')
            .on('shown.bs.collapse', function (e) {
                $(this).closest('.question-list').addClass('is-task-item-selected');
            })
            .on('hidden.bs.collapse', function (e) {
                $(this).closest('.question-list').removeClass('is-task-item-selected');
            });
        //endregion

        //region Question Type
        $(document).on('change', '.question-type-dp', function(e){
            var showThisQArea = $(this).val();
            var qArea = $(this).closest('.modal-body');
            qArea.find('.question-type-area')
                .css('display', 'none')
                .find('.q-form')
                .attr('disabled', 'disabled');
            qArea.find('.question-type-area[data-type="' + showThisQArea + '"]')
                .css('display', 'block')
                .find('.q-form')
                .removeAttr('disabled');
        });
        //endregion

        //region Question Delete

        var delete_question_btn = $('.delete-question-btn');
        delete_question_btn.click(function(e){
            var thisId = this.id;
            var thisQuestion = $(this).closest('.question-list');
            $.ajax({
                url: '{{ URL::to('quiz') }}/' + thisId + '?t=2',
                method: "DELETE",
                success: function(doc) {
                    thisQuestion.remove();
                }
            });
        });
        //endregion

        //region Choices Events
        $(document).on('click', '.add-choice-btn', function(e){
            var qAHtml = testModal.find('.modal-body .question-answer').html();
            qAHtml = qAHtml.replace('checked', '');
            var thisChoice = '<div class="row question-answer">' + qAHtml + '</div>';
            $(this).parent().before(thisChoice);
            var question_answer = $(this).parent().prev('.question-answer');
            question_answer.find('.question_choices').val('');
            question_answer.find('.radio').removeAttr('checked');
        });
        $(document).on('click', '.remove-choice-btn', function(e){
            $(this).closest('.row').remove();
        });
        //endregion

        //region Textarea folder
        $(document).on('click', '.test-modal .form-control', function() {
            var t = $('.test-modal textarea');
            t.removeClass('active');
            if($(this).is('textarea')){
                $(this).addClass('active');
            }
        });
        //endregion
    });
</script>
@stop