@extends('layouts.default')
@section('content')
<div class="job-posting-container">
    <div class="row">
        @if(!Auth::check())
        <div class="col-md-12">
            @else    
            <div class="col-md-6">
                @endif    
                <div class="job-header">
                    <input name="job_id" class="job_id" type="hidden" value="{{$job->id}}"/>
                </div>
                @if(!Auth::check())    
                <div class="job-logged-out">
                    @else
                    <div class="job">
                        @endif
                        <div class="job-info row">
                            <div class="col-md-12">
                                <div class="media">
                                    @if(!Auth::check())
                                    <input class="btn btn-assign btn-shadow btn-lg pull-right apply-to-job" type="button" value="Apply"/>
                                    @endif
                                    <div class="media-middle">
                                        <a target="_blank" href="{{url('job/'.$job->id)}}">
                                            @if($job->photo !== '')
                                            <img class="job-photo" src="{{url($job->photo)}}" alt="Job Photo">
                                            @endif
                                        </a>
                                        @if(Auth::check())
                                        <div class="pull-right">
                                            @if($job->applicants->count() > 1)
                                            <a class="view-applicants hidden-sm hidden-xs"><i class="fa fa-user"></i>&nbsp;&nbsp;{{$job->applicants->count()}} Applicants</a>
                                            <a class="view-applicants-mobile hidden-lg hidden-md"><i class="fa fa-user"></i>&nbsp;&nbsp;{{$job->applicants->count()}} Applicants</a>
                                            @elseif ($job->applicants->count() === 1)
                                            <a class="view-applicants hidden-sm hidden-xs"><i class="fa fa-user"></i>&nbsp;&nbsp;{{$job->applicants->count()}} Applicant</a>
                                            <a class="view-applicants-mobile hidden-lg hidden-md"><i class="fa fa-user"></i>&nbsp;&nbsp;{{$job->applicants->count()}} Applicant</a>
                                            @elseif ($job->applicants->count() === 0)
                                            <a class="view-applicants hidden-sm hidden-xs"><i class="fa fa-user"></i>&nbsp;&nbsp;No Applicants</a>
                                            <a class="view-applicants-mobile hidden-lg hidden-md"><i class="fa fa-user"></i>&nbsp;&nbsp;No Applicants</a>
                                            @endif
                                            <input name="job_id" class="job_id" type="hidden" value="{{$job->id}}"/>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="media-body">
                                        <text class="media-heading"><a target="_blank" href="{{url('job/'.$job->id)}}">{{$job->title}}</a></text>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mini-space"></div>
                        <div class="row tasklist-row">
                            <div id="applicant-list-{{$count}}" class="applicant-list col-md-12">
                                <p class="job-description">{!! $job->description !!}</p>
                                <input class="token" name="_token" type="hidden" value="{{csrf_token()}}">
                            </div>
                            @if(Auth::user()->level() === 1 || Auth::user()->user_id === $job->user_id)
                            <div class="row">
                                <div class="col-md-12">
                                    <div  class="box box-default">
                                        <div class="box-container">
                                            <div class="box-header" id="notes-{{$job->id}}" data-toggle="collapse" data-target="#notes-collapse-{{ $job->id }}">
                                                <h3 class="box-title">Notes</h3>
                                            </div>
                                            <div class="box-body">
                                                <div id="notes-collapse-{{ $job->id }}" class="box-content collapse">
                                                    <textarea id="job-notes">{{$job->notes}}</textarea> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="mini-space"></div>
                    </div>
                    <div class="mini-space"></div>
                    @if(Auth::check())
                    <div class="job-header pull-right">
                        <a class="btn btn-edit btn-lg btn-shadow edit-job" data-toggle="modal"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                        <a class="btn btn-delete btn-lg btn-shadow delete-job"><i class="fa fa-trash-o"></i>&nbsp;Delete</a>
                        <input name="job_id" class="job_id" type="hidden" value="{{$job->id}}"/>
                    </div>
                    @endif
                </div>
                @if(Auth::check())
                <div class="job-header">
                    <div>&nbsp;</div>
                </div>
                <div class="col-md-6 hidden-sm hidden-xs job-applicant-list-container">
                    @include('jobs.partials.applicantList')
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

