<?php

namespace WpWritingAssistant;

class AjaxRequests{

    public $settingsKey = "ai_writing_assistant__";
    /**
     * AjaxRequests constructor.
     */
    public function __construct()
    {
        $this->require_ajax_files();
        $this->initAjaxClasses();
    }

    public function require_ajax_files()
    {

        require 'ajax-requests/ajax-save-settings.php';
        require 'ajax-requests/get-ai-data.php';
        require 'ajax-requests/generate-placeholders.php';
        require 'ajax-requests/generate-image.php';
        require 'ajax-requests/save-single-generated-post.php';
        require 'ajax-requests/get-intro-and-conc.php';
        require 'ajax-requests/save-scheduled-posts.php';
        require 'ajax-requests/update-scheduled-post.php';
        require 'ajax-requests/delete-scheduled-post.php';
        require 'ajax-requests/add-scheduled-post.php';
        require 'ajax-requests/check-is-scheduled-image-generated.php';
        require 'ajax-requests/rating-box-closed.php';
        require 'ajax-requests/suggest-post-titles.php';
        require 'ajax-requests/replace-post-titles.php';
        require 'ajax-requests/generate-variation-images.php';
        require 'ajax-requests/save-image-to-media-library.php';
        require 'ajax-requests/chatting-with-gpt.php';
        require 'ajax-requests/prompt-based-command-generation.php';
    }

    public function initAjaxClasses()
    {
        new AjaxRequests\SaveSettings($this);
        new AjaxRequests\GetAIDATA($this);
        new AjaxRequests\GeneratePlaceholders($this);
        new AjaxRequests\GenerateImage($this);
        new AjaxRequests\SaveSingleGeneratedPost($this);
        new AjaxRequests\GetIntroAndConc($this);
        new AjaxRequests\SaveScheduledPosts($this);
        new AjaxRequests\UpdateScheduledPost($this);
        new AjaxRequests\DeleteScheduledPost($this);
        new AjaxRequests\AddScheduledPost($this);
        new AjaxRequests\IscheduledPostImageGenerated($this);
        new AjaxRequests\RatingBoxClosed($this);
        new AjaxRequests\SuggestPostTitles($this);
        new AjaxRequests\ReplacePostTitles($this);
        new AjaxRequests\GenerateVariationImages($this);
        new AjaxRequests\SaveMediaImageToMedia($this);
        new AjaxRequests\ChattingWithGPT($this);
        new AjaxRequests\promptBasedGeneration($this);
    }




    /**
     * Set AI Writing Assistant's settings
     * @since 1.0.0
     *
     * @return bool
     */
    public function setSettings( $settings_name="", $value ="")
    {
        if(!empty($settings_name)){
            $settings_name = $this->settingsKey . $settings_name;
            update_option($settings_name, $value);
        }
        return true;
    }


    /**
     * Get Supreme Cashes setting
     * @since 1.0.0
     *
     * @return void
     */
    public function getSettings( $settings_name="", $default = "")
    {
        $settings_name = $this->settingsKey . $settings_name;
        $rc_sc_settings = get_option($settings_name);

        if(empty($rc_sc_settings) && !empty($default)){
            return $default;
        }

        return $rc_sc_settings;
    }


    /**
     * Remove Supreme Cache setting
     * @since 1.0.0
     *
     * @return bool
     */
    public function removeSettings( $settings_name="")
    {
        $settings_name = $this->settingsKey . $settings_name;
        $rc_sc_settings = delete_option($settings_name);

        if ($rc_sc_settings) {
            return true;
        }
        return false;
    }


    /**
     * Remove all Supreme Cache settings
     * @since 1.0.0
     *
     * @return bool
     */

    public function removeAllSettings()
    {
        global $wpdb;
        $removefromdb = $wpdb->query("UPDATE {$wpdb->prefix}options SET option_value = '' WHERE option_name LIKE '{$this->settingsKey}%'");

        if ($removefromdb) {
            return true;
        }
        return false;
    }

    public function is_response_has_error($obj){
        if (isset($obj->error)){
//            if ($obj->error->code == 'invalid_api_key'){
//                return __("API key is invalid. You can find your API key at https://platform.openai.com/account/api-keys", "ai-writing-assistant");
//            }elseif ($obj->error->type == "insufficient_quota"){
//                return __("You exceeded your OpenAI's current quota, please check your OpenAI plan and billing details.", "ai-writing-assistant");
//            }
//            elseif ($obj->error->type == "server_error"){
//                return __("The OpenAI server had an error while processing your request. Sorry about that!", "ai-writing-assistant");
//            }
//            else{
                return __('OpenAI says: ', 'ai-writing-assistant') . $obj->error->message;
//            }

        }else{
            return false;
        }
    }


}

new AjaxRequests();
