/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$('.edit-job').click(function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var job_id = $(this).siblings('.job_id').val();
    var edit_job_form = public_path +'/job/' + job_id + '/edit';

    /*BootstrapDialog.show({
        title: 'Edit Job',
        size: 'size-normal',
        message: function (dialog) {
            var $message = $('<div></div>');
            var pageToLoad = dialog.getData('pageToLoad');
            $message.load(pageToLoad);
            return $message;
        },
        buttons: [{
                label: 'Save',
                action: function (dialog) {
                    var ajaxurl = public_path +'/updateJob/'+job_id;
                    var form = $(".edit-job-form")[0];
                    
                    console.log(JSON.stringify(form));
                    var formData = new FormData(form);
                    formData.append('job_id', job_id);
                    
                    var $button = this; // 'this' here is a jQuery object that wrapping the <button> DOM element.
                    $button.disable();
                    $button.spin();

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

                            var errorDialog = new BootstrapDialog({
                                title: 'Fields Required',
                                message: data,
                                buttons: [{
                                        label: 'Ok',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }]
                            }).setType(BootstrapDialog.TYPE_DANGER);

                            var successDialog = new BootstrapDialog({
                                title: 'Success',
                                message: data,
                                buttons: [{
                                        label: 'Ok',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }]
                            }).setType(BootstrapDialog.TYPE_SUCCESS);

                            if (data.toString() === "Job Added" || data.toString() === "Job Updated") {
                                successDialog.open();
                                dialog.close();
                            } else {
                                errorDialog.open();
                                $button.stopSpin();
                                $button.enable();
                            }

                        },
                        error: function (xhr, status, error) {

                            var errorDialog = new BootstrapDialog({
                                title: 'Fields Required',
                                message: status,
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
            }, {
                label: 'Close',
                action: function (dialog) {
                    dialog.close();
                }
            }],
        data: {
            'pageToLoad': edit_job_form
        },
        onshown: function () {

        },
        closable: false
    });*/
});

$('.delete-job').click(function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var job_id = $(this).siblings('.job_id').val();

    BootstrapDialog.confirm('Are you sure you want to delete this job?', function (result) {
        if (result) {
            var ajaxurl = public_path +'/job/'+job_id;
            var formData = new FormData();
            formData.append('job_id', job_id);
            formData.append('_token', $('.applicant-list').find('.token').val());

            $.ajax({
                url: ajaxurl,
                type: "DELETE",
                data: formData,
                // THIS MUST BE DONE FOR FILE UPLOADING
                contentType: false,
                processData: false,
                beforeSend: function () {

                },
                success: function (data) {

                    var errorDialog = new BootstrapDialog({
                        title: 'Fields Required',
                        message: data,
                        buttons: [{
                                label: 'Ok',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }]
                    }).setType(BootstrapDialog.TYPE_DANGER);

                    var successDialog = new BootstrapDialog({
                        title: 'Success',
                        message: data,
                        buttons: [{
                                label: 'Ok',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }]
                    }).setType(BootstrapDialog.TYPE_SUCCESS);

                    if (data.toString() === "Job Deleted") {
                        successDialog.open();
                    } else {
                        errorDialog.open();
                        $button.stopSpin();
                        $button.enable();
                    }

                },
                error: function (xhr, status, error) {

                    var errorDialog = new BootstrapDialog({
                        title: 'Fields Required',
                        message: status,
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

        } else {

        }
    });

});

$(".apply-to-job").click(function () {

    var apply_to_job_form = public_path + "/applyToJobForm";
    var job_id = $('.job_id').val();
    //var token = $('meta[name=csrf-token]').attr('content');
    //var token = $('.applicant-list').find('.token').val();

    BootstrapDialog.show({
        title: 'Apply to Job',
        size: 'size-normal',
        message: function (dialog) {
            var $message = $('<div></div>');
            var pageToLoad = dialog.getData('pageToLoad');
            $message.load(pageToLoad);
            return $message;
        },
        buttons: [{
                label: 'Save',
                action: function (dialog) {
                    var ajaxurl = public_path +'/applyToJob';
                    var form = $(".apply-to-job-form")[0];
                    var formData = new FormData(form);
                    formData.append('job_id', job_id);
                    formData.append('_token', $('.apply-to-job-form').find('.token').val());

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

                            var errorDialog = new BootstrapDialog({
                                title: 'Fields Required',
                                message: data,
                                buttons: [{
                                        label: 'Ok',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }]
                            }).setType(BootstrapDialog.TYPE_DANGER);

                            var successDialog = new BootstrapDialog({
                                title: 'Success',
                                message: data,
                                buttons: [{
                                        label: 'Ok',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }]
                            }).setType(BootstrapDialog.TYPE_SUCCESS);

                            if (data.toString() === "Application Submitted") {
                                successDialog.open();
                                dialog.close();
                            } else {
                                errorDialog.open();
                            }

                        },
                        error: function (xhr, status, error) {

                            var errorDialog = new BootstrapDialog({
                                title: 'Fields Required',
                                message: error,
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
            }, {
                label: 'Close',
                action: function (dialog) {
                    dialog.close();
                }
            }],
        data: {
            'pageToLoad': apply_to_job_form
        },
        onshown: function () {

        },
        closable: false
    });
});

$('.status-container').tagEditor({
    maxTags: 9999,
    placeholder: 'Enter tags ...',
    autocomplete: {
        delay: 0, // show suggestions immediately
        position: { collision: 'flip' }, // automatic menu position up/down
        source: public_path +'/getAvailableTags'
    },
    onChange: function (field, editor, tags) {
        var ajaxurl = public_path + '/addTag';

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