<?php

    $content   = '';
    $editor_id = 'aiwa-editor';
    $settings  = array(
        'media_buttons' => false,
    );

?>
<h1><?php _e('Single Content Generator', 'ai-writing-assistant'); ?></h1>
<div class="aiwa-single-generation-page aiwa-menu-page">
    <?php do_action('before_aiwa_single_generation_tinymce'); ?>
    <div id="aiwa_single_generation_promptbox">

        <div id="titlediv">
            <div id="titlewrap">
                <label class="" id="title-prompt-text" for="title"><?php echo __('Enter title here','ai-writing-assistant'); ?></label>
                <input type="text" name="post_title" size="30" class="aiwa-single-content-title" value="" id="title" spellcheck="true" autocomplete="off">
            </div>
            <div class="inside">
                <div id="edit-slug-box" class="hide-if-no-js">
                </div>
            </div>
        </div>
    </div>
    <div class="aiwa-featured-image-section aiwa-hidden">
        <img class="aiwa-featured-image" src="" />
    </div>
    <button id="media-library-button" class="aiwa-media-browse">
        <?php echo __('Select Featured Image','ai-writing-assistant'); ?>
    </button>


    <div id="aiwa-editor-section">
        <?php wp_editor( $content, $editor_id, $settings ); ?>
    </div>

    <div class="aiwa-single-generation-metaboxes">
        <div class="inside">
            <label for="excerpt"><?php echo __('Excerpt','ai-writing-assistant'); ?></label><textarea rows="1" cols="40" name="excerpt" id="excerpt" spellcheck="false"></textarea>
            <p><?php echo __('Excerpts are optional hand-crafted summaries of your content that can be used in your theme.','ai-writing-assistant'); ?> <a href="https://wordpress.org/support/article/excerpt/"><?php echo __('Learn more about manual excerpts','ai-writing-assistant'); ?></a>.</p>
        </div>

        <?php aiwa_post_types_and_categories(); ?>
        <div class="settings-item">
            <label for="aiwa-single-post-status"><span><?php echo __('Post Status','ai-writing-assistant'); ?></span> </label>
            <select name="content-length" id="aiwa-single-post-status" data-has-subsettings="">
                <?php
                aiwa_add_select_option('Publish', 'publish', true);
                aiwa_add_select_option('Private');
                aiwa_add_select_option('Future (Auto publish)', 'future');
                aiwa_add_select_option('Draft');
                aiwa_add_select_option('Pending');
                ?>
            </select>
            <p><?php echo __('Select a post status','ai-writing-assistant'); ?></p>
        </div>

        <?php

        ?>
        <div class="settings-item sub-settings-item aiwa-hidden" data-subsettings-of="aiwa-single-post-status" data-sub-settings-key="future">
            <input type="text" class="aiwa-date-picker" name="aiwa-date-picker" data-dd-opt-default-date="<?php echo current_time("Y/m/d"); ?>" value="<?php echo current_time("Y-m-d"); ?>">
            <input type="text" class="aiwa-time-picker" name="aiwa-time-picker" value="<?php echo aiwa_get_time_after(); ?>" data-timepicki-tim="<?php echo aiwa_get_time_after('10 minutes', 'g'); ?>" data-timepicki-mini="<?php echo aiwa_get_time_after('10 minutes', 'i'); ?>" data-timepicki-meri="<?php echo aiwa_get_time_after('10 minutes', 'A'); ?>">
        </div>
    </div>

    <?php do_action('after_aiwa_single_generation_tinymce'); ?>
    <input type="hidden" name="featured_image_id" class="featured_image_id" value="">
    <button type="button" class="aiwa-button save-single-generation"><?php echo __('Publish','ai-writing-assistant'); ?></button>
    <span style="font-size: 13px; font-weight: normal; margin-right: 10px;margin-left: 5px;" class="single-generated-post-saved badge badge-success aiwa-hidden"><?php echo __('Saved! some inputted data will be earased in <span class="in-five-seconds">5</span> seconds.','ai-writing-assistant'); ?></span>
</div>
