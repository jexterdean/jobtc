var interval;
var slider_div = $('.slider-div');
var btn_next = $('.btn-next');
var btn_prev = $('.btn-prev');
var btn_finish = $('.btn-finish');
btn_next.click(function (e) {
    var thisId = this.id;
    var type = $(this).data('type');
    var slider_div = $(this).closest('.slider-div');
    if (thisId) {
        var thisElement = $('input[name="answer[' + thisId + ']"]');
        var answer =
                type == 3 ?
                $('textarea[name="answer[' + thisId + ']"]').summernote('code') :
                (
                        thisElement.attr('type') == "radio" ?
                        $('input[name="answer[' + thisId + ']"]:checked').val() :
                        thisElement.val()
                        );
        var data = {
            question_id: thisId,
            answer: answer == undefined ? '' : answer
        };
        var quiz_id = $('.slider-body').find('.quiz_id').val();
        var ajaxurl = public_path + 'quiz?id=' + quiz_id + '&p=exam';
        $.ajax({
            url: ajaxurl,
            data: data,
            method: "POST",
            success: function (doc) {
                slider_div.remove();
            },
            error: function (a, b, c) {

            }
        });
    }

    clearInterval(interval);
    $(this)
            .closest('.slider-div')
            .removeClass('active')
            .next('.slider-div')
            .addClass('active');
    var time_limit = $(this)
            .closest('.slider-div')
            .next('.slider-div')
            .find('.time-limit');
    if (time_limit.length != 0) {
        if (time_limit.data('length') != "00:00:00") {
            time_limit.removeClass('hidden');
            time_limit.timerStart();
        }
    }
});
btn_finish.click(function (e) {
    var ajaxurl = public_path + 'getApplicantQuizResults';
    var slider_body = $(this).parent();
    var applicant_id = slider_body.find('.applicant_id').val();
    var quiz_id = slider_body.find('.quiz_id').val();
    
    
    
    console.log('applicant_id : '+applicant_id);
    console.log('quiz_id : '+quiz_id);
    
    var data = {
      'applicant_id' :  applicant_id,
      'quiz_id' : quiz_id
    };
      
    $.ajax({
        url: ajaxurl,
        data: data,
        method: "POST",
        success: function (data) {
            //$(this).parent().html('Your score is: ' + score);
            //slider_body.html('Your score is: ' + data);
            //console.log('Getting applicant result');
            
            //var result_url = public_path + 'quiz/'+quiz_id+'?p=review';
            slider_body.html(data);
        },
        error: function (a, b, c) {

        }
    });
});
//region summer note
var options = $.extend(true,
        {
            lang: '',
            codemirror: {
                theme: 'monokai',
                mode: 'text/html',
                htmlMode: true,
                lineWrapping: true
            }
        },
{
    "toolbar": [
        ["style", ["style"]],
        ["font", ["bold", "underline", "italic", "clear"]],
        ["color", ["color"]],
        ["para", ["ul", "ol", "paragraph"]],
        ["table", ["table"]],
        ["insert", ["link", "picture", "video"]],
        ["view", ["fullscreen", "codeview", "help"]]
    ]
}
);
$("textarea.summernote-editor").summernote(options);
//endregion

$.fn.timerStart = function () {
    var timer_btn = $(this);
    if (timer_btn.find('.timer-area').length == 0) {
        timer_btn.prepend('<span class="timer-area" />');
    }
    var timer = timer_btn.find('.timer-area');
    var l = timer_btn.data('length');
    var a = l.split(':'); // split it at the colons

    var h = a[0];
    var m = parseInt(a[1]);
    var s = parseInt(a[2]);
    // minutes are worth 60 seconds. Hours are worth 60 minutes.
    var time_limit = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
    interval = setInterval(function (e) {
        if (time_limit == 0) {
            clearInterval(interval);
            timer_btn.parent().find('.btn-next').trigger('click');
        }

        m = Math.floor(time_limit / 60); //Get remaining minutes
        s = time_limit - (m * 60);
        var time = (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s);
        timer.html(time);
        time_limit--;
    }, 1000);
};