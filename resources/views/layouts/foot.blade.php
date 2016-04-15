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

{!!  HTML::script('assets/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')  !!}

{!! HTML::script('assets/js/validation-form.js')  !!}
{!! HTML::script('assets/js/plugins/input-mask/jquery.inputmask.js')  !!}
{!! HTML::script('assets/js/plugins/input-mask/jquery.inputmask.date.extensions.js') !!}
{!! HTML::script('assets/js/plugins/input-mask/jquery.inputmask.extensions.js')  !!}

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

    });

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
