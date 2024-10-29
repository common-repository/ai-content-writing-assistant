<?php
namespace WpWritingAssistant\AjaxRequests;

class RatingBoxClosed
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_rating_box_closed", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();

        $already_did = isset($_POST['already_did']) && !empty($_POST['already_did']) ? sanitize_text_field($_POST['already_did']) : '0';

        if ($already_did){
            update_option('aiwa_rating_box_closed', "already_did");
        }
        else{
            update_option('aiwa_rating_box_closed', time());
        }

        wp_send_json_success();
        wp_die();

    }
}
