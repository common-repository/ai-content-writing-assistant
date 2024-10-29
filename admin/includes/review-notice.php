<?php

function aiwa_wp_plugin_admin_notice() {
    // Check if the plugin has been active for 7 days
    $activation_date = get_option('aiwa_wp_plugin_activation_date');
    if ($activation_date && (time() - $activation_date) > 3600*24*7) {
        // Check if the notice has been dismissed
        $dismissal_date = get_option('aiwa_wp_plugin_dismissal_date');
        if (!$dismissal_date || (time() - $dismissal_date) > 3600*24*7) {
            // Check if the notice has been closed forever
            $notice_closed = get_option('aiwa_wp_plugin_notice_closed');
            if (!$notice_closed) {
                // Display the notice
                $review_url = 'https://wordpress.org/support/plugin/ai-content-writing-assistant/reviews/#new-post';
                $notice_text = __('Help us to help you! Hope you liked our <strong>"AI Content Writing Assistant"</strong> plugin! Please help us improve the plugin by leaving a five start ratings and review. Your feedback is greatly appreciated.', 'ai-writing-assistant');
                $notice_html = '
                  <div class="notice notice-info aiwa-wp-plugin-notice">
                    <p>' . wp_kses_post($notice_text) . '</p>
                    <p>
                      <a href="https://wordpress.org/support/plugin/ai-content-writing-assistant/reviews/#new-post" class="wp-plugin-button" target="_blank" style="background-color: #2271b1;">' . __('Ok, you deserved it', 'ai-writing-assistant') . '</a> 
                      <a href="#" class="wp-plugin-button aiwa-wp-plugin-dismiss" style="color: #2271b1 !important;">' . __('Dismiss', 'ai-writing-assistant') . '</a> 
                      <a href="#" class="wp-plugin-button aiwa-wp-plugin-close" style="float: right;">' . __('Close Forever', 'ai-writing-assistant') . '</a>
                    </p>
                  </div>
                  <style>.wp-plugin-button{display:inline-block;padding:8px 16px;border:none;border-radius:4px;font-size:16px;cursor:pointer;text-align:center;transition:all 0.2s;text-decoration:none;color:white !important;}.wp-plugin-button:hover{transform:translateY(-2px);box-shadow:0 4px 6px rgba(0,0,0,0.1);}.aiwa-wp-plugin-close{background-color:#3498db}</style>
                ';
                echo $notice_html;
            }
        }
    }
}
add_action('admin_notices', 'aiwa_wp_plugin_admin_notice');

function aiwa_wp_plugin_dismiss_notice() {
    if (is_admin() && wp_verify_nonce(sanitize_key($_REQUEST['rc_nonce']), 'rc-nonce')) {
        update_option('aiwa_wp_plugin_dismissal_date', time());
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_aiwa_wp_plugin_dismiss_notice', 'aiwa_wp_plugin_dismiss_notice');

function aiwa_wp_plugin_close_notice() {
    if (is_admin() && wp_verify_nonce(sanitize_key($_REQUEST['rc_nonce']), 'rc-nonce')) {
        update_option('aiwa_wp_plugin_notice_closed', true);
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_aiwa_wp_plugin_close_notice', 'aiwa_wp_plugin_close_notice');

function aiwa_admin_print_footer_scripts(){
    ?>
    <script>
        jQuery(document).ready(function($) {
            // Dismiss the notice
            $('.aiwa-wp-plugin-dismiss').click(function(e) {
                e.preventDefault();
                $.post(aiwa.ajax_url, {
                    action: 'aiwa_wp_plugin_dismiss_notice',
                    rc_nonce: aiwa.nonce,
                }, function(response) {
                    if (response.success) {
                        $('.aiwa-wp-plugin-notice').hide();
                    }
                });
            });

            // Close the notice forever
            $('.aiwa-wp-plugin-close').click(function(e) {
                e.preventDefault();
                $.post(aiwa.ajax_url, {
                    action: 'aiwa_wp_plugin_close_notice',
                    rc_nonce: aiwa.nonce,
                }, function(response) {
                    if (response.success) {
                        $('.aiwa-wp-plugin-notice').hide();
                    }
                });
            });
        });
    </script>
    <?php
}

add_action("admin_print_footer_scripts", "aiwa_admin_print_footer_scripts");
