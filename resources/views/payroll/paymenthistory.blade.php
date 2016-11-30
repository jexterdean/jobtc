<table id="payroll-table" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Hours Rendered</th>
            <th>Regular Pay</th>
            <th class="additions-header">Additions(+)</th>
            <th class="deductions-header">Deductions(-)</th>
            <th>Total Owed</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $employee)
        <tr>
            @if($employee->rate->count() > 0)
            <td>{{$employee->user->name}}&nbsp;<h4>({{$employee->rate[0]->currency." ".$employee->rate[0]->rate_value}})</h4></td>
            @else
            <td>{{$employee->user->name}}&nbsp;<h4>(No Rate Set)</h4></td>
            @endif
            <td>
                @foreach($total_time->where('user_id',$employee->user->user_id) as $time)
                {{round($time->hours,2)}}
                @endforeach
            </td>
            <td>
                @foreach($total_time->where('user_id',$employee->user->user_id) as $time)
                {{$time->hours * $employee->rate[0]->rate_value}}
                @endforeach
            </td>
            <td>
                <table id="additions-table" class="table table-bordered">
                    @forelse($additions as $addition)
                    <tr>    
                        <td>
                            <span>{{$addition->column_name}}</span>
                        </td>
                        <td class="additions-item">
                            <span>{{$addition->default_value}}</span>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                    <tr>
                        <td>Total</td>
                        <td class="addition-total">{{$additions->sum('default_value')}}</td>
                    </tr>
                </table>
            </td>
            <td>
                <table id="deductions-table" class="table table-bordered">
                    @forelse($deductions as $deduction)
                    <tr>
                        <td>
                            <span>{{$deduction->column_name}}</span>
                        </td>
                        <td>
                            <span>{{$deduction->default_value}}</span>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                    <tr>
                        <td>Total</td>
                        <td><span class="deduction-total">{{$deductions->sum('default_value')}}</span></td>
                    </tr>
                </table>
            <td>
                @foreach($total_time->where('user_id',$employee->user->user_id) as $time)
                {{$employee->rate[0]->currency}}&nbsp;{{$additions->sum('default_value') + ($time->hours * $employee->rate[0]->rate_value) - $deductions->sum('default_value')}}
                @endforeach
            </td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>
<input class="company_id" type="hidden" value="{{$company_id}}">
