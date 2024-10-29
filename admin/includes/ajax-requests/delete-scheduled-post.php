<?php
namespace WpWritingAssistant\AjaxRequests;

class DeleteScheduledPost
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_delete_scheduled_post", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();

        global $wpdb;
        $table_name = $wpdb->prefix.'ai_writing_assistant_sceduled_posts';
        $id = isset($_POST['id']) && !empty($_POST['id']) ? sanitize_key($_POST['id']) : '0';
        $wpdb->delete( $table_name, array( 'id' => intval($id) ), array( '%d' ) );

        wp_send_json_success();
        wp_die();

    }
}
