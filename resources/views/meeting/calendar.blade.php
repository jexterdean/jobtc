<div class="box box-primary">
    <div class="box-body no-padding">
        <div id="meeting_calendar"></div>
    </div>
</div>

<div class="modal fade addEventModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Meeting</h4>
            </div>
            <?php
            echo Form::open(array('url' => 'meeting'));
                ?>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="submit" name="addEventBtn" class="btn btn-success addEventBtn">Add</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
                <?php
            echo Form::close();
            ?>
        </div>
    </div>
</div>
<div class="modal fade editEventModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit Meeting</h4>
                </div>
                <?php
                echo Form::open(array('url' => 'meeting', 'method' => 'PATCH', 'class' => 'editMeetingForm'));
                    ?>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="submit" name="editEventBtn" class="btn btn-success editEventBtn">Edit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                    <?php
                echo Form::close();
                ?>
            </div>
        </div>
    </div>

@section('js_footer')
@parent
<script>
    $(function(e){
        //to fix the PATCH method type of form not working
        $.ajaxSetup(
        {
            headers:
            {
                'X-CSRF-Token': $('input[name="_token"]').val()
            }
        });

        var paddingLeft = function (paddingValue, str) {
           return String(paddingValue + str).slice(-paddingValue.length);
        };

        var date = new Date();
        var meeting_calendar = $('#meeting_calendar');
        var addEventModal = $('.addEventModal');
        var editEventModal = $('.editEventModal');
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
            meeting_calendar.fullCalendar({
                timezone: currentTimezone,
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
                    var title = '<strong>Project:</strong> ' + event.project_title + '<br />';
                    title += '<strong>Description:</strong> ' + event.description + '<br />';
                    title += '<strong>Type:</strong> ' + event.meeting_type + '<br />';
                    title += '<strong>Priority:</strong> ' + event.meeting_priority + '<br />';
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
                                meeting_calendar.fullCalendar('destroy'); //remove existing calendar
                                renderCalendar(); //create new calendar
                            });
                    }
                },
                events: function(start, end, timezone, callback) {
                    var thisUrl = '{{ URL::to('meetingJson') }}' + (currentTimezone ? ('?timezone=' + currentTimezone) : '');
                    $.ajax({
                        url: thisUrl,
                        success: function(doc) {
                            callback(doc);
                        }
                    });
                },
                dayClick: function(date, jsEvent, view) {
                    //add meeting pop out triggered
                    var d = date._d;
                    var dStr = d.getFullYear() + '-' + paddingLeft("00", parseInt(d.getMonth()) + 1) + '-' + paddingLeft("00", d.getDate());
                    $.ajax({
                        url: '{{ URL::to("/meeting/create") }}',
                        method: "GET",
                        data: {
                            date: dStr
                        },
                        success: function(doc) {
                            addEventModal.find('.modal-body').html(doc);
                            addEventModal.modal('show');
                        }
                    });
                },
                eventClick: function(calEvent, jsEvent, view) {
                    //edit meeting pop out triggered
                    var thisUrl = '{{ URL::to("/meeting") }}/' + calEvent.id;
                    $.ajax({
                        url: thisUrl,
                        success: function(doc) {
                            editEventModal.find('.editMeetingForm').attr('action', thisUrl);
                            editEventModal.find('.modal-body').html(doc);
                            editEventModal.modal('show');
                        }
                    });
                },
                editable: true,
                eventDrop: function(event, delta, revertFunc) {
                    //drag and drop functionality
                    $.ajax({
                        url: '{{ URL::to("/meeting") }}/' + event.id,
                        method: "PATCH",
                        data: {
                            is_drag: 1,
                            new_date: event.start.format(),
                            start_date: event.start_date,
                            end_date: event.end_date
                        },
                        success: function(doc) {

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                        }
                    });
                }
            });
        }
    });
</script>
@stop