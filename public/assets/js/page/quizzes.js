/* 
 *  For Quizzes Module
 */

  var interval;
    $(function(e){
        var slider_div = $('.slider-div');
        var btn_next = $('.btn-next');
        var btn_prev = $('.btn-prev');

        btn_next.click(function(e){
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
            if(time_limit.length != 0){
                if(time_limit.data('length') != "00:00:00"){
                    time_limit.removeClass('hidden');
                    time_limit.timerStart();
                }
            }
        });
        btn_prev.click(function(e){
            clearInterval(interval);
            $(this)
                .closest('.slider-div')
                .removeClass('active')
                .prev('.slider-div')
                .addClass('active');

            var time_limit = $(this)
                .closest('.slider-div')
                .prev('.slider-div')
                .find('.time-limit');
            if(time_limit.length != 0){
                if(time_limit.data('length') != "00:00:00"){
                    time_limit.removeClass('hidden');
                    time_limit.timerStart();
                }
            }
        });
    });

    $.fn.timerStart = function(){
        var timer_btn = $(this);
        if(timer_btn.find('.timer-area').length == 0){
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
        interval = setInterval(function(e){
            if(time_limit == 0){
                clearInterval(interval);
                timer_btn.parent().find('.btn-next').trigger('click');
            }

            m = Math.floor(time_limit/60); //Get remaining minutes
            s = time_limit - (m * 60);
            var time = (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s);
            timer.html(time);

            time_limit --;
        }, 1000);
    };