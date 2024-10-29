<?php
namespace WpWritingAssistant\AjaxRequests;

class AddScheduledPost
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_add_scheduled_post", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();

        global $wpdb;

        $table_name = $wpdb->prefix.'ai_writing_assistant_sceduled_posts';
        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

        if ( ! $wpdb->get_var( $query ) == $table_name ) {
            // Include the file containing the activation class
            require AIWA_DIR_PATH . 'includes/plugin-activator.php';

            // Create an instance of the activation class and call its activator method
            $plugin = new \WpWritingAssistant\PluginActivator;
            $plugin->createTable();

        }


        $language = isset($_POST['language']) && !empty($_POST['language']) ? sanitize_text_field($_POST['language']) : 'en';
        $content_structure = isset($_POST['content_structure']) && !empty($_POST['content_structure']) ? sanitize_key($_POST['content_structure']) : 'topic_wise';
        $content_length = isset($_POST['content_length']) && !empty($_POST['content_length']) ? sanitize_key($_POST['content_length']) : 'long';
        $keywords = isset($_POST['keywords']) && !empty($_POST['keywords']) ? sanitize_text_field($_POST['keywords']) : '';
        $writing_style = isset($_POST['writing_style']) && !empty($_POST['writing_style']) ? sanitize_text_field($_POST['writing_style']) : 'normal';
        $writing_tone = isset($_POST['writing_tone']) && !empty($_POST['writing_tone']) ? sanitize_text_field($_POST['writing_tone']) : 'informative';

        $posttype = isset($_POST['posttype']) && !empty($_POST['posttype']) ? sanitize_text_field($_POST['posttype']) : 'post';
        $title = isset($_POST['title']) && !empty($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $cat = isset($_POST['cat']) && !empty($_POST['cat']) ? sanitize_key($_POST['cat']) : '0';
        $generate_image = isset($_POST['generate_image']) && !empty($_POST['generate_image']) ? sanitize_text_field($_POST['generate_image']) : '0';

        $scheduledDate = isset($_POST['scheduled']) && !empty($_POST['scheduled']) ? sanitize_text_field($_POST['scheduled']) : '';
        $date = \DateTime::createFromFormat('Y-m-d g:i A', $scheduledDate);
        $scheduled = $date->format('Y-m-d H:i:s');
        $pattern = "/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/";

        if (!preg_match($pattern, $scheduled)) {
            wp_send_json_error(__("Date time format error {$scheduled}", "ai-writing-assistant"));
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

        $generated_posts = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ai_writing_assistant_sceduled_posts WHERE status != 'completed'");
        if ( $generated_posts >= 5) {
            wp_send_json_error("You can only add max 5 titles in queued list in the free version. Upgrade to pro for more!");
            die();
        }

        $table_name = $wpdb->prefix.'ai_writing_assistant_sceduled_posts';
        $isUpdate = $wpdb->insert(
            $table_name,
            array(
                'title' => $title,
                'scheduled_time' => $scheduled,
                'post_type' => $posttype,
                'category' => $cat,
                'language' => $language,
                'content_structure' => $content_structure,
                'content_length' => $content_length,
                'keywords' => $keywords,
                'writing_style' => $writing_style,
                'writing_tone' => $writing_tone,
                'generate_image' => $generate_image,
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
            )
        );

        if ($isUpdate){
            wp_send_json_success();
        }
        else{
            wp_send_json_error('Failed to add!');
        }

        wp_die();

    }
}
