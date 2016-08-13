<div class="panel panel-default">
    <div class="panel-container">
        <div class="panel-heading collapsed" data-target="#collapseThree" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion_" aria-expanded="false" aria-controls="collapseTwo">
            <h4 class="panel-title">Employees</h4>
        </div>
        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
            <div class="panel-body">
                <div class="panel-content">
                    <table class="table table-striped">
                        <tbody>
                            @foreach($teams->where('project_id',$project->project_id) as $team)
                            @foreach($team->team_member as $member)
                            <tr>
                                <td>
                                    <strong>
                                        {{$member->user->name}}
                                    </strong>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!--End Project Details-->