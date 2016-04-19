<div class="box box-solid box-success">
    <div class="box-header">
        <h3 class="box-title">Payroll</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-warning btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="form-inline">
            <label>User:</label>
            <?php
            echo Form::select(
                'user_id',
                $user, '',
                array(
                  'class' => 'user-dp form-control'
                )
            );
            ?>
        </div>
        <table class="table table-hover">
            <thead>
                <tr class="table-header">
                    <th>Date</th>
                    <th>Task</th>
                    <th>Time</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div style="clear:both;"></div>
</div>

@section('js_footer')
@parent
<script>
    $(document).ready(function () {
        var user_dp = $('.user-dp');
        loadPayroll();
        user_dp.change(function(e){
            loadPayroll();
        });

        function loadPayroll(){
            var user_id = user_dp.val();
            $.ajax({
                url: '{{ URL::to('payrollJson') }}?user_id=' + user_id,
                success: function(payroll) {
                    $('.table tbody').html('');
                    if(payroll.length > 0){
                        $.each(payroll, function(k, v){
                            var trContent =
                                '<tr>' +
                                    '<td>' + v.date + '</td>' +
                                    '<td>' + v.task_title + '</td>' +
                                    '<td>' + v.time + '</td>' +
                                    '<td>' + v.amount + '</td>' +
                                '</tr>';
                            $('.table tbody').append(trContent);
                        });
                    }
                }
            });
        }
    });
</script>
@stop