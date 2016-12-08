<div class="mini-space"></div>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <select id="payment-history-filter" class="selectpicker hidden">
                <option value="all">All Pay Periods</option>
                <option value="monthly">Monthly</option>
                <option value="semi-monthly">Semi-Monthly</option>
                <option value="biweekly">Biweekly</option>
                <option value="weekly">Weekly</option>
            </select>
        </div>
        <div class="col-md-9">
            <div class="date-label">
                <h4 class="date-options">
                    <span class="date-text">{{date('M Y')}}</span>
                    <input class="date" type="hidden" value="{{date('Y-m-d')}}">
                    <input class="date_day" type="hidden" value="{{date('d')}}">
                    <input class="date_week" type="hidden" value="{{date('W')}}">
                    <input class="date_month" type="hidden" value="{{date('m')}}">
                    <input class="date_year" type="hidden" value="{{date('Y')}}">
                    <input class="date_today" type="hidden" value="{{date('Y-m-d')}}">
                    <button class="btn btn-primary payment-history-filter-previous"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Previous</button>
                </h4>
            </div>
        </div>
    </div>
</div>
<table id="payroll-table" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Pay Period</th>
            <th>Next Due</th>
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
            <td>
                <span><b>{{$employee->user->name}}</b>&nbsp;({{$employee->rate[0]->currency." ".$employee->rate[0]->rate_value}})</span>
                <br />
                <span>
                    @if($employee->rate[0]->user_pay_period->payroll->status === 'Paid')
                    <button class="btn btn-primary pay-employee"><i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;<span class="pay-employee-text">Paid</span></button>
                    @else
                    <button class="btn btn-primary pay-employee"><i class="fa fa-minus-circle" aria-hidden="true"></i>&nbsp;<span class="pay-employee-text">Pay</span></button>
                    @endif
                    <input class="profile_id" type="hidden" value="{{$employee->id}}"/>
                </span>
            </td>
            @else
            <td><span><b>{{$employee->user->name}}</b>&nbsp;(No Rate Set)</span></td>
            @endif
            <td>
                @if($employee->rate->count() > 0)
                {{$employee->rate[0]->pay_period->period}}
                @endif
            </td>
            <td>
                @if($employee->rate->count() > 0)
                {{$employee->rate[0]->user_pay_period->payroll->next_due}}
                @endif
            </td>
            <td>
                @foreach($total_time->where('user_id',$employee->user->user_id) as $time)
                {{round($time->hours,2)}}
                @endforeach
            </td>
            <td>
                @foreach($total_time->where('user_id',$employee->user->user_id) as $time)
                @if($employee->rate->count() > 0)
                {{$time->hours * $employee->rate[0]->rate_value}}
                @endif
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
                @if($employee->rate->count() > 0)
                {{$employee->rate[0]->currency}}&nbsp;{{$additions->sum('default_value') + ($time->hours * $employee->rate[0]->rate_value) - $deductions->sum('default_value')}}
                @endif
                @endforeach
            </td>
            <td>
                <span class="payroll-status-{{$employee->id}}">
                 @if($employee->rate->count() > 0)
                {{$employee->rate[0]->user_pay_period->payroll->status}}
                @endif
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<input class="company_id" type="hidden" value="{{$company_id}}">
