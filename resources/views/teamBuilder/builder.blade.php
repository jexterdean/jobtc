<div class="row">
    <div class="col-md-12">
        <a data-toggle="modal" href="#create_team">
            <button class="btn btn-sm"><i class="fa fa-floppy-o"></i> Create New Team</button>
        </a>
    </div>
    <div class="col-md-12"><br /></div>
    <div class="col-md-12">
        <div class="box box-solid box-primary">
            <div class="box-header">
                <h3 class="box-title">Team</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                                <h4 class="box-title" style="font-size: 14px;">Team Box</h4>
                                <div class="box-tools pull-right">
                                    <a data-toggle="modal" href="#add_member">
                                        <button class="btn btn-sm"><i class="fa fa-plus-circle"></i> Add</button>
                                    </a>
                                </div>
                            </div>
                            <div class="box-body">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div id="team_calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create_team">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Create Team</h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_member">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Member</h4>
            </div>
            {!! Form::open(array('url' => 'teamBuilder')) !!}
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="submit" name="addEventBtn" class="btn btn-success addEventBtn">Add</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@section('js_footer')
@parent
<script>
    $(function(e){
        var paddingLeft = function (paddingValue, str) {
           return String(paddingValue + str).slice(-paddingValue.length);
        };

        var team_calendar = $('#team_calendar');
        var currentTimezone = '';
        var timezone = [];

        $.ajax({
            url: '{{ URL::to('meetingTimezone') }}',
            success: function(doc) {
                currentTimezone = doc.current_timezone;
                timezone = doc.timezone;
                renderCalendar();
            }
        });

        function renderCalendar(){
            team_calendar.fullCalendar({
                timezone: currentTimezone,
                defaultView: 'agendaWeek',
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
                titleFormat: {
                    month:  "[<strong style='font-size: 20px;'>]MMMM YYYY[</strong>]",
                    week:  "[<strong style='font-size: 20px;'>]MMM D YYYY[</strong>]",
                    day:  "[<strong style='font-size: 20px;'>]MMMM D YYYY[</strong>]"
                },
                columnFormat: {
                    week: 'ddd MMMM D' //to customize the weekly title from Sun 4/4 to Sun April 4
                },
                theme: 1,
                eventRender: function(event, element, view) {
                    //hide time as requested by Tom
                    element.find('.fc-event-time').css('display', 'none');

                    //custom title content for each calendar meeting
                    var title = '';
                    element
                        .find('.fc-event-title')
                        .html(title);

                    // render the timezone offset below the event title
                    if (event.start.hasZone()) {
                         element
                            .find('.fc-event-title')
                            .after($('<div class="tzo"/>').text(event.start.format('Z'))
                        );
                    }
                },
                eventAfterAllRender: function( view ){
                    var header_left = $('.fc-header-left');
                    if(header_left.find('.timezoneArea').length == 0){ //only append if drop down timezone doesn't exist yet
                        var tStr =
                            '<span class="form-inline timezoneArea" style="margin-left: 10px;">' +
                                '<select class="timezone-selector form-control" style="width: 130px;font-size: 12px;"></select>' +
                             '</span>';
                        header_left.append(tStr);

                        //add the options for timezone drop down
                        $.each(timezone, function(i, t){
                            header_left
                                .find('.timezone-selector')
                                .append($("<option/>").text(t).attr('value', t));
                        });

                        //set the default value and add event when the timezone dp is change
                        header_left
                            .find('.timezone-selector')
                            .val(currentTimezone)
                            .on('change', function() {
                                currentTimezone = $(this).val(); //pass new value
                                team_calendar.fullCalendar('destroy'); //remove existing calendar
                                renderCalendar(); //create new calendar
                            });
                    }
                },
                events: function(start, end, timezone, callback) {

                },
                dayClick: function(date, jsEvent, view) {

                },
                eventClick: function(calEvent, jsEvent, view) {

                },
                editable: true,
                eventDrop: function(event, delta, revertFunc) {

                }
            });
        }

        var add_member = $('#add_member');
        add_member.on('show.bs.modal', function(e){
            $.ajax({
                url: '{{ URL::to("/teamBuilder/create") }}',
                success: function(doc) {
                    add_member.find('.modal-body').html(doc);
                }
            });
        });
    });
</script>
@stop