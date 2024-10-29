<?php
namespace WpWritingAssistant\AjaxRequests;

class SaveSettings{

    private $ajax;
    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_ai_writing_assistant_save_settings", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();

        $formData = isset($_POST['formData']) ? wp_filter_kses($_POST['formData']) : "";

        parse_str(str_replace('&amp;', '&', $formData), $form);

        $api_key = isset($form['api-key']) ? sanitize_text_field($form['api-key']) : "";
        $best_of = isset($form['best-of']) && !empty($form['best-of']) ? sanitize_text_field($form['best-of']) : "1";
        $frequency_penalty = isset($form['frequency-penalty']) && !empty($form['frequency-penalty']) ? sanitize_text_field($form['frequency-penalty']) : "0";
        $max_tokens = isset($form['max-tokens']) && !empty($form['max-tokens']) ? sanitize_text_field($form['max-tokens']) : "300";
        $presence_penalty = isset($form['presence-penalty']) && !empty($form['presence-penalty']) ? sanitize_text_field($form['presence-penalty']) : "0";
        $temperature = isset($form['temperature']) && !empty($form['temperature']) ? sanitize_text_field($form['temperature']) : "0.4";
        $top_p = isset($form['top-p']) && !empty($form['top-p']) ? sanitize_text_field($form['top-p']) : "1.0";
        $ai_image_size = isset($form['ai-image-size']) && !empty($form['ai-image-size']) ? sanitize_text_field($form['ai-image-size']) : "medium-plus";

        /*content tab*/
        $ai_content_structure = isset($form['ai-content-structure']) && !empty($form['ai-content-structure']) ? sanitize_text_field($form['ai-content-structure']) : "topic_wise";
        $aiwa_language = isset($form['aiwa-language']) && !empty($form['aiwa-language']) ? sanitize_text_field($form['aiwa-language']) : "en";
        $previously_failed = isset($form['previously_failed']) && !empty($form['previously_failed']) ? sanitize_text_field($form['previously_failed']) : "off";
        $super_fast_generation_mode = isset($form['super_fast_generation_mode']) && !empty($form['super_fast_generation_mode']) ? sanitize_text_field($form['super_fast_generation_mode']) : "off";
        $aiwa_language_text = isset($form['aiwa_language_text']) && !empty($form['aiwa_language_text']) ? sanitize_text_field($form['aiwa_language_text']) : "English";
        $aiwa_title_before_generate = isset($form['select-title-before-generate']) && !empty($form['select-title-before-generate']) ? sanitize_text_field($form['select-title-before-generate']) : "off";
        $how_many_titles_show_first = isset($form['how-many-titles-show-first']) && !empty($form['how-many-titles-show-first']) ? sanitize_text_field($form['how-many-titles-show-first']) : "3";
        $content_length = isset($form['content-length']) && !empty($form['content-length']) ? sanitize_text_field($form['content-length']) : "long";
        $writing_Style = isset($form['writing-style']) && !empty($form['writing-style']) ? sanitize_text_field($form['writing-style']) : "normal";
        $writing_tone = isset($form['writing-tone']) && !empty($form['writing-tone']) ? sanitize_text_field($form['writing-tone']) : "informative";
        $topics_heading_tag = isset($form['aiwa-topics-tag']) && !empty($form['aiwa-topics-tag']) ? sanitize_text_field($form['aiwa-topics-tag']) : "h2";
        $topics_count = isset($form['topics-count']) && !empty($form['topics-count']) ? sanitize_text_field($form['topics-count']) : "5";
        $add_introduction = isset($form['add-introduction']) && !empty($form['add-introduction']) ? sanitize_text_field($form['add-introduction']) : "off";
        $add_introduction_text = isset($form['add-introduction-text']) && !empty($form['add-introduction-text']) ? sanitize_text_field($form['add-introduction-text']) : "off";
        $add_conclusion = isset($form['add-conclusion']) && !empty($form['add-conclusion']) ? sanitize_text_field($form['add-conclusion']) : "off";
        $add_conclusion_text = isset($form['add-conclusion-text']) && !empty($form['add-conclusion-text']) ? sanitize_text_field($form['add-conclusion-text']) : "off";
        $add_call_to_action = isset($form['add-call-to-action']) && !empty($form['add-call-to-action']) ? sanitize_text_field($form['add-call-to-action']) : "off";
        $call_to_action_url = isset($form['call-to-action-url']) && !empty($form['call-to-action-url']) ? esc_url_raw(sanitize_text_field($form['call-to-action-url'])) : "";
        $call_to_action_position = isset($form['call-to-action-position']) && !empty($form['call-to-action-position']) ? sanitize_text_field($form['call-to-action-position']) : "";
        $pros_and_cons_count = isset($form['pros-and-cons-count']) && !empty($form['pros-and-cons-count']) ? sanitize_text_field($form['pros-and-cons-count']) : "7";
        $list_items_count = isset($form['list-items-count']) && !empty($form['list-items-count']) ? sanitize_text_field($form['list-items-count']) : "10";
        $how_many_minutes = isset($form['how_many_minutes']) && !empty($form['how_many_minutes']) ? sanitize_text_field($form['how_many_minutes']) : "10";
        $faq_items_count = isset($form['faq-items-count']) && !empty($form['faq-items-count']) ? sanitize_text_field($form['faq-items-count']) : "7";
        $article_paragraphs_count = isset($form['article-paragraphs-count']) && !empty($form['article-paragraphs-count']) ? sanitize_text_field($form['article-paragraphs-count']) : "3";
        $excerpt_number_of_words = isset($form['excerpt_number_of_words']) && !empty($form['excerpt_number_of_words']) ? sanitize_text_field($form['excerpt_number_of_words']) : "50";

