var ajaxurl = object.ajaxurl;

jQuery(document).ready(function($) {

    $('#add_real_estate_form').submit(function (event) {
        event.preventDefault();
        var data = new FormData(this);
        var file_data = jQuery('#real_estate_img').prop('files')[0];
        data.append('file', file_data);
        data.append('action', 'my_action');

        var ajax_req = {
            url: ajaxurl, // there on the admin side, do-it-yourself on the front-end
            data: data,
            type: 'POST',
            cache: false,
            processData: false,
            traditional: false,
            contentType: false,
            success: function (response) {
                $("#success_msg_text").html("Объект добавлен");
                $("#success_msg_box").css('display', 'block');
            },
        };

        $.ajax(ajax_req).fail(function () {
            $("#error_msg_text").html("Что-то пошло не так :(");
            $("#error_msg_box").css('display', 'block');
        });
    });

    $('#close_primary_box_button').click(function () {
        $("#success_msg_box").css('display', 'none');
    });

    $('#close_error_box_button').click(function () {
        $("#error_msg_box").css('display', 'none');
    });

});