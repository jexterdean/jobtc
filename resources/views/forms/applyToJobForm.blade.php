<form actions="" method="post" class="apply-to-job-form form-horizontal" role="form" novalidate="novalidate">
    {!! csrf_field() !!}
    <input type="hidden" name="remember" value="forever" />
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="name">Name</label>
            <div class="col-md-10">
                @if(isset($applicant->name))
                <input class="form-control last_name" name="name" type="text" value="{{$applicant->name}}" />
                @else
                <input class="form-control last_name" name="name" type="text" value="" />
                @endif
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="description">Email</label>
            <div class="col-md-10">
                @if(isset($applicant->email))
                <input class="form-control email" name="email" type="text" value="{{$applicant->email or ''}}" />
                @else
                <input class="form-control email" name="email" type="text" value="" />
                @endif
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="phone">Phone</label>
            <div class="col-md-10">
                @if(isset($applicant->email))
                <input class="form-control phone" name="phone" type="text" value="{{$applicant->phone or ''}}" />
                @else
                <input class="form-control phone" name="phone" type="text" value="" />
                @endif
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="resume">Resume</label>
            <div class="col-md-10">
                <input class="form-control" name="resume" type="file" value="" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label col-md-2" for="photo">Photo</label>
            <div class="col-md-10">
                @if(isset($applicant->photo))
                <img class="profile-pic" src="{{url($applicant->photo)}}"/>
                @endif
                <input class="form-control" name="photo" type="file" value="" />
            </div>
        </div>
    </div>
</form>