/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$('.status-container').tagEditor({
    maxTags: 9999,
    placeholder: 'Enter tags ...',
    autocomplete: {
        delay: 0, // show suggestions immediately
        position: { collision: 'flip' }, // automatic menu position up/down
        source: public_path +'getAvailableTags'
    },
    onChange: function (field, editor, tags) {
        var ajaxurl = public_path + 'addTag';

        var job_id;
        var applicant_id;

        //For Single page applicant
        if ($('.add-comment-form').length > 0) {

            job_id = $('input[name=job_id]').val();
            applicant_id = $('input[name=applicant_id]').val();

        }
        
        //For Multiple applicants page
        if ($('.applicant-list-table').length > 0) {

            parent_container = '#' + $(field).parent().parent().attr('id');

            job_id = $(parent_container).find('.job_id').val();
            applicant_id = $(parent_container).find('.applicant_id').val();

        }

        var token = $('input[name=_token]').val();
        var formData = new FormData();
        formData.append('job_id', job_id);
        formData.append('applicant_id', applicant_id);
        formData.append('tags', tags);
        formData.append('_token', token);
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: formData,
            // THIS MUST BE DONE FOR FILE UPLOADING
            contentType: false,
            processData: false,
            beforeSend: function () {
            },
            success: function (data) {
            },
            error: function (xhr, status, error) {

            }
        }); //ajax
        //alert(tags);
    }
});

$(".submit-comment").click(function (e) {
    e.preventDefault();
    var applicant_id = $('input[name=applicant_id]').val();
    var ajaxurl = public_path + '/addComment';
    var form = $(".add-comment-form")[0];
    var formData = new FormData(form);
    formData.append('module','applicant');
    formData.append('send_email', $('.email-comment').is(':checked'));

    if ($.trim($(".comment-textarea").val())) {
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: formData,
            // THIS MUST BE DONE FOR FILE UPLOADING
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.comment-textarea').attr('disabled', 'disabled');
                $('.submit-comment').attr('disabled', 'disabled');
            },
            success: function (data) {
                //$('.no-comment-notifier').remove();
                $('.comment-textarea').val("");
                $('.comment-textarea').attr('disabled', false);
                $('.submit-comment').attr('disabled', false);
                //socket.emit('applicant-comment', data);
                $('#comment-list-'+applicant_id).prepend(data);
            },
            error: function (xhr, status, error) {

                var errorDialog = new BootstrapDialog({
                    title: 'Fields Required',
                    message: xhr,
                    buttons: [{
                            label: 'Ok',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }]
                }).setType(BootstrapDialog.TYPE_DANGER);

                errorDialog.open();
            }
        }); //ajax
    }
});


//For Applicant Notes
var applicant_notes = CKEDITOR.replace('applicant-notes');

applicant_notes.on('change',function(evt) {
    
    var ajaxurl = public_path + 'saveApplicantNotes';
    var applicant_id = window.location.href.split("/").pop();
    
     var formData = new FormData();
        formData.append('applicant_id', applicant_id);
        formData.append('notes', evt.editor.getData());
      
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: formData,
            // THIS MUST BE DONE FOR FILE UPLOADING
            contentType: false,
            processData: false,
            beforeSend: function () {
            },
            success: function (data) {
            },
            error: function (xhr, status, error) {

            }
        }); //ajax
    
});