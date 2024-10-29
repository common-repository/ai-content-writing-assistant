<?php
namespace WpWritingAssistant\AjaxRequests;

class GenerateVariationImages
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_generate_variation_images", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();
        $prompt = isset($_POST['prompt']) ? sanitize_text_field($_POST['prompt']) : "";
        $image_size = isset($_POST['ai-image-size']) ? sanitize_text_field($_POST['ai-image-size']) : "medium";
        $numberOfImages = isset($_POST['number_of_image']) ? sanitize_key($_POST['number_of_image']) : "1";

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

        if (!empty($this->ajax->getSettings('api-key'))) {
            $ai = new \OpenAIAPI($this->ajax->getSettings('api-key'));
            $ai->setModel('gpt-3.5-turbo-instruct');

            $image_experiments = isset($_POST['image_experiments']) && is_array($_POST['image_experiments']) ? array_keys($_POST['image_experiments']) : array();
            $image_experiments = array_map('sanitize_text_field', $image_experiments);

            if (empty($image_experiments)){
                $image_experiments = $this->ajax->getSettings('image_experiments', array());
            }

            $image_styles = '';
            if (!empty($image_experiments)){
                $image_styles = implode(', ', $image_experiments);
                $image_styles = ' | ' . rtrim($image_styles, ',').'.';
            }

            $image_styles = str_replace(array('four_k', 'eight_k', '_'), array("4K", "8K", " "), $image_styles);

            $data = array(
                'prompt' => $prompt . $image_styles,
                'n' => intval($numberOfImages),
                'size' => $image_size_,
            );
            $response = $ai->image($data);

            if (aiwa_is_json($response)) {
                $obj = json_decode($response);
                $hasError = $this->ajax->is_response_has_error($obj);
                if ($hasError!==false){
                    wp_send_json_error($hasError);
                }
                else if (isset($obj->data) && !empty($obj->data)){
                    wp_send_json_success($obj->data);
                }
                else{
                    wp_send_json_error("__something_went_wrong__");
                }

            }

        }
        else{
            wp_send_json_error('API key is empty, please enter the API key on the settings panel first.');
        }

        wp_die();


    }
}

