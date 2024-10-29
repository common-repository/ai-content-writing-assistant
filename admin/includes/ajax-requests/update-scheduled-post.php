<?php
namespace WpWritingAssistant\AjaxRequests;

class UpdateScheduledPost
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_update_scheduled_post", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();

        global $wpdb;


        //$language = isset($_POST['language']) && !empty($_POST['language']) ? sanitize_text_field($_POST['language']) : 'en';
        $id = isset($_POST['id']) && !empty($_POST['id']) ? sanitize_key($_POST['id']) : '0';
        $content_structure = isset($_POST['content_structure']) && !empty($_POST['content_structure']) ? sanitize_key($_POST['content_structure']) : 'topic_wise';
        $content_length = isset($_POST['content_length']) && !empty($_POST['content_length']) ? sanitize_key($_POST['content_length']) : 'long';
        $keywords = isset($_POST['keywords']) && !empty($_POST['keywords']) ? sanitize_text_field($_POST['keywords']) : '';
        $writing_style = isset($_POST['writing_style']) && !empty($_POST['writing_style']) ? sanitize_text_field($_POST['writing_style']) : 'normal';
        $writing_tone = isset($_POST['writing_tone']) && !empty($_POST['writing_tone']) ? sanitize_text_field($_POST['writing_tone']) : 'informative';

        $posttype = isset($_POST['posttype']) && !empty($_POST['posttype']) ? sanitize_text_field($_POST['posttype']) : 'post';
        $title = isset($_POST['title']) && !empty($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $cat = isset($_POST['cat']) && !empty($_POST['cat']) ? sanitize_key($_POST['cat']) : '0';
        $generate_image = isset($_POST['generate_image']) && !empty($_POST['generate_image']) ? sanitize_text_field($_POST['generate_image']) : '0';
        $scheduled = isset($_POST['scheduled']) && !empty($_POST['scheduled']) ? sanitize_text_field($_POST['scheduled']) : '';


        $pattern = "/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/";

        if (!preg_match($pattern, $scheduled)) {
            wp_send_json_error(__("Date time format error, format should be YYYY-MM-DD HH:MM:SS, example: 2023-02-10 15:59:00", "ai-writing-assistant"));
            wp_die();
        }

        $timestamp = strtotime($scheduled);
        if ($timestamp < time()) {
            wp_send_json_error(__("You can not set the past date!", "ai-writing-assistant"));
            wp_die();
        }

        if (empty($title)){
            wp_send_json_error(__("The title is empty", "ai-writing-assistant"));
            wp_die();
        }

        $table_name = $wpdb->prefix.'ai_writing_assistant_sceduled_posts';
        $isUpdate = $wpdb->update(
            $table_name,
            array(
                'title' => $title,
                'scheduled_time' => $scheduled,
                'post_type' => $posttype,
                'category' => $cat,
                //'language' => $language,
                'content_structure' => $content_structure,
                'content_length' => $content_length,
                'keywords' => $keywords,
                'writing_style' => $writing_style,
                'writing_tone' => $writing_tone,
                'generate_image' => $generate_image,
            ),
            array( 'id' => intval($id) ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                //'%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
            ),
            array( '%d' )
        );

        if ($isUpdate){
            wp_send_json_success();
        }
        else{
            wp_send_json_error('Failed to update! Please change something to save.');
        }

        wp_die();

    }
}
