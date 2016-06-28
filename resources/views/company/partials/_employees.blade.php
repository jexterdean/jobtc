<div class="employee-container">
    @foreach($profiles->chunk(2) as $chunk)
    <div class="row employee-row">
        @foreach($chunk as $profile)
        <div class="col-md-6 employee-column">
            <div class="row">
                <div class="col-md-9">
                    <a class="profile-toggle" data-toggle="collapse" href="#profile-collapse-{{$profile->user->user_id}}">
                        <i class="pull-left" aria-hidden="true">
                            @if($profile->user->photo === '' || $profile->user->photo === NULL)
                            <img class="employee-photo" src="{{url('assets/user/default-avatar.jpg')}}" />
                            @else
                            <img class="employee-photo" src="{{url($profile->user->photo)}}"/>
                            @endif
                        </i>
                        <div class="employee-details">
                            <div class="name">{{$profile->user->name}}</div>
                            <div class="email">{{$profile->user->email}}</div>
                            <div class="phone">{{$profile->user->phone}}</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>
<div class="mini-space"></div>
<div class="row">
    <div class="employee_tab_options">
        <a href="#" id="add-employee" class="btn btn-shadow btn-default add-employee">
            <i class="fa fa-plus"></i> 
            <strong>New Employee</strong>
        </a>
        <input class="company_id" type="hidden" value="{{$company_id}}"/>
    </div>
</div>

<script>
    /*Add Employee*/
    $('#employees').on('click', '#add-employee', function (e) {
        e.stopImmediatePropagation();
        $(this).addClass('disabled');

        var url = public_path + 'addEmployeeForm';
        var employee_container = $('.employee-container');

        $.get(url, function (data) {
            employee_container.append(data);
        });
    });

    $('#employees').on('click', '.save-employee', function (e) {
        e.stopImmediatePropagation();
        var url = public_path + 'addEmployee';
        var employee_container = $('.employee-container');
        var company_id = $('.employee_tab_options').find('.company_id').val();

        console.log(company_id);

        var data = {
            'name': $('input[name="employee-name"]').val(),
            'email': $('input[name="employee-email"]').val(),
            'password': $('input[name="employee-password"]').val(),
            'company_id': company_id
        };

        $.post(url, data, function (data) {
            $('#add-employee-form').remove();
            $('#add-employee').removeClass('disabled');
            var employee_count = employee_container.find('.employee-row').last().children().length;

            if (employee_count === 1) {
                employee_container.find('.employee-row').last().append(data);
            } else {
                employee_container.append('<div class="row employee-row">' + data + '</div>');
            }


        });
    });

    $('#employees').on('click', '.cancel-employee', function (e) {
        e.stopImmediatePropagation();
        $('#add-employee').removeClass('disabled');
        $('#add-employee-form').remove();
    });
</script>
