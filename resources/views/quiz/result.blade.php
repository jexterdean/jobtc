<div class="panel-group" id="accordion_" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-container">
            <div class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-target="#task-details" data-parent="#accordion_" aria-expanded="true">
                <h4 class="panel-title">
                    Test List
                </h4>
            </div>
            <div id="task-details" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <div class="panel-content">
                        <table class="table table-hover table-striped">
                            @if(count($test) > 0)
                                @foreach($test as $v)
                                    <tr>
                                        <td>{{ $v->title }}</td>
                                        <td>{{ 'Ave. '. $v->average }}</td>
                                    </tr>
                                @endforeach
                            @else
                                 <tr>
                                    <td>No data was found.</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-container">
            <div class="panel-heading" data-toggle="collapse" data-target="#test-result">
                <h4 class="panel-title">
                    Test Results
                </h4>
            </div>
            <div id="test-result" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="panel-content">
                        <table class="table table-hover table-striped">
                            @if(count($result) > 0)
                                @foreach($result as $v)
                                    <tr>
                                        <td><strong>{{ $v->title }}</strong></td>
                                        <td><em> - {{ $v->name }}</em></td>
                                        <td>{{ $v->score . '/' . $v->total_question }}</td>
                                        <td>
                                            <a href="{{ url('quiz/' . $v->test_id . '?p=review') }}">
                                                <i class="fa fa-external-link" style="font-size: 2em;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                 <tr>
                                    <td>No data was found.</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>