        $first_person_name = isset($form['first-person-name']) && !empty($form['first-person-name']) ? sanitize_text_field($form['first-person-name']) : "";
        $second_person_name = isset($form['second-person-name']) && !empty($form['second-person-name']) ? sanitize_text_field($form['second-person-name']) : "";
        $generate_title = isset($form['generate-title']) && !empty($form['generate-title']) ? sanitize_text_field($form['generate-title']) : "off";
        $add_excerpt = isset($form['add-excerpt']) && !empty($form['add-excerpt']) ? sanitize_text_field($form['add-excerpt']) : "off";
        $aiwa_image_beautifier = isset($form['aiwa_image_beautifier']) && !empty($form['aiwa_image_beautifier']) ? sanitize_text_field($form['aiwa_image_beautifier']) : "off";
        $select_topics_before_generate = isset($form['select-topics-before-generate']) && !empty($form['select-topics-before-generate']) ? sanitize_text_field($form['select-topics-before-generate']) : "off";
        $intrduction_size = isset($form['introduction-size']) && !empty($form['introduction-size']) ? sanitize_text_field($form['introduction-size']) : "short";
        $conclusion_size = isset($form['conclusion-size']) && !empty($form['conclusion-size']) ? sanitize_text_field($form['conclusion-size']) : "short";

        $auto_generate_image = isset($form['auto-generate-image']) && !empty($form['auto-generate-image']) ? sanitize_text_field($form['auto-generate-image']) : "off";
        $image_experiments = isset($form['image_experiments']) && is_array($form['image_experiments']) ? array_keys($form['image_experiments']) : array();
        $image_experiments = array_map('sanitize_text_field', $image_experiments);

        $user_roles = isset($form['user_roles']) && is_array($form['user_roles']) ? array_keys($form['user_roles']) : array();
        $user_roles = array_map('sanitize_text_field', $user_roles);

        if (!isset($user_roles['administrator'])){
            $user_roles[] = 'administrator';
        }

        $post_types = isset($form['post_types']) && is_array($form['post_types']) ? array_keys($form['post_types']) : array();
        $post_types = array_map('sanitize_text_field', $post_types);

        $page_builders = isset($form['page_builders']) && is_array($form['page_builders']) ? array_keys($form['page_builders']) : array();
        $page_builders = array_map('sanitize_text_field', $page_builders);

        if (isset($form['from-aiwa-settings'])&& sanitize_key($form['from-aiwa-settings']) == "1" && strpos(sanitize_text_field($form['api-key']), '*')==false && !empty(sanitize_text_field($form['api-key']))){
            $this->ajax->setSettings('api-key', $api_key);
        }

        $collapse_aiwa = isset($form['collapse_aiwa']) && !empty($form['collapse_aiwa']) ? sanitize_text_field($form['collapse_aiwa']) : "off";

        $this->ajax->setSettings('best-of', $best_of);
        $this->ajax->setSettings('frequency-penalty', $frequency_penalty);
        $this->ajax->setSettings('max-tokens', $max_tokens);
        $this->ajax->setSettings('presence-penalty', $presence_penalty);
        $this->ajax->setSettings('temperature', $temperature);
        $this->ajax->setSettings('top-p', $top_p);
        $this->ajax->setSettings('ai-image-size', $ai_image_size);

