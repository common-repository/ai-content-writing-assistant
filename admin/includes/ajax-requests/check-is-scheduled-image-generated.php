<?php
namespace WpWritingAssistant\AjaxRequests;

class IscheduledPostImageGenerated
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_check_is_scheduled_image_generated", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();

        $p =array();
        global $wpdb;
        $generated_posts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ai_writing_assistant_sceduled_posts");
        if (!empty($generated_posts)){
            foreach ($generated_posts as $post) {
                $id = $post->id;
                $title = $post->title;
                $post_id = $post->post_id;
                $generate_image = $post->generate_image;
                $post_thumbnail_id = get_post_thumbnail_id( $post_id );

                if ($generate_image==1&&$post_thumbnail_id==0){
                    $image = $this->generateImage($title);
                    if (isset($image['id'])){
                        set_post_thumbnail( $post_id, $image['id'] );
                    }
                    break;
                }
            }
        }

        wp_send_json_success();

        wp_die();

    }

    private function generateImage($title)
    {
        $media = array();
        if (!empty($this->ajax->getSettings('api-key'))) {
            $ai = new \OpenAIAPI($this->ajax->getSettings('api-key'));
            $ai->setModel('gpt-3.5-turbo-instruct');

            $image_size = $this->ajax->getSettings('ai-image-size', '512x512');
            $image_experiments = $this->ajax->getSettings('image_experiments', array());
            $image_styles      = '';
            if (!empty($image_experiments)) {
                $image_styles = implode(', ', $image_experiments);
                $image_styles = ' | ' . rtrim($image_styles, ',') . '.';
            }

            $image_size_ = '512x512';
            if ($image_size=='thumbnail'){
                $image_size_ = '256x256';
            }
            elseif ($image_size=='medium'){
                $image_size_ = '512x512';
            }
            elseif ($image_size=='large'){
                $image_size_ = '1024x1024';
            }

            $data     = array(
                'prompt' => $title . $image_styles,
                'n' => 1,
                'size' => $image_size_,
            );
            $response = $ai->image($data);

            if (aiwa_is_json($response)) {
                $json = json_decode($response);
                if (isset($json->data) && isset($json->data[0])) {
                    $url = $json->data[0]->url;

                    $media = aiwa_upload_image_to_media_gallery($url);
                }
            }
        }

        return $media;
    }
}
