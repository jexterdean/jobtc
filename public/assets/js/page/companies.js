/* 
 * Companies Page scripts
 */


//For Click toggle
$.fn.clickToggle = function(func1, func2) {
        var funcs = [func1, func2];
        this.data('toggleclicked', 0);
        this.click(function() {
            var data = $(this).data();
            var tc = data.toggleclicked;
            $.proxy(funcs[tc], this)();
            data.toggleclicked = (tc + 1) % 2;
        });
        return this;
    };
  
  

//For Dragging employees to projects
$('.taskgroup-list').sortable({
    dropOnEmpty: true,
    connectWith: ".taskgroup-list",
    handle: '.drag-handle',
    remove: function (event, ui) {
        //Don't remove item when dropped to the project list group
        $(this).append($(ui.item).clone());
    },
    receive: function (event, ui) {

        project_id = $(this).siblings().val();
        list_group_user_id = $(ui.item).attr('id');
        user_id = list_group_user_id.split('-').pop();

        //var identicalItemCount = $("#project-" + project_id + ' .list-group').children('.list-group-item:contains(' + ui.item.text() + ')').length;

        var identicalItemCount = $("#project-" + project_id + ' .list-group').children('li:contains(' + ui.item.find('.user_id').val() + ')').length;
        
        console.log(identicalItemCount);
        //If a duplicate, remove it
        if (identicalItemCount > 1) {
            $("#project-" + project_id + ' .list-group').children('li:contains(' + ui.item.find('.user_id').val() + ')').remove();
        }

        //Show unassign button
        $(ui.item).find('.unassign-member').removeClass('hidden');
        //Remove Edit profile button
        $(ui.item).find('.edit-profile').remove();

        //Create Team
        team_url = public_path + 'createTeam';
        team_data = {
            'project_id': project_id,
            'user_id': user_id
        };

        $.post(team_url, team_data,function(data){
            //Assign the team id to the this list group item's input
            //$(ui.item).find('.team_id').val(team_id);
            $(ui.item).find('.profile-container').html(data);
        });

        //Remove warning that no employee is assigned.
        $(this).find('li:contains("No Employees assigned to this project.")').remove();


    },
    update: function (event, ui) {

    }
});


//For dragging tests to applicants or jobs

$('.job-applicant-list').sortable({
    dropOnEmpty: true,
    connectWith: ".job-applicant-list",
    handle: '.drag-handle',
    remove: function (event, ui) {
        //Don't remove item when dropped to the project list group
        $(this).append($(ui.item).clone());
    },
    receive: function (event, ui) {
        
        test_id = $(ui.item).find('.test_id').val();
        list_group_applicant_id = $(ui.item).parent().attr('id');
        applicant_id = list_group_applicant_id.split('-').pop();

        //var identicalItemCount = $("#project-" + project_id + ' .list-group').children('.list-group-item:contains(' + ui.item.text() + ')').length;

        var identicalItemCount = $("#applicant-" + applicant_id + ' .list-group').children('li:contains(' + ui.item.find('.test_id').val() + ')').length;
        
        //If a duplicate, remove it
        if (identicalItemCount > 1) {
            $("#applicant-" + applicant_id + ' .list-group').children('li:contains(' + ui.item.find('.test_id').val() + ')').remove();
        }

        //Show unassign button
        $(ui.item).find('.unassign-test').removeClass('hidden');
        
        //Assign Test to Job
        test_url = public_path + 'assignTestToApplicant';
        test_data = {
            'test_id': test_id,
            'applicant_id': applicant_id
        };

        $.post(test_url, test_data,function(data){
            //Assign the team id to the this list group item's input
            //$(ui.item).find('.team_id').val(team_id);
            $(ui.item).find('.profile-container').html(data);
        });

        //Remove warning that no employee is assigned.
        $(this).find('li:contains("No Tests assigned to this applicant.")').remove();

    },
    update: function (event, ui) {

    }
});

/*Unassign Test from Applicant*/
$('.job-applicant-list').on('click', '.unassign-test', function () {
    var list_item = $(this).parent().parent().parent();
    
    list_item.remove();
});


/*Unassign Team Member from project*/
$('.list-group').on('click', '.unassign-member', function () {

    var list_item = $(this).parent().parent().parent().parent();

    //Remove the element immediately
    list_item.remove();

    var user_id = $(this).parent().find('.user_id').val();
    var team_id = $(this).parent().find('.team_id').val();
    var project_id = $(this).parent().find('.project_id').val();

    data = {
        'team_id': team_id,
        'user_id': user_id,
        'project_id': project_id
    };

    url = public_path + 'unassignTeamMember';

    $.post(url, data);
});


