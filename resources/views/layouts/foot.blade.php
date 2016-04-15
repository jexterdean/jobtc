<form id="form-delete" action="" method="post" style="width: 0px;height: 0px;">
    {!! csrf_field() !!}
    {!! method_field('delete') !!}
    </form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

{!! HTML::script('assets/js/AdminLTE/app.js')  !!}
{!!  HTML::script('assets/js/bootbox.js')  !!}

@if(in_array('table',$assets))
    {!!   HTML::script('assets/js/plugins/datatables/jquery.dataTables.js') !!}
    {!!  HTML::script('assets/js/plugins/datatables/dataTables.bootstrap.js')  !!}
    {!! HTML::script('assets/js/plugins/datatables/dataTables.tableTools.js')  !!}
    {!! HTML::script('assets/js/plugins/datatables/dataTables.colVis.js')  !!}
    {!! HTML::script('assets/js/plugins/datatables/dataTables.colReorder.js')  !!}
@endif

@if(in_array('knob',$assets))
    {!! HTML::script('assets/js/plugins/jqueryKnob/jquery.knob.js') !!}
@endif

@if(in_array('calendar',$assets))
    {!!  HTML::script('assets/js/moment.min.js')  !!}
    {!!  HTML::script('assets/js/fullcalendar.min.js')  !!}
@endif

{!!  HTML::script('assets/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')  !!}

{!! HTML::script('assets/js/validation-form.js')  !!}
{!! HTML::script('assets/js/plugins/input-mask/jquery.inputmask.js')  !!}
{!! HTML::script('assets/js/plugins/input-mask/jquery.inputmask.date.extensions.js') !!}
{!! HTML::script('assets/js/plugins/input-mask/jquery.inputmask.extensions.js')  !!}

{!! HTML::script('assets/js/countdown.timer.js')  !!}
{!! HTML::script('assets/js/jquery-dateFormat.js')  !!}


<script>
    $(function () {
        $("#datemask").inputmask("dd-mm-yyyy", {"placeholder": "dd-mm-yyyy"});
        $("[data-mask]").inputmask();
    });
    $(document).ajaxComplete(function () {
        $("#datemask").inputmask("dd-mm-yyyy", {"placeholder": "dd-mm-yyyy"});
        $("[data-mask]").inputmask();
    });

    $(document).ready(function () {
                @if(in_array('table',$assets))
        var table = $('.datatableclass').dataTable({
                    "sDom": 'RC<"clear">lfrtip',
                    colVis: {
                        restore: "Restore",
                        showAll: "Show all",
                        showNone: "Show none"
                    },
                    "language": {
                          "emptyTable": "No data available in table"
                    },
                    "bPaginate": true,
                    "bLengthChange": true,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "sAjaxSource": "{{ url('/data/'. (isset($cacheKey)?$cacheKey:'none')) }}"
                });
        var tt = new $.fn.dataTable.TableTools(table);
        $(tt.fnContainer()).insertBefore('div.dataTables_wrapper');

        @endif

        $(".textarea").wysihtml5({"link": false, "image": false});

        $('#task-list-box').slimScroll({
            height: '250px'
        });

        /*region Auto Change and Select Category Name*/
        $('.category-name')
            .bind('keyup keypress blur',function(){
                var myStr = $(this).val();
                myStr = myStr.toLowerCase();
                myStr = myStr.replace(/\s+/g, "-");
                $(this).val(myStr);
            })
            .focusout(function(){
                $('#category-name').val($(this).val());
                var cat_form = $('.category-form');
                var form_data = cat_form.serializeArray();
                var url = cat_form.attr('action');
                var cat_value = $(this).val();
                if($(this).val()){
                    form_data.push(
                        {name:'slug',value:$(this).val()},
                        {name:'request_from_link_page',value:'1'}
                    );

                    $.post(url,form_data,function(data){
                        var _return_data = jQuery.parseJSON(data);
                        var option_ele = '<option value>Select Category</option>';

                        $.each(_return_data,function(key,val){
                            var is_selected = cat_value == val.name ? 'selected' : '';
                            option_ele += '<option value="' + val.id + '" ' + is_selected + '>' + val.name + '</option>';
                        });
                       $('select.category').html(option_ele);
                    });
                }

                $(this).val('');
            });
        /*endregion*/

        /*region Timer*/
        var element = $('#timer');
        function startEditTimer(s){
            var timerStart = parseInt(0) + parseInt(s);
            var $minutes = parseInt(timerStart/60);
            var $hoursValue = parseInt($minutes/60);
            var $minutesValue = $minutes - ($hoursValue * 60);
            var $secondsValue = timerStart - (($hoursValue * 3600) + ($minutesValue * 60));

            $.countDownTimer(element, {
                includeTimer: {
                    hour: 1,
                    minutes: 1,
                    seconds: 1
                },
                isMilitaryTime: 0,
                isCountUp: 1,
                hours: $hoursValue,
                minutes: $minutesValue,
                seconds: $secondsValue
            });
        }
        $('.btn-stop').click(function(e){
            var form = $('.task-form');
            var data = form.serializeArray();
            var date = $.now();
            var now = $.format.date(date, "yyyy-MM-dd HH:mm:ss");
            var tbody = $('.task-table-body');

            if($(this).hasClass('start_time')){
                startEditTimer(0);
                $(this)
                    .html('Stop Time')
                    .removeClass('start_time')
                    .addClass('stop_time');
                element.css({
                            'color': '#000000'
                        });
                data.push({'name':'start_time','value':now});

                $.post(form.attr('action'),data,function(data){
                    console.log(data);
                    var ele = '';
                    var _return_data = jQuery.parseJSON(data);
                    $.each(_return_data,function(index,value){
                        ele += '<tr>';
                        ele += '<td>' + value.name + '</td>';
                        ele += '<td class="text-center">' + $.format.date(value.start_time, "dd-MM-yyyy hh:mm:ss a") + '</td>';
                        ele += '<td class="text-center">&nbsp;</td>';
                        ele += '<td class="text-center" style="width: 5%;"><a href="deleteTaskTimer/' + value.id + '" class="alert_delete"> <i class="fa fa-trash-o fa-2x"></i> </a></td>';
                        ele += '</tr>';
                    });
                    tbody.html(ele);
                });
            }
            else{
                $(this)
                    .html('Start Time')
                    .removeClass('stop_time')
                    .addClass('start_time');
                element.css({
                    'color': '#ff0000'
                });
                $.stopCountDownTimer();
            }

        });
        /*endregion*/
    });
    setTimeout(function(){
        $('.alert').fadeTo(2000, 500).slideUp(500, function(){
                $(this).alert('close');
        });
    }, 2000);
    $(document).on('click', '.show_edit_form',function(e){
        e.preventDefault();

        var link = e.currentTarget.href;

        console.log(link);
        $.get(link, function(data){
            $('#ajax .modal-content').html(data);
            $('#ajax').modal('show');
        });
    });

    $(document).on("click", ".alert_delete", function (e) {
        var link = $(this).attr("href");

        e.preventDefault();
        bootbox.confirm("Are you sure want to proceed?", function (result) {
            if (result) {
                var form = $('#form-delete');
                form.attr('action',link);
                form.submit();

//                document.location.href = link;
            }
        });
    });

    @if(in_array('calendar',$assets))
    $(function () {
        var date = new Date();
        var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: 'today',
                month: 'month',
                week: 'week',
                day: 'day'
            },
            events:{!!  $EVENTS or "[]" !!}
        });
    });
    @endif
</script>

@section('js_footer')
@show


</body>
</html>
