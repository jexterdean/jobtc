<div class="row">
    <div class="col-md-6">
        @foreach($profiles as $profile)
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">{{$profile->user->name}}</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul id="user-{{$profile->user_id}}" class="job-list-group list-group">
                            @foreach($jobs as $job)
                            @foreach($shared_jobs->where('user_id',$profile->user_id) as $shared_job)
                            @if($shared_job->job_id === $job->id)
                            <li id="job-{{$job->id}}" class="list-group-item">
                                <div class="row">
                                    <div class="col-md-9">
                                        <i class="pull-left fa fa-chevron-down" aria-hidden="true"></i>
                                        {{$job->title}}
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" class="drag-handle">
                                            <i class="fa fa-arrows"></i>
                                        </a>
                                        <a href="#" class="unshare-job">
                                            <i class="fa fa-times"></i>
                                            <input class="job_id" type="hidden" value="{{$job->id}}"/>
                                            <input class="user_id" type="hidden" value="{{$profile->user_id}}"/>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-md-6">
        <div id="jobs-container" class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">Jobs</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="job-list-group list-group">
                            @foreach($jobs as $job)
                            <li id="job-{{$job->id}}" class="list-group-item">
                                <div class="row">
                                    <div class="col-md-9">
                                        <i class="pull-left fa fa-chevron-down" aria-hidden="true"></i>
                                        {{$job->title}}
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" class="drag-handle">
                                            <i class="fa fa-arrows"></i>
                                        </a>
                                        <a href="#" class="unshare-job hidden">
                                            <i class="fa fa-times"></i>
                                            <input class="job_id" type="hidden" value="{{$job->id}}"/>
                                            <input class="user_id" type="hidden" value=""/>
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