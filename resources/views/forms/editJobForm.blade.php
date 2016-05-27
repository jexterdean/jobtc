<form actions="" method="POST" class="edit-job-form form-horizontal" role="form" novalidate="novalidate">
    {!! csrf_field() !!}
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="title">Title</label>
            <div class="col-md-10">
                <input class="form-control title" name="title" type="text" value="{{$job->title}}" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="description"></label>
            <div class="col-md-10">
                <textarea id="edit-description" class="form-control description" name="description">{{$job->description}}</textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="photo">Photo</label>
            <div class="col-md-10">
                @if(isset($job->photo))
                <img class="profile-pic" src="{{url($job->photo)}}"/>
                @endif
                <input class="form-control" name="photo" type="file" value="" />
            </div>
        </div>
    </div>
</form>