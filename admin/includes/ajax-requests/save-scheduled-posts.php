<?php
namespace WpWritingAssistant\AjaxRequests;

class SaveScheduledPosts
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_set_scheduled_posts", [$this, 'ajax']);
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


        $scheduled_posts = isset($_POST['scheduled_posts']) && !empty($_POST['scheduled_posts']) ? stripslashes(sanitize_textarea_field($_POST['scheduled_posts'])) : '';

        $language = isset($_POST['language']) && !empty($_POST['language']) ? sanitize_text_field($_POST['language']) : 'en';
        $content_structure = isset($_POST['content_structure']) && !empty($_POST['content_structure']) ? sanitize_key($_POST['content_structure']) : 'topic_wise';
        $content_length = isset($_POST['content_length']) && !empty($_POST['content_length']) ? sanitize_key($_POST['content_length']) : 'long';
        $keywords = isset($_POST['keywords']) && !empty($_POST['keywords']) ? sanitize_text_field($_POST['keywords']) : '';
        $writing_style = isset($_POST['writing_style']) && !empty($_POST['writing_style']) ? sanitize_text_field($_POST['writing_style']) : 'normal';
        $writing_tone = isset($_POST['writing_tone']) && !empty($_POST['writing_tone']) ? sanitize_text_field($_POST['writing_tone']) : 'informative';
        $generate_image = isset($_POST['generate_image']) && !empty($_POST['generate_image']) ? sanitize_text_field($_POST['generate_image']) : '0';

        if (aiwa_is_json($scheduled_posts)){

            $datas = json_decode($scheduled_posts);

            if (!empty($datas)){
                $generated_posts = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ai_writing_assistant_sceduled_posts WHERE status != 'completed'");
                if ( ($generated_posts+count($datas)) > 5) {
                    wp_send_json_error(__("You can only add max 5 titles in queued list in the free version. Upgrade to pro for more!", "ai-writing-assistant"));
                    die();
                }
                foreach ($datas as $data) {
                    $title = isset($data[0]) && !empty($data[0]) ? sanitize_text_field($data[0]) : "";
                    $scheduledDate = isset($data[1]) && !empty($data[1]) ? sanitize_text_field($data[1]) : "";

                    $date = \DateTime::createFromFormat('Y-m-d H:i', $scheduledDate);
                    $formattedDate = $date->format('Y-m-d H:i:s');

                    $postType = isset($data[2]) && !empty($data[2]) ? sanitize_text_field($data[2]) : "";
                    $cat = isset($data[3]) && !empty($data[3]) ? sanitize_text_field($data[3]) : "";

                    if (empty($title)){
                        wp_send_json_error(__("The title is empty!", "ai-writing-assistant"));
                        die();
                    }


                    $table_name = $wpdb->prefix.'ai_writing_assistant_sceduled_posts';

                    $wpdb->insert(
                        $table_name,
                        array(
                            'title' => $title,
                            'scheduled_time' => $formattedDate,
                            'post_type' => $postType,
                            'category' => $cat,
                            'language' => $language,
                            'content_structure' => $content_structure,
                            'content_length' => $content_length,
                            'keywords' => $keywords,
                            'writing_style' => $writing_style,
                            'writing_tone' => $writing_tone,
                            'generate_image' => intval($generate_image),
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
                }
            }
        }

        wp_send_json_success();
        wp_die();

    }
}
