<form actions="" method="POST" class="edit-job-form form-horizontal" role="form" novalidate="novalidate">
    {!! csrf_field() !!}
    <div class="form-group">
        <div class="col-md-12">
            <input class="form-control title" name="title" type="text" placeholder="Title" value="{{$job->title}}" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <textarea id="edit-description" class="form-control description" name="description">{{$job->description}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            @if(isset($job->photo))
            <img class="profile-pic" src="{{url($job->photo)}}"/>
            @endif
            <input class="form-control" name="photo" type="file" value="" />
        </div>
    </div>
</form>