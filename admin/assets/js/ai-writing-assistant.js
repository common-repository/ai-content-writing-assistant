
(function ($) {
    'use strict';

    $(document).on("click", ".nav-item .nav-link", function(e){
        e.preventDefault();

        $('.nav-item .nav-link').removeClass('active');
        $('.tab-pane').removeClass('active');
        jQuery(this).addClass('active');

        var data = $(this).data('id');
        $('.'+data).addClass('active');

        /*Add hash to browser address*/
        var url=window.location.href.split('#')[0];
        var to_url=url+"#"+data;
        window.location.href=to_url;

    });


    $(document).on("click", "#aiwa-save-settings", function (e) {
        e.preventDefault(); // prevent the form from being submitted
        var formData = $('#ai-settings-form').serialize(); // serialize the form data

        var datas = {
            'action': 'ai_writing_assistant_save_settings',
            'rc_nonce': aiwa.nonce,
            'formData': formData,
        };

        var select = $('[name="ai-image-size"]').val();
        var custom = $('[name="custom-ai-image-size"]').val();
        var regex = /^\d+x\d+$/; // Regular expression to check for "XxY" format

        if (select == 'custom' && !regex.test(custom)) { // If input does not match the regular expression
            alert("Input must be in the format like '100x100'"); //todo
            return false; // Prevent form submission
        }

        $.ajax({
            url: aiwa.ajax_url,
            data: datas,
            type: 'post',
            dataType: 'json',

            beforeSend: function () {
            },
            success: function (r) {
                if (r.success) {
                    $('#aiwa-save-settings').siblings('.badge').removeClass('aiwa-hidden');
                    setTimeout(function(){
                        $('#aiwa-save-settings').siblings('.badge').addClass('aiwa-hidden');
                    }, 5000);

                    if ($('#aiwa-placeholders-is-set').val()==="0"){
                        generate_placeholders();
                    }
                } else {
                    console.log('Something went wrong, please try again!'); //Todo

                }

            }, error: function () {
            }
        });

    });

    function generate_placeholders() {
        var datas = {
            'action': 'generate_placeholders',
            'rc_nonce': aiwa.nonce,
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
                    console.log(r);

                } else {
                    console.log('Something went wrong, please try again!');
                }

            }, error: function () {

            }
        });
    }

    jQuery(document).on("click", ".variation-image-item", function () {
        jQuery('.theSingleImage img').attr("src", "");
        var imgSrc = $(this).children('img').attr('src');
        var prompt = $('#prompt-input').val();

        if ( imgSrc === "" ){
            alert("Image is empty"); //todo
            return;
        }
        const words = prompt.split(" "); // split the string into an array of words
        const firstFiveWords = words.slice(0, 5); // extract the first 5 elements of the array

        var fileName = aiwa_cleanFilename(firstFiveWords.join('-'))+'.png';

        var title_element = $('.image-form-container #title');
        var alternative_text_element = $('.image-form-container #alternative_text');
        var caption_element = $('.image-form-container #caption');
        var description_element = $('.image-form-container #description');
        var file_name_element = $('.image-form-container #file_name');

        title_element.val(prompt);
        alternative_text_element.val(prompt);
        caption_element.val(prompt);
        description_element.val(prompt);
        file_name_element.val(fileName.toLowerCase());

        jQuery('.theSingleImage img').attr("src", imgSrc);
        jQuery.tinyModal({
            title: 'Save image to media library', //todo
            html: '#save-image-to-gallery',
            OkButton: "<span class='modal_label'>Save to media</span> <span class=\"aiwa_spinner hide_spin\"></span>", //todo
            successBtnToClose: false,
            OkButtonClass: "saveGeneratedImageToMedia",
            badge: '<span style="font-size: 13px; font-weight: normal;" class="image-saved-to-media-library badge badge-success aiwa-hidden">Image has been saved to media library!</span>' //todo
        });

        if (window.outerHeight > 790){
            $('.tinymodal-content').css({'max-height': '100%'});
        }
        else{
            $('.theSingleImage img').css({'max-width': '300px', 'max-height': '300px'});
        }


        jQuery('.tinymodal-buttons .inner .saveGeneratedImageToMedia').click(function () {
            var title = title_element.val();
            var alternative_text = alternative_text_element.val();
            var caption = caption_element.val();
            var description = description_element.val();
            var file_name = file_name_element.val();
            var img_url = imgSrc;

            $(this).addClass('running');
            $('.saveGeneratedImageToMedia .aiwa_spinner').css({'display': 'inline'});
            $('.saveGeneratedImageToMedia .modal_label').text("Saving to gallery");

            aiwa_ajax_("aiwa_save_image_to_media_library", {"title": title,"alternative_text": alternative_text,"caption": caption,"description": description,"file_name": file_name,"img_url": img_url}).then(function (resposne) {
                $('.saveGeneratedImageToMedia .aiwa_spinner').hide();
                $('.saveGeneratedImageToMedia').removeClass('running');
                $('.image-saved-to-media-library').css({"display": "inline"});
                $('.saveGeneratedImageToMedia .modal_label').text("Saved to media");

                setTimeout(function(){
                    $('.tinymodal-close').click();
                }, 3000);

            }, function () {
                alert("Something went wrong, please try again later.");
            })
        });

    });

    jQuery(document).on("click", ".suggest_titles", function (e) {
        e.preventDefault();

        jQuery('.aiwa_suggested_titles').html('<span class="suggest_titles aiwa_spinner"></span>');
        var title = jQuery(this).closest('.title').find('strong a').text();

        var titles_array = [];
        if (title !== "" && title !== "(no title)"){
            var id = jQuery(this).closest('tr').attr('id');
            var theId = id.split("-")[1];
            jQuery('.title_for_suggestion').text(title);
            aiwa_ajax_("aiwa_suggest_post_titles", {main_title: title}).then(function (response) {
                if (response.success){
                    if ("data" in response){
                        var titles = aiwa_replace_double_quo(aiwa_removeNumbers2(response.data));
                        titles = titles.split("\n");
                        titles_array = titles;
                        var html = "";
                        for (let i = 0; i < titles.length; i++) {
                            var title = titles[i].trim();
                            html += '<div class="aiwa_suggested_title_item"><input id="su_'+i+'" type="radio" name="aiwa_suggest_title" value="'+i+'"><label for="su_'+i+'">'+title+'</div>';
                        }
                        jQuery('.aiwa_suggested_titles').html(html)
                    }

                }

            }, function () {
                jQuery('.tinymodal-close').click();
            })

            jQuery.tinyModal({
                title: 'Suggest titles', //todo
                html: '#suggestion_title_modal',
                OkButton: "Replace", //todo
            });

            jQuery('.tinymodal-buttons .inner button:nth-child(1)').click(function () {
                var selected_title = jQuery('[name="aiwa_suggest_title"]:checked').val();
                var title = titles_array[selected_title];
                var tr_id = 'tr#post-'+theId;

                aiwa_ajax_("aiwa_replace_with_suggested_title", {'id': theId, title: titles_array[selected_title]}).then(function () {
                    jQuery(tr_id).find('.row-title').text(title);
                    jQuery(tr_id).find('.row-title').addClass("aiwa_title_replaced");

                    setTimeout(function(){
                        jQuery(tr_id).find('.row-title').removeClass("aiwa_title_replaced");
                    }, 3000);

                })
            });
        }

    });


})(jQuery);

function aiwa_cleanFilename(filename) {
    // remove all non-alphanumeric characters (except dots and dashes)
    let cleanedFilename = filename.replace(/[^a-zA-Z0-9.-]/g, '');

    // remove any commas
    cleanedFilename = cleanedFilename.replace(/,/g, '');

    // return the cleaned filename
    return cleanedFilename;
}