/*Edit Profile of an employee*/
$('.list-group').on('click', '.edit-profile', function () {

    //Get the user profile id
    var user_id = $(this).parent().parent().parent().attr('id').split('-').pop();

    $('#profile-collapse-' + user_id).collapse('show');

    //Name element
    var name_element = $(this).parent().siblings().find('.name');
    var name_text = $(this).parent().siblings().find('.name').text();

    //Email element
    var email_element = $(this).parent().parent().parent().find('.email');
    var email_text = $(this).parent().parent().parent().find('.email').text();

    //Phone element
    var phone_element = $(this).parent().parent().parent().find('.phone');
    var phone_text = $(this).parent().parent().parent().find('.phone').text();

    //Skype element
    var skype_element = $(this).parent().parent().parent().find('.skype');
    var skype_text = $(this).parent().parent().parent().find('.skype').text();

    //Address 1 element
    var address_1_element = $(this).parent().parent().parent().find('.address_1');
    var address_1_text = $(this).parent().parent().parent().find('.address_1').text();

    //Address 2 element
    var address_2_element = $(this).parent().parent().parent().find('.address_2');
    var address_2_text = $(this).parent().parent().parent().find('.address_2').text();

    //Zipcode element
    var zipcode_element = $(this).parent().parent().parent().find('.zipcode');
    var zipcode_text = $(this).parent().parent().parent().find('.zipcode').text();

    //Country element
    var country_element = $(this).parent().parent().parent().find('.country');
    var country_dropdown = $(this).parent().parent().parent().find('.country-dropdown');
    var country_text = $(this).parent().parent().parent().find('.country').text();

    //Facebook element
    var facebook_element = $(this).parent().parent().parent().find('.facebook');
    var facebook_text = $(this).parent().parent().parent().find('.facebook').text();

    //Linkedin element
    var linkedin_element = $(this).parent().parent().parent().find('.linkedin');
    var linkedin_text = $(this).parent().parent().parent().find('.linkedin').text();

    //Name Editor
    var name_ele = '<input type="text" name="name" class="form-control edit-name" placeholder="Edit Name" value="' + name_text + '"/>';

    //Password Editor
    var password_ele = '<input type="password" name="password" class="form-control edit-password" placeholder="Edit Password" value=""/>';;
    
    var password_ele = '<div class="text-area-content">';
    password_ele += '<div class="input-group">';
    password_ele += '<span class="input-group-addon" id="password-addon" ><i class="fa fa-lock" aria-hidden="true"></i></span>';
    password_ele += '<input type="password" name="password" class="form-control edit-password" placeholder="Enter New Password" aria-describedby="password-addon" value=""/>';
    password_ele += '</div>'; //input-group
    password_ele += '</div>';
    
    //Email Editor
    var email_ele = '<div class="text-area-content">';
    email_ele += '<div class="input-group">';
    email_ele += '<span class="input-group-addon" id="email-addon" ><i class="fa fa-envelope-o" aria-hidden="true"></i></span>';
    email_ele += '<input type="text" name="email" class="form-control edit-email" placeholder="Edit Email" aria-describedby="email-addon" value="' + email_text + '"/>';
    email_ele += '</div>'; //input-group
    email_ele += '</div>';

    //Phone Editor
    var phone_ele = '<div class="text-area-content">';
    phone_ele += '<div class="input-group">';
    phone_ele += '<span class="input-group-addon" id="phone-addon" ><i class="fa fa-phone-square" aria-hidden="true"></i></span>';
    phone_ele += '<input type="text" name="email" class="form-control edit-phone" placeholder="Edit Phone Number" aria-describedby="phone-addon" value="' + phone_text + '"/>';
    phone_ele += '</div>'; //input-group
    phone_ele += '</div>';

    //Skype Editor
    var skype_ele = '<div class="text-area-content">';
    skype_ele += '<div class="input-group">';
    skype_ele += '<span class="input-group-addon" id="skype-addon" ><i class="fa fa-skype" aria-hidden="true"></i></span>';
    skype_ele += '<input type="text" name="skype" class="form-control edit-skype" placeholder="Edit Skype" aria-describedby="skype-addon" value="' + skype_text + '"/>';
    skype_ele += '</div>'; //input-group
    skype_ele += '</div>';

    //Address 1 Editor
    var address_1_ele = '<div class="text-area-content">';
    address_1_ele += '<div class="input-group">';
    address_1_ele += '<span class="input-group-addon" id="address-1-addon" ><i class="fa fa-map-marker" aria-hidden="true"></i></span>';
    address_1_ele += '<input type="text" name="skype" class="form-control edit-address-1" placeholder="Edit Address 1" aria-describedby="address-1-addon" value="' + address_1_text + '"/>';
    address_1_ele += '</div>'; //input-group
    address_1_ele += '</div>';

    //Address 2 Editor
    var address_2_ele = '<div class="text-area-content">';
    address_2_ele += '<div class="input-group">';
    address_2_ele += '<span class="input-group-addon" id="address-2-addon" ><i class="fa fa-map-marker" aria-hidden="true"></i></span>';
    address_2_ele += '<input type="text" name="skype" class="form-control edit-address-2" placeholder="Edit Address 2" aria-describedby="address-2-addon" value="' + address_2_text + '"/>';
    address_2_ele += '</div>'; //input-group
    address_2_ele += '</div>';

    //Zipcode Editor
    var zipcode_ele = '<div class="text-area-content">';
    zipcode_ele += '<div class="input-group">';
    zipcode_ele += '<span class="input-group-addon" id="zipcode-addon" ><i class="fa fa-map-marker" aria-hidden="true"></i></span>';
    zipcode_ele += '<input type="text" name="skype" class="form-control edit-zipcode" placeholder="Edit Zipcode" aria-describedby="zipcode-addon" value="' + zipcode_text + '"/>';
    zipcode_ele += '</div>'; //input-group
    zipcode_ele += '</div>';

    //Facebook Editor
    var facebook_ele = '<div class="text-area-content">';
    facebook_ele += '<div class="input-group">';
    facebook_ele += '<span class="input-group-addon" id="facebook-addon" ><i class="fa fa-facebook-square" aria-hidden="true"></i></span>';
    facebook_ele += '<input type="text" name="skype" class="form-control edit-facebook" placeholder="Edit Facebook" aria-describedby="facebook-addon" value="' + facebook_text + '"/>';
    facebook_ele += '</div>'; //input-group
    facebook_ele += '</div>';

    //Linkedin Editor
    var linkedin_ele = '<div class="text-area-content">';
    linkedin_ele += '<div class="input-group">';
    linkedin_ele += '<span class="input-group-addon" id="linkedin-addon" ><i class="fa fa-linkedin-square" aria-hidden="true"></i></span>';
    linkedin_ele += '<input type="text" name="skype" class="form-control edit-linkedin" placeholder="Edit Skype" aria-describedby="linkedin-addon" value="' + linkedin_text + '"/>';
    linkedin_ele += '</div>'; //input-group
    linkedin_ele += '</div>';

    var save_button_ele = '<br /><button class="btn btn-submit btn-shadow btn-sm update-profile" type="button">Save & Close</button>&nbsp;&nbsp;&nbsp;';

    name_element.css({'display': 'none'}).before(name_ele);
    email_element.css({'display': 'none'}).before(email_ele);
    email_element.after(password_ele);
    phone_element.css({'display': 'none'}).before(phone_ele);
    skype_element.css({'display': 'none'}).before(skype_ele);
    address_1_element.css({'display': 'none'}).before(address_1_ele);
    address_2_element.css({'display': 'none'}).before(address_2_ele);
    zipcode_element.css({'display': 'none'}).before(zipcode_ele);

    country_element.css({'display': 'none'});
    country_dropdown.removeClass('hidden');

    facebook_element.css({'display': 'none'}).before(facebook_ele);
    linkedin_element.css({'display': 'none'}).before(linkedin_ele);

    //Append Save button to the last element
    linkedin_element.after(save_button_ele);

});

