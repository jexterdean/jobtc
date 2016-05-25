<div class="row">
    <div class="col-md-6">
        @foreach($jobs as $job)
        <div id="job-{{$job->id}}" class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">{{$job->title}}</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><i class="fa fa-users" aria-hidden="true"></i>&nbsp;All Applicants</div>
                            <div class="panel-body">
                                <ul class="job-applicant-list list-group">
                                    <li class="list-group-item">Drag a test here to make it available for all applicants in this job posting.</li>
                                </ul>
                            </div>
                        </div>

                        <ul class="list-group">
                            @foreach($job->applicants as $applicant)
                            <li class="bg-gray list-group-item">
                                <div class="panel panel-primary">
                                    <div class="panel-heading"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;{{$applicant->name}}</div>
                                    <div class="panel-body">
                                        <ul id="applicant-{{$applicant->id}}" class="job-applicant-list list-group">
                                            @if($test_applicants
                                            ->where('applicant_id',$applicant->id)
                                            ->count() > 0)

                                            @foreach($tests as $test)
                                            @foreach($test_applicants as $test_applicant)
                                            @if($test->id === $test_applicant->test_id 
                                                && $applicant->id === $test_applicant->applicant_id)
                                            <li class="bg-gray list-group-item">
                                                <i class="fa fa-chevron-down" aria-hidden="true"></i>&nbsp;{{$test->title}}
                                                <div class="pull-right">
                                                    <div class="btn-group pull-right">
                                                        <a href="#" class="drag-handle">
                                                            <i class="fa fa-arrows"></i>
                                                        </a>
                                                        <a href="#" class="unassign-test hidden">
                                                            <i class="fa fa-times"></i>
                                                            <input class="test_id" type="hidden" value="{{$test->id}}"/>
                                                            <input class="applicant_id" type="hidden" value=""/>
                                                            <input class="job_id" type="hidden" value=""/>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                            @endif
                                            @endforeach
                                            @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul> 
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-md-6">
        <div  class="box box-default tests">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">Tests</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="job-applicant-list list-group">
                            @foreach($tests as $test)
                            <li class="bg-gray list-group-item">
                                <i class="fa fa-chevron-down" aria-hidden="true"></i>&nbsp;{{$test->title}}
                                <div class="pull-right">
                                    <div class="btn-group pull-right">
                                        <a href="#" class="drag-handle">
                                            <i class="fa fa-arrows"></i>
                                        </a>
                                        <a href="#" class="unassign-test hidden">
                                            <i class="fa fa-times"></i>
                                            <input class="test_id" type="hidden" value="{{$test->id}}"/>
                                            <input class="applicant_id" type="hidden" value=""/>
                                            <input class="job_id" type="hidden" value=""/>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>