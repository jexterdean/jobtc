@foreach($jobs as $job)
<div class="row">
    <div id="job-{{$job->id}}" class="portlet">
        <div class="portlet-header">
            @if($job->photo !== '')
            <img src="{{url($job->photo)}}" class="img-rounded" alt="Job Photo" width="50" height="50">
            @endif
            &nbsp;
            <span>{{$job->title}}&nbsp;({{$job->company['name']}})</span>
        </div>
        <div class="portlet-content">
            <div class="company-info">

            </div>
            <div class="job-info">
                {!! $job->description !!}
            </div>
            <div class="job-options pull-right">
                <a href="#" class="btn btn-edit btn-sm btn-shadow apply-to-job"> Apply</a>
                <input class="job_id" type="hidden" value="{{$job->id}}"/>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    $(".column").sortable({
        connectWith: ".column",
        handle: ".portlet-header",
        cancel: ".portlet-toggle",
        placeholder: "portlet-placeholder ui-corner-all"
    });

    $(".portlet")
            .addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
            .find(".portlet-header")
            .addClass("ui-widget-header ui-corner-all")
            .prepend("<span class='ui-icon ui-icon-minusthick portlet-toggle'></span>");

    $(".portlet-toggle").on("click", function () {
        var icon = $(this);
        icon.toggleClass("ui-icon-minusthick ui-icon-plusthick");
        icon.closest(".portlet").find(".portlet-content").toggle();
    });
</script>
