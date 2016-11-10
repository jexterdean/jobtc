<div id="edit-rate-form" class="row">
    <div class="col-md-4">
        <select class="form-control" name="currency" placeholder="Currency">
            @if($rate->currency === 'CAD')
            <option value="CAD" selected="selected">CAD</option>
            @else
            <option value="CAD">CAD</option>
            @endif
            @if($rate->currency === 'USD')
            <option value="USD" selected="selected">USD</option>
            @else
            <option value="USD">USD</option>
            @endif
            @if($rate->currency === 'PHP')
            <option value="PHP" selected="selected">PHP</option>
            @else
            <option value="PHP">PHP</option>
            @endif
        </select>
    </div>
    <div class="col-md-4">
        <select class="form-control" name="rate_type" placeholder="Currency">
            @if($rate->rate_type === 'hourly')
            <option value="hourly" selected="selected">Hourly</option>
            @else
            <option value="hourly">Hourly</option>
            @endif
            @if($rate->rate_type === 'fixed')
            <option value="fixed" selected="selected">Fixed</option>
            @else
            <option value="fixed">Fixed</option>
            @endif
        </select>
    </div>
    <div class="col-md-4">
        <input class="form-control" name="rate_value" placeholder="Rate Value" value="{{$rate->rate_value}}" />
    </div>
</div>
