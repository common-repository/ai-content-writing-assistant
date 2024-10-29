(function ($) {
    'use strict';

    $(document).on("click", ".aiwa-create-response", function (e) {
        e.preventDefault();

        var datas = {
            'action': 'aiwa_generate_ai_response',
            'rc_nonce': aiwa.nonce,
            //'post2': '',
        };

        $.ajax({
            url: aiwa.ajax_url,
            data: datas,
            type: 'post',
            dataType: 'json',

            beforeSend: function () {

            },
            success: function (r) {
                if (r.success) {
                    console.log(r.response);


                } else {
                    console.log('Something went wrong, please try again!');
                }

            }, error: function () {

            }
        });
    });
})(jQuery);