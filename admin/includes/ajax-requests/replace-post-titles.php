<?php
namespace WpWritingAssistant\AjaxRequests;

class ReplacePostTitles
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_replace_with_suggested_title", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();
        $title = isset($_POST['title']) && !empty($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $id = isset($_POST['id']) && !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '0';

        // Update post 37
        $my_post = array(
            'ID'           => intval($id),
            'post_title'   => $title,
        );

        // Update the post into the database
        wp_update_post( $my_post );

        wp_send_json_success();
        wp_die();

    }
}
