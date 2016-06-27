<div class="mini-space"></div>
<div class="row">
    <div class="col-md-12">
        <ul id="my_jobs_tabs" class="nav nav-tabs">
            <li class="my_jobs_tab active"><a data-toggle="pill" href="#my_jobs_list">My Jobs</a></li>
            <li class="shared_jobs_tab"><a data-toggle="pill" href="#shared_jobs_list">Shared Jobs</a></li>
        </ul>
        <div class="tab-content">
            <div id="my_jobs_list" class="tab-pane fade in active">
                @foreach($my_jobs->chunk(2) as $chunk)
                <div class="row">
                    @foreach($chunk as $job)
                    <div class="col-md-6">
                        <div  class="box box-default">
                            <div class="box-container">
                                <div class="box-header">
                                    <h3 class="box-title">
                                        <a target="_blank" href="{{url('job/'.$job->id)}}">{{$job->title}}</a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            <div id="shared_jobs_list" class="tab-pane fade in">
                  @foreach($shared_jobs->chunk(2) as $chunk)
                <div class="row">
                    @foreach($chunk as $job)
                    <div class="col-md-6">
                        <div  class="box box-default">
                            <div class="box-container">
                                <div class="box-header">
                                    <h3 class="box-title">
                                        <a target="_blank" href="{{url('job/'.$job->id)}}">{{$job->title}}</a>
                                    </h3>
                                    <div class="pull-right">
                                        <div class="row">
                                        <label>Shared by {{$job->user->name}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>