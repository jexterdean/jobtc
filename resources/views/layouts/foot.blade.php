<form id="form-delete" action="" method="post" style="width: 0px;height: 0px;">
    {!! csrf_field() !!}
    {!! method_field('delete') !!}
    </form>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

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
    {!!  HTML::script('assets/js/moment.js')  !!}
    {!!  HTML::script('assets/js/fullcalendar.min.js')  !!}
    {!! HTML::script('assets/js/bootstrap-datetimepicker.min.js') !!}
@endif

@if(in_array('select',$assets))
    {!! HTML::script('assets/js/bootstrap-select.js') !!}
@endif

@if(in_array('magicSuggest',$assets))
    {!! HTML::script('assets/js/magicsuggest-min.js') !!}
@endif

@if(in_array('waiting',$assets))
    {!! HTML::script('assets/js/bootstrap.waiting.js') !!}
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
        //to fix the ajax PATCH/POST method type of form not working
        $.ajaxSetup(
        {
            headers:
            {
                'X-CSRF-Token': $('input[name="_token"]').val()
            }
        });

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
        var timer = function(){
            var current = $('.stop_time').attr('data-current');
            if(current){
                startEditTimer(current);
            }
        };

        timer();

        var _post_timer = function(url,_data,_this){
            var tbody = $('.task-table-body');

            $.post(url,_data,function(return_data){
                var ele = '';
                var _return_data = jQuery.parseJSON(return_data);
                var total = 0;
                $.each(_return_data.table,function(index,value){
                    ele += '<tr>';
                    ele += '<td>' + value.name + '</td>';
                    ele += '<td class="text-center">' + $.format.date(value.start_time, "dd/MM/yyyy hh:mm:ss a") + '</td>';
                    ele += '<td class="text-center">' + (value.end_time != '0000-00-00 00:00:00' ? $.format.date(value.end_time, "dd/MM/yyyy hh:mm:ss a") : '&nbsp;') + '</td>';
                    ele += '<td class="text-center">' + (value.time ? value.time : '0.00') + '</td>';
                    ele += '<td class="text-center" style="width: 5%;"><a href="/deleteTaskTimer/' + value.id + '" class="alert_delete"> <i class="fa fa-trash-o fa-2x"></i> </a></td>';
                    ele += '</tr>';
                    total += (value.time ? parseFloat(value.time) : 0);
                });
                total = total.toFixed(2);
                ele += '<tr>';
                ele += '<td class="text-right" colspan="3"><strong>Total Time:</strong></td>';
                ele += '<td class="text-center">' + total + '</td>';
                ele += '<td>&nbsp;</td>';
                ele += '</tr>';
                $('.total-time').html(total);
                if(_this){
                    var _return_latest_task_timer = _return_data.return_task_timer;
                    _this.attr('id',_return_latest_task_timer);
                }
                tbody.html(ele);
            });
        };

        $('.timer-btn').click(function(e){
            var form = $('.task-form');
            var data = form.serializeArray();
            var date = $.now();
            var now = $.format.date(date, "yyyy-MM-dd HH:mm:ss");
            var tbody = $('.task-table-body');
            var _this = $(this);

            if($(this).hasClass('start_time')){
                startEditTimer(0);
                _this
                    .html('Stop Time')
                    .removeClass('btn-stop start_time')
                    .addClass('btn-danger stop_time');
                element
                    .removeClass('bg-red-gradient')
                    .addClass('bg-green');
                data.push({'name':'start_time','value':now});
                _post_timer(form.attr('action'),data,_this);
                alert_msg('Successfully added start time!!','alert-success');
            }
            else{
                var url = public_path + 'updateTaskTimer/' + this.id;
                $(this)
                    .html('Start Time')
                    .removeClass('btn-danger stop_time')
                    .addClass('btn-stop start_time');
                element
                    .removeClass('bg-green')
                    .addClass('bg-red-gradient');
                $.stopCountDownTimer();
                data.push({'name':'end_time','value':now});
                _post_timer(url,data);
                alert_msg('Successfully stop timer!!','alert-success');
            }

        });
        /*endregion*/
        var alert_msg = function(msg,_class){
            var alert = '<div class="alert ' + _class + ' alert-dismissable">';
                    alert += '<i class="fa fa-check"></i>';
                    alert += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
                    alert +=  '<strong>' + msg + '</strong>';
                alert += '</div>';
            $('section.content').prepend(alert);
        };
        var finish_checklist = function(){
            var count = 0;
            var over_all = 0;
            $('.checklist-checkbox').each(function(){
                over_all++;
                if($(this).is(':checked')){
                    count++;
                }
            });
            var _percentage = (count / over_all) * 100;
            $(".progress-bar")
                .animate({
                    width: _percentage.toFixed(2) + '%'
                }, 100)
                .html(_percentage.toFixed(2) + '%');
        };

        /*region Check List*/
        var check_list_container = $('.list-group');
        $('.check-list-btn').click(function(){
            var text_area_ele = '<li class="list-group-item text-area-content">';
                text_area_ele += '<textarea class="form-control" name="checklist" placeholder="Checklist" rows="3"></textarea><br/>';
                text_area_ele += '<button class="btn btn-success btn-shadow btn-sm submit-checklist" type="button">Submit</button>&nbsp;&nbsp;&nbsp;';
                text_area_ele += '<button class="btn btn-danger btn-shadow btn-sm cancel-checklist" type="button">Cancel</button>';
                text_area_ele += '</li>';

            var _this = $(this);

            _this.attr('disabled','disabled');
            check_list_container.prepend(text_area_ele);
            $('textarea[name="checklist"]').focus();
            check_list_container.on('click','.submit-checklist',function(){
                var data = $('.task-form').serializeArray();
                $.post(public_path + 'checkList', data,function(d){
                    var _return_data = jQuery.parseJSON(d);
                    var ele = '';
                    alert_msg('Successfully added checklist!!','alert-success');
                    $.each(_return_data,function(index,val){
                        ele += '<li class="list-group-item">';
                            ele += '<label class="checklist-label">';
                            ele += '<div class="icheckbox_minimal" aria-checked="false" aria-disabled="false" style="position: relative;">';
                                ele += '<input type="checkbox" class="checkbox" style="position: absolute; opacity: 0;">';
                                ele += '<ins class="iCheck-helper" style="position: absolute; top: 0; left: 0; display: block; width: 100%; height: 100%; margin: 0; padding: 0; border: 0; opacity: 0; background: rgb(255, 255, 255);"></ins>';
                            ele += '</div>&nbsp;';
                            ele += val.checklist;
                            ele += '</label>';
                            ele += '<div class="pull-right">';
                                ele += '<a href="/updateCheckList/' + val.id + '"><i class="glyphicon glyphicon-pencil glyphicon-lg"></i></a>&nbsp;';
                                ele += '<a href="/deleteCheckList/' + val.id + '" class="alert_delete"><i class="glyphicon glyphicon-trash glyphicon-lg"></i></a>';
                            ele += '</div>';
                        ele += '</li>';
                    });
                    check_list_container.html(ele);
                });
            })
            .on('click','.cancel-checklist',function(){
                _this.removeAttr('disabled');
                $('.text-area-content').remove();
            });
        });
        check_list_container.on('click','.update-checklist',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var checklist_label = $(this).parent().parent().find('.checklist-label');
            var checklist_item = $(this).parent().parent().parent().find('.checklist-item');
            var _text = checklist_label.text().replace(/(^\s+|[^a-zA-Z0-9 ]+|\s+$)/g,'');
            var text_area_ele = '<div class="text-area-content">';
                text_area_ele += '<textarea class="form-control" name="checklist" placeholder="Checklist" rows="3">' + _text + '</textarea><br/>';
                text_area_ele += '<button class="btn btn-success btn-shadow btn-sm submit-checklist" type="button">Submit</button>&nbsp;&nbsp;&nbsp;';
                text_area_ele += '<button class="btn btn-danger btn-shadow btn-sm cancel-checklist" type="button">Cancel</button>';
                text_area_ele += '</div>';

            checklist_item
                .css({'display':'none'})
                .before(text_area_ele);

            $('.cancel-checklist')
                .click(function(){
                    $('.text-area-content').remove();
                    checklist_item.removeAttr('style');
                });

            $('.submit-checklist')
                .click(function(){
                    var data = $('.task-form').serializeArray();
                    $.post(url,data,function(_data){
                        var _return_data = jQuery.parseJSON(_data);
                        $('.text-area-content').remove();

                        var ele = '<label class="checklist-label">';
                              ele += '<div class="icheckbox_minimal" aria-checked="false" aria-disabled="false" style="position: relative;">';
                                  ele += '<input type="checkbox" class="checkbox" style="position: absolute; opacity: 0;">';
                                  ele += '<ins class="iCheck-helper" style="position: absolute; top: 0; left: 0; display: block; width: 100%; height: 100%; margin: 0; padding: 0; border: 0; opacity: 0; background: rgb(255, 255, 255);"></ins>';
                              ele += '</div>&nbsp;';
                              ele += _return_data.checklist;
                              ele += '</label>';
                              ele += '<div class="pull-right">';
                                  ele += '<a href="/updateCheckList/' + _return_data.id + '"><i class="glyphicon glyphicon-pencil glyphicon-lg"></i></a>&nbsp;';
                                  ele += '<a href="/deleteCheckList/' + _return_data.id + '" class="alert_delete"><i class="glyphicon glyphicon-trash glyphicon-lg"></i></a>';
                              ele += '</div>';

                        checklist_item
                                  .removeAttr('style')
                                  .html(ele);

                    });
                });
        });

        $('.checklist-label,.checklist-checkbox,.iCheck-helper').click(function(e){
            finish_checklist();
            var checkbox = $(this).parent().parent().find('.checklist-checkbox');
            var _id = checkbox.attr('id');
            var data = [];
            data.push(
                {'name':'_token','value':$('input[name="_token"]').val()},
                {'name':'task_id','value':$('input[name="task_id"]').val()},
                {'name':'user_id','value':$('input[name="user_id"]').val()}
            );
            if(checkbox.is(':checked')){
                data.push({'name':'is_finished','value':checkbox.val()});
            }
            $.post(public_path + 'updateCheckList/' + _id,data,function(e){});
        });
        /*endregion*/
    });
    /*region Auto Close Alert Message*/
    setInterval(function(){
        $('section.content').find('.alert').fadeTo(2000, 500).slideUp(500, function(){
            $(this).alert('close');
        });
    }, 2000);
    /*endregion*/
    $(document).on('click', '.show_edit_form',function(e){
        e.preventDefault();

        var link = e.currentTarget.href;

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
