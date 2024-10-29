(function ($) {
    'use strict';

    var totalGenerated = 0;
    $(document).on("click", ".generate-ai-content", function (e) {
        e.preventDefault();

        totalGenerated = 0;

        var prompt = $('#prompt-input').val();

        if (!$('#aiwa-generate-ai-content .aiwa_spinner').hasClass('hide_spin')){
            return;
        }

        if (prompt===""){
            $('.empty-prompt.badge').fadeIn(300);
            setTimeout(function(){
                $('.empty-prompt.badge').fadeOut(300);
            }, 4000);

            return;
        }

        var session_key = generateKey();
        $('#generation_session_key').val(session_key);

        var first_prompt = prompt;

        var inputs = $('#aiwa-ai-inputs').serialize();
        let params = Object.fromEntries(new URLSearchParams(inputs));
        Object.assign(params, {'session_key': session_key});

        if ("aiwa_auto_content_settings" in params){
            setInitInputValues();
            $('#aiwa-generate-ai-content span.title').text("Generating titles"); //todo
            generatePostTitles(params, prompt);
            setTimeout(function () {
                $('#ai-response').slideDown(500);
            }, 1000);

            return;
        }

        if ("image_generation_mode" in params){
            setInitInputValues();
            generate_image_from_image_generation_page(params)

            return;
        }

        if ("select-title-before-generate" in params && 'aiwa_is_title_selected' in params && params['aiwa_is_title_selected'] === "0"){

            setInitInputValues();
            $('#aiwa-generate-ai-content span.title').text("Generating titles"); //todo
            generatePostTitlesBeforeGenerate(params, prompt);
            setTimeout(function () {
                $('#ai-response').slideDown(500);
            }, 1000);

            return;
        }

        generate_init(params);

        if ('auto-generate-image' in params && params['auto-generate-image'] === 'on'){
            if ($('.aiwa-single-generation-page').length){
                generate_image(prompt, 'true');
            }
            else{
                generate_image(prompt);
            }
        }

        generate_contents(params, first_prompt, prompt);

    });


    function generate_contents(params, first_prompt, prompt) {
        if ("generate-title" in params && ("select-title-before-generate" in params) !== true) {
            /*Getting the title*/
            Object.assign(params, {"type": "title", "first_prompt": first_prompt});

            aiwa_ajax(prompt, params, function (content) {
                $('.blog-title h1').html(aiwa_getTitle(content));
            }, 'title')

                .then(function (res) {
                    $('.blog-title h1').html(aiwa_getTitle(res));

                    if ($('#aiwa_title').val().length > 0){
                        prompt = $('#aiwa_title').val();
                    }

                    /*If call-to-action set to the start of the content*/

                    if ("add-call-to-action" in params && params['call-to-action-position'] === 'start') {
                        Object.assign(params, {"type": "call_to_action"});

                        aiwa_ajax(prompt, params, function (response) {
                            $('.cta-start.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                        }, 'call_to_action').then(function (response) {
                            $('.cta-start.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));

                            /*Is introduction has selected*/
                            if ("add-introduction" in params) {
                                if ("add-introduction-text" in params) {
                                    add_introduction_text();
                                }

                                Object.assign(params, {"type": "introduction"});
                                aiwa_ajax(prompt, params, add_introduction, 'introduction').then(function (response) {
                                    totalGenerated +=1;
                                    $('.introduction .rc_loader').remove();
                                    $('.introduction p').html(remove_first_br(unescapeHTML(response)));
                                    scrollToBottom();

                                    content_structure_request_and_rest(prompt, params);
                                })
                            } else {
                                content_structure_request_and_rest(prompt, params);
                            }
                        })
                    } else { //if call-to-action not set
                        /*Is introduction has selected*/
                        if ("add-introduction" in params) {

                            if ("add-introduction-text" in params) {
                                add_introduction_text();
                            }
                            Object.assign(params, {"type": "introduction"});
                            aiwa_ajax(prompt, params, add_introduction, 'introduction').then(function (response) {
                                totalGenerated +=1;
                                $('.introduction p').html(remove_first_br(unescapeHTML(response)));
                                scrollToBottom();

                                content_structure_request_and_rest(prompt, params);
                            })
                        } else {
                            content_structure_request_and_rest(prompt, params);
                        }
                    }


                })
        } else {
            if ("add-call-to-action" in params && params['call-to-action-position'] === 'start') {
                Object.assign(params, {"type": "call_to_action"});

                aiwa_ajax(prompt, params, function (response) {
                    $('.cta-start.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                }, 'call_to_action').then(function () {

                    /*Is introduction has selected*/
                    if ("add-introduction" in params) {

                        if ("add-introduction-text" in params) {
                            add_introduction_text();
                        }
                        Object.assign(params, {"type": "introduction"});
                        aiwa_ajax(prompt, params, add_introduction, 'introduction').then(function () {
                            content_structure_request_and_rest(prompt, params);
                        })
                    } else {
                        content_structure_request_and_rest(prompt, params);
                    }
                })
            } else { //if call-to-action not set
                /*Is introduction has selected*/
                if ("add-introduction" in params) {

                    if ("add-introduction-text" in params) {
                        add_introduction_text();
                    }
                    Object.assign(params, {"type": "introduction"});
                    aiwa_ajax(prompt, params, add_introduction, 'introduction').then(function () {
                        content_structure_request_and_rest(prompt, params);
                    })
                } else {
                    content_structure_request_and_rest(prompt, params);
                }
            }
        }
    }
    
    function generate_image_from_image_generation_page(params) {

        var prompt = $('#prompt-input').val();
        Object.assign(params, {"prompt": prompt});

        $('#ai-response').slideDown(300);

        var html = '';
        var number_of_image = parseInt(params['number_of_image']);
        for (let i = 0; i < number_of_image; i++) {
            html += '<div class="variation-image-item aiwa-image-not-set"><img src=""></div>';
        }
        var el = $('.aiwa-images .aiwa-image-variation-items');
        if ($('#aiwa-new-images-with-existing').is(":checked")){
            el.html(el.html()+html);
        }
        else{
            el.html(html);
        }

        aiwa_ajax_("aiwa_generate_variation_images", params).then(function (response) {
            generation_finished();

            if (typeof response.data != "undefined"){
                var images = response.data;
                //var html = '<div class="aiwa-image-variation-items">';

                //console.log(images)
                $('.aiwa-image-not-set').each(function (i, v) {
                    $(this).find('img').attr('src', images[i].url)
                    /*if (typeof images[i].url != "undefined"){
                        html += '<div class="variation-image-item"><img src="' + images[i].url + '"></div>';
                    }*/
                });
                //html += '</div>';

                $('.variation-image-item').removeClass("aiwa-image-not-set");
                //$('.aiwa-images').html(html);
            }
            else{
                $('.aiwa-image-not-set').remove();
                generate_canceled();
                alert("Something went wrong! please try again later."); //todo
            }


        }, function (e) {
            $('.aiwa-image-not-set').remove();
                generate_canceled();
            }
        )
    }

    function generatePostTitles(params, prompt) {
        /*Getting the title*/
        Object.assign(params, {"type": "generate_titles"});

        aiwa_ajax(prompt, params, function (content) {
            //$('.aiwa-titles').html(aiwa_getTitle(content));
        }, 'generate_titles').then(function (response) {
            create_list(response)
            generation_finished();
        });
    }

    function generatePostTitlesBeforeGenerate(params, prompt) {
        /*Getting the title*/

        var titles_count = $('#aiwa-how-many-titles-show-first').val();
        Object.assign(params, {"type": "generate_titles", "titles-count": titles_count});

        aiwa_ajax(prompt, params, function (content) {
            //$('.aiwa-titles').html(aiwa_getTitle(content));
        }, 'generate_titles').then(function (response) {
            create_list_before_generate(response)
            generation_finished();
        })
    }

    function getDateTime(i=1, timestamp=0) {
        var date = getDateByTimesTamp(timestamp);
        var time = getMeridianTime(timestamp);

        return '<label for="publish-start-from-'+i+'"><span>Sheduled</span>\n' + //todo
            '<div class="time-input input-container"><input type="text" class="aiwa-date-picker dd__trigger" name="aiwa_date_picker-'+i+'" data-dd-opt-default-date="'+getDateByTimesTamp(timestamp, '/')+'" value="'+date+'">\n' +
            '<span class="time_tooltip">Use YYYY-M-D format (e.g. 2023-2-1)</span></div>'+
            '<div class="time-input input-container"><input type="text" id="publish-start-from-'+i+'" class="aiwa_time_picker" name="aiwa-time-picker-'+i+'" value="'+time+'">\n' +
            '<span class="time_tooltip">Use 24 hour format (e.g. 14:20)</span></div>'+ //todo
            '</label>';
    }


    function create_list(response) {
        if (response==""){
            alert("Something went wrong, response is empty please try again!"); //todo
            return;
        }
        var post_types = $('.aiwa-exportable-post-types-selectbox').clone();
        var activePostType = $('#aiwa-single-post-type').val();
        post_types.find('option').removeAttr('selected');
        post_types.find('option[value="'+activePostType+'"]').attr('selected', 'selected');
        post_types = post_types.html();


        var categories = $('.aiwa-exportable-categories-selectbox').clone();

        //if ($('#aiwa-single-category:visible').length){ //todo: need to set logic if the post type has category
        var cat = $('#aiwa-single-category:visible').val();
        categories.find('option').removeAttr('selected');

        categories.find('option[value="'+cat+'"]').attr('selected', 'selected');
        //}

        categories = categories.html();
        var titles = remove_first_br(unescapeHTML(response)).split("<br>");
        var html = '<ul class="aiwa-list-items">';

        var dp = "";
        var tp = "";

        var exceeded = false;
        titles.forEach(function (value, i ) {
            if (i > 2){
                exceeded = true;
            }
            var post_types_selectbox = post_types.replace(/aiwa-post-type-selectbox/g, 'aiwa-generated-title-posttype-'+i);
            var categories_selectbox = categories.replace(/aiwa-category-selectbox/g, 'aiwa-generated-title-cat-'+i);
            var title = aiwa_replace_double_quo(aiwa_removeNumbers(value.trim()));
            if (title!=="" && i < 3) {
                var li = '<li class="aiwa-list-item">\n' +
                    '                        <svg class="aiwa-drag-icon" width="24" height="24" viewBox="0 0 24 24">\n' +
                    '                            <path d="M11 18c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm-2-8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm6 4c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>\n' +
                    '                        </svg>\n' +
                    '\n' +
                    '                        <div class="list-sub-item aiwa-list-title" contenteditable="true">\n' +
                    '                            ' + title + '\n' +
                    '                        </div>\n' +
                    '                        <div class="aiwa-list-sub-items">\n' +
                    '                        <div class="list-sub-item aiwa-list-sheduled">\n' +
                    '                            ' + getDateTime(i, getTheTime(i + 1)) +
                    '                        </div>\n' +
                    '                        <div class="list-sub-item aiwa-list-post-type">\n' +
                    '                            ' + post_types_selectbox +
                    '                        </div>\n' +
                    '                        <div class="list-sub-item aiwa-list-post-category">\n' +
                    '                            ' + categories_selectbox +
                    '                        </div>\n' +
                    '                        </div>\n' +
                    '                        <div class="aiwa-remove-icon">\n' +
                    '                            X\n' +
                    '                        </div>\n' +
                    '                    </li>';
                html += li;


                dp += '.aiwa-date-picker-' + i + ',';
                tp += '#publish-start-from-' + i + ',';
            }

        })
        html += '</ul>';

        if (exceeded){
            html += '<h3>You can generate only 3 titles in the free version. Upgrade to Pro for more!</h3>'; //todo
        }

        $('.aiwa-titles').html(html);


        /* dp = dp.replace(/,\s*$/, "");
         tp = tp.replace(/,\s*$/, "");

         const d = new Date();
         let year = d.getFullYear();

         new dateDropper({
             selector: dp,
             expandable: true,
             minYear: year,
             maxYear: 2050,
             defaultDate: true
         });
         $(tp).timepicki();*/

        const removeIcons = document.querySelectorAll(".aiwa-remove-icon");

        removeIcons.forEach(icon => {
            icon.addEventListener("click", e => {
                e.target.parentNode.remove();
                aiwa_change_list_date_time();
            });
        });

        $("ul.aiwa-list-items").dragsort({
            dragSelector: "li",
            placeHolderTemplate: "<li></li>",
            dragEnd: aiwa_change_list_date_time
        });

    }

    function aiwa_change_list_date_time() {
        $('.aiwa-list-items .aiwa-list-item').each(function (i) {
            $(this).find('.aiwa-list-sheduled').html(getDateTime(i, getTheTime(i + 1)));
        });
    }

    function create_list_before_generate(response) {
        if (response==""){
            alert("Something went wrong, empty respopnse, please try again!");
            return;
        }

        var titles = remove_first_br(unescapeHTML(response)).split("<br>");

        var html = '<h2>Select a title to generate a post</h2><ul class="aiwa-before_content_generate-list-items">'; //todo

        var exceeded = false;
        titles.forEach(function (value, i ) {
            var title = aiwa_replace_double_quo(aiwa_removeNumbers(value.trim()));

            if (i > 2){
                exceeded = true;
            }
            if (title!=="" && i < 3){
                var li = '<li class="aiwa-list-item" style="cursor: pointer;" data-cursor="pointer">\n' +
                    '     <div class="list-sub-item aiwa-list-title">\n' +
                    '           '+title+'\n' +
                    '     </div>\n' +
                    '</li>';
                html += li;
            }

        })
        html += '</ul>';

        if (exceeded){
            html += '<h3>You can generate only 3 titles in the free version. Upgrade to Pro for more!</h3>'; //todo
        }

        $('.titles_before_content_generate').html(html);
        $('.titles_before_content_generate').slideDown(300);
    }

    function generate_image(prompt, return_both='false', numberOfImages = 1) {
        var datas = {
            'action': 'aiwa_generate_image',
            'rc_nonce': aiwa.nonce,
            'prompt': prompt,
            'image-size': $('[name="ai-image-size"]').val(),
            'return_both': return_both,
            'numberOfImages': numberOfImages,
        };


        var inputs = $('#aiwa-ai-inputs').serialize();
        let params = Object.fromEntries(new URLSearchParams(inputs));

        Object.assign(datas, params);

        $.ajax({
            url: aiwa.ajax_url,
            data: datas,
            type: 'post',
            dataType: 'json',

            beforeSend: function () {

            },
            success: function (r) {
                if (r.success) {
                    $('.featured-image-generation-complete').fadeIn(300);
                    setTimeout(function(){
                        $('.featured-image-generation-complete').fadeOut(300);
                    }, 5000);
                    if ($('.aiwa-single-generation-page').length){
                        if (r.data.id!==undefined){
                            $('.aiwa-featured-image-section').removeClass('aiwa-hidden');
                            $('.aiwa-featured-image').attr('src', r.data.url);
                            $('.featured_image_id').val(r.data.id);
                        }
                    }
                    else if(wp.media.featuredImage!==undefined){
                        wp.media.featuredImage.set( r.data.id );
                        setTimeout(function(){
                            if ($('.components-button.is-destructive:not(.editor-post-trash)').length){
                                $('.components-button.is-destructive:not(.editor-post-trash)').click();
                                setTimeout(function(){
                                    $('.components-button.editor-post-featured-image__toggle').click();
                                    $('.media-frame').addClass('aiwa-hidden');
                                    $('.button.media-button').click();
                                    $('.media-frame').removeClass('aiwa-hidden');
                                }, 200);
                            }
                            else{
                                $('.components-button.editor-post-featured-image__toggle').click();
                                $('.media-frame').addClass('aiwa-hidden');
                                $('.button.media-button').click();
                                $('.media-frame').removeClass('aiwa-hidden');
                            }

                        }, 500);
                    }


                } else {
                    console.log('Something went wrong, please try again!');
                }

            }, error: function () {

            }
        });
    }


    $(document).on("click", ".aiwa-blog-post", function (e) {
        e.preventDefault();
        $('#aiwa_is_content_scrollable').val("0");
    });

    $(document).on("click", "#aiwa-cancel-btn", function (e) {
        e.preventDefault();
        $('#aiwa_is_generation_cancelled').val("1");
        $('#aiwa-generate-ai-content .aiwa_spinner').addClass('hide_spin');
    });

    function scrollToBottom() {
        if ($('#aiwa_is_content_scrollable').val()==="1"){
            $('.aiwa-blog-post').scrollTop($('.aiwa-blog-post')[0].scrollHeight - $('.aiwa-blog-post').height());
        }
    }
    function add_introduction_text() {
        $('.introduction h2').removeClass('aiwa-hidden');
        scrollToBottom();
        content_streaming($('.introduction_text').val(),$('.introduction h2'));
    }

    function content_streaming(str="", element, speedInMiliSeconds=50) {
        var i = 0;
        var AutoRefresh = setInterval(function () {
            var ii = i+1;
            element.text(str.substring(0, ii));

            if (str.length<=ii){
                clearInterval(AutoRefresh);
            }
            i++;
        }, speedInMiliSeconds);
    }

    function add_introduction(response) {
        totalGenerated +=1;
        if (response.length > 0){
            $('.introduction .rc_loader').remove();
        }
        $('.introduction p').html(remove_first_br(unescapeHTML(response)));
        scrollToBottom();
    }

    function add_conclusion(response) {
        totalGenerated +=1;

        if (response.length > 0){
            $('.conclusion .rc_loader').remove();
        }
        $('.conclusion p').html(remove_first_br(unescapeHTML(response)));
        scrollToBottom()
    }
    function add_conclusion_text() {
        $('.conclusion h2').removeClass('aiwa-hidden');
        scrollToBottom()
        content_streaming($('.conclusion_text').val(),$('.conclusion h2'));
    }

    function generate_init(params) {
        setInitInputValues();

        $('.titles_before_content_generate').slideUp(200);
        $('[name="aiwa_is_title_selected"]').val("0");

        var html = '<button class="extend-blog-post-preview" style="visibility: hidden;"><svg class="extend" fill="#000000" height="32px" width="32px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M469.5,0H44.755C20.697,0,0,18.838,0,42.891v424.745C0,491.689,20.697,512,44.755,512H469.5 c24.059,0,42.5-20.311,42.5-44.364V42.891C512,18.838,493.558,0,469.5,0z M490.213,468.372c0,12.061-9.778,21.84-21.84,21.84 H43.628c-12.063,0-21.84-9.779-21.84-21.84V43.628c0-12.061,9.778-21.84,21.84-21.84h424.745c12.063,0,21.84,9.779,21.84,21.84 V468.372z"></path> </g> </g> <g> <g> <path d="M436.872,65.362H327.936c-6.016,0-10.894,4.872-10.894,10.894s4.878,10.894,10.894,10.894h82.638L298.447,198.91 c-4.255,4.255-4.255,10.963,0,15.218c2.128,2.128,4.915,3.101,7.702,3.101c2.787,0,5.01-1.109,7.138-3.237l111.564-112.176v82.638 c0,6.021,4.878,10.894,10.894,10.894s10.894-4.872,10.894-10.894V75.519C446.638,69.497,442.888,65.362,436.872,65.362z"></path> </g> </g> <g> <g> <path d="M226.42,285.872c-4.255-4.255-11.431-4.439-15.686-0.184L98.043,397.816v-82.638c0-6.021-4.878-10.894-10.894-10.894 s-10.894,4.872-10.894,10.894v108.936c0,0.715,0.638,1.428,0.779,2.133c0.066,0.338,0.474,0.652,0.572,0.979 c0.105,0.351,0.326,0.71,0.467,1.05c0.168,0.404,0.463,0.771,0.675,1.149c0.134,0.239,0.274,0.492,0.427,0.723 c0.8,1.199,1.85,2.231,3.05,3.032c0.234,0.154,0.496,0.261,0.739,0.396c0.375,0.21,0.745,0.434,1.146,0.601 c0.342,0.141,0.701,0.221,1.053,0.327c0.327,0.096,0.641,0.59,0.979,0.657c0.703,0.141,1.419,0.583,2.133,0.583h108.936 c6.016,0,10.894-4.872,10.894-10.894s-4.878-10.894-10.894-10.894h-82.638l112.128-112.495 C230.958,297.208,230.676,290.128,226.42,285.872z"></path> </g> </g> </g></svg><svg class="compress aiwa-hidden" fill="#000000" height="32px" width="32px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.201 512.201" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M509.081,3.193c-4.16-4.16-10.88-4.16-15.04,0l-195.2,195.2V75.086c0-5.333-3.84-10.133-9.067-10.88 c-6.613-0.96-12.267,4.16-12.267,10.56V224.1c0,5.867,4.8,10.667,10.667,10.667h149.013c5.333,0,10.133-3.84,10.88-9.067 c0.96-6.613-4.16-12.267-10.56-12.267H313.881l195.2-195.093C513.241,14.18,513.241,7.353,509.081,3.193z"></path> <path d="M224.174,277.433H75.161c-5.333,0-10.133,3.84-10.88,9.067c-0.96,6.613,4.16,12.267,10.56,12.267h123.627L3.268,493.86 c-4.267,4.053-4.373,10.88-0.213,15.04c4.16,4.16,10.88,4.373,15.04,0.213c0.107-0.107,0.213-0.213,0.213-0.213l195.2-195.093 v123.2c0,5.333,3.84,10.133,9.067,10.88c6.613,0.96,12.267-4.16,12.267-10.56V288.1 C234.841,282.233,230.041,277.433,224.174,277.433z"></path> </g> </g> </g> </g></svg></button>';

        var contentLoaderHtml = '<div class="rc_loader"><div class="rc_loader-bar"></div><div class="rc_loader-bar"></div><div class="rc_loader-bar"></div></div>';

        var title_text = "";
        if ($('.aiwa_selected_title').val() !== ""){
            title_text = $('.aiwa_selected_title').val();
        }

        if ('generate-title' in params || 'select-title-before-generate' in params){
            html += '<div class="blog-title"><h1>'+title_text+'</h1></div>';
        }
        if ('add-call-to-action' in params && params['call-to-action-position'] === 'start'){
            html += '<div class="cta-start cta aiwa-hidden">'+contentLoaderHtml+'</div>';
        }
        if ('add-introduction' in params){
            html += '<div class="introduction"><h2 class="aiwa-hidden"></h2><p></p>'+contentLoaderHtml+'</div>';
        }
        if ('ai-content-structure' in params && params['ai-content-structure']==='topic_wise'){
            html += '<div class="topics"></div>';
        }
        else{
            html += '<div class="ai-generated-contents">'+contentLoaderHtml+'</div>';
        }
        if ('add-conclusion' in params){
            html += '<div class="conclusion aiwa-hidden"><h2 class="aiwa-hidden"></h2><p></p>'+contentLoaderHtml+'</div>';
        }
        if ('add-call-to-action' in params && params['call-to-action-position'] === 'end'){
            html += '<div class="cta-end cta aiwa-hidden">'+contentLoaderHtml+'</div>';
        }
        if ('add-excerpt' in params){
            html += '<div class="excerpt aiwa-hidden"><h2>Excerpt</h2><p></p>'+contentLoaderHtml+'</div>';
        }
        $('.aiwa-blog-post').html(html);

        if ( ("aiwa-language" in params && params['aiwa-language'] !== "en") && ('add-introduction' in params && "add-introduction-text" in params) ||  ('add-conclusion' in params && "add-conclusion-text" in params) ){
            getIntroAndConcText(params['aiwa-language'], params['aiwa-language-text']).then(
                function (e) {
                    var array;
                    if (typeof e.data == "string"){
                        array = JSON.parse(remove_first_br(unescapeHTML(e.data)));
                    }
                    else{
                        array = e;
                    }

                    $('.introduction_text').val(array[0]);
                    $('.conclusion_text').val(array[1]);
                },
                function (error) {
                    console.log("Introduction or conclusion text could not set for unknown reason.")
                }
            )
        }

        empty_html_implements_in_preview_body(params);

        setTimeout(function () {
            $('#ai-response').slideDown(500);
        }, 1000);

        if ($('body').outerWidth() > 782){
            if ( $('.components-button[aria-label="Settings"]').length){
                if($('.edit-post-sidebar').length==0){
                    $('.components-button[aria-label="Settings"]').click();

                    setTimeout(function(){
                        $('.components-button[data-label="Post"]').click();
                    }, 500);
                }
                else{
                    $('.components-button[data-label="Post"]').click();
                }

            }
        }



    }

    function setInitInputValues() {
        $('#aiwa_is_content_scrollable').val("1");
        $('#ai-response').removeClass('aiwa-hidden-important');
        $('.aiwa-cancel-btn').removeClass('aiwa-hidden');
        $('.aiwa-blog-post').removeClass('expandable').html("");
        $('#aiwa_content_structure_completed').val("0")
        //$('[name="aiwa_is_title_selected"]').val("0")

        $('#aiwa-generate-ai-content .title').text('Generating'); //Todo

        $('#aiwa-generate-ai-content .aiwa_spinner').removeClass('hide_spin');
        $('#aiwa_is_generation_cancelled').val("0")
    }

    function generation_finished(){
        $('.aiwa-cancel-btn').addClass('aiwa-hidden');
        $('#aiwa-generate-ai-content .aiwa_spinner').addClass('hide_spin');
        $('#aiwa-generate-ai-content .title').text('Generate'); //Todo
        $('.generation-complete.badge').fadeIn(300);
        $('[name="aiwa_is_title_selected"]').val("0");

        setTimeout(function(){
            $('.generation-complete.badge').fadeOut(300);
        }, 5000);

        $('.aiwa_rating_section').fadeIn(400);
    }

    function content_structure_request_and_rest(prompt, params) {

        if ("ai-content-structure" in params) {
            if (params['ai-content-structure']==='topic_wise'){
                Object.assign(params, {"type" : "topic_wise"});
                aiwa_ajax(prompt, params, null, 'topic_wise').then(function (response) {
                    var topics = getTheListToArray(aiwa_replace_double_quo(remove_first_br(unescapeHTML(response))));

                    Object.assign(params, {"type" : "topic_detailes"});
                    request_topic_detailes_recursively(topics, params, 1);

                    checkForTopicsComplete().then(function () {
                        if ("add-conclusion" in params) {
                            if ("add-conclusion-text" in params) {
                                add_conclusion_text();
                            }
                            $('.conclusion').removeClass('aiwa-hidden');
                            scrollToBottom();

                            Object.assign(params, {"type" : "conclusion"});
                            aiwa_ajax(prompt, params, add_conclusion, 'conclusion').then(function (response) {
                                totalGenerated +=1;
                                $('.conclusion .rc_loader').remove();
                                $('.conclusion p').html(remove_first_br(unescapeHTML(response)));
                                scrollToBottom()

                                if ("add-call-to-action" in params && params['call-to-action-position']==='end') {
                                    Object.assign(params, {"type" : "call_to_action"});


                                    aiwa_ajax(prompt, params, function (response) {
                                        if (response.length > 0){
                                            $('.cta-end.cta .rc_loader').remove();
                                        }
                                        $('.cta-end.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                                    }, 'call_to_action').then(function (response) {
                                        $('.cta-end.cta .rc_loader').remove();
                                        $('.cta-end.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));

                                        if("add-excerpt" in params){
                                            generate_eaxcerpt(prompt, params);
                                        }
                                        else{
                                            generation_finished();
                                        }
                                    })
                                }
                                else if("add-excerpt" in params){
                                    generate_eaxcerpt(prompt, params);
                                }
                                else{
                                    generation_finished();
                                }
                            })
                        }else if ("add-call-to-action" in params && params['call-to-action-position']==='end') {
                            Object.assign(params, {"type" : "call_to_action"});
                            $('.cta-end.cta').removeClass('aiwa-hidden');
                            scrollToBottom();
                            aiwa_ajax(prompt, params, function (response) {
                                if (response.length > 0){
                                    $('.cta-end.cta .rc_loader').remove();
                                }

                                $('.cta-end.cta').html(remove_first_br(unescapeHTML(response)));
                            }, 'call_to_action').then(function (response) {

                                $('.cta-end.cta .rc_loader').remove();
                                $('.cta-end.cta').html(remove_first_br(unescapeHTML(response)));

                                if("add-excerpt" in params){
                                    generate_eaxcerpt(prompt, params);
                                }
                                else{
                                    generation_finished();
                                }
                            })
                        }
                        else if("add-excerpt" in params){
                            generate_eaxcerpt(prompt, params);
                        }
                        else{
                            generation_finished();
                        }
                    })

                })
            }
            else{
                Object.assign(params, {"type" : params['ai-content-structure']});
                aiwa_ajax(prompt, params, function (response) {
                    if (params['ai-content-structure']=="table"||params['ai-content-structure']=="tutorial"||params['ai-content-structure']=="how-to"||params['ai-content-structure']=="analysis"){
                        response = unescapeHTML(response).replace(/<br>/g, "");
                    }
                    else if (params['ai-content-structure']=="interviews"||params['ai-content-structure']=="opinion"||params['ai-content-structure']=="review"||params['ai-content-structure']=="case-study"||params['ai-content-structure']=="guide"||params['ai-content-structure']=="email"||params['ai-content-structure']=="youtube_script"||params['ai-content-structure']=="social_media_post"){
                        response = unescapeHTML(response).replace(/<br><br>/g, "");
                    }
                    else if (params['ai-content-structure']=="article"){
                        response = unescapeHTML(response).replace(/<br><br>/g, "<br>");
                    }
                    else{
                        response = remove_first_br(unescapeHTML(response));
                    }

                    totalGenerated +=1;
                    $('.ai-generated-contents').html(response);
                }, 'not_topic_wise').then(function (response) {
                    totalGenerated +=1;

                    if (params['ai-content-structure']=="table"||params['ai-content-structure']=="tutorial"||params['ai-content-structure']=="how-to"||params['ai-content-structure']=="analysis"){
                        response = unescapeHTML(response).replace(/<br>/g, "");
                    }
                    else if (params['ai-content-structure']=="interviews"||params['ai-content-structure']=="opinion"||params['ai-content-structure']=="review"||params['ai-content-structure']=="case-study"||params['ai-content-structure']=="guide"||params['ai-content-structure']=="email"||params['ai-content-structure']=="youtube_script"||params['ai-content-structure']=="social_media_post"){
                        response = unescapeHTML(response).replace(/<br><br>/g, "");
                    }
                    else{
                        response = remove_first_br(unescapeHTML(response));
                    }

                    $('.ai-generated-contents').html(response);

                    if ("add-conclusion" in params) {
                        if ("add-conclusion-text" in params) {
                            add_conclusion_text();
                        }
                        $('.conclusion').removeClass('aiwa-hidden');
                        scrollToBottom();

                        Object.assign(params, {"type" : "conclusion"});
                        aiwa_ajax(prompt, params, add_conclusion, 'conclusion').then(function (response) {
                            totalGenerated +=1;
                            $('.conclusion p').html(remove_first_br(unescapeHTML(response)));

                            scrollToBottom()
                            if ("add-call-to-action" in params && params['call-to-action-position']==='end') {
                                Object.assign(params, {"type" : "call_to_action"});

                                aiwa_ajax(prompt, params, function (response) {
                                    $('.cta-end.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                                }, 'call_to_action').then(function (response) {
                                    $('.cta-end.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));

                                    generation_finished();
                                })
                            }
                            else{
                                generation_finished();
                            }
                        })
                    }else if ("add-call-to-action" in params && params['call-to-action-position']==='end') {
                        Object.assign(params, {"type" : "call_to_action"});

                        $('.cta-end.cta').removeClass('aiwa-hidden');
                        scrollToBottom();

                        aiwa_ajax(prompt, params, function (response) {
                            if (response.length > 0){
                                $('.cta-end.cta .rc_loader').remove();
                            }

                            $('.cta-end.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                        }, 'call_to_action').then(function (response) {

                            $('.cta-end.cta .rc_loader').remove();
                            $('.cta-end.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));

                            if("add-excerpt" in params){
                                generate_eaxcerpt(prompt, params);
                            }
                            else{
                                generation_finished();
                            }
                        })
                    }
                    else if("add-excerpt" in params){
                        generate_eaxcerpt(prompt, params);
                    }
                    else{
                        generation_finished();
                    }

                })
            }
        }
        //Object.assign(params, {"type" : "content_structure"});
        /*aiwa_ajax(prompt, params, content_structure, 'content_structure').then(function () {

        })*/
    }


    function request_topic_detailes_recursively(topics, params, now) {

        var i = now-1;
        var prompt = topics[i];

        var to = topicsTitleStream(topics[i], i);
        setTimeout(function(){
            $('.topics .topic-'+now+' .rc_loader').removeClass("aiwa-hidden-important");
            scrollToBottom();
            aiwa_ajax(prompt, params, function (response) {
                if (response.length > 0){
                    $('.topics .topic-'+now+' .rc_loader').remove();
                }
                $('.topics .topic-'+now+' p').html(remove_first_br(unescapeHTML(response)));
            }, 'topic_wise').then(function (response) {

                $('.topics .topic-'+now+' .rc_loader').remove();
                $('.topics .topic-'+now+' p').html(remove_first_br(unescapeHTML(response)));

                if (topics.length>now){
                    Object.assign(params, {"type" : "topic_detailes"});
                    request_topic_detailes_recursively(topics, params, now+1);
                }else{
                    $('#aiwa_content_structure_completed').val("1");
                }
            });
            var ii = i+1;
            $('.topics .topic-'+ii+' .move-buttons').removeClass('aiwa-hidden');
        }, to);
    }

    function generate_eaxcerpt(prompt, params) {
        Object.assign(params, {"type" : "excerpt"});
        $('.excerpt').removeClass('aiwa-hidden');
        scrollToBottom();

        aiwa_ajax(prompt, params, function (response) {
            if (response.length > 0){
                $('.excerpt .rc_loader').remove();
            }
            $('.excerpt p').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
        }, 'call_to_action').then(function (response) {
            $('.excerpt .rc_loader').remove();
            $('.excerpt p').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));

            generation_finished();
        })
    }

    function super_fast_generation_mode_request_topic_detailes(topics, params, now) {
        for (let j = 0; j < topics.length; j++) {
            (function (i) {
                var prompt = topics[i];
                var now = (i+1);

                var to = topicsTitleStream(topics[i], i);
                //setTimeout(function(){
                aiwa_ajax(prompt, params, null, 'topic_wise').then(function (response) {
                    totalGenerated +=1;
                    $('.topics .topic-'+now+' p').html(remove_first_br(unescapeHTML(response)));
                })
                $('.topics .topic-'+now+' .move-buttons').removeClass('aiwa-hidden');
                //}, to);
            })(j);
        }


    }

    function add_blog_contents(response) {
        if (params['ai-content-structure']=="table"){
            response = response.replace("<br>", "");
        }

        totalGenerated +=1;
        $('.ai-generated-contents').html(remove_first_br(unescapeHTML(response)));
    }

    function getIntroAndConcText(lang="en", lang_name = 'English', need='introduction') {
        var json = {"en":["Introduction","Conclusion"],"bn":["ভূমিকা","উপসংহার"],"es":["Introducción","Conclusión"],"de":["Einleitung","Schlussfolgerung"],"fr":["Introduction","Conclusion"],"hr":["Uvod","Zaključak"],"it":["Introduzione","Conclusione"],"nl":["Inleiding","Conclusie"],"pl":["Wprowadzenie","Wnioski"],"pt-BR":["Introdução","Conclusão"],"pt-PT":["Introdução","Conclusão"],"vi":["Giới thiệu","Kết luận"],"tr":["Giriş","Sonuç"],"ru":["Введение","Заключение"],"ar":["مقدمة","خاتمة"],"th":["บทนำ","บทสรุป"],"ko":["서론","결론"],"zh-CN":["引言","结论"],"zh-TW":["引言","結論"],"zh-HK":["引言","结论"],"ja":["「はじめに」","「結論」"],"ach":["Entwodiksyon","Konklizyon"],"af":["Inleiding","Gevolgtrekking"],"ak":["Pengantar","Kesimpulan"],"az":["Giriş","Nəticə"],"mk":["Вовед","Заклучок",["Вовед","Заклучок"]],"mn":["Танилцуулга","Дүгнэлт"],"hi":["परिचय","निष्कर्ष"],"sr":["Увод","Закључак"],"tt":["Кереш","Йомгаклау"],"tg":["Муқаддима","Хулоса"],"uk":["Вступ","Висновок"],"id":["Pendahuluan","Kesimpulan"],"ro":["Introducere","Concluzie"],"rm":["Ro-ràdh","Co-dhùnadh"],"sl":["Uvod","Zaključek"],"sk":["Úvod","Záver"],"tk":["Giriş","Netije"],"tw":["Nnianim asɛm","Awiei"],"be":["Уводзіны","Заключэнне"],"bg":["Въведение","Заключение"],"ky":["Кириш","Корутунду"],"kk":["Кіріспе","Қорытынды"],"fil":["Panimula","Konklusyon"],"fo":["Inngangur","Niðurstaða"],"fy":["Ynlieding","Konklúzje"],"ga":["Réamhrá","Conclúid"],"gd":["Ro-ràdh","Co-dhùnadh"],"gn":["Introduzione","Conclusione"],"haw":["Introduction","Hoʻopau"],"bem":["Sumo","Mhedziso"],"rn":["Intangiriro","Umwanzuro"],"xh":["Intshayelelo","Isiphelo"],"zu":["Isingeniso","Isiphetho"],"pa":["ਜਾਣ-ਪਛਾਣ","ਸਿੱਟਾ"]};
        return new Promise((resolve, reject) => {
            if (lang in json) {
                resolve(json[lang]);
            } else {
                aiwa_ajax_('get_intro_and_conc', {'lang': lang, 'lang_name': lang_name}).then(
                    function (e) {
                        resolve(e);
                    },
                    function (error) {
                        reject(new Error('Something is not right!'));
                    }
                )
            }
        })
    }
    function checkForTopicsComplete() {
        return new Promise((resolve, reject) => {
            var intVal = setInterval(function () {
                if ($('#aiwa_content_structure_completed').val() == "1"){
                    resolve('success');
                    clearInterval(intVal);
                }
            }, 3000);
        });
    }

    function topicsTitleStream(string, topic_class) {
        topic_class += 1;
        var splitWords = string.split(' ');
        for (let i = 0; i < splitWords.length; i++) {
            setTimeout(function(){
                $('.topics .topic-'+topic_class+' .topic-heading').append(splitWords[i]+' ');
                $('#aiwa-generate-ai-content .aiwa_spinner').removeClass('hide_spin');
                scrollToBottom()
            }, 100*i);
        }

        return (100*splitWords.length);
    }
    function generateKey() {
        const characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        let key = '';
        for (let i = 0; i < 8; i++) {
            key += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        return key;
    }


    function aiwa_request_content_body(prompt, params) {
        Object.assign(params, {"type":"content_body"});
        aiwa_ajax(prompt, params, function (response) {

        }, 'call_to_action');
    }

    function aiwa_getTitle(html) {
        return aiwa_replace_double_quo(aiwa_remove_html_tags(unescapeHTML(html)));
    }

    function getTheListToArray(string) {
        if (string.includes("<br>")){
            return aiwa_removeNumbers(string).split("<br>");
        }
        else{
            return aiwa_removeNumbers(string).split("</br>");
        }
    }

    function empty_html_implements_in_preview_body(params) {
        if ("ai-content-structure" in params && params['ai-content-structure'] === 'topic_wise'){
            if ("topics-count" in params && params['topics-count'] !== ''){
                var topics = parseInt(params['topics-count']);
                var topics_html = "";
                var topics_heading_tag = 'h2';
                if ("aiwa-topics-tag" in params && params['aiwa-topics-tag'] !== ''){
                    topics_heading_tag = params['aiwa-topics-tag'];
                }

                var contentLoaderHtml = '<div class="rc_loader aiwa-hidden-important"><div class="rc_loader-bar"></div><div class="rc_loader-bar"></div><div class="rc_loader-bar"></div></div>';

                for (let i = 1; i <= topics; i++) {
                    topics_html += '<div class="topic topic-'+i+'"><'+topics_heading_tag+' class="topic-heading"></'+topics_heading_tag+'><div class="move-buttons aiwa-hidden"><button class="move-up" onclick="aiwa_moveUp.call(this)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 14L12 9L17 14H7Z" fill="#000000"></path></svg></button><button class="move-down" onclick="aiwa_moveDown.call(this)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 10L12 15L17 10H7Z" fill="#000000"></path></svg></button></div><p></p>'+contentLoaderHtml+'</div>';
                }

                $('.aiwa-blog-post .topics').html(topics_html);
            }
        }
    }

    function aiwa_ajax(prompt="", ai_inputs, callback=null, currentAction="") {
        if ($('#aiwa_is_generation_cancelled').val() === "1"){
            generate_canceled(ai_inputs['session_key']);
            return;
        }
        if ('session_key' in ai_inputs && ai_inputs['session_key']!==$('#generation_session_key').val()){
            generate_canceled(ai_inputs['session_key']);
            return;
        }

        var datas = {
            'action': 'ai_writing_assistant_ai_data',
            'rc_nonce': aiwa.nonce,
            'prompt': prompt,
        };
        if (Object.keys(ai_inputs).length > 0){
            Object.assign(datas, ai_inputs);
        }

        var ai_response = "";
        var error_message = "";
        return new Promise((resolve, reject) => {
            return $.ajax({
                url: aiwa.ajax_url,
                data: datas,
                type: 'post',
                //dataType: 'json',

                beforeSend: function () {

                },
                xhrFields: {
                    // Getting on progress streaming response
                    onprogress: function (e) {

                        var response = e.target.response;

                        ai_response = response;

                        if (aiwa_isJSON(response)){
                            var json = JSON.parse(response);
                            if ( json.data !== "undefined" && json.data.error !== "undefined"){
                                ai_response = "__error__";
                                error_message = json.data.error.message
                            }
                        }

                        if ($('#aiwa_is_generation_cancelled').val() === "1"){
                            generate_canceled(ai_inputs['session_key']);
                            reject("");
                            return;
                        }
                        if ('session_key' in ai_inputs && ai_inputs['session_key']!==$('#generation_session_key').val()){
                            generate_canceled(ai_inputs['session_key']);
                            reject("");
                            return;
                        }
                        if(callback!==null) callback(response, ai_inputs);

                        scrollToBottom()

                    }

                },
                success: function (e) {

                    if (e.data !== undefined){
                        if ( e.data !== "undefined" && e.data.error !== "undefined"){
                            ai_response = "__error__";
                            error_message = e.data.error.message
                        }
                    }

                    $('#aiwa_recent_task_completed').val(currentAction);

                    if (currentAction==='title'){
                        $('#aiwa_title').val(aiwa_getTitle(ai_response));
                    }
                    if ( ai_response.trim()== "__api-empty__" ){
                        alert("Please enter the API key on the settings panel first."); //todo
                        $('#ai-response').addClass('aiwa-hidden-important');
                        generate_canceled(ai_inputs['session_key']);
                        reject(ai_response);
                    }else if(ai_response.trim()=='__invalid_api_key__'){
                        alert("API key is invalid. You can find your API key at https://platform.openai.com/account/api-keys"); //todo
                        $('#ai-response').addClass('aiwa-hidden-important');
                        generate_canceled(ai_inputs['session_key']);
                        reject(ai_response);
                    }else if(ai_response.trim()=='__insufficient_quota__'){
                        alert("You exceeded your OpenAI's current quota, please check your OpenAI plan and billing details."); //todo
                        $('#ai-response').addClass('aiwa-hidden-important');
                        generate_canceled(ai_inputs['session_key']);
                        reject(ai_response);
                    }else if(ai_response.trim()=='__server_error__'){
                        alert("The OpenAI server had an error while processing your request. Sorry about that!"); //todo
                        $('#ai-response').addClass('aiwa-hidden-important');
                        generate_canceled(ai_inputs['session_key']);
                        reject(ai_response);
                    }else if(ai_response.trim()=='__error__'){
                        alert(error_message);
                        $('#ai-response').addClass('aiwa-hidden-important');
                        generate_canceled(ai_inputs['session_key']);
                        reject(ai_response);
                    }
                    else{
                        resolve(ai_response);
                    }
                    //console.log(ai_response)
                },
                error: function (e) {
                    $('#aiwa-generate-ai-content .aiwa_spinner').addClass('hide_spin');
                    $('#aiwa-generate-ai-content .title').text('Generate'); //Todo
                    if (currentAction==='title'){
                        $('#aiwa_title').val(aiwa_getTitle(ai_response));
                    }
                    reject(ai_response);
                    generate_canceled(ai_inputs['session_key']);
                }
            })
        });

    }

    $(document).on("mousemove", ".aiwa-blog-post", function () {
        if ($(this).innerHeight() > 299){
            $(this).addClass('expandable');
        }else{
            $(this).removeClass('expandable');
        }
    });

    $(document).on("click", ".extend-blog-post-preview .extend", function (e) {
        e.preventDefault();
        $('.aiwa-blog-post.expandable').css({'max-height': 'max-content'});
        $(this).hide();
        $('.extend-blog-post-preview .compress').show();

    });
    $(document).on("click", ".extend-blog-post-preview .compress", function (e) {
        e.preventDefault();
        $('.aiwa-blog-post.expandable').css({'max-height': '400px'});
        $(this).hide();
        $('.extend-blog-post-preview .extend').show();

    });


    function splitWords(string) {
        return string.split(' ');
    }

    $(document).on("input", ".range-input .slider", function () {
        $(this).siblings('label').children('input').val($(this).val());
    });

    $('.range-input label .input-box').keydown(function(event) {
        if(event.keyCode !== 8 && !$.isNumeric(event.key)) {
            event.preventDefault();
        }
    });

    $(document).on("input", ".range-input label .input-box", function () {
        $(this).closest('.range-input').find('.slider').val($(this).val());
        $(this).closest('.range-input').find('.slider').change();
    });


    $(document).on("click", "#aiwa-save-for-future-use", function (e) {
        e.preventDefault();
        var formData = $('#aiwa-ai-inputs').serialize(); // serialize the form data

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
                    $('#aiwa-save-for-future-use').siblings('.badge').removeClass('aiwa-hidden');
                    setTimeout(function(){
                        $('#aiwa-save-for-future-use').siblings('.badge').addClass('aiwa-hidden');
                    }, 5000);

                } else {
                    console.log('Something went wrong, please try again!'); //Todo

                }

            }, error: function () {
            }
        });

    });
    $(document).on("click", ".aiwa-settings-btn", function (e) {
        e.preventDefault();
        var setting = $(this).data('settings');
        $('.prompt-settings-item').not('.prompt-settings-item[data-tab="'+setting+'"]').slideUp();
        $('.prompt-settings-item[data-tab="'+setting+'"]').slideToggle();

    });

    $(document).on("click", ".minimize-btn", function (e) {
        e.preventDefault();
        $(this).closest('.prompt-settings-item').slideUp();
    });

    $(document).on("click", ".aiwa_rating_close", function (e) {
        e.preventDefault();

        aiwa_ajax_("aiwa_rating_box_closed").then(function () {
            $('.aiwa_rating_section').fadeOut(400);
        })
    });


    $(document).on("click", ".aiwa_rating_already_did", function (e) {
        e.preventDefault();

        aiwa_ajax_("aiwa_rating_box_closed", {"already_did": "1"}).then(function () {
            $('.aiwa_rating_section').fadeOut(400);
        })
    });

    $(document).on("click", ".aiwa-before_content_generate-list-items .aiwa-list-item", function () {
        var title = $(this).find('.aiwa-list-title').text();
        $('#prompt-input').val(title.trim());
        $('.aiwa_selected_title').val(title.trim());
        $('[name="aiwa_is_title_selected"]').val("1");
        $('#aiwa-generate-ai-content').click();
        $('.titles_before_content_generate').slideUp(300);
    });

    $(document).on("change", "select[data-has-subsettings]", function () {
        var id = $(this).attr('id');
        var settings_key = $(this).val();


        var isMultiple = false;
        var shown = false;
        $('[data-subsettings-of="'+id+'"]').each(function () {
            if ($(this).attr('data-sub-settings-key').indexOf(",")){
                isMultiple = true;
                var keys = $(this).attr('data-sub-settings-key').split(',');
                for (let i = 0; i < keys.length; i++) {
                    if (settings_key == keys[i]){
                        shown = true;
                        $(this).slideDown(300);
                    }

                }

                if(!shown){
                    $(this).slideUp(300);
                }
            }
        });
        if (!isMultiple){
            $('[data-subsettings-of="'+id+'"]').not('[data-sub-settings-key="'+settings_key+'"]').slideUp(300);
            $('[data-subsettings-of="'+id+'"][data-sub-settings-key="'+settings_key+'"]').slideDown(300);
        }

    });

    $(document).on("change", "input[data-has-subsettings]", function () {
        var id = $(this).attr('id');
        var is_checked = $(this).is(':checked');
        if (is_checked){
            $('[data-subsettings-of="'+id+'"]').slideDown(300);
        }
        else{
            $('[data-subsettings-of="'+id+'"]').slideUp(300);
        }
    });

    $(document).on("change", '[name="ai-image-size"]', function () {
        if ($(this).val() == 'custom'){
            $('[name="custom-ai-image-size"]').removeClass('aiwa-hidden');
        }else{
            $('[name="custom-ai-image-size"]').addClass('aiwa-hidden');
        }
    });

    function getTitleHtml(titles) {

        var title_arrays = titles.split("\n");
        title_arrays = aiwa_removeNumbers(title_arrays);

        if (title_arrays.length){
            var arrays_length = (title_arrays.length)-1;
            var rand = aiwa_rand(0, arrays_length);
            return title_arrays[rand];
        }

    }

    function aiwa_removeNumbers(list) {
        return list.replace(/\d\.+/g, "");
    }

    function aiwa_rand(min, max) { // min and max included
        return Math.floor(Math.random() * (max - min + 1) + min)
    }

    $(document).on("click", "#aiwa-writing-assistant-btn", function (e) {
        e.preventDefault();
        if (!$(this).hasClass('loading')){
            $(this).toggleClass('activate');
            $('#ai-writing-assistant-promptbox').slideToggle();
        }

    });

    $(document).on("change", "#aiwa-language", function () {
        var language = $(this).find('option:selected').data('name');
        $('#aiwa_language_text').val(language);
    });

    $(document).on("focus", ".aiwa-single-content-title", function () {
        $(this).siblings('label').addClass('screen-reader-text')
    });


    function getTheTime(post=1) {
        var start_date = $('#aiwa-date-picker').val();
        var start_time = $('#publish-start-from').val();
        var each_from_other_value = $('.aiwa_time_value').val();
        var publish_start_from_another_in = $('#publish_start_from_another').val();

        var hours = parseInt(start_time.split(":")[0]);
        var minutes = parseInt(start_time.split(":")[1].split(" ")[0]);
        var ampm = start_time.split(" ")[1];

        if (ampm === "PM"&&hours!==12) {
            hours += 12;
        }

        var time_24 = ("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(-2);
        var date = new Date();
        var offset = date.getTimezoneOffset();
        var timezone = offset / 60;
        var timezoneStr = (timezone > 0) ? "-" + ("0" + timezone).slice(-2) + ":00" : "+" + ("0" + Math.abs(timezone)).slice(-2) + ":00";
        var date_string = start_date + "T" + time_24 + timezoneStr;
        var date_time = new Date(date_string);

        var time_interval = parseInt(each_from_other_value) * 1000;

        switch (publish_start_from_another_in) {
            case "seconds":
                time_interval *= 1;
                break;
            case "minutes":
                time_interval *= 60;
                break;
            case "hours":
                time_interval *= 3600;
                break;
            case "days":
                time_interval *= 86400;
                break;
            case "weeks":
                time_interval *= 604800;
                break;
            case "months":
                time_interval *= 2592000;
                break;
            default:
                break;
        }

        return date_time.getTime() + time_interval * (post - 1);
    }

    function getDateByTimesTamp(timestamp, symbol = '-') {
        //console.log(timeStamp)
        var dateFormat= new Date(timestamp);

        return  dateFormat.getFullYear() + symbol + (dateFormat.getMonth()+1) + symbol + dateFormat.getDate();
    }

    function insertSheduledPostsToList() {
        var titles = $('.aiwa-list-items .aiwa-list-item');



        /*console.log(start_date)
        console.log(start_time)
        console.log(each_from_other_value)
        console.log(publish_start_from_another_in)*/

        var lists = "";
        $(titles).each(function (i, value) {
            //var date = new Date().toISOString().slice(0, 19);
            var title = $(value).find('.aiwa-list-title').html().trim();

            var timeStamp= getTheTime(i+1)

            var date = getDateByTimesTamp(timeStamp);
            var time = getMeridianTime(timeStamp);

            //console.log(date + " " + time)

            /*            var list = '<tr scope="row" class="active">\n' +
                            '            <td>\n' +
                            '                1392\n' +
                            '            </td>\n' +
                            '            <td><div contenteditable="" class="list-title">'+title+'</div></td>\n' +
                            '            <td>\n' +
                            '                Post type' +
                            '            </td>\n' +
                            '            <td>Category</td>\n' +
                            '            <td>'+date + " " + time+'</td>\n' +
                            '            <td>Action</td>\n' +
                            '        </tr>\n' +
                            '        <tr class="spacer"><td colspan="100"></td></tr>';

                        $('#aiwa-auto-content-writer table tbody').prepend(list)*/

        })
    }

    function saveAndInsertIntoList() {
        var scheduled_posts = [];


        $('.aiwa-titles .aiwa-list-item').each(function(){

            var titleData = [];
            var title = $(this).find('.aiwa-list-title').text().trim();
            var scheduledDateTime = $(this).find('.aiwa-date-picker').val() + " " + $(this).find('.aiwa_time_picker').val();
            var postType = $(this).find('[name="aiwa_post_type_selectbox"]').val();
            var cat = $(this).find('#exportable-cat').val();

            titleData.push(title, scheduledDateTime, postType, cat);

            scheduled_posts.push(titleData);

        })

        if( ( ($('.scheduled-lists tr').length-1)+scheduled_posts.length) > 5){
            alert("You can only add max 5 titles in queued list in the free version. Upgrade to pro for more!"); //todo
            return;
        }


        var language = $('#aiwa-language').val();
        var content_length = $('#aiwa-content-length').val();
        var content_structure = $('#aiwa-content-structure').val();
        var keywords = $('#aiwa-include-keywords').val();
        var writing_style = $('#aiwa-writing-style').val();
        var writing_tone = $('#aiwa-writing-tone').val();
        var generate_image = $('#aiwa-auto-generate-image').is(":checked") ? "1" : "0";

        aiwa_ajax_('aiwa_set_scheduled_posts', {'scheduled_posts': JSON.stringify(scheduled_posts), 'language': language, 'content_length': content_length, 'content_structure': content_structure, 'keywords': keywords, 'writing_style': writing_style, 'writing_tone': writing_tone, 'generate_image': generate_image}).then(function (res) {
            $('#ai-response').slideUp(300);

            setTimeout(function(){
                $('.aiwa-titles').html();
                location.reload();
            }, 301);

        }),
            function (e) {
                console.log(e)
            }
    }

    //$(document).ready(function() {
    //var screenWidth = $(window).width();

    //$(window).resize(function() {
    /*if ($(window).width() < 1000) {
        $('.custom-table th:gt(3)').hide();
        $('.custom-table td:nth-child(n+5)').hide();
        $("#expand-table-btn").show();
    } else {
        $('.custom-table th:gt(3)').show();
        $('.custom-table td:nth-child(n+5)').show();
        $("#expand-table-btn").hide();
    }*/
    /*var width = $('#aiwa-auto-content-writer').width();
    if (width > 1200){
        $('.table.custom-table').css("width", width+"px");
    }*/
    //});

    /*if (screenWidth < 1000) {
        $('.custom-table th:gt(3)').hide();
        $('.custom-table td:nth-child(n+5)').hide();
        $("#expand-table-btn").show();
    }

    $("#expand-table-btn").click(function(){
        $('.custom-table .language, .custom-table .content_length, .custom-table .keywords, .custom-table .writing_style, .custom-table .writing_tone').toggle();
    });*/
    //});

    var expandBtnClicked = 0;
    $(document).on("click", "#expand-table-btn", function () {
        if (expandBtnClicked===0){
            $('.table.custom-table').addClass('aiwa_table_expanded')
            $(this).text("Collapse the table") //todo
            expandBtnClicked = 1;
        }
        else{
            $('.table.custom-table').removeClass('aiwa_table_expanded');
            $(this).text("Expand the table") //todo
            expandBtnClicked = 0;
        }
        $('.custom-table .language, .custom-table .content_length, .custom-table .keywords, .custom-table .writing_style, .custom-table .writing_tone').toggle();
    });

    $(document).on("click", ".scheduled-action-btn.edit-sheduled", function (e) {
        e.preventDefault();
        $(this).closest('tr').find('td.title').attr("contenteditable", "true");
        $(this).closest('tr').find('td.keywords').attr("contenteditable", "true");
        $(this).closest('tr').find('select').removeAttr("disabled");
        $(this).closest('tr').find('input').removeAttr("readonly");
        $(this).closest('tr').find('input[type="checkbox"]').removeAttr("disabled");
        $(this).hide();
        $(this).closest('tr').find('.update-sheduled').show();
    });
    $(document).on("click", ".scheduled-action-btn.update-sheduled", function (e) {
        e.preventDefault();
        var id = $(this).closest('tr').find('td.id').text();
        var title = $(this).closest('tr').find('td.title').text();
        var keywords = $(this).closest('tr').find('td.keywords').text();
        var posttype = $(this).closest('tr').find('#aiwa-post-type-selectbox').val();
        var cat = $(this).closest('tr').find('.category select').val();
        var scheduled = $(this).closest('tr').find('.scheduled_time_input').val();
        var content_length = $(this).closest('tr').find('.content_length select').val();
        var content_structure = $(this).closest('tr').find('.content_structure select').val();
        var writing_style = $(this).closest('tr').find('#aiwa-writing-style').val();
        var writing_tone = $(this).closest('tr').find('#aiwa-writing-tone').val();
        var generate_image = $(this).closest('tr').find('#aiwa-is-generate-image').is(":checked") ? "1": "0";

        var this_ = $(this);
        aiwa_ajax_('aiwa_update_scheduled_post', { "id": id, "title": title, "keywords": keywords, "content_structure": content_structure, "posttype": posttype, "cat": cat, "scheduled": scheduled, "content_length": content_length, "writing_style": writing_style, "writing_tone": writing_tone, "generate_image": generate_image}).then(function (res) {
            this_.hide();
            this_.closest('tr').find('.edit-sheduled').show();
            this_.closest('tr').find('.aiwa_checkmark').show();
            setTimeout(function(){
                this_.closest('tr').find('.aiwa_checkmark').hide();
            }, 3000);

            this_.closest('tr').find('td.title').attr("contenteditable", "false");
            this_.closest('tr').find('td.keywords').attr("contenteditable", "false");
            this_.closest('tr').find('select').attr("disabled", "disabled");
            this_.closest('tr').find('input').attr("readonly", "true");
            this_.closest('tr').find('input[type="checkbox"]').attr("disabled", "disabled");
        }, function (e) {
            alert(e.data);
        });

    });


    $(document).on("click", "#add-scheduled-post-btn", function (e) {
        e.preventDefault();
        var title = $('#scheduled-post-title').val();
        var language = $('#aiwa-scheduled-language').val();
        var keywords = $('#aiwa-scheduled-include-keywords').val();
        var posttype = $('#scheduled-post-posttype').val();
        var cat = $('#scheduled-post-cats').val();
        var scheduled = $('#aiwa-scheduled-date-picker').val() + " " + $('#publish-scheduled-time-picker').val();
        var content_length = $('#aiwa-scheduled-content-length').val();
        var content_structure = $('#aiwa-add-scheduled-content-structure').val();
        var writing_style = $('#aiwa-scheduled-writing-style').val();
        var writing_tone = $('#aiwa-scheduled-writing-tone').val();
        var generate_image = $('#aiwa-scheduled-generate-image-checkbox').is(":checked") ? "1": "0";

        var this_ = $(this);
        aiwa_ajax_('aiwa_add_scheduled_post', { "title": title, "language": language, "keywords": keywords, "content_structure": content_structure, "posttype": posttype, "cat": cat, "scheduled": scheduled, "content_length": content_length, "writing_style": writing_style, "writing_tone": writing_tone, "generate_image": generate_image}).then(function (res) {
            setTimeout(function(){
                location.reload();
            }, 301);
        }, function (e) {
            alert(e.data);
        });

    });


    $(document).on("click", ".scheduled-action-btn.delete-sheduled", function () {
        var id = $(this).closest('tr').find('td.id').text();
        var this_ = $(this);
        if (confirm("Are you sure you want to delete this item?")) { //todo
            aiwa_ajax_("aiwa_delete_scheduled_post", {"id": id}).then(function (res) {
                this_.closest('tr').find('td').css({"background-color": "#cb2027", "color": "white"})
                setTimeout(function(){
                    this_.closest('tr').remove()
                }, 1000);

            })
        }
    });


    $(document).on("click", ".code-box-insert-btn", function (e) {
        e.preventDefault();
        if ( $('#aiwa-auto-content-writer').length){
            saveAndInsertIntoList();

            return;
        }
        var generated_title = $('.aiwa-blog-post .blog-title h1');
        var html = $(".aiwa-blog-post").clone();
        html.find(".extend-blog-post-preview, .blog-title, .move-buttons, .aiwa-hidden").remove();
        html.find("*").removeClass(function(index, className) {
            return (className.match(/(^|\s)topic-\S+/g) || []).join(' ');
        });
        if ($('#content').length) {
            $('#content').focus();
        }

        if ($('#aiwa-editor').length){
            $('#aiwa-editor').focus();
        }

        $('.html-active #aiwa-editor-tmce').click();
        if (tinymce.activeEditor) {
            $('#title').siblings('label').addClass('screen-reader-text')
            $('#title').val(generated_title.html());

            setTimeout(function(){
                tinymce.activeEditor.execCommand('mceInsertContent', false, html.html());
            }, 1000);

            if ($('.excerpt').length&&$('#excerpt').length){
                $('#excerpt').text($('.excerpt p').text());
            }
        } else {
            if (wp.data.dispatch('core/editor')==undefined){
                return
            }
            if (generated_title.length){
                wp.data.dispatch('core/editor').editPost({title: generated_title.html()});
            }

            var name = 'core/paragraph';
            var insertedBlock = wp.blocks.createBlock(name, {
                content: html.html(),
            });
            wp.data.dispatch('core/editor').insertBlocks(insertedBlock);

            setTimeout(function(){

                if ( $('.components-button[aria-label="Settings"]').length){
                    if($('.edit-post-sidebar').length==0){
                        $('.components-button[aria-label="Settings"]').click();

                        setTimeout(function(){
                            $('.components-button[data-label="Post"]').click();
                        }, 500);
                    }
                    else{
                        $('.components-button[data-label="Post"]').click();
                    }

                }
                setTimeout(function(){
                    var textarea = $('.editor-post-excerpt textarea');
                    if ($('.excerpt').length&&textarea.length){
                        textarea.text($('.excerpt p').text());
                    }
                }, 700);


            }, 100);
        }


    });




    function getMeridianTime(timeStamp) {

        var dateFormat = new Date(timeStamp);
        var options = { hour: 'numeric', minute: 'numeric', hour12: false };
        var meridian = dateFormat.toLocaleString("default", options);

        return meridian;

        /*        const date = new Date(timeStamp / 1000);
        const options = { hour: 'numeric', minute: 'numeric', hour12: true };
        const meridian = date.toLocaleString('default', options);
        */
    }

    $(document).ready(function () {

        const d = new Date();
        let year = d.getFullYear();

        new dateDropper({
            selector: '#aiwa-date-picker, #aiwa-scheduled-date-picker',
            expandable: true,
            minYear: year,
            maxYear: 2050,
            defaultDate: true
        });
        /*$('.aiwa-time-picker').timeDropper({
            autoswitch: true,
            meridians: true,
            mousewheel: true,
            init_animation: 'dropDown'
        });*/
        $('#publish-start-from, #publish-scheduled-time-picker').timepicki();
    });

    $(document).on("click", ".code-box-copy-btn", function (e) {
        e.preventDefault();

        if ( $('#aiwa-auto-content-writer').length){

            var html = "";
            $('.aiwa-list-items .aiwa-list-item').each(function () {
                html += $(this).find('.aiwa-list-title').text().trim() + ",\n";
            });

            $('textarea.code-box-code').html(html);
        }
        else{

            var html = $(".aiwa-blog-post").clone();
            html.find(".extend-blog-post-preview, .move-buttons").remove();
            html.find("*").removeClass(function(index, className) {
                return (className.match(/(^|\s)topic-\S+/g) || []).join(' ');
            });

            $('textarea.code-box-code').html(html.html());
        }
        var t = $(this);

        var textarea = $('#ai-response .code-box-code');
        textarea.parent().show(); // make the textarea visible
        textarea.select(); // Select the contents of the textarea
        document.execCommand("copy"); // Execute the copy command
        textarea.hide(); // hide the textarea


        t.children('span').text('Copied!'); //Todo

        setTimeout(function(){
            t.children('span').text('Copy'); //Todo
        }, 5000);


    });

    $(document).ready(function () {

        if($('#aiwa_single_generation_promptbox').length){
            $('#aiwa_single_generation_promptbox').prepend($('#ai-writing-assistant-promptbox'));
            $('#ai-writing-assistant-promptbox').removeClass('aiwa-hidden');
        }

        if($('#aiwa-auto-content-writer').length){
            $('#aiwa-auto-content-writer').prepend($('#ai-writing-assistant-promptbox'));
            $('.code-box-insert-btn span').text("Insert to queued list"); //todo
            $('.code-box-insert-btn').attr('title',"Insert to queued list"); //todo
            $('#ai-writing-assistant-promptbox').removeClass('aiwa-hidden');
        }

        if($('#aiwa-image-generator').length){
            $('#aiwa-image-generator').prepend($('#ai-writing-assistant-promptbox'));
            $('#ai-response .code-box-header').html('<label>Generated images</label>');//todo
            $('#image-settings').show();
            $('#ai-writing-assistant-promptbox').removeClass('aiwa-hidden');

        }

        const timeoutVar = setTimeout(function () {
            var gutenbergVitual_editor = document.getElementsByClassName('editor-post-title__input');
            var promptBoxHolder = document.getElementById('aiwa-prompt-box-holder');
            if (gutenbergVitual_editor!==null && promptBoxHolder !== null){
                const newElement = document.createElement('div');
                newElement.innerHTML = promptBoxHolder.innerHTML;

                const element = gutenbergVitual_editor[0];

                if (element !== undefined) {
                    element.parentNode.insertAdjacentHTML('beforebegin', newElement.outerHTML);
                }
            }
            var titleDiv = jQuery("#titlediv");
            if (titleDiv.length && titleDiv.siblings('#ai-writing-assistant-promptbox').length == 0){
                $('#ai-writing-assistant-promptbox').insertBefore(jQuery("#titlediv"));
            }


            if (document.getElementById('aiwa-placeholders') != null) {

                placeholderStreaming('aiwa-placeholders', 'prompt-input');

            }
            const bPost = document.getElementsByClassName('aiwa-blog-post')[0];
            if (bPost !== undefined && window.innerHeight < 600) {
                bPost.style.maxHeight = '300px';
            }

            var aiwa_btn = document.getElementById('aiwa-writing-assistant-btn');
            if (aiwa_btn !== null) {
                aiwa_btn.classList.remove("loading");
            }

        }, 2000);

        if (document.getElementById('aiwa-placeholders') != null) {
            setTimeout(function () {
                placeholderStreaming('playground-placeholders', 'gpt-playground-prompt', 30, 7000);
            }, 5000);
        }

    });

    function placeholderStreaming(placeholderElement='aiwa-placeholders', outputElement= 'prompt-input', speed = 50, timeOut = 10000) {

        if (document.getElementById(placeholderElement) == null){
            return;
        }

        var placeholders = document.getElementById(placeholderElement).value;
        placeholders = placeholders.split(',');
        var rand = aiwa_rand2(0, (placeholders.length - 1));
        var placeholder_init_text = aiwa_removeNumbers2(placeholders[rand]).trim();
        //document.getElementById('prompt-input').setAttribute('placeholder', placeholder_init_text);


        document.getElementById(outputElement).setAttribute('placeholder', '');
        for (let i = 0; i < placeholder_init_text.length; i++) {
            setTimeout(function () {
                var placeholder = document.getElementById(outputElement).getAttribute('placeholder');
                document.getElementById(outputElement).setAttribute('placeholder', placeholder + placeholder_init_text[i]);
            }, i * speed);
        }


        var AutoRefresh = setInterval(function () {
            var rand = aiwa_rand2(0, (placeholders.length - 1));
            aiwa_replace_placeholder_like_stream(aiwa_removeNumbers2(placeholders[rand]).trim(), outputElement, speed);
        }, timeOut);
    }

    $(document).on("click", "#aiwa-generate-title", function () {
        if ($(this).is(":checked")){
            $("#aiwa-select-title-before-generate").prop("checked", false);
        }
    });

    $(document).on("click", "#aiwa-select-title-before-generate", function () {
        if ($(this).is(":checked")){
            $("#aiwa-generate-title").prop("checked", false);
        }
    });

    var aiwa_get_time = 1;

    $(document).ready(function () {
        if ($('.aiwa-scheduled-posts-table-section').length){
            //console.log("asdasdasdasd")
            aiwa_get_time = new Date($('.aiwa_get_wp_current_timestamp').val()).getTime();
            //console.log(aiwa_get_time)

            setInterval(function () {
                aiwa_get_time += 1000;
            }, 1000);
        }

    });

    $(document).on("mouseover", ".scheduled_time_input.t-scheduled[readonly]", function (e) {
        var this_ = $(this);


        var remaining = aiwa_get_remaining_time(this_.val(), aiwa_get_time);

        this_.parent().find('.time_tooltip_s').text(remaining);

        var aiwa_ti = setInterval(function () {
            var remaining = aiwa_get_remaining_time(this_.val(), aiwa_get_time);
            this_.parent().find('.time_tooltip_s').text(remaining);
        }, 1000);


        this_.parent().addClass('visible');

        $(e.target).mouseout(function () {
            clearInterval(aiwa_ti);

            this_.parent().removeClass("visible");
        })

    });

    /*Image generation promptbox*/

    $(document).on("change", "#aiwa_imagePresets", function () {
        var items = $(this).val();
        items = items.split(",");

        var elements = "";
        $(items).each(function (i) {
            var item = items[i].trim();

            elements += '.image-experiments #aiwa_'+item+',';


        });

        elements = elements.replace(/,\s*$/, "");

        $('.image-experiments input').prop('checked', false);
        $(elements).prop('checked', true);

    });


})(jQuery);

function aiwa_moveUp() {
    let topic = this.parentNode.parentNode;
    topic.parentNode.insertBefore(topic, topic.previousSibling);
}

function aiwa_moveDown() {
    let topic = this.parentNode.parentNode;
    topic.parentNode.insertBefore(topic.nextSibling, topic);
}
function aiwa_rand2(min, max) { // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min)
}
function aiwa_removeNumbers2(list) {
    return list.replace(/\d\.|\d\d\.+/g, "");
}
function aiwa_replace_placeholder_like_stream(string, id='prompt-input', speed=50){
    var prompt_input = document.getElementById(id);
    prompt_input.setAttribute('placeholder', '');
    for (let i = 0; i < string.length; i++) {

        setTimeout(function(){
            var placeholder = document.getElementById(id).getAttribute('placeholder');
            prompt_input.setAttribute('placeholder', placeholder + string[i]);
        }, i*speed);
    }
}


jQuery(function($) {
    'use strict';


    var media_properties = ['id', 'url'];

    jQuery('.aiwa-media-remove').on('click', function(e) {
        e.preventDefault();

        if (jQuery(this).attr('data-browse-button')) var $browse = jQuery(jQuery(this).attr('data-browse-button'));
        else var $browse = jQuery(this).siblings('.media-browse');

        if (!$browse.length) {
            alert('No sibling browse button found, or the data-browse-button attribute had no matching elements');
            return false;
        }

        $browse.data('attachment', false).trigger('attachment-removed');

        // Trigger the update for the browse button's fields
        for (i = 0; i < media_properties.length; i++) {
            var media_key = media_properties[i];
            var selector = $browse.attr('data-media-' + media_key); // data-media-url, data-media-link, data-media-height

            if (selector) {
                var $target = jQuery(selector);

                if ($target.length) {
                    $target.val('').trigger('media-updated').trigger('change');
                }
            }
        }

        return false;
    });

    var file_frame;
    jQuery('.aiwa-media-browse').on('click', function(e) {
        e.preventDefault();

        var $this = jQuery(this);

        if (!wp || !wp.media) {
            alert('The media gallery is not available. You must admin_enqueue this function: wp_enqueue_media()');
            return;
        }

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $this.attr('data-media-title') || 'Browsing Media',
            button: {
                text: $this.attr('data-media-text') || 'Select',
            },
            multiple: false // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function() {
            // We set multiple to false so only get one image from the uploader
            var attachment = file_frame.state().get('selection').first().toJSON();
            //console.log(attachment)
            $('.aiwa-featured-image').attr('src', attachment.url)
            $('.aiwa-featured-image-section').removeClass('aiwa-hidden');
            $('.featured_image_id').val(attachment.id);

        });

        // Finally, open the modal
        file_frame.open();
    });

    /*single post generation codes*/

    $(document).on("click", ".aiwa-button.save-single-generation", function (e) {
        e.preventDefault();

        var title = $('.aiwa-single-content-title').val();
        var content = tinymce.get("aiwa-editor").getContent();
        var excerpt = $('#excerpt').val();
        var post_type = $('#aiwa-single-post-type').val();
        var post_status = $('#aiwa-single-post-status').val();
        var featured_image_id = $('.featured_image_id').val();

        var date_picker = $('.aiwa-date-picker').val();
        var time_picker = $('.aiwa-time-picker').val();
        var category = $('#aiwa-single-category').val();

        if (!title.length && post_status !== 'draft'){
            alert('Post title is empty! Please enter a title or save as draft.'); //todo
            return;
        }

        var datas = {
            'action': 'aiwa_save_single_post_generation',
            'rc_nonce': aiwa.nonce,
            'title': title,
            'content': content,
            'excerpt': excerpt,
            'post_type': post_type,
            'post_status': post_status,
            'featured_image_id': featured_image_id,
            'date_picker': date_picker,
            'time_picker': time_picker,
            'category': category,
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
                    $('.single-generated-post-saved').fadeIn(100);

                    var seconds = 5;
                    var AutoRefresh = setInterval(function () {
                        seconds -= 1;
                        $('.in-five-seconds').text(seconds);

                        if (seconds==0){
                            clearInterval(AutoRefresh);
                            seconds = 5;
                            setTimeout(function(){
                                $('.in-five-seconds').text('5');
                            }, 1000);

                        }
                    }, 1000);

                    setTimeout(function(){
                        $('#prompt-input').val("");
                        $('#excerpt').val("");
                        $('#ai-response').hide();
                        $('#title-prompt-text').removeClass('screen-reader-text');
                        $('#title').val("");
                        $('.featured_image_id').val("");
                        $('.aiwa-featured-image-section').hide();

                        if ($('#aiwa-editor').length){
                            $('#aiwa-editor').focus();
                        }

                        if (tinymce.activeEditor) {
                            $('.html-active #aiwa-editor-tmce').click();

                            setTimeout(function(){
                                tinymce.activeEditor.setContent('');
                            }, 500);
                        }

                        $('.single-generated-post-saved').fadeOut(200);
                    }, 5000);

                } else {
                    console.log('Something went wrong, please try again!');
                }

            }, error: function () {

            }
        });
    });


});

