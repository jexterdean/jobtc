/* 
 * Payroll Scripts
 */
$(document).ready(function () {
    $('#filter').removeClass('hidden');

//Filter Scripts
    $('#filter').change(function () {
        var filter_val = $('#filter').val();
        var company_id = $('.company_id').val();
        var date = $('.date').val();
        var date_today = $('.date_today').val();

        var ajaxurl;

        if (filter_val === 'day') {
            var day_text = moment(date_today).format('MMM DD, YYYY');
            var day = moment(date_today).format('YYYY-MM-DD');
            $('.date-text').text(day_text);
            $('.date').val(day);

            ajaxurl = public_path + 'payroll/filter/' + company_id + '/' + filter_val + '/' + day;
        }

        if (filter_val === 'month') {
            var date_month = $('.date_month').val();
            var date_year = $('.date_year').val();
            
            var month = moment(date).format('MMMM YYYY');
            var month_number = moment(date).format('MM-YYYY');
            $('.date-text').text(month);
            $('.date').val(month_number);

            ajaxurl = public_path + 'payroll/filter/' + company_id + '/' + filter_val + '/' + month_number;
        }

        if (filter_val === 'year') {
            var date_year = $('.date_year').val();
            var year = moment(date_year).format('YYYY');
            $('.date-text').text(year);
            $('.date').val(year);
            
            ajaxurl = public_path + 'payroll/filter/' + company_id + '/' + filter_val + '/' + year;
        }

        filter(ajaxurl);

    });

    $('.date-label').on('click', '.filter-previous', function () {
        var company_id = $('.company_id').val();
        var filter_val = $('#filter').val();
        var date = $('.date').val();

        var date_previous;
        var date_previous_text;
        var ajaxurl;

        if (filter_val === 'day') {
            date_previous = moment(date).subtract(1, 'days').format('YYYY-MM-DD');
            date_previous_text = moment(date).subtract(1, 'days').format('MMM DD, YYYY');
            console.log('date_previous: ' + date_previous);

            $('.date').val(date_previous);
            $('.date-text').text(date_previous_text);
            if ($('.filter-next').length === 0) {
                $('.date-options').append('<button class="btn btn-primary filter-next"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>&nbsp;Next</button>');
            }
            
            ajaxurl = public_path + 'payroll/filter/' + company_id + '/' + filter_val + '/' + date_previous;
        }

        if (filter_val === 'month') {
            var date_month = $('.date_month').val();
            var date_year = $('.date_year').val();
            
            date_previous = moment(date_month).subtract(1, 'month').format('M');
            date_previous_text = moment(date_month).subtract(1, 'month').format('MMMM');
            //console.log('date_previous: ' + date_previous);
            
            
            console.log(date_previous);
            
            $('.date').val(date_previous+"-"+date_year);
            $('.date-text').text(date_previous_text+" "+date_year);
            if ($('.filter-next').length === 0) {
                $('.date-options').append('<button class="btn btn-primary filter-next"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>&nbsp;Next</button>');
            }
            ajaxurl = public_path + 'payroll/filter/' + company_id + '/' + filter_val + '/' + date_previous+"-"+date_year;
        }

        if (filter_val === 'year') {
            date_previous = moment(date).subtract(1, 'years').format('YYYY');
            date_previous_text = moment(date).subtract(1, 'years').format('YYYY');
            console.log('date_previous: ' + date_previous);

            $('.date').val(date_previous);
            $('.date-text').text(date_previous_text);
            if ($('.filter-next').length === 0) {
                $('.date-options').append('<button class="btn btn-primary filter-next"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>&nbsp;Next</button>');
            }
            ajaxurl = public_path + 'payroll/filter/' + company_id + '/' + filter_val + '/' + date_previous;
        }

        filter(ajaxurl);

    });

    $('.date-label').on('click', '.filter-next', function () {
        var date = $('.date').val();
        var date_today = $('.date_today').val();
        var filter_val = $('#filter').val();
        var company_id = $('.company_id').val();
        var date_next;
        var date_next_text;
        var ajaxurl;

        if (filter_val === 'day') {
            date_next = moment(date).add(1, 'days').format('YYYY-MM-DD');
            date_next_text = moment(date).add(1, 'days').format('MMM DD, YYYY');
            console.log('date_next: ' + date_next);
            $('.date').val(date_next_text)
            $('.date-text').text(date_next_text)
            if (date_today === date_next) {
                $('.filter-next').remove();
            }
            ajaxurl = public_path + 'payroll/filter/' + company_id + '/' + filter_val + '/' + date_next;
        }

        if (filter_val === 'month') {
            var date_month = $('.date_month').val();
            var date_year = $('.date_year').val();
            date_next = moment(date_month).add(1, 'month').format('MM');
            date_next_text = moment(date_month).add(1, 'month').format('MMMM');
            console.log('date_next: ' + date_next);
            $('.date').val(date_next_text+"-"+date_year);
            $('.date-text').text(date_next_text+" "+date_year);
            
            ajaxurl = public_path + 'payroll/filter/' + company_id + '/' + filter_val + '/' + date_next+"-"+date_year;
        }

        if (filter_val === 'year') {
            date_next = moment(date).add(1, 'year').format('YYYY-MM-DD');
            date_next_text = moment(date).add(1, 'year').format('YYYY');
            console.log('date_next: ' + date_next);
            $('.date').val(date_next_text)
            $('.date-text').text(date_next_text)
            ajaxurl = public_path + 'payroll/filter/' + company_id + '/' + filter_val + '/' + date_next;
        }

        filter(ajaxurl);
    });


});

function filter(ajaxurl) {
    $.ajax({
        url: ajaxurl,
        type: "GET",
        beforeSend: function () {
        },
        success: function (data) {
            $('#payroll-table-container').html(data);
        },
        error: function (xhr, status, error) {

        }
    }); //ajax
}