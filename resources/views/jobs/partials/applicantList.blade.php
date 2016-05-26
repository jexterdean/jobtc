<div class="applicant-list-container container-fluid">
    @if($applicants->total() > 1)
    <div class="text-center hidden-sm hidden-xs">
        <ul class="pagination job-applicant-list-pager">
            @if($applicants->currentPage() > 1)
            <li><a class="pager-previous" href="{{$applicants->previousPageUrl()}}" rel="previous">Previous</a></li> 
            @endif
            @for($i = 1; $i <= $applicants->lastPage(); $i++)
            @if($i === $applicants->currentPage())
            <li class="active"><a id="pager-item-{{$i}}" class="pager-item" href="{{$applicants->url($i)}}">{{$i}}</a></li>
            @else
            <li><a id="pager-item-{{$i}}" class="pager-item" href="{{$applicants->url($i)}}">{{$i}}</a></li>
            @endif
            @endfor
            @if($applicants->currentPage() < $applicants->lastPage())
            <li><a class="pager-next" href="{{$applicants->nextPageUrl()}}" rel="next">Next</a></li>
            @endif
        </ul>
    </div>
    <div class="text-center hidden-lg hidden-md">
        <ul class="pagination job-applicant-list-pager">
            @if($applicants->currentPage() > 1)
            <li><a class="pager-previous-mobile" href="{{$applicants->previousPageUrl()}}" rel="previous">Previous</a></li> 
            @endif
            @for($i = 1; $i <= $applicants->lastPage(); $i++)
            @if($i === $applicants->currentPage())
            <li class="active"><a id="pager-item-mobile-{{$i}}" class="pager-item-mobile" href="{{$applicants->url($i)}}">{{$i}}</a></li>
            @else
            <li><a id="pager-item-mobile-{{$i}}" class="pager-item-mobile" href="{{$applicants->url($i)}}">{{$i}}</a></li>
            @endif
            @endfor
            @if($applicants->currentPage() < $applicants->lastPage())
            <li><a class="pager-next-mobile" href="{{$applicants->nextPageUrl()}}" rel="next">Next</a></li>
            @endif
        </ul>
    </div>
    @endif
    {!! csrf_field() !!}
    @unless($applicants->count())
    <div class="no-applicants-notifier">No applicants for this job yet.</div>
    @else
    <div class="applicant-list-table">
        <div class="row">
            <div class="col-xs-12">
                <a href="#" class="btn btn-default pull-right hidden-lg hidden-md close-applicant-list-mobile"><i class="fa fa-times"></i></a>
            </div>
        </div>
        @foreach($applicants as $applicant)
        <div id="applicant-{{$applicant->id}}" class="row applicant-row">
            @if(ends_with($applicant->resume,'pdf'))
            <div class="col-xs-3">
                <!--a target="_blank" href="https://docs.google.com/viewer?url={{url($applicant->resume)}}" class="applicant-resume"><img class="applicant-photo" src="{{url($applicant->photo)}}"/></a-->
                <a target="_blank" href="{{url('/a/'.$applicant->id)}}" class="applicant-resume"><img class="applicant-photo" src="{{url($applicant->photo)}}"/></a>

            </div>
            <div class="col-xs-9">
                <!--a target="_blank" href="https://docs.google.com/viewer?url={{url($applicant->resume)}}" class="applicant-resume">{{$applicant->first_name}}&nbsp{{$applicant->last_name}}</a-->
                <a target="_blank" href="{{url('/a/'.$applicant->id)}}" class="applicant-resume">{{$applicant->name}}</a>
                <br />
                <a href="tel:{{$applicant->phone}}" class="applicant-phone">{{$applicant->phone}}</a>
                <br />
                <a href="mailto:{{$applicant->email}}" class="applicant-email">{{$applicant->email}}</a>
                <br>
                <text class="applicant-post-date">{{date_format(date_create($applicant->created_at),'M d,Y')}}</text>
                <br />
                <textarea class="status-container">
                    @if(isset($applicant->tags[0]))
                    {{$applicant->tags[0]->tags}}
                    @endif
                </textarea>
                <input class="job_id" type="hidden" value="{{$applicant->job_id}}" />
                <input class="applicant_id" type="hidden" value="{{$applicant->id}}" />
            </div>
            @else
            <div class="col-xs-3">
                <!--a target="_blank" href="http://view.officeapps.live.com/op/view.aspx?src={{url($applicant->resume)}}" class="applicant-resume"><img class="applicant-photo" src="{{url($applicant->photo)}}"/></a-->
                <a target="_blank" href="{{url('/a/'.$applicant->id)}}" class="applicant-resume"><img class="applicant-photo" src="{{url($applicant->photo)}}"/></a>
            </div>
            <div class="col-xs-9">
                <!--a target="_blank" href="http://view.officeapps.live.com/op/view.aspx?src={{url($applicant->resume)}}" class="applicant-resume">{{$applicant->first_name}}&nbsp{{$applicant->last_name}}</a-->
                <a target="_blank" href="{{url('/a/'.$applicant->id)}}" class="applicant-resume">{{$applicant->name}}</a>
                <br />
                <a href="tel:{{$applicant->phone}}" class="applicant-phone">{{$applicant->phone}}</a>
                <br />
                <a href="mailto:{{$applicant->email}}" class="applicant-email">{{$applicant->email}}</a>
                <br>
                <text class="applicant-post-date">{{date_format(date_create($applicant->created_at),'M d,Y')}}</text>
                <br />
                <textarea class="status-container">
                    @if(isset($applicant->tags[0]))
                    {{$applicant->tags[0]->tags}}
                    @endif
                </textarea>
                <input class="job_id" type="hidden" value="{{$applicant->job_id}}" />
                <input class="applicant_id" type="hidden" value="{{$applicant->id}}" />
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endunless
</div>