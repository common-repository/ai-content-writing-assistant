<h1><?php echo esc_html(__('AI Writing Assistant Settings')); ?> <span class="badge badge-dark" style="font-size: 14px;">v<?php echo AIWA_VERSION; ?></span></h1>

<?php if(empty(get_option('ai_writing_assistant__api-key'))): ?>
    <div style="background-color: #b01111; padding: 0 10px; border-radius: 5px; text-align: left; border: 2px solid #a63838;margin-bottom: 10px;">
        <p style="color: white;  font-size: 14px;margin: 1em 0 !important;"><?php _e('OpenAI API key field is empty. To generate content, you must enter a valid API key first.', 'ai-writing-assistant'); ?></p>
    </div>
<?php endif; ?>

<div class="ai-writing-assistant-settings-panel">
    <ul class="nav nav-tabs" role="tablist" style="text-transform: uppercase">
        <?php require AIWA_DIR_PATH . 'admin/includes/tab/nav-items.php'; ?>
    </ul>

    <!-- Tab panes -->
    <div class="aiwa_row">
        <div class="aiwa_col-7">
            <div class="tab-content">

                <form id="ai-settings-form">
                    <div class="tab-pane gpt-api-settings active" data-id="tabs-gpt-api-settings" role="tabpanel">
                        <?php require AIWA_DIR_PATH . 'admin/includes/tab/tab-pane/gpt-api-settings.php'; ?>
                    </div>
                    <div class="tab-pane content-settings" data-id="tabs-content-settings" role="tabpanel">
                        <?php require AIWA_DIR_PATH . 'admin/includes/tab/tab-pane/content-tab.php'; ?>
                    </div>
                    <div class="tab-pane general-settings" data-id="tabs-general-settings" role="tabpanel">
                        <?php require AIWA_DIR_PATH . 'admin/includes/tab/tab-pane/general-settings-tab.php'; ?>
                    </div>


                    <input type="hidden" name="from-aiwa-settings" value="1">

                </form>

                <div class="quick-action-item">
                    <button id="aiwa-save-settings" class="aiwa-button save-settings" role="button"> <span class="title"><?php _e('Save Settings', 'ai-writing-assistant'); ?></span><span class="dashicons dashicons-saved"></span></button> <span style="font-size: 13px;font-weight: normal;position: relative;margin-left: 8px;" class="aiwa-save-settings badge badge-success aiwa-hidden"><?php _e('Saved!', 'ai-writing-assistant'); ?></span>
                </div>
            </div>
        </div>

        <div class="aiwa_col-3 p-10 dev_section">

            <div class="created_by py-2 mt-1 border-bottom"> <?php _e('Created by', 'ai-writing-assistant'); ?> <a href="https://myrecorp.com"><img src="<?php echo AIWA_DIR_URL ?>admin/assets/images/recorp-logo.png" alt="ReCorp" width="100"></a></div>


            <div class="documentation my-2" style="background-color: #ff0;padding: 10px;border-radius: 5px;margin-top: 10px;">
                <span><?php _e('See the documentation', 'ai-writing-assistant'); ?> </span> <a href="https://myrecorp.com/ai-writing-assistant-documentation/"><?php _e('here', 'ai-writing-assistant'); ?></a>
            </div>

            <?php aiwa_support_and_feature_request_text(); ?>

            <div class="show-support">
                <p><?php _e('If you\'ve found our plugin helpful, please show your appreciation by leaving a', 'ai-writing-assistant'); ?> <a href="https://wordpress.org/support/plugin/ai-content-writing-assistant/reviews/?filter=5" target="_blank"><?php _e('five star review', 'ai-writing-assistant'); ?></a>.</p>
            </div>


            <div class="right_side_notice mt-4">
                <?php echo do_action('aiwa_right_side_notice'); ?>
            </div>
        </div>
    </div>


</div>

