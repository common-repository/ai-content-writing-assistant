<?php
namespace WpWritingAssistant\AjaxRequests;

class SaveSingleGeneratedPost
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_save_single_post_generation", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();
        $title = isset($_POST['title']) && !empty($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $content = isset($_POST['content']) ? wp_kses_post($_POST['content']) : '';
        $excerpt = isset($_POST['excerpt']) && !empty($_POST['excerpt']) ? sanitize_textarea_field($_POST['excerpt']) : '';
        $post_type = isset($_POST['post_type']) && !empty($_POST['post_type']) ? sanitize_key($_POST['post_type']) : 'post';
        $post_status = isset($_POST['post_status']) && !empty($_POST['post_status']) ? sanitize_key($_POST['post_status']) : 'draft';
        $featured_image_id = isset($_POST['featured_image_id']) && !empty($_POST['featured_image_id']) ? sanitize_key($_POST['featured_image_id']) : '0';

        $date_picker = isset($_POST['date_picker']) && !empty($_POST['date_picker']) ? sanitize_text_field($_POST['date_picker']) : '';
        $time_picker = isset($_POST['time_picker']) && !empty($_POST['time_picker']) ? sanitize_text_field($_POST['time_picker']) : '';
        $category = isset($_POST['category']) && !empty($_POST['category']) ? sanitize_text_field($_POST['category']) : '';


        if (empty($title)){
            $post_status = 'draft';
        }
/*$time = strtotime( 'tomorrow' );
    $my_post = array(
        'ID'            => 1,
        'post_status'   => 'future',
        'post_date'     => date( 'Y-m-d H:i:s', $time ),
        'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $time ),
    );
    wp_update_post( $my_post );*/

        $post = array(
            'post_title'    => $title,
            'post_content'  => $content,
            'post_type'   => $post_type,
            'post_status'   => $post_status,
            'post_author'   => 1,
            //'post_category' => array( 8,39 )
        );

        if ($post_status=='future'&&!empty($date_picker)&&!empty($time_picker)){
            $time = strtotime( $date_picker . ' ' . $time_picker );
            $post['post_date'] = date( 'Y-m-d H:i:s', $time );
            $post['post_date_gmt'] = gmdate( 'Y-m-d H:i:s', $time );
        }

        if (!empty($category)){
            $cats = array();
            $cats[] = $category;
            $post['post_category'] = $cats;
        }

        if (!empty($excerpt)){
            $post['post_excerpt'] = $excerpt;
        }

        // Insert the post into the database
        $post_id = wp_insert_post( $post );

        //if (is_attachment($featured_image_id)) {
            set_post_thumbnail($post_id, $featured_image_id);
        //}

        wp_send_json_success();
        wp_die();

    }
}