        /*from content tab*/
        $this->ajax->setSettings('select-title-before-generate', $aiwa_title_before_generate);
        $this->ajax->setSettings('how-many-titles-show-first', $how_many_titles_show_first);
        $this->ajax->setSettings('ai-content-structure', $ai_content_structure);
        $this->ajax->setSettings('content-length', $content_length);
        $this->ajax->setSettings('writing-style', $writing_Style);
        $this->ajax->setSettings('writing-tone', $writing_tone);
        $this->ajax->setSettings('aiwa-topics-tag', $topics_heading_tag);
        $this->ajax->setSettings('topics-count', $topics_count);
        $this->ajax->setSettings('add-introduction', $add_introduction);
        $this->ajax->setSettings('add-introduction-text', $add_introduction_text);
        $this->ajax->setSettings('add-conclusion', $add_conclusion);
        $this->ajax->setSettings('add-conclusion-text', $add_conclusion_text);
        $this->ajax->setSettings('add-call-to-action', $add_call_to_action);
        $this->ajax->setSettings('call-to-action-url', $call_to_action_url);
        $this->ajax->setSettings('call-to-action-position', $call_to_action_position);
        $this->ajax->setSettings('pros-and-cons-count', $pros_and_cons_count);
        $this->ajax->setSettings('list-items-count', $list_items_count);
        $this->ajax->setSettings('faq-items-count', $faq_items_count);
        $this->ajax->setSettings('article-paragraphs-count', $article_paragraphs_count);
        $this->ajax->setSettings('first-person-name', $first_person_name);
        $this->ajax->setSettings('second-person-name', $second_person_name);
        $this->ajax->setSettings('generate-title', $generate_title);
        $this->ajax->setSettings('aiwa_image_beautifier', $aiwa_image_beautifier);
        $this->ajax->setSettings('add-excerpt', $add_excerpt);
        $this->ajax->setSettings('select-topics-before-generate', $select_topics_before_generate);
        $this->ajax->setSettings('excerpt_number_of_words', intval($excerpt_number_of_words));
        $this->ajax->setSettings('introduction-size', $intrduction_size);
        $this->ajax->setSettings('conclusion-size', $conclusion_size);
        $this->ajax->setSettings('auto-generate-image', $auto_generate_image);
        $this->ajax->setSettings('aiwa-language', $aiwa_language);
        $this->ajax->setSettings('aiwa_language_text', $aiwa_language_text);
        $this->ajax->setSettings('previously_failed', $previously_failed);
        $this->ajax->setSettings('super_fast_generation_mode', $super_fast_generation_mode);
        $this->ajax->setSettings('how_many_minutes', $how_many_minutes);

        $this->ajax->setSettings('image_experiments', $image_experiments);

        if (isset($form['user_roles'])){
            $this->ajax->setSettings('user_roles', $user_roles);
        }
        if (isset($form['post_types'])){
            $this->ajax->setSettings('post_types', $post_types);
        }
        if (isset($form['page_builders'])){
            $this->ajax->setSettings('page_builders', $page_builders);
        }
        if (isset($form['collapse_aiwa'])){
            $this->ajax->setSettings('collapse_aiwa', $collapse_aiwa);
        }

        if (isset($form['aiwa_auto_content_settings'])&&sanitize_key($form['aiwa_auto_content_settings'])=='true'){
            $titles_count = isset($form['titles-count']) && !empty($form['titles-count']) ? sanitize_text_field($form['titles-count']) : "5";
            $this->ajax->setSettings('titles_count', $titles_count);
        }

        /*this action will help the developers to add new or edit field data */
        do_action('aiwa_save_settings', $form);

        if ($ai_image_size =='custom' ){
            $custom_ai_image_size = isset($form['custom-ai-image-size']) && !empty($form['custom-ai-image-size']) ? sanitize_text_field($form['custom-ai-image-size']) : "512x512";
            $this->ajax->setSettings('custom-ai-image-size', $custom_ai_image_size);
        }

        if (isset($form['image_generation_mode'])){
            $new_images_with_existing = isset($form['new_images_with_existing']) && !empty($form['image_generation_mode']) ? sanitize_text_field($form['new_images_with_existing']) : 'off';
            $number_of_image = isset($form['number_of_image']) && !empty($form['number_of_image']) ? sanitize_text_field($form['number_of_image']) : '3';

            $this->ajax->setSettings('new_images_with_existing', $new_images_with_existing);
            $this->ajax->setSettings('number_of_image', $number_of_image);
        }


        wp_send_json_success($form);

        wp_die();
    }
}
