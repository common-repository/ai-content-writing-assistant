<?php

// Declare a namespace for the class
namespace WpWritingAssistant;


class ScheduledPostGeneration{

    /**
     * ScheduledPostGeneration constructor.
     */
    public function __construct()
    {
        if (isset($_GET['page']) && sanitize_text_field($_GET['page'])=='scheduled-content-generator'){
            add_action('aiwa_after_promptbox_language', array($this, 'aiwa_after_promptbox_language'));
            add_action('aiwa_codebox', array($this, 'aiwa_codebox'));
            add_filter('aiwa_promptbox_title', array($this, 'promptbox_title_for_auto_write'));
            add_filter('aiwa_metabox_settings', array($this, 'metabox_settings'));
            add_filter('aiwa_after_promptbox_form', array($this, 'aiwa_after_promptbox_form'));
            add_filter('aiwa_generate_button_text', array($this, 'aiwa_generate_button_text'));
        }
    }


    public function metabox_settings($settings)
    {

        return array('content_settings', 'language', 'content_structure', 'content_length', 'include_keywords', 'writing_style', 'writing_tone',  'generate_images');
    }

    public function aiwa_generate_button_text($text)
    {
        $text = __("Generate titles", "ai-writing-assistant");
        return $text;
    }


    public function aiwa_after_promptbox_form()
    {
        echo '<div class="aiwa-hidden-important">';
        echo '<div class="aiwa-exportable-post-types-selectbox">';
        aiwa_post_types_selectbox();
        echo '</div>';

        echo '<div class="aiwa-exportable-categories-selectbox">';
        aiwa_category_selectbox();
        echo '</div>';
        echo '</div>';
    }

    public function aiwa_after_promptbox_language()
    {

        $key = 'ai_writing_assistant__';
        ?>
        <input type="hidden" name="aiwa_auto_content_settings" value="true">
        <div class="settings-item">
            <label for="aiwa-how-many-titles"><span><?php _e('How many titles', 'ai-writing-assistant'); ?></span>
                <input id="aiwa-how-many-titles" class="content-settings-input" type="number" name="titles-count" value="<?php echo esc_attr(get_option($key.'titles-count', '5')); ?>" placeholder="5">
            </label>
            <p><?php _e('Enter the number of titles you want to show before add to the queue list.', 'ai-writing-assistant'); ?></p>
            <p class="title_exceeded" style="margin: 0;color: #b01111;">
                <?php echo sprintf(
                    __('You can generate only 3 titles in the free version. %s for more!', 'wp-plugin'),
                    '<a href="https://myrecorp.com/ai-content-writing-assistant/" target="_blank">' . __('Upgrade to Pro', 'ai-writing-assistant') . '</a>'
                ); ?>
            </p>
        </div>
        <div class="settings-item sheduled-content-generator">
            <label for="publish-start-from"><span><?php _e('Publish start from', 'ai-writing-assistant'); ?></span>
                <input type="text" id="aiwa-date-picker" class="aiwa-date-picker" name="aiwa_date_picker" data-dd-opt-default-date="<?php echo current_time("Y/m/d"); ?>" value="<?php echo current_time("Y-m-d"); ?>">
                <input type="text" id="publish-start-from" class="aiwa_time_picker" name="aiwa-time-picker" value="<?php echo aiwa_get_time_after(); ?>" data-timepicki-tim="<?php echo aiwa_get_time_after('10 minutes', 'g'); ?>" data-timepicki-mini="<?php echo aiwa_get_time_after('10 minutes', 'i'); ?>" data-timepicki-meri="<?php echo aiwa_get_time_after('10 minutes', 'A'); ?>">
            </label>
            <p><?php _e('Posts will be published after this time you selected. Above timezone is from your wordpress site settings.', 'ai-writing-assistant'); ?></p>
        </div>
        <div class="settings-item">
            <label for="publish_start_from_another"><span><?php _e('Publish post each from other after', 'ai-writing-assistant'); ?></span>
                <input type="text" name="time_value" class="aiwa_time_value" placeholder="1" value="1">
                <select name="publish_start_from_another" id="publish_start_from_another">
                    <?php
                    //aiwa_add_select_option('Seconds');
                    aiwa_add_select_option('Minute(s)', 'minutes');
                    aiwa_add_select_option('Hour(s)', 'hours');
                    aiwa_add_select_option('Day(s)', 'days', true);
                    aiwa_add_select_option('Week(s)', 'weeks');
                    aiwa_add_select_option('Month(s)', 'months');
                    ?>
                </select>
            </label>
            <p><?php _e('Select a time to publish a post each from another.', 'ai-writing-assistant'); ?></p>
        </div>
        <?php
        aiwa_post_types_and_categories();
    }


    public function promptbox_title_for_auto_write($title)
    {
        $title .= __(' (write a topic to generate blog post titles to add scheduled posts list)', 'ai-writing-assistant');

        return $title;
    }


    public function aiwa_codebox()
    {
        ?>
        <div class="aiwa-titles"></div>
        <?php
    }

}


new ScheduledPostGeneration();