function generate_canceled(session_key){
    var cncl_btn = jQuery('.aiwa-cancel-btn[cancelled_session]');
    if (cncl_btn.length && cncl_btn.attr('cancelled_session') === session_key){
        return;
    }
    jQuery('.aiwa-cancel-btn').addClass('aiwa-hidden').attr('cancelled_session', session_key);
    jQuery('#aiwa-generate-ai-content .aiwa_spinner').addClass('hide_spin');
    jQuery('#aiwa-generate-ai-content .title').text('Generate'); //Todo
}

function unescapeHTML(escapedHTML) {
    return escapedHTML.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&').replace(/&quot;/g,'"');
}

function aiwa_remove_html_tags(content) {
    return content.replace(/<[^>]+>/g, '');
}
function aiwa_replace_double_quo(content) {
    return content.replace(/"/g, '');
}
function remove_first_br(content) {
    var index = content.indexOf("<br><br>");
    return content.substring(index+8);
}
function remove_first_br2(content) {
    return content.replace(/^(&lt;br&gt;&lt;br&gt;)/g, '');
}
function br_remove(content) {
    content = content.replace(/<br>/g, "");
    return content.replace(/\n/g, "");
}
function aiwa_isJSON(str) {
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}

function aiwa_ajax_(action, input_data, callback=null) {
    var datas = {
        'action': action,
        'rc_nonce': aiwa.nonce
    };
    Object.assign(datas, input_data);

    return new Promise((resolve, reject) => {
        return jQuery.ajax({
            url: aiwa.ajax_url,
            data: datas,
            type: 'post',
            dataType: 'json',

            beforeSend: function () {

            },

            xhrFields: {
                // Getting on progress streaming response
                onprogress: function (e) {

                    var response = e.target.response;

                    /*ai_response = response;

                    if (aiwa_isJSON(response)){
                        var json = JSON.parse(response);
                        if ( json.data !== "undefined" && json.data.error !== "undefined"){
                            ai_response = "__error__";
                            error_message = json.data.error.message
                        }
                    }*/

                    if(callback!==null) callback(response, input_data);


                }

            },
            success: function (r) {
                if (r.success) {
                    resolve(r);
                } else {
                    if ( r.data !== undefined && r.data.trim()== "__api-empty__" ){
                        alert("Please enter the API key on the settings panel first."); //todo
                        jQuery('#ai-response').addClass('aiwa-hidden-important');
                        generate_canceled(input_data['session_key']);
                        reject(r.data);
                    }else if(r.data !== undefined && r.data.trim()=='__something_went_wrong__'){
                        alert("Something went wrong! please try again later"); //todo
                        jQuery('#ai-response').addClass('aiwa-hidden-important');
                        generate_canceled(input_data['session_key']);
                        reject(r.data);
                    }else if(r.data !== undefined){
                        alert(r.data); //todo
                        reject(r);
                    }
                    else{
                        reject(r);
                    }
                }

            }, error: function (r) {
                if (r.data !== undefined && r.data.error !== undefined){
                    alert( r.data.error.message);
                    reject(r);
                }
                console.log(r)
                reject(new Error('Something is not right!'));
            }
        });
    })
}


function aiwa_streaming_ajax(action, params, streamCallback=null, onSuccess=null, onError=null) {
    /*if ($('#aiwa_is_generation_cancelled').val() === "1"){
        generate_canceled(params['session_key']);
        return;
    }
    if ('session_key' in params && params['session_key']!==$('#generation_session_key').val()){
        generate_canceled(params['session_key']);
        return;
    }*/

    var datas = {
        'action': action,
        'rc_nonce': aiwa.nonce,
    };
    if (Object.keys(params).length > 0){
        Object.assign(datas, params);
    }

    var ai_response = "";
    var error_message = "";
    return new Promise((resolve, reject) => {
        return jQuery.ajax({
            url: aiwa.ajax_url,
            data: datas,
            type: 'post',
            //dataType: 'json',

            beforeSend: function () {

            },
            xhrFields: {
                // Getting on progress streaming response
                onprogress: function (e) {

                    var response = e.target.response;

                    ai_response = response;

                    if (aiwa_isJSON(response)){
                        var json = JSON.parse(response);
                        if ( json.data !== "undefined" && json.data.error !== "undefined" && json.data.error.message !== "undefined"){
                            ai_response = "__error__";
                            error_message = json.data.error.message
                        }
                    }

                    /*if ($('#aiwa_is_generation_cancelled').val() === "1"){
                        generate_canceled(params['session_key']);
                        reject("");
                        return;
                    }
                    if ('session_key' in params && params['session_key']!==$('#generation_session_key').val()){
                        generate_canceled(params['session_key']);
                        reject("");
                        return;
                    }*/

                    if(streamCallback!==null) streamCallback(response, params);

                    //scrollToBottom()

                }

            },
            success: function (e) {
                if(onSuccess!==null) onSuccess(e);

                if (e.data !== undefined){
                    if ( e.data !== "undefined" && e.data.error !== "undefined"){
                        ai_response = "__error__";
                        error_message = e.data.error.message
                    }
                }

                if ( ai_response.trim()== "__api-empty__" ){
                    alert("Please enter the API key on the settings panel first."); //todo
                    $('#ai-response').addClass('aiwa-hidden-important');
                    generate_canceled(params['session_key']);
                    reject(ai_response);
                }else if(ai_response.trim()=='__invalid_api_key__'){
                    alert("API key is invalid. You can find your API key at https://platform.openai.com/account/api-keys"); //todo
                    reject(ai_response);
                }else if(ai_response.trim()=='__insufficient_quota__'){
                    alert("You exceeded your OpenAI's current quota, please check your OpenAI plan and billing details."); //todo
                    reject(ai_response);
                }else if(ai_response.trim()=='__server_error__'){
                    alert("The OpenAI server had an error while processing your request. Sorry about that!"); //todo
                    reject(ai_response);
                }else if(ai_response.trim()=='__error__'){
                    alert(error_message);
                    reject(ai_response);
                }
                else{
                    resolve(ai_response);
                }
                //console.log(ai_response)
            },
            error: function (e) {
                if(onError!==null) onError(e);
                /*$('#aiwa-generate-ai-content .aiwa_spinner').addClass('hide_spin');
                $('#aiwa-generate-ai-content .title').text('Generate'); //Todo
                if (currentAction==='title'){
                    $('#aiwa_title').val(aiwa_getTitle(ai_response));
                }*/
                reject(ai_response);
                //generate_canceled(params['session_key']);
            }
        })
    });

}



function aiwa_get_remaining_time(date, current_time = Date.now()) {
    const timestamp = new Date(date).getTime();
    //const remaining = timestamp - Date.now();

    const remaining = timestamp - current_time;

    const days = Math.floor(remaining / (86400 * 1000));
    const hours = Math.floor((remaining % (86400 * 1000)) / (3600 * 1000));
    const minutes = Math.floor((remaining % (3600 * 1000)) / (60 * 1000));
    const seconds = Math.floor((remaining % (60 * 1000)) / 1000);

    let remainingTime = "";
    if (days > 0) {
        remainingTime += `${days} day${days > 1 ? 's' : ''} `;
    }
    if (hours > 0) {
        remainingTime += `${hours} hour${hours > 1 ? 's' : ''} `;
    }
    if (minutes > 0) {
        remainingTime += `${minutes} minute${minutes > 1 ? 's' : ''} and `;
    }
    remainingTime += `${seconds} second${seconds > 1 ? 's' : ''} left`;

    return remainingTime;
}

var $ = jQuery;
jQuery(document).on("click", "#aiwa-send-prompt", function (e) {
    e.preventDefault();
    var prompt = jQuery('#gpt-playground-prompt').val();

    if (prompt===""){
        jQuery('.empty-prompt.badge').fadeIn(300);
        setTimeout(function(){
            jQuery('.empty-prompt.badge').fadeOut(300);
        }, 4000);

        return;
    }

    prompt = prompt.replace(/\n/g, '<br>');

    var rand = generateRandomString(10);
    if ($('.gpt-playground-chat-item .thinking').length == 0){
        var avatar = jQuery('.aiwa-user-avatar').html();
        var html = '<div class="gpt-playground-chat-item user-prompt" chat-id="'+rand+'">\n' +
            '                <div class="gpt-playground-user-avatar">'+avatar+'</div>\n' +
            '                <div class="gpt-playground-user-prompt">'+prompt+'</div>\n' +
            '                <div class="playground-user-prompt-edit"><svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" height="1.5em" width="1.5em" xmlns="http://www.w3.org/2000/svg"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></div>\n' +
            '            </div>' +
            '<div class="gpt-playground-chat-item ai-response">\n' +
            '                <div class="gpt-playground-ai-avatar"><svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg" stroke-width="1.5" class="h-6 w-6 avatar"><path d="M37.5324 16.8707C37.9808 15.5241 38.1363 14.0974 37.9886 12.6859C37.8409 11.2744 37.3934 9.91076 36.676 8.68622C35.6126 6.83404 33.9882 5.3676 32.0373 4.4985C30.0864 3.62941 27.9098 3.40259 25.8215 3.85078C24.8796 2.7893 23.7219 1.94125 22.4257 1.36341C21.1295 0.785575 19.7249 0.491269 18.3058 0.500197C16.1708 0.495044 14.0893 1.16803 12.3614 2.42214C10.6335 3.67624 9.34853 5.44666 8.6917 7.47815C7.30085 7.76286 5.98686 8.3414 4.8377 9.17505C3.68854 10.0087 2.73073 11.0782 2.02839 12.312C0.956464 14.1591 0.498905 16.2988 0.721698 18.4228C0.944492 20.5467 1.83612 22.5449 3.268 24.1293C2.81966 25.4759 2.66413 26.9026 2.81182 28.3141C2.95951 29.7256 3.40701 31.0892 4.12437 32.3138C5.18791 34.1659 6.8123 35.6322 8.76321 36.5013C10.7141 37.3704 12.8907 37.5973 14.9789 37.1492C15.9208 38.2107 17.0786 39.0587 18.3747 39.6366C19.6709 40.2144 21.0755 40.5087 22.4946 40.4998C24.6307 40.5054 26.7133 39.8321 28.4418 38.5772C30.1704 37.3223 31.4556 35.5506 32.1119 33.5179C33.5027 33.2332 34.8167 32.6547 35.9659 31.821C37.115 30.9874 38.0728 29.9178 38.7752 28.684C39.8458 26.8371 40.3023 24.6979 40.0789 22.5748C39.8556 20.4517 38.9639 18.4544 37.5324 16.8707ZM22.4978 37.8849C20.7443 37.8874 19.0459 37.2733 17.6994 36.1501C17.7601 36.117 17.8666 36.0586 17.936 36.0161L25.9004 31.4156C26.1003 31.3019 26.2663 31.137 26.3813 30.9378C26.4964 30.7386 26.5563 30.5124 26.5549 30.2825V19.0542L29.9213 20.998C29.9389 21.0068 29.9541 21.0198 29.9656 21.0359C29.977 21.052 29.9842 21.0707 29.9867 21.0902V30.3889C29.9842 32.375 29.1946 34.2791 27.7909 35.6841C26.3872 37.0892 24.4838 37.8806 22.4978 37.8849ZM6.39227 31.0064C5.51397 29.4888 5.19742 27.7107 5.49804 25.9832C5.55718 26.0187 5.66048 26.0818 5.73461 26.1244L13.699 30.7248C13.8975 30.8408 14.1233 30.902 14.3532 30.902C14.583 30.902 14.8088 30.8408 15.0073 30.7248L24.731 25.1103V28.9979C24.7321 29.0177 24.7283 29.0376 24.7199 29.0556C24.7115 29.0736 24.6988 29.0893 24.6829 29.1012L16.6317 33.7497C14.9096 34.7416 12.8643 35.0097 10.9447 34.4954C9.02506 33.9811 7.38785 32.7263 6.39227 31.0064ZM4.29707 13.6194C5.17156 12.0998 6.55279 10.9364 8.19885 10.3327C8.19885 10.4013 8.19491 10.5228 8.19491 10.6071V19.808C8.19351 20.0378 8.25334 20.2638 8.36823 20.4629C8.48312 20.6619 8.64893 20.8267 8.84863 20.9404L18.5723 26.5542L15.206 28.4979C15.1894 28.5089 15.1703 28.5155 15.1505 28.5173C15.1307 28.5191 15.1107 28.516 15.0924 28.5082L7.04046 23.8557C5.32135 22.8601 4.06716 21.2235 3.55289 19.3046C3.03862 17.3858 3.30624 15.3413 4.29707 13.6194ZM31.955 20.0556L22.2312 14.4411L25.5976 12.4981C25.6142 12.4872 25.6333 12.4805 25.6531 12.4787C25.6729 12.4769 25.6928 12.4801 25.7111 12.4879L33.7631 17.1364C34.9967 17.849 36.0017 18.8982 36.6606 20.1613C37.3194 21.4244 37.6047 22.849 37.4832 24.2684C37.3617 25.6878 36.8382 27.0432 35.9743 28.1759C35.1103 29.3086 33.9415 30.1717 32.6047 30.6641C32.6047 30.5947 32.6047 30.4733 32.6047 30.3889V21.188C32.6066 20.9586 32.5474 20.7328 32.4332 20.5338C32.319 20.3348 32.154 20.1698 31.955 20.0556ZM35.3055 15.0128C35.2464 14.9765 35.1431 14.9142 35.069 14.8717L27.1045 10.2712C26.906 10.1554 26.6803 10.0943 26.4504 10.0943C26.2206 10.0943 25.9948 10.1554 25.7963 10.2712L16.0726 15.8858V11.9982C16.0715 11.9783 16.0753 11.9585 16.0837 11.9405C16.0921 11.9225 16.1048 11.9068 16.1207 11.8949L24.1719 7.25025C25.4053 6.53903 26.8158 6.19376 28.2383 6.25482C29.6608 6.31589 31.0364 6.78077 32.2044 7.59508C33.3723 8.40939 34.2842 9.53945 34.8334 10.8531C35.3826 12.1667 35.5464 13.6095 35.3055 15.0128ZM14.2424 21.9419L10.8752 19.9981C10.8576 19.9893 10.8423 19.9763 10.8309 19.9602C10.8195 19.9441 10.8122 19.9254 10.8098 19.9058V10.6071C10.8107 9.18295 11.2173 7.78848 11.9819 6.58696C12.7466 5.38544 13.8377 4.42659 15.1275 3.82264C16.4173 3.21869 17.8524 2.99464 19.2649 3.1767C20.6775 3.35876 22.0089 3.93941 23.1034 4.85067C23.0427 4.88379 22.937 4.94215 22.8668 4.98473L14.9024 9.58517C14.7025 9.69878 14.5366 9.86356 14.4215 10.0626C14.3065 10.2616 14.2466 10.4877 14.2479 10.7175L14.2424 21.9419ZM16.071 17.9991L20.4018 15.4978L24.7325 17.9975V22.9985L20.4018 25.4983L16.071 22.9985V17.9991Z" fill="currentColor"></path></svg></div>\n' +
            '                <div class="gpt-playground-ai-response thinking">Loading</div>\n' +
            '            </div>';

        jQuery('.gpt-playground-chatbox').append(html);
        jQuery('#gpt-playground-prompt').val("");

        aiwa_chatting_with_gpt();
    }


});

$(document).on("click", "#aiwa-generation-prompt", function (e) {
    e.preventDefault();

    var prompt = jQuery('.promptBased-settings .gpt-prompt-based').val();
    var model = jQuery('#aiwa-chatting-models').val();
    var temperature = jQuery('#temperature').val();
    //var max_tokens = jQuery('#max-tokens').val();
    var top_p = jQuery('#top-p').val();
    var best_of = jQuery('#best-of').val();
    var frequency_penalty = jQuery('#frequency-penalty').val();
    var presence_penalty = jQuery('#presence-penalty').val();



    if (!$('#aiwa-generation-prompt .aiwa_spinner').hasClass('hide_spin')){
        return;
    }

    if (prompt===""){
        $('.empty-prompt.badge').fadeIn(300);
        setTimeout(function(){
            $('.empty-prompt.badge').fadeOut(300);
        }, 4000);

        return;
    }

    $('#aiwa-generation-prompt .aiwa_spinner').removeClass('hide_spin');
    $('#aiwa-generation-prompt .title').text('Generating');
    $('#gpt-playground-generated-output').val("");

    aiwa_streaming_ajax('aiwa_prompt_based_generation', { prompt: prompt, model: model, temperature: temperature, top_p: top_p, best_of: best_of, frequency_penalty: frequency_penalty, presence_penalty: presence_penalty }, function(streamData) {
        streamData = decodeHtmlEntities(unescapeHTML(streamData));
        streamData = streamData.replace(/\\n/g, "\n"); // Replace "\\n" with actual new lines
        streamData = streamData.replace("<br><br>", "");
        streamData = streamData.replace(/<br>/g, "\n");

        jQuery('#gpt-playground-generated-output').val(streamData);
    }, function(e) {
        console.log("success");
        $('#aiwa-generation-prompt .title').text('Generate');
        $('#aiwa-generation-prompt .aiwa_spinner').addClass('hide_spin');
    }, function () {
        $('#aiwa-generation-prompt .title').text('Generate');
        $('#aiwa-generation-prompt .aiwa_spinner').addClass('hide_spin');
    }).then(function(response) {
        response = decodeHtmlEntities(unescapeHTML(response));
        response = response.replace(/\\n/g, "\n"); // Replace "\\n" with actual new lines
        response = response.replace("<br><br>", "");
        response = response.replace(/<br>/g, "\n");

        jQuery('#gpt-playground-generated-output').val(response);

    });

});

function aiwa_chatting_with_gpt(userPrompt="") {
    var chattings = [];
    var currentUserPrompt = null;
    var model = $('#aiwa-chatting-models').val();
    $('.gpt-playground-chat-item').each(function () {
        if ($(this).hasClass('user-prompt')){
            var userPrompt = $(this).find('.gpt-playground-user-prompt').html();
            userPrompt = userPrompt.replace(/<br>/g, "\n");
            currentUserPrompt = { "user": userPrompt };
            chattings.push(currentUserPrompt);
        }
        if ($(this).hasClass('ai-response')){
            if ($(this).find('.thinking').length == 0){
                var aiPrompt = $(this).find('.gpt-playground-ai-response').html();
                aiPrompt = aiPrompt.replace(/<br>/g, "\n");
                //currentUserPrompt["ai"] = aiPrompt;
                chattings.push({ "ai": aiPrompt });
            }
        }
    });


    if (chattings.length == 0){
        console.log("empty")
    }else{
        var gptPGResponseElement = $('.gpt-playground-ai-response.thinking');
        var rand = generateRandomString(10);
        gptPGResponseElement.attr('chat-id', rand);

        /*gptPGResponseElement.html(rand);
        gptPGResponseElement.removeClass("thinking");*/

        aiwa_streaming_ajax('aiwa_chatting_with_gpt', { "chattings": JSON.stringify(chattings), model: model }, function (streamData) {
            streamData = decodeHtmlEntities(unescapeHTML(streamData));
            console.log(streamData)
            streamData = streamData.replace(/\n/g, "<br>"); // Replace "\\n" with actual new lines
            gptPGResponseElement.html(streamData);
            //console.log(streamData)
        }, function (e) {
            console.log("success")
        }).then(function (response) {
            response = decodeHtmlEntities(unescapeHTML(response));
            console.log(response)
            response = response.replace(/\n/g, "<br>"); // Replace "\\n" with actual new lines
            gptPGResponseElement.html(response);
            gptPGResponseElement.removeClass("thinking");
        })
    }

}

function decodeHtmlEntities(input) {
    var entities = {
        '&#039;': '\'',
        '&quot;': '"',
        '&lt;': '<',
        '&gt;': '>',
        '&amp;': '&'
        // Add more entities if needed
    };

    return input.replace(/&(?:#([0-9]+)|([a-z]+));/gi, function (match, num, name) {
        if (num) {
            return String.fromCharCode(parseInt(num, 10));
        } else if (name) {
            return entities[match] || match;
        }
        return match;
    });
}



$(document).on("click", ".playground-user-prompt-edit", function (e) {
    e.preventDefault();

    var userPrompt = $(this).closest('.gpt-playground-chat-item.user-prompt').addClass('editing').find('.gpt-playground-user-prompt').html();
    $(this).closest('.gpt-playground-chat-item').find('.gpt-playground-user-prompt').html('<textarea class="userPromptEdit">'+userPrompt+'</textarea><div class="button-group">\n' +
        '      <button type="submit" class="save-and-submit">Save and Submit</button>\n' +
        '      <button type="button" class="cancel">Cancel</button>\n' +
        '    </div>');
});

$(document).on("click", ".gpt-playground-user-prompt .save-and-submit", function (e) {
    e.preventDefault();

    var index = $(this).closest('.gpt-playground-chat-item').index();
    var indexesArrays = getEachItemIndex(index);
    var clonedElement = $('.gpt-playground-chatbox').clone();
    var clonedElement2 = clonedElement;

    //html.remove('.gpt-playground-chat-item:nth-child(1)');
    // Find the child element you want to remove

    //console.log(index);

    /*for (let i = index; i < $(clonedElement2).find('.gpt-playground-chat-item').length; i++) {
        clonedElement2.children().eq(i).remove();
        //console.log(clonedElement2.children().eq(i).text())
    }*/
    console.log(clonedElement2.find('.gpt-playground-chat-item').length)
    clonedElement2.children('.gpt-playground-chat-item').slice(index).remove();
    $('.gpt-playground-column-middle').append(clonedElement2);
    console.log(clonedElement2)

    for (let i = 0; i < indexesArrays.length; i++) {
        clonedElement.children().eq(0).remove();
    }
    var chatId = $(this).closest('.gpt-playground-chat-item').attr('chat-id');
    $(this).closest('.editing').before('<div class="edited-chat-group" group-id="'+chatId+'"><div class="aiwa-pagination"><a href="#" class="aiwa-arrow aiwa-arrow-left"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12l4.58-4.59z"/><path d="M0 0h24v24H0z" fill="none"/></svg></a><span class="page-numbers">1/2</span><a href="#" class="aiwa-arrow aiwa-arrow-right"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12l-4.58 4.59z"/><path d="M0 0h24v24H0z" fill="none"/></svg></a></div></div>');
    var textarea = $(this).closest('.gpt-playground-user-prompt').find('.userPromptEdit').val();
    $(this).closest('.gpt-playground-user-prompt').html(textarea);


    //clonedElement.find('.gpt-playground-chat-item:nth-child(1)').remove();

    /*var childElementToRemove = clonedElement.find(willRemove);

    // Remove the child element
    childElementToRemove.remove();

    $('.gpt-playground-column-middle').append(childElementToRemove)*/

    /*var textarea = clonedElement.find('.editing').find('.userPromptEdit').val();
    clonedElement.find('.editing').find('.gpt-playground-user-prompt').html(textarea);

    $('.gpt-playground-column-middle').append(clonedElement);*/

});
//<div class="aiwa-pagination"><a href="#" class="aiwa-arrow aiwa-arrow-left"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12l4.58-4.59z"/><path d="M0 0h24v24H0z" fill="none"/></svg></a><span class="page-numbers">1/2</span><a href="#" class="aiwa-arrow aiwa-arrow-right"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12l-4.58 4.59z"/><path d="M0 0h24v24H0z" fill="none"/></svg></a></div>
/*AI user response pagination*/
var $ = jQuery;

var currentPage = 1;
var totalPages = 2;

$(document).on("click", ".aiwa-arrow-left", function (e) {
    e.preventDefault();
    if (currentPage > 1) {
        currentPage--;
        updatePagination();
    }
});

$(document).on("click", ".aiwa-arrow-right", function (e) {
    e.preventDefault();
    if (currentPage < totalPages) {
        currentPage++;
        updatePagination();
    }
});

function getEachItemIndex(number){
    var numbs = [];
    for (let i = 0; i < number; i++) {
        numbs.push(i);
    }
    return numbs;
}
function updatePagination() {
    $('.page-numbers').text(currentPage + '/' + totalPages);
}

function generateRandomString(length) {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;

    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    return result;
}


/*
* Starting GPT playground menu page scripts
* */


const selectbox = document.querySelector('.multiselect-selectbox');
const optionsContainer = document.querySelector('.multiselect-options');
const searchBox = document.querySelector('.multiselect-searchbox');
const selectedValuesContainer = document.querySelector('.selected-values');
const gridItems = document.querySelectorAll('.grid-item');

selectbox.addEventListener('click', () => {
    optionsContainer.classList.toggle('show');
    searchBox.focus();
});

searchBox.addEventListener('focus', () => {
    optionsContainer.style.display = 'block';
});

searchBox.addEventListener('blur', () => {
    optionsContainer.style.display = 'none';
});

optionsContainer.addEventListener('click', (event) => {
    if (event.target.classList.contains('multiselect-option')) {
        const checkbox = event.target.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
        updateSelectedValues();

        const checkboxes = optionsContainer.querySelectorAll('input[type="checkbox"]:checked');
        const values = Array.from(checkboxes).map((checkbox) => checkbox.value);

        const searchValuesArray = values.map(value => value.trim().toLowerCase());

        gridItems.forEach((item) => {
            const cats = item.getAttribute('cats');
            const catsArray = cats.split(',').map(cat => cat.trim().toLowerCase());

            var display = 'none';
            searchValuesArray.forEach(value => {
                const hasMatch = catsArray.includes(value);
                if (hasMatch && display=='none') {
                    display = '';
                }
            });
            item.style.display = display;

        });

        if (searchValuesArray.length === 0){
            gridItems.forEach((item) => {
                item.style.display = '';
            });
        }
    }
});

searchBox.addEventListener('input', () => {
    const searchValue = searchBox.value.toLowerCase();

    gridItems.forEach((item) => {
        const prompt = item.textContent.toLowerCase();

        if (prompt.includes(searchValue)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});

function updateSelectedValues() {
    const checkboxes = optionsContainer.querySelectorAll('input[type="checkbox"]:checked');
    const values = Array.from(checkboxes).map((checkbox) => checkbox.value);

    selectedValuesContainer.textContent = values.join(', ');

    if (values.length === 0) {
        selectedValuesContainer.textContent = 'Select options';
    }
}

jQuery(document).on("click", ".gpt-playground-column .multiselect-container", function () {
    if (jQuery('.multiselect-options').hasClass('show')){
        jQuery('.grid-container').animate({ marginTop: '215px' }, 100);
    }
    else{
        jQuery('.grid-container').animate({ marginTop: '0' }, 100);
    }
});

jQuery(document).on("click", ".gpt-container .grid-item", function (e) {
    e.preventDefault();

    var offset = jQuery(this).offset().top;
    var promptBoxOffset = jQuery('.gpt-prompt-based').offset().top;
    if ( (offset-promptBoxOffset) > 300){
        jQuery('html, body').animate({
            scrollTop: jQuery('.gpt-prompt-based').offset().top-70
        }, 600);
    }
    var prompt = jQuery(this).attr('prompt');
    jQuery('.tab-pane.active .gpt-prompt-based, .tab-pane.active .gpt-playground-prompt').val(prompt);

});

jQuery(document).on("click", ".output-box-copy-btn", function (e) {
    e.preventDefault();

    // Get the textarea element
    const textarea = document.getElementById('gpt-playground-generated-output');

    // Select the text in the textarea
    textarea.select();

    // Copy the selected text to the clipboard
    document.execCommand('copy');

    // Deselect the text

    setTimeout(function(){
        textarea.blur();
    }, 1000);



});

jQuery(document).on("click", ".gpt-container .nav-link", function (e) {
    e.preventDefault();
    var tab = jQuery('.nav-link.active').data('id');

    var models = jQuery('#aiwa-chatting-models');
    if (tab !== "promptBased-settings"){
        models.find('option').removeAttr('disabled');
    }
    else{
        models.find('option').removeAttr('selected');
        models.val("gpt-3.5-turbo").change();

        models.find('option[value="gpt-3.5-turbo-instruct"]').attr('disabled', 'disabled');
        models.find('option[value="text-curie-001"]').attr('disabled', 'disabled');
        models.find('option[value="text-babbage-001"]').attr('disabled', 'disabled');
        models.find('option[value="text-ada-001"]').attr('disabled', 'disabled');
    }
});

jQuery(document).on("click", "#aiwa-chatting-models", function (e) {
    var model = jQuery(this).val();
    jQuery('.option-short-description span').hide();
    jQuery('.option-short-description span[data-id="'+model+'"]').show();

});

/*
* Ending GPT playground menu page scripts
* */
