<?php
function aiwa_cron_events_activate() {
    if (! wp_next_scheduled ( 'aiwa_daily_schedules' )) {
        wp_schedule_event( time(), 'daily', 'aiwa_daily_schedules');
    }
}

add_action( 'aiwa_daily_schedules', 'aiwa_active_cron_job_every_day', 10, 2 );
function aiwa_active_cron_job_every_day() {
    $home_url = get_home_url();
    $notices = file_get_contents('http://api.myrecorp.com/aiwa_notices.php?version=pro&url='.$home_url);

    update_option('aiwa_notices', ($notices));
}

function aiwa_cron_events_deactivate() {
    wp_clear_scheduled_hook( 'aiwa_daily_schedules' );
}

function aiwa_right_side_notice(){
    $notices = get_option('aiwa_notices');
    $notices = json_decode($notices);
    $html = "";

    if (!empty($notices)) {
        foreach ($notices as $key => $notice) {

            $title = $notice->title;
            $key = $notice->key;
            $publishing_date = $notice->publishing_date;
            $auto_hide = $notice->auto_hide;
            $auto_hide_date = $notice->auto_hide_date;
            $is_right_sidebar = $notice->is_right_sidebar;
            $content = $notice->content;
            $status = $notice->status;
            $version = isset($notice->version) ? $notice->version : array();

            $styles = isset($notice->styles) ? $notice->styles : "";

            $current_time = time();
            $publish_time = strtotime($publishing_date);
            $auto_hide_time = strtotime($auto_hide_date);

            if ( $status == "true" && $is_right_sidebar == "true" && $current_time > $publish_time && $current_time < $auto_hide_time && in_array('free', $version) ) {
                $html .= '<div class="sidebar_notice_section">';
                $html .=	'<div class="right_notice_title">'.wp_kses_post($title).'</div>';
                $html .=	'<div class="right_notice_details">'.wp_kses_post($content).'</div>';
                $html .= '</div>';

                if ( !empty($styles) ) {
                    $html .= '<style>' . esc_html($styles) . '</style>';
                }
            }
        }
    }


    echo $html;
}
add_action("aiwa_right_side_notice", "aiwa_right_side_notice");

function aiwa_admin_notices(){


    $notices = get_option('aiwa_notices');
    $notices = json_decode($notices);
    $html = "";


    if (!empty($notices)) {
        foreach ($notices as $key2 => $notice) {
            $title = isset($notice->title) ? sanitize_text_field($notice->title) : "";
            $key = isset($notice->key) ? sanitize_text_field($notice->key) : "";
            $publishing_date = isset($notice->publishing_date) ? sanitize_text_field($notice->publishing_date) : time();
            $auto_hide = isset($notice->auto_hide) ? sanitize_text_field($notice->auto_hide) : "false";
            $auto_hide_date = isset($notice->auto_hide_date) ? sanitize_text_field($notice->auto_hide_date) : time();
            $is_right_sidebar = isset($notice->is_right_sidebar) ? sanitize_text_field($notice->is_right_sidebar) : "true";
            $content = isset($notice->content) ? sanitize_text_field($notice->content) : "";
            $status = isset($notice->status) ? sanitize_text_field($notice->status) : "false";
            $alert_type = isset($notice->alert_type) ? sanitize_key($notice->alert_type) : "success";
            $version = isset($notice->version) ? $notice->version : array();
            $version = array_map('sanitize_key', $version);
            $styles = isset($notice->styles) ? sanitize_textarea_field($notice->styles) : "";

            $current_time = time();
            $publish_time = strtotime($publishing_date);
            $auto_hide_time = strtotime($auto_hide_date);

            $clicked_data = (array) get_option('aiwa_notices_clicked_data');

            if ( $status == "true" && $is_right_sidebar == "false" && $current_time > $publish_time && $current_time < $auto_hide_time && !in_array($key, $clicked_data) && in_array('free', $version) ) {
                $html .=  '<div class="notice notice-'. $alert_type .' is-dismissible dcim-alert aiwa" aiwa_notice_key="'.$key.'">
						'.wp_kses_post($content).'
					</div>';

                if ( !empty($styles) ) {
                    $html .= '<style>' . esc_html($styles) . '</style>';
                }
            }
        }
    }

    echo $html;

}
add_action('admin_notices', 'aiwa_admin_notices');



add_action('wp_ajax_aiwa_notice_has_clicked', 'aiwa_notice_has_clicked');
add_action('wp_ajax_nopriv_aiwa_notice_has_clicked', 'aiwa_notice_has_clicked');

function aiwa_notice_has_clicked(){
    //$post = $_POST['post'];
    $aiwa_notice_key = isset($_POST['aiwa_notice_key']) ? sanitize_text_field($_POST['aiwa_notice_key']) : "";
    $nonce = isset($_POST['rc_nonce']) ? sanitize_text_field($_POST['rc_nonce']) : "";

    if(!empty($nonce)){
        if(!wp_verify_nonce( $nonce, "rc-notice-dismiss-nonce" )){
            echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }
    }

    set_aiwa_notices_clicked_data($aiwa_notice_key);

    $response = "";


    echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));

    die();
}


function set_aiwa_notices_clicked_data($new = ""){

    $gop = get_option('aiwa_notices_clicked_data');

    if (!empty($gop)) {

        if (!empty($new)) {
            $gop[] = $new;
        }


    } else {
        $gop = array();
        $gop[] = $new;
    }

    update_option('aiwa_notices_clicked_data', $gop);

    return $gop;
}

function rc_aiwa_notice_dissmiss_scripts(){
    ?>
    <script>
        jQuery(document).on("click", ".aiwa .notice-dismiss", function(){
            if (jQuery(this).parent().attr('aiwa_notice_key').length) {
                var datas = {
                    'action': 'aiwa_notice_has_clicked',
                    'rc_nonce': '<?php echo wp_create_nonce( "rc-notice-dismiss-nonce" ); ?>',
                    'aiwa_notice_key': jQuery(this).parent().attr('aiwa_notice_key'),
                };

                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: datas,
                    type: 'post',
                    dataType: 'json',

                    beforeSend: function(){

                    },
                    success: function(r){
                        if(r.success == 'true'){
                            console.log(r.response);


                        } else {
                            alert('Something went wrong, please try again!');
                        }

                    }, error: function(){

                    }
                });
            }
        });


        /*Dissmiss successfully export notice*/
        jQuery(document).on("click", ".export-html-notice .notice-dismiss", function () {
            var datas = {
                'action': 'dismiss_export_html_notice',
                'rc_nonce': rcewpp.nonce,
            };

            jQuery.ajax({
                url: rcewpp.ajax_url,
                data: datas,
                type: 'post',
                dataType: 'json',

                beforeSend: function () {

                },
                success: function (r) {
                    if (r.success) {
                        //console.log(r.response);


                    } else {
                        console.log('Something went wrong, please try again!');
                    }

                }, error: function () {

                }
            });
        });


    </script>
    <?php
}
add_action("admin_print_footer_scripts", "rc_aiwa_notice_dissmiss_scripts");
