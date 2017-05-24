{"changed":false,"filter":false,"title":"profiles.js","tooltip":"/public/assets/js/page/profiles.js","value":"/* \n Profile Page Scripts\n Created on : Jun 6, 2016, 4:41:19 PM\n Author     : Jexter Dean Buenaventura\n */\n\n$('.update-profile').click(function (e) {\n    e.preventDefault();\n\n    var ajaxurl = public_path + 'updateMyProfile';\n    var form = $('.profile-form')[0];\n    var formData = new FormData(form);\n    formData.append('no_password', 'true');\n\n    $.ajax({\n        url: ajaxurl,\n        type: \"POST\",\n        data: formData,\n        // THIS MUST BE DONE FOR FILE UPLOADING\n        contentType: false,\n        processData: false,\n        beforeSend: function () {\n\n        },\n        success: function (data) {\n            $('.profile-form').find('.profile-img').attr('src', public_path + data);\n\n            $('.update-progress').addClass('bg-green');\n\n            $('.update-progress').html('<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;Profile Updated');\n            $('.update-progress').css('display', 'inline');\n            $('.update-progress').fadeOut(5000);\n        },\n        error: function (xhr, status, error) {\n            $('.update-progress').html('<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;Profile Updated');\n            $('.update-progress').addClass('bg-red');\n            $('.update-progress').css('display', 'inline');\n            $('.update-progress').fadeOut(5000);\n        }\n    }); //ajax\n\n});\n\n\n$('.change-password').click(function (e) {\n    e.preventDefault();\n\n    var ajaxurl = public_path + 'changePassword';\n    var password = $('#new_password').val();\n    var formData = new FormData();\n    formData.append('password', password);\n\n    $.ajax({\n        url: ajaxurl,\n        type: \"POST\",\n        data: formData,\n        // THIS MUST BE DONE FOR FILE UPLOADING\n        contentType: false,\n        processData: false,\n        beforeSend: function () {\n\n        },\n        success: function (data) {\n            $('.update-password').html('<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;Password Updated');\n            $('.update-password').css('display', 'inline');\n            $('.update-password').fadeOut(5000);\n            $('.change-password-form input[type=\"password\"]').val('');\n        },\n        error: function (xhr, status, error) {\n            $('.update-password').html('<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;Password Updated');\n            $('.update-password').addClass('bg-red');\n            $('.update-password').css('display', 'inline');\n            $('.update-password').fadeOut(5000);\n            $('.change-password-form input[type=\"password\"]').val('');\n        }\n    }); //ajax\n});\n\nvalidateChangePassword();\n\nfunction validateChangePassword() {\n\n    var ajaxurl = public_path + '/checkPassword';\n\n    $(\".change-password-form\").validate({\n        rules: {\n            password: {\n                required: true,\n                remote: {\n                    url: ajaxurl,\n                    type: 'POST',\n                    data: {\n                        password: function () {\n                            console.log($('#current_password').val());\n                            return $('#current_password').val();\n                        }\n                    }\n                }\n            },\n            new_password: {\n                required: \"#current_password:filled\"\n            },\n            new_password_confirmation: {\n                required: \"#new_password:filled\",\n                equalTo: \"#new_password\"\n            }\n        },\n        messages: {\n            password: {\n                required: \"\",\n                remote: \"Wrong password\"\n            },\n            new_password: {\n                required: \"Fill in your current password first\"\n            },\n            new_password_confirmation: {\n                required: \"\",\n                equalTo: \"Passwords don't match\"\n            }\n\n        }\n    }).form();\n\n    //Enable save button when email is valid\n    $('.change-password-form').on('keyup blur', function () { // fires on every keyup & blur\n        if ($('.change-password-form').valid()) { // checks form for validity\n            $('.change-password').attr('disabled', false);\n        } else {\n            $('.change-password').attr('disabled', 'disabled');\n        }\n    });\n\n}","undoManager":{"mark":-1,"position":-1,"stack":[]},"ace":{"folds":[],"scrolltop":0,"scrollleft":0,"selection":{"start":{"row":0,"column":0},"end":{"row":0,"column":0},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":0},"timestamp":1494336850445}