$('.list-group').on('click', '.update-profile', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    var user_id = $(this).parent().parent().parent().attr('id').split('-').pop();

    $('#profile-collapse-' + user_id).collapse('hide');

    var name_container = $('#profile-'+user_id);
    var profile_container = $(this).parent();
    
    var name_ele = name_container.find('.name');
    var email_ele = profile_container.find('.email');
    var phone_ele = profile_container.find('.phone');
    var skype_ele = profile_container.find('.skype');
    var address_1_ele = profile_container.find('.address_1');
    var address_2_ele = profile_container.find('.address_2');
    var zipcode_ele = profile_container.find('.zipcode');
    var country_ele = profile_container.find('.country');
    var facebook_ele = profile_container.find('.facebook');
    var linkedin_ele = profile_container.find('.linkedin');

    var name = name_container.find('.edit-name').val().trim();
    var email = profile_container.find('.edit-email').val().trim();
    var password = profile_container.find('.edit-password').val().trim();
    var phone = profile_container.find('.edit-phone').val().trim();
    var skype = profile_container.find('.edit-skype').val().trim();
    var address_1 = profile_container.find('.edit-address-1').val().trim();
    var address_2 = profile_container.find('.edit-address-2').val().trim();
    var zipcode = profile_container.find('.edit-zipcode').val().trim();
    var country_id = profile_container.find('.country-dropdown').find('.edit-country').val().trim();
    var country = profile_container.find('.country-dropdown').find('.edit-country option:selected').text().trim();
    var facebook = profile_container.find('.edit-facebook').val().trim();
    var linkedin = profile_container.find('.edit-linkedin').val().trim();

    var data = [];

    data.push(
            {'name': 'user_id', 'value': user_id},
    {'name': 'name', 'value': name},
    {'name': 'password', 'value': password},
    {'name': 'email', 'value': email},
    {'name': 'phone', 'value': phone},
    {'name': 'skype', 'value': skype},
    {'name': 'address_1', 'value': address_1},
    {'name': 'address_2', 'value': address_2},
    {'name': 'zipcode', 'value': zipcode},
    {'name': 'country_id', 'value': country_id},
    {'name': 'facebook', 'value': facebook},
    {'name': 'linkedin', 'value': linkedin}
    );

    $.post(public_path + '/updateProfile', data);
    
    name_container.find('.edit-name').remove();
    name_container.find('.text-area-content').remove();
    profile_container.find('.update-profile').remove();
    profile_container.find('.country-dropdown').addClass('hidden');
    
    name_ele.removeAttr('style').html(name);
    email_ele.removeAttr('style').html('<i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;'+email);
    phone_ele.removeAttr('style').html('<i class="fa fa-phone-square" aria-hidden="true"></i>&nbsp;'+phone);
    skype_ele.removeAttr('style').html('<i class="fa fa-skype" aria-hidden="true"></i>&nbsp;'+skype);
    address_1_ele.removeAttr('style').html('<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+address_1);
    address_2_ele.removeAttr('style').html('<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+address_2);
    zipcode_ele.removeAttr('style').html('<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+zipcode);
    country_ele.removeAttr('style').html('<i class="fa fa-globe" aria-hidden="true"></i>&nbsp;'+country);
    facebook_ele.removeAttr('style').html('<i class="fa fa-facebook-square" aria-hidden="true"></i>&nbsp;'+facebook);
    linkedin_ele.removeAttr('style').html('<i class="fa fa-linkedin-square" aria-hidden="true"></i>&nbsp;'+linkedin);
});

$('.list-group').on('click','.task-permission',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            
            var user_id = $(this).children('.user_id').val();
            var task_id = $(this).children('.task_id').val();
            var project_id = $(this).children('.project_id').val();

            var assign_html = '<i class="fa fa-check" aria-hidden="true"></i>';
            assign_html += '<input class="user_id" type="hidden" value="'+user_id+'"/>';
            assign_html += '<input class="task_id" type="hidden" value="'+task_id+'"/>';
            assign_html += '<input class="project_id" type="hidden" value="'+project_id+'"/>';
                                                                
            var unassign_html = '<i class="fa fa-plus" aria-hidden="true"></i>';
            unassign_html += '<input class="user_id" type="hidden" value="'+user_id+'"/>';
            unassign_html += '<input class="task_id" type="hidden" value="'+task_id+'"/>';
            unassign_html += '<input class="project_id" type="hidden" value="'+project_id+'"/>';
            
            /*Assign the Task List to this user*/
            if ($(this).hasClass('bg-gray')) {
                $(this).switchClass('bg-gray', 'bg-green', function () {
                    $(this).html(assign_html);
                    assignTask(user_id,task_id,project_id);
                });
            }
            /*Unassign the Task List from this user*/
            if ($(this).hasClass('bg-green')) {
                $(this).switchClass('bg-green', 'bg-gray', function () {
                    $(this).html(unassign_html);
                    unassignTask(user_id,task_id,project_id);
                });
            }
            
        });

$('.name').clickToggle(function(){
    $(this).css('font-size','23px');
},
function(){
    $(this).attr('style','');
});

//General Functions
function removeDuplicates(listName, newItem) {
    var dupl = false;
    $("#" + listName + " > li").each(function () {
        if ($(this)[0] !== newItem[0]) {
            if ($(this).html() === newItem.html()) {
                dupl = true;
            }
        }
    });

    return !dupl;
}

function assignTask(user_id,task_id,project_id) {
    
    var url = public_path + 'assignTaskList';
    
    var data = {
        'user_id':user_id,
        'task_id': task_id,
        'project_id': project_id
    };
    
    $.post(url,data);
}

function unassignTask(user_id,task_id,project_id) {
    
    var url = public_path + 'unassignTaskList';
    
    var data = {
        'user_id':user_id,
        'task_id': task_id,
        'project_id': project_id
    };
    
    $.post(url,data);
}

