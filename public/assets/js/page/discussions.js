/* 
 * Discussion Page scripts
 */

$('.create-room').click(function (e) {
    //e.preventDefault();
    //e.stopImmediatePropagation();
    var job_id = $(this).siblings('.job_id').val();
    var create_discussions_room_form = public_path + '/discussions/create';

    BootstrapDialog.show({
        title: 'Create Discussion Room',
        size: 'size-normal',
        message: function (dialog) {
            var $message = $('<div></div>');
            var pageToLoad = dialog.getData('pageToLoad');
            $message.load(pageToLoad);
            return $message;
        },
        buttons: [{
                label: 'Save',
                cssClass: 'btn-edit btn-shadow',
                action: function (dialog) {
                    var ajaxurl = public_path + 'discussions';

                    var formData = new FormData();
                    var room_name = $('.room_name').val();
                    formData.append('room_name', room_name);

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

                            var json_data = JSON.parse(data);
                            var row = "<tr><td>" + json_data.room_name + "</td><td><a target='_blank' href='" + public_path + 'discussions/' + json_data.id + "' class='btn btn-success'>Join</a></td></tr>";
                            $('.table').append(row);
                            dialog.close();

                        },
                        error: function (xhr, status, error) {

                        }
                    }); //ajax
                }
            }, {
                label: 'Close',
                cssClass: 'btn-delete btn-shadow',
                action: function (dialog) {
                    dialog.close();
                }
            }],
        data: {
            'pageToLoad': create_discussions_room_form
        },
        onshown: function (ref) {
            //initCkeditor(ref);
        },
        closable: false
    });
});

