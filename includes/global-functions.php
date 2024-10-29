<?php

ini_set('max_execution_time', '500');
function aiwa_checkNonce()
{
    $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

    if(!wp_verify_nonce( $nonce, "rc-nonce" )){
        //echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
        wp_send_json_error('nonce_verify_error');
        die();
    }

}

if (!function_exists('rc_isJson')){
    function rc_isJson($str) {
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists('rc_extractJson')){
    function rc_extractJson($str) {
        preg_match('/({.*})/', $str, $match);
        if (count($match) > 0) {
            return $match[0];
        } else {
            return null;
        }
    }
}

function aiwa_get_post_types(){
    $exclude_types = array('attachment', 'revision', 'nav_menu_item', 'oembed_cache', 'user_request');
    $args = array(
        'public'   => true,
    );
    $output = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'
    $post_types = get_post_types($args, $output, $operator);
    $post_types = array_diff($post_types, $exclude_types);

    return $post_types;
}

function aiwa_add_select_option($name, $value="", $isSelected=false, $id="", $echo=true, $isDisabled=false){
    if (!empty($name) && empty($value)){
        $value = str_replace(array(' ', '-'), '', strtolower($name));
    }
    $isSelected = $isSelected ? 'selected' : '';
    $isDisabled = $isDisabled ? 'disabled' : '';
    if ($echo){
        if (!empty($id)){
            echo '<option id="aiwa-'.esc_attr($id).'" '.esc_attr($isSelected).' value="'.esc_attr($value).'" '.$isDisabled.'> '.esc_attr($name).'</option>';
        }else{
            echo '<option '.esc_attr($isSelected).' value="'.esc_attr($value).'" '.$isDisabled.'> '.esc_attr($name).'</option>';
        }
    }else{
        if (!empty($id)){
            return '<option id="aiwa-'.esc_attr($id).'" '.esc_attr($isSelected).' value="'.esc_attr($value).'" '.$isDisabled.'> '.esc_attr($name).'</option>';
        }else{
            return '<option '.esc_attr($isSelected).' value="'.esc_attr($value).'" '.$isDisabled.'> '.esc_attr($name).'</option>';
        }
    }

}

function aiwa_get_time_after($after='10 minutes', $format="g:i A"){
    $current_time = current_time( "Y-m-d H:i:s", false );
    return date($format, strtotime("+ " . $after, strtotime($current_time)));
}
function aiwaHasAccess()
{
    require( ABSPATH . WPINC . '/pluggable.php' );
    $capabilities = get_option('ai_writing_assistant__user_roles',array('administrator'));

    if (!empty($capabilities)){
        foreach ($capabilities as $cap) {
            if (current_user_can($cap)){
                return true;
                break;
            }
        }
    }
    if (current_user_can('administrator')){
        return true;
    }
    return false;
}
function aiwa_get_post_type () {
    global $pagenow;

    $post_type = NULL;

    if(empty($post_type) && isset($_REQUEST['post_type']) && !empty($_REQUEST['post_type']))
        $post_type = sanitize_key($_REQUEST['post_type']);

    if(empty($post_type) && 'edit.php' == $pagenow)
        $post_type = 'post';

    if(empty($post_type) && 'post-new.php' == $pagenow)
        $post_type = 'post';

    if(empty($post_type) && isset($_REQUEST['post']) && !empty($_REQUEST['post']) && function_exists('get_post_type') && $get_post_type = get_post_type((int)$_REQUEST['post']))
        $post_type = $get_post_type;

    return $post_type;
}

function aiwa_get_content_structure_options(){
    $key = 'ai_writing_assistant__';
    aiwa_add_select_option(__('Topic-Wise', 'ai-writing-assistant'), 'topic_wise', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='topic_wise');
    aiwa_add_select_option(__('Article', 'ai-writing-assistant'), 'article', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='article');
    aiwa_add_select_option(__('Review', 'ai-writing-assistant'), 'review', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='review');
    aiwa_add_select_option(__('Opinion', 'ai-writing-assistant'), 'opinion', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='opinion');
    aiwa_add_select_option(__('FAQ', 'ai-writing-assistant'), 'faq', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='faq');

    aiwa_add_select_option(__('Pros and Cons (Pro)', 'ai-writing-assistant'), 'pros_and_cons', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='pros_and_cons', "", true, true);
    aiwa_add_select_option(__('Tutorial (Pro)', 'ai-writing-assistant'), 'tutorial', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='tutorial', "", true, true);
    aiwa_add_select_option(__('How-to (Pro)', 'ai-writing-assistant'), 'how-to', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='how-to', "", true, true);
    aiwa_add_select_option(__('Analysis (Pro)', 'ai-writing-assistant'), 'analysis', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='analysis', "", true, true);
    aiwa_add_select_option(__('Interviews (Pro)', 'ai-writing-assistant'), 'interviews', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='interviews', "", true, true);
    aiwa_add_select_option(__('Case-study (Pro)', 'ai-writing-assistant'), 'case-study', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='case-study', "", true, true);
    aiwa_add_select_option(__('Guide (Pro)', 'ai-writing-assistant'), 'guide', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='guide', "", true, true);
    aiwa_add_select_option(__('Email (Pro)', 'ai-writing-assistant'), 'email', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='email', "", true, true);
    aiwa_add_select_option(__('Youtube script (Pro)', 'ai-writing-assistant'), 'youtube_script', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='youtube_script', "", true, true);
    aiwa_add_select_option(__('Social Media Post (Pro)', 'ai-writing-assistant'), 'social_media_post', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='social_media_post', "", true, true);
    aiwa_add_select_option(__('Table (Pro)', 'ai-writing-assistant'), 'table', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='table', "", true, true);
}

function aiwa_get_scheduled_content_structure_options($id="", $content_structure="topic_wise", $echo = true, $isDisabled= false){
    $disabled = $isDisabled ? "disabled": "";
    ob_start();
    echo '<select name="aiwa-content-structure" id="'.$id.'" '.$disabled.'>';
    aiwa_add_select_option(__('Topic-Wise', 'ai-writing-assistant'), 'topic_wise', $content_structure =='topic_wise');
    aiwa_add_select_option(__('Article', 'ai-writing-assistant'), 'article', $content_structure =='article');
    aiwa_add_select_option(__('Review', 'ai-writing-assistant'), 'review', $content_structure =='review');
    aiwa_add_select_option(__('Opinion', 'ai-writing-assistant'), 'opinion', $content_structure =='opinion');
    aiwa_add_select_option(__('FAQ', 'ai-writing-assistant'), 'faq', $content_structure =='faq');

    /*pro*/
    aiwa_add_select_option(__('Pros and Cons (Pro)', 'ai-writing-assistant'), 'pros_and_cons', $content_structure =='pros_and_cons', "", true, true);
    aiwa_add_select_option(__('Tutorial (Pro)', 'ai-writing-assistant'), 'tutorial', $content_structure =='tutorial', "", true, true);
    aiwa_add_select_option(__('How-to (Pro)', 'ai-writing-assistant'), 'how-to', $content_structure == 'how-to', "", true, true);
    aiwa_add_select_option(__('Analysis (Pro)', 'ai-writing-assistant'), 'analysis', $content_structure == 'analysis', "", true, true);
    aiwa_add_select_option(__('Interviews (Pro)', 'ai-writing-assistant'), 'interviews', $content_structure == 'interviews', "", true, true);
    aiwa_add_select_option(__('Case-study (Pro)', 'ai-writing-assistant'), 'case-study', $content_structure == 'case-study', "", true, true);
    aiwa_add_select_option(__('Guide (Pro)', 'ai-writing-assistant'), 'guide', $content_structure =='guide', "", true, true);
    aiwa_add_select_option(__('Email (Pro)', 'ai-writing-assistant'), 'email', $content_structure =='email', "", true, true);
    aiwa_add_select_option(__('Youtube script (Pro)', 'ai-writing-assistant'), 'youtube_script', $content_structure =='youtube_script', "", true, true);
    aiwa_add_select_option(__('Social Media Post (Pro)', 'ai-writing-assistant'), 'social_media_post', $content_structure =='social_media_post', "", true, true);
    aiwa_add_select_option(__('Table (Pro)', 'ai-writing-assistant'), 'table', $content_structure =='table', "", true, true);
    echo '</select>';

    if ($echo){
        echo ob_get_clean();
    }
    else{
        return ob_get_clean();
    }
}

function aiwa_get_topics_tag_options(){
    $key = 'ai_writing_assistant__';
    aiwa_add_select_option(__('h1', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h1');
    aiwa_add_select_option(__('h2', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h2');
    aiwa_add_select_option(__('h3', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h3');
    aiwa_add_select_option(__('h4', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h4');
    aiwa_add_select_option(__('h5', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h5');
    aiwa_add_select_option(__('h6', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h6');
}


function aiwa_get_writing_tone_options(){
    $key = 'ai_writing_assistant__';
    aiwa_add_select_option(__('Informative', 'ai-writing-assistant'), 'informative', esc_attr(get_option($key.'writing-tone', 'informative')) =='informative');
    aiwa_add_select_option(__('Professional', 'ai-writing-assistant'), 'professional', esc_attr(get_option($key.'writing-tone', 'informative')) =='professional');
    aiwa_add_select_option(__('Approachable', 'ai-writing-assistant'), 'approachable', esc_attr(get_option($key.'writing-tone', 'informative')) =='approachable');
    aiwa_add_select_option(__('Confident', 'ai-writing-assistant'), 'confident', esc_attr(get_option($key.'writing-tone', 'informative')) =='confident');
    aiwa_add_select_option(__('Enthusiastic', 'ai-writing-assistant'), 'enthusiastic', esc_attr(get_option($key.'writing-tone', 'informative')) =='enthusiastic');
    aiwa_add_select_option(__('Casual', 'ai-writing-assistant'), 'casual', esc_attr(get_option($key.'writing-tone', 'informative')) =='casual');
    aiwa_add_select_option(__('Respectful', 'ai-writing-assistant'), 'respectful', esc_attr(get_option($key.'writing-tone', 'informative')) =='respectful');
    aiwa_add_select_option(__('Sarcastic', 'ai-writing-assistant'), 'sarcastic', esc_attr(get_option($key.'writing-tone', 'informative')) =='sarcastic');
    aiwa_add_select_option(__('Serious', 'ai-writing-assistant'), 'serious', esc_attr(get_option($key.'writing-tone', 'informative')) =='serious');
    aiwa_add_select_option(__('Thoughtful', 'ai-writing-assistant'), 'thoughtful', esc_attr(get_option($key.'writing-tone', 'informative')) =='thoughtful');
    aiwa_add_select_option(__('Witty', 'ai-writing-assistant'), 'witty', esc_attr(get_option($key.'writing-tone', 'informative')) =='witty');
    aiwa_add_select_option(__('Passionate', 'ai-writing-assistant'), 'passionate', esc_attr(get_option($key.'writing-tone', 'informative')) =='passionate');
    aiwa_add_select_option(__('Lighthearted', 'ai-writing-assistant'), 'lighthearted', esc_attr(get_option($key.'writing-tone', 'informative')) =='lighthearted');
    aiwa_add_select_option(__('Hilarious', 'ai-writing-assistant'), 'hilarious', esc_attr(get_option($key.'writing-tone', 'informative')) =='hilarious');
    aiwa_add_select_option(__('Soothing', 'ai-writing-assistant'), 'soothing', esc_attr(get_option($key.'writing-tone', 'informative')) =='soothing');
    aiwa_add_select_option(__('Emotional', 'ai-writing-assistant'), 'emotional', esc_attr(get_option($key.'writing-tone', 'informative')) =='emotional');
    aiwa_add_select_option(__('Inspirational', 'ai-writing-assistant'), 'inspirational', esc_attr(get_option($key.'writing-tone', 'informative')) =='inspirational');
    aiwa_add_select_option(__('Objective', 'ai-writing-assistant'), 'objective', esc_attr(get_option($key.'writing-tone', 'informative')) =='objective');
    aiwa_add_select_option(__('Persuasive', 'ai-writing-assistant'), 'persuasive', esc_attr(get_option($key.'writing-tone', 'informative')) =='persuasive');
    aiwa_add_select_option(__('Vivid', 'ai-writing-assistant'), 'vivid', esc_attr(get_option($key.'writing-tone', 'informative')) =='vivid');
    aiwa_add_select_option(__('Imaginative', 'ai-writing-assistant'), 'imaginative', esc_attr(get_option($key.'writing-tone', 'informative')) =='imaginative');
    aiwa_add_select_option(__('Musical', 'ai-writing-assistant'), 'musical', esc_attr(get_option($key.'writing-tone', 'informative')) =='musical');
    aiwa_add_select_option(__('Rhythmical', 'ai-writing-assistant'), 'rhythmical', esc_attr(get_option($key.'writing-tone', 'informative')) =='rhythmical');
    aiwa_add_select_option(__('Humorous', 'ai-writing-assistant'), 'humorous', esc_attr(get_option($key.'writing-tone', 'informative')) =='humorous');
    aiwa_add_select_option(__('Critical', 'ai-writing-assistant'), 'critical', esc_attr(get_option($key.'writing-tone', 'informative')) =='critical');
    aiwa_add_select_option(__('Clear', 'ai-writing-assistant'), 'clear', esc_attr(get_option($key.'writing-tone', 'informative')) =='clear');
    aiwa_add_select_option(__('Neutral', 'ai-writing-assistant'), 'neutral', esc_attr(get_option($key.'writing-tone', 'informative')) =='neutral');
    aiwa_add_select_option(__('Objective', 'ai-writing-assistant'), 'objective', esc_attr(get_option($key.'writing-tone', 'informative')) =='objective');
    aiwa_add_select_option(__('Biased', 'ai-writing-assistant'), 'biased', esc_attr(get_option($key.'writing-tone', 'informative')) =='biased');
    aiwa_add_select_option(__('Passionate', 'ai-writing-assistant'), 'passionate', esc_attr(get_option($key.'writing-tone', 'informative')) =='passionate');
    aiwa_add_select_option(__('Argumentative', 'ai-writing-assistant'), 'argumentative', esc_attr(get_option($key.'writing-tone', 'informative')) =='argumentative');
    aiwa_add_select_option(__('Reflective', 'ai-writing-assistant'), 'reflective', esc_attr(get_option($key.'writing-tone', 'informative')) =='reflective');
    aiwa_add_select_option(__('Helpful', 'ai-writing-assistant'), 'helpful', esc_attr(get_option($key.'writing-tone', 'informative')) =='helpful');
    aiwa_add_select_option(__('Connective', 'ai-writing-assistant'), 'connective', esc_attr(get_option($key.'writing-tone', 'informative')) =='connective');
    aiwa_add_select_option(__('Assertive', 'ai-writing-assistant'), 'assertive', esc_attr(get_option($key.'writing-tone', 'informative')) =='assertive');
    aiwa_add_select_option(__('Energetic', 'ai-writing-assistant'), 'energetic', esc_attr(get_option($key.'writing-tone', 'informative')) =='energetic');
    aiwa_add_select_option(__('Relaxed', 'ai-writing-assistant'), 'relaxed', esc_attr(get_option($key.'writing-tone', 'informative')) =='relaxed');
    aiwa_add_select_option(__('Polite', 'ai-writing-assistant'), 'polite', esc_attr(get_option($key.'writing-tone', 'informative')) =='polite');
    aiwa_add_select_option(__('Clever', 'ai-writing-assistant'), 'clever', esc_attr(get_option($key.'writing-tone', 'informative')) =='clever');
    aiwa_add_select_option(__('Funny', 'ai-writing-assistant'), 'funny', esc_attr(get_option($key.'writing-tone', 'informative')) =='funny');
    aiwa_add_select_option(__('Amusing', 'ai-writing-assistant'), 'amusing', esc_attr(get_option($key.'writing-tone', 'informative')) =='amusing');
    aiwa_add_select_option(__('Comforting', 'ai-writing-assistant'), 'comforting', esc_attr(get_option($key.'writing-tone', 'informative')) =='comforting');
}

function aiwa_get_writing_styles_options(){
    $key = 'ai_writing_assistant__';
    aiwa_add_select_option(__('normal', 'ai-writing-assistant'), 'normal', esc_attr(get_option($key.'writing-style', 'normal')) =='normal');
    aiwa_add_select_option(__('business', 'ai-writing-assistant'), 'business', esc_attr(get_option($key.'writing-style', 'normal')) =='business');
    aiwa_add_select_option(__('legal', 'ai-writing-assistant'), 'legal', esc_attr(get_option($key.'writing-style', 'normal')) =='legal');
    aiwa_add_select_option(__('technical', 'ai-writing-assistant'), 'technical', esc_attr(get_option($key.'writing-style', 'normal')) =='technical');
    aiwa_add_select_option(__('marketing', 'ai-writing-assistant'), 'marketing', esc_attr(get_option($key.'writing-style', 'normal')) =='marketing');
    aiwa_add_select_option(__('creative', 'ai-writing-assistant'), 'creative', esc_attr(get_option($key.'writing-style', 'normal')) =='creative');
    aiwa_add_select_option(__('narrative', 'ai-writing-assistant'), 'narrative', esc_attr(get_option($key.'writing-style', 'normal')) =='narrative');
    aiwa_add_select_option(__('expository', 'ai-writing-assistant'), 'expository', esc_attr(get_option($key.'writing-style', 'normal')) =='expository');
    aiwa_add_select_option(__('reflective', 'ai-writing-assistant'), 'reflective', esc_attr(get_option($key.'writing-style', 'normal')) =='reflective');
    aiwa_add_select_option(__('persuasive', 'ai-writing-assistant'), 'persuasive', esc_attr(get_option($key.'writing-style', 'normal')) =='persuasive');
    aiwa_add_select_option(__('descriptive', 'ai-writing-assistant'), 'descriptive', esc_attr(get_option($key.'writing-style', 'normal')) =='descriptive');
    aiwa_add_select_option(__('instructional', 'ai-writing-assistant'), 'instructional', esc_attr(get_option($key.'writing-style', 'normal')) =='instructional');
    aiwa_add_select_option(__('news', 'ai-writing-assistant'), 'news', esc_attr(get_option($key.'writing-style', 'normal')) =='news');
    aiwa_add_select_option(__('personal', 'ai-writing-assistant'), 'personal', esc_attr(get_option($key.'writing-style', 'normal')) =='personal');
    aiwa_add_select_option(__('travel', 'ai-writing-assistant'), 'travel', esc_attr(get_option($key.'writing-style', 'normal')) =='travel');
    aiwa_add_select_option(__('recipe', 'ai-writing-assistant'), 'recipe', esc_attr(get_option($key.'writing-style', 'normal')) =='recipe');
    aiwa_add_select_option(__('poetic', 'ai-writing-assistant'), 'poetic', esc_attr(get_option($key.'writing-style', 'normal')) =='poetic');
    aiwa_add_select_option(__('satirical', 'ai-writing-assistant'), 'satirical', esc_attr(get_option($key.'writing-style', 'normal')) =='satirical');
    aiwa_add_select_option(__('formal', 'ai-writing-assistant'), 'formal', esc_attr(get_option($key.'writing-style', 'normal')) =='formal');
    aiwa_add_select_option(__('informal', 'ai-writing-assistant'), 'informal', esc_attr(get_option($key.'writing-style', 'normal')) =='informal');
}


function aiwa_get_writing_styles_selectbox($selectedValue="normal", $isDisabled=false){
    ob_start();
    $disabled = $isDisabled ? "disabled" : "";
        echo '<select id="aiwa-writing-style" name="writing-style" '.$disabled.'>';
            aiwa_add_select_option(__('normal', 'ai-writing-assistant'), 'normal', $selectedValue =='normal');
            aiwa_add_select_option(__('business', 'ai-writing-assistant'), 'business', $selectedValue =='business');
            aiwa_add_select_option(__('legal', 'ai-writing-assistant'), 'legal', $selectedValue =='legal');
            aiwa_add_select_option(__('technical', 'ai-writing-assistant'), 'technical', $selectedValue =='technical');
            aiwa_add_select_option(__('marketing', 'ai-writing-assistant'), 'marketing', $selectedValue =='marketing');
            aiwa_add_select_option(__('creative', 'ai-writing-assistant'), 'creative', $selectedValue =='creative');
            aiwa_add_select_option(__('narrative', 'ai-writing-assistant'), 'narrative', $selectedValue =='narrative');
            aiwa_add_select_option(__('expository', 'ai-writing-assistant'), 'expository', $selectedValue =='expository');
            aiwa_add_select_option(__('reflective', 'ai-writing-assistant'), 'reflective', $selectedValue =='reflective');
            aiwa_add_select_option(__('persuasive', 'ai-writing-assistant'), 'persuasive', $selectedValue =='persuasive');
            aiwa_add_select_option(__('descriptive', 'ai-writing-assistant'), 'descriptive', $selectedValue =='descriptive');
            aiwa_add_select_option(__('instructional', 'ai-writing-assistant'), 'instructional', $selectedValue =='instructional');
            aiwa_add_select_option(__('news', 'ai-writing-assistant'), 'news', $selectedValue =='news');
            aiwa_add_select_option(__('personal', 'ai-writing-assistant'), 'personal', $selectedValue =='personal');
            aiwa_add_select_option(__('travel', 'ai-writing-assistant'), 'travel', $selectedValue =='travel');
            aiwa_add_select_option(__('recipe', 'ai-writing-assistant'), 'recipe', $selectedValue =='recipe');
            aiwa_add_select_option(__('poetic', 'ai-writing-assistant'), 'poetic', $selectedValue =='poetic');
            aiwa_add_select_option(__('satirical', 'ai-writing-assistant'), 'satirical', $selectedValue =='satirical');
            aiwa_add_select_option(__('formal', 'ai-writing-assistant'), 'formal', $selectedValue =='formal');
            aiwa_add_select_option(__('informal', 'ai-writing-assistant'), 'informal', $selectedValue =='informal');
        echo '</select>';
    return ob_get_clean();
}


function aiwa_get_writing_tones_selectbox($selectedValue="informative", $isDisabled=false){
    ob_start();
    $disabled = $isDisabled ? "disabled" : "";
        echo '<select id="aiwa-writing-tone" name="writing-tone" '.$disabled.'>';
            aiwa_add_select_option(__('Informative', 'ai-writing-assistant'), 'informative', $selectedValue =='informative');
            aiwa_add_select_option(__('Professional', 'ai-writing-assistant'), 'professional', $selectedValue =='professional');
            aiwa_add_select_option(__('Approachable', 'ai-writing-assistant'), 'approachable', $selectedValue =='approachable');
            aiwa_add_select_option(__('Confident', 'ai-writing-assistant'), 'confident', $selectedValue =='confident');
            aiwa_add_select_option(__('Enthusiastic', 'ai-writing-assistant'), 'enthusiastic', $selectedValue =='enthusiastic');
            aiwa_add_select_option(__('Casual', 'ai-writing-assistant'), 'casual', $selectedValue =='casual');
            aiwa_add_select_option(__('Respectful', 'ai-writing-assistant'), 'respectful', $selectedValue =='respectful');
            aiwa_add_select_option(__('Sarcastic', 'ai-writing-assistant'), 'sarcastic', $selectedValue =='sarcastic');
            aiwa_add_select_option(__('Serious', 'ai-writing-assistant'), 'serious', $selectedValue =='serious');
            aiwa_add_select_option(__('Thoughtful', 'ai-writing-assistant'), 'thoughtful', $selectedValue =='thoughtful');
            aiwa_add_select_option(__('Witty', 'ai-writing-assistant'), 'witty', $selectedValue =='witty');
            aiwa_add_select_option(__('Passionate', 'ai-writing-assistant'), 'passionate', $selectedValue =='passionate');
            aiwa_add_select_option(__('Lighthearted', 'ai-writing-assistant'), 'lighthearted', $selectedValue =='lighthearted');
            aiwa_add_select_option(__('Hilarious', 'ai-writing-assistant'), 'hilarious', $selectedValue =='hilarious');
            aiwa_add_select_option(__('Soothing', 'ai-writing-assistant'), 'soothing', $selectedValue =='soothing');
            aiwa_add_select_option(__('Emotional', 'ai-writing-assistant'), 'emotional', $selectedValue =='emotional');
            aiwa_add_select_option(__('Inspirational', 'ai-writing-assistant'), 'inspirational', $selectedValue =='inspirational');
            aiwa_add_select_option(__('Objective', 'ai-writing-assistant'), 'objective', $selectedValue =='objective');
            aiwa_add_select_option(__('Persuasive', 'ai-writing-assistant'), 'persuasive', $selectedValue =='persuasive');
            aiwa_add_select_option(__('Vivid', 'ai-writing-assistant'), 'vivid', $selectedValue =='vivid');
            aiwa_add_select_option(__('Imaginative', 'ai-writing-assistant'), 'imaginative', $selectedValue =='imaginative');
            aiwa_add_select_option(__('Musical', 'ai-writing-assistant'), 'musical', $selectedValue =='musical');
            aiwa_add_select_option(__('Rhythmical', 'ai-writing-assistant'), 'rhythmical', $selectedValue =='rhythmical');
            aiwa_add_select_option(__('Humorous', 'ai-writing-assistant'), 'humorous', $selectedValue =='humorous');
            aiwa_add_select_option(__('Critical', 'ai-writing-assistant'), 'critical', $selectedValue =='critical');
            aiwa_add_select_option(__('Clear', 'ai-writing-assistant'), 'clear', $selectedValue =='clear');
            aiwa_add_select_option(__('Neutral', 'ai-writing-assistant'), 'neutral', $selectedValue =='neutral');
            aiwa_add_select_option(__('Objective', 'ai-writing-assistant'), 'objective', $selectedValue =='objective');
            aiwa_add_select_option(__('Biased', 'ai-writing-assistant'), 'biased', $selectedValue =='biased');
            aiwa_add_select_option(__('Passionate', 'ai-writing-assistant'), 'passionate', $selectedValue =='passionate');
            aiwa_add_select_option(__('Argumentative', 'ai-writing-assistant'), 'argumentative', $selectedValue =='argumentative');
            aiwa_add_select_option(__('Reflective', 'ai-writing-assistant'), 'reflective', $selectedValue =='reflective');
            aiwa_add_select_option(__('Helpful', 'ai-writing-assistant'), 'helpful', $selectedValue =='helpful');
            aiwa_add_select_option(__('Connective', 'ai-writing-assistant'), 'connective', $selectedValue =='connective');
            aiwa_add_select_option(__('Assertive', 'ai-writing-assistant'), 'assertive', $selectedValue =='assertive');
            aiwa_add_select_option(__('Energetic', 'ai-writing-assistant'), 'energetic', $selectedValue =='energetic');
            aiwa_add_select_option(__('Relaxed', 'ai-writing-assistant'), 'relaxed', $selectedValue =='relaxed');
            aiwa_add_select_option(__('Polite', 'ai-writing-assistant'), 'polite', $selectedValue =='polite');
            aiwa_add_select_option(__('Clever', 'ai-writing-assistant'), 'clever', $selectedValue =='clever');
            aiwa_add_select_option(__('Funny', 'ai-writing-assistant'), 'funny', $selectedValue =='funny');
            aiwa_add_select_option(__('Amusing', 'ai-writing-assistant'), 'amusing', $selectedValue =='amusing');
            aiwa_add_select_option(__('Comforting', 'ai-writing-assistant'), 'comforting', $selectedValue =='comforting');
        echo '</select>';
    return ob_get_clean();
}

function aiwa_get_languages_options(){
    $key = 'ai_writing_assistant__';
    ?>
    <option data-name="Deutsch" id="dde" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "de"? "selected":""; ?> value="de">Deutsch</option>
    <option data-name="English" id="den" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "en"? "selected":""; ?> value="en">English</option>
    <option data-name="español" id="des" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "es"? "selected":""; ?> value="es">español</option>
    <option data-name="español (Latinoamérica)" id="des-419" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "es-419"? "selected":""; ?> value="es-419">español (Latinoamérica)</option>
    <option data-name="français" id="dfr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fr"? "selected":""; ?> value="fr">français</option>
    <option data-name="hrvatski" id="dhr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "hr"? "selected":""; ?> value="hr">hrvatski</option>
    <option data-name="italiano" id="dit" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "it"? "selected":""; ?> value="it">italiano</option>
    <option data-name="Nederlands" id="dnl" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "nl"? "selected":""; ?> value="nl">Nederlands</option>
    <option data-name="polski" id="dpl" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "pl"? "selected":""; ?> value="pl">polski</option>
    <option data-name="português (Brasil)" id="dpt-BR" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "pt-BR"? "selected":""; ?> value="pt-BR">português (Brasil)</option>
    <option data-name="português (Portugal)" id="dpt-PT" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "pt-PT"? "selected":""; ?> value="pt-PT">português (Portugal)</option>
    <option data-name="Tiếng Việt" id="dvi" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "vi"? "selected":""; ?> value="vi">Tiếng Việt</option>
    <option data-name="Türkçe" id="dtr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tr"? "selected":""; ?> value="tr">Türkçe</option>
    <option data-name="русский" id="dru" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ru"? "selected":""; ?> value="ru">русский</option>
    <option data-name="العربية" id="dar" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ar"? "selected":""; ?> value="ar">العربية</option>
    <option data-name="ไทย" id="dth" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "th"? "selected":""; ?> value="th">ไทย</option>
    <option data-name="한국어" id="dko" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ko"? "selected":""; ?> value="ko">한국어</option>
    <option data-name="中文 (简体)" id="dzh-CN" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "zh-CN"? "selected":""; ?> value="zh-CN">中文 (简体)</option>
    <option data-name="中文 (繁體)" id="dzh-TW" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "zh-TW"? "selected":""; ?> value="zh-TW">中文 (繁體)</option>
    <option data-name="香港中文" id="dzh-HK" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "zh-HK"? "selected":""; ?> value="zh-HK">香港中文</option>
    <option data-name="日本語" id="dja" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ja"? "selected":""; ?> value="ja">日本語</option>
    <option data-name="Acoli" id="dach" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ach"? "selected":""; ?> value="ach">Acoli</option>
    <option data-name="Afrikaans" id="daf" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "af"? "selected":""; ?> value="af">Afrikaans</option>
    <option data-name="Akan" id="dak" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ak"? "selected":""; ?> value="ak">Akan</option>
    <option data-name="azərbaycan" id="daz" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "az"? "selected":""; ?> value="az">azərbaycan</option>
    <option data-name="Balinese" id="dban" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ban"? "selected":""; ?> value="ban">Balinese</option>
    <option data-name="Basa Sunda" id="dsu" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "su"? "selected":""; ?> value="su">Basa Sunda</option>
    <option data-name="Bork, bork, bork!" id="dxx-bork" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "xx-bork"? "selected":""; ?> value="xx-bork">Bork, bork, bork!</option>
    <option data-name="bosanski" id="dbs" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "bs"? "selected":""; ?> value="bs">bosanski</option>
    <option data-name="brezhoneg" id="dbr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "br"? "selected":""; ?> value="br">brezhoneg</option>
    <option data-name="català" id="dca" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ca"? "selected":""; ?> value="ca">català</option>
    <option data-name="Cebuano" id="dceb" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ceb"? "selected":""; ?> value="ceb">Cebuano</option>
    <option data-name="čeština" id="dcs" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "cs"? "selected":""; ?> value="cs">čeština</option>
    <option data-name="chiShona" id="dsn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sn"? "selected":""; ?> value="sn">chiShona</option>
    <option data-name="Corsican" id="dco" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "co"? "selected":""; ?> value="co">Corsican</option>
    <option data-name="créole haïtien" id="dht" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ht"? "selected":""; ?> value="ht">créole haïtien</option>
    <option data-name="Cymraeg" id="dcy" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "cy"? "selected":""; ?> value="cy">Cymraeg</option>
    <option data-name="dansk" id="dda" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "da"? "selected":""; ?> value="da">dansk</option>
    <option data-name="Èdè Yorùbá" id="dyo" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "yo"? "selected":""; ?> value="yo">Èdè Yorùbá</option>
    <option data-name="eesti" id="det" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "et"? "selected":""; ?> value="et">eesti</option>
    <option data-name="Elmer Fudd" id="dxx-elmer" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "xx-elmer"? "selected":""; ?> value="xx-elmer">Elmer Fudd</option>
    <option data-name="esperanto" id="deo" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "eo"? "selected":""; ?> value="eo">esperanto</option>
    <option data-name="euskara" id="deu" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "eu"? "selected":""; ?> value="eu">euskara</option>
    <option data-name="Eʋegbe" id="dee" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ee"? "selected":""; ?> value="ee">Eʋegbe</option>
    <option data-name="Filipino" id="dtl" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tl"? "selected":""; ?> value="tl">Filipino</option>
    <option data-name="Filipino" id="dfil" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fil"? "selected":""; ?> value="fil">Filipino</option>
    <option data-name="føroyskt" id="dfo" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fo"? "selected":""; ?> value="fo">føroyskt</option>
    <option data-name="Frysk" id="dfy" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fy"? "selected":""; ?> value="fy">Frysk</option>
    <option data-name="Ga" id="dgaa" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "gaa"? "selected":""; ?> value="gaa">Ga</option>
    <option data-name="Gaeilge" id="dga" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ga"? "selected":""; ?> value="ga">Gaeilge</option>
    <option data-name="Gàidhlig" id="dgd" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "gd"? "selected":""; ?> value="gd">Gàidhlig</option>
    <option data-name="galego" id="dgl" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "gl"? "selected":""; ?> value="gl">galego</option>
    <option data-name="Guarani" id="dgn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "gn"? "selected":""; ?> value="gn">Guarani</option>
    <option data-name="Hacker" id="dxx-hacker" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "xx-hacker"? "selected":""; ?> value="xx-hacker">Hacker</option>
    <option data-name="Hausa" id="dha" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ha"? "selected":""; ?> value="ha">Hausa</option>
    <option data-name="ʻŌlelo Hawaiʻi" id="dhaw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "haw"? "selected":""; ?> value="haw">ʻŌlelo Hawaiʻi</option>
    <option data-name="Ichibemba" id="dbem" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "bem"? "selected":""; ?> value="bem">Ichibemba</option>
    <option data-name="Igbo" id="dig" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ig"? "selected":""; ?> value="ig">Igbo</option>
    <option data-name="Ikirundi" id="drn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "rn"? "selected":""; ?> value="rn">Ikirundi</option>
    <option data-name="Indonesia" id="did" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "id"? "selected":""; ?> value="id">Indonesia</option>
    <option data-name="interlingua" id="dia" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ia"? "selected":""; ?> value="ia">interlingua</option>
    <option data-name="IsiXhosa" id="dxh" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "xh"? "selected":""; ?> value="xh">IsiXhosa</option>
    <option data-name="isiZulu" id="dzu" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "zu"? "selected":""; ?> value="zu">isiZulu</option>
    <option data-name="íslenska" id="dis" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "is"? "selected":""; ?> value="is">íslenska</option>
    <option data-name="Jawa" id="djw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "jw"? "selected":""; ?> value="jw">Jawa</option>
    <option data-name="Kinyarwanda" id="drw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "rw"? "selected":""; ?> value="rw">Kinyarwanda</option>
    <option data-name="Kiswahili" id="dsw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sw"? "selected":""; ?> value="sw">Kiswahili</option>
    <option data-name="Klingon" id="dtlh" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tlh"? "selected":""; ?> value="tlh">Klingon</option>
    <option data-name="Kongo" id="dkg" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "kg"? "selected":""; ?> value="kg">Kongo</option>
    <option data-name="kreol morisien" id="dmfe" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mfe"? "selected":""; ?> value="mfe">kreol morisien</option>
    <option data-name="Krio (Sierra Leone)" id="dkri" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "kri"? "selected":""; ?> value="kri">Krio (Sierra Leone)</option>
    <option data-name="Latin" id="dla" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "la"? "selected":""; ?> value="la">Latin</option>
    <option data-name="latviešu" id="dlv" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "lv"? "selected":""; ?> value="lv">latviešu</option>
    <option data-name="lea fakatonga" id="dto" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "to"? "selected":""; ?> value="to">lea fakatonga</option>
    <option data-name="lietuvių" id="dlt" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "lt"? "selected":""; ?> value="lt">lietuvių</option>
    <option data-name="lingála" id="dln" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ln"? "selected":""; ?> value="ln">lingála</option>
    <option data-name="Lozi" id="dloz" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "loz"? "selected":""; ?> value="loz">Lozi</option>
    <option data-name="Luba-Lulua" id="dlua" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "lua"? "selected":""; ?> value="lua">Luba-Lulua</option>
    <option data-name="Luganda" id="dlg" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "lg"? "selected":""; ?> value="lg">Luganda</option>
    <option data-name="magyar" id="dhu" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "hu"? "selected":""; ?> value="hu">magyar</option>
    <option data-name="Malagasy" id="dmg" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mg"? "selected":""; ?> value="mg">Malagasy</option>
    <option data-name="Malti" id="dmt" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mt"? "selected":""; ?> value="mt">Malti</option>
    <option data-name="Māori" id="dmi" <?php echo esc_attr(get_option($key."aiwa-language","en") == "mi"? "selected":""); ?> value="mi">Māori</option>
    <option data-name="Melayu" id="dms" <?php echo esc_attr(get_option($key."aiwa-language","en") == "ms"? "selected":""); ?> value="ms">Melayu</option>
    <option data-name="Nigerian Pidgin" id="dpcm" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "pcm"? "selected":""; ?> value="pcm">Nigerian Pidgin</option>
    <option data-name="norsk" id="dno" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "no"? "selected":""; ?> value="no">norsk</option>
    <option data-name="norsk nynorsk" id="dnn" <?php echo esc_attr(get_option($key."aiwa-language","en") == "nn"? "selected":""); ?> value="nn">norsk nynorsk</option>
    <option data-name="Northern Sotho" id="dnso" <?php echo esc_attr(get_option($key."aiwa-language","en") == "nso"? "selected":""); ?> value="nso">Northern Sotho</option>
    <option data-name="Nyanja" id="dny" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ny"? "selected":""; ?> value="ny">Nyanja</option>
    <option data-name="o‘zbek" id="duz" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "uz"? "selected":""; ?> value="uz">o‘zbek</option>
    <option data-name="Occitan" id="doc" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "oc"? "selected":""; ?> value="oc">Occitan</option>
    <option data-name="Oromoo" id="dom" <?php echo esc_attr(get_option($key."aiwa-language","en") == "om"? "selected":""); ?> value="om">Oromoo</option>
    <option data-name="Pirate" id="dxx-pirate" <?php echo esc_attr(get_option($key."aiwa-language","en") == "xx-pirate"? "selected":""); ?> value="xx-pirate">Pirate</option>
    <option data-name="română" id="dro" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ro"? "selected":""; ?> value="ro">română</option>
    <option data-name="rumantsch" id="drm" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "rm"? "selected":""; ?> value="rm">rumantsch</option>
    <option data-name="Runasimi" id="dqu" <?php echo esc_attr(get_option($key."aiwa-language","en") == "qu"? "selected":""); ?> value="qu">Runasimi</option>
    <option data-name="Runyankore" id="dnyn" <?php echo esc_attr(get_option($key."aiwa-language","en") == "nyn"? "selected":""); ?> value="nyn">Runyankore</option>
    <option data-name="Seychellois Creole" id="dcrs" <?php echo esc_attr(get_option($key."aiwa-language","en") == "crs"? "selected":""); ?> value="crs">Seychellois Creole</option>
    <option data-name="shqip" id="dsq" <?php echo esc_attr(get_option($key."aiwa-language","en") == "sq"? "selected":""); ?> value="sq">shqip</option>
    <option data-name="slovenčina" id="dsk" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sk"? "selected":""; ?> value="sk">slovenčina</option>
    <option data-name="slovenščina" id="dsl" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sl"? "selected":""; ?> value="sl">slovenščina</option>
    <option data-name="Soomaali" id="dso" <?php echo esc_attr(get_option($key."aiwa-language","en") == "so"? "selected":""); ?> value="so">Soomaali</option>
    <option data-name="Southern Sotho" id="dst" <?php echo esc_attr(get_option($key."aiwa-language","en") == "st"? "selected":""); ?> value="st">Southern Sotho</option>
    <option data-name="srpski (Crna Gora)" id="dsr-ME" <?php echo esc_attr(get_option($key."aiwa-language","en") == "sr-ME"? "selected":""); ?> value="sr-ME">srpski (Crna Gora)</option>
    <option data-name="srpski (latinica)" id="dsr-Latn" <?php echo esc_attr(get_option($key."aiwa-language","en") == "sr-Latn"? "selected":""); ?> value="sr-Latn">srpski (latinica)</option>
    <option data-name="suomi" id="dfi" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fi"? "selected":""; ?> value="fi">suomi</option>
    <option data-name="svenska" id="dsv" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sv"? "selected":""; ?> value="sv">svenska</option>
    <option data-name="Tswana" id="dtn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tn"? "selected":""; ?> value="tn">Tswana</option>
    <option data-name="Tumbuka" id="dtum" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tum"? "selected":""; ?> value="tum">Tumbuka</option>
    <option data-name="türkmen dili" id="dtk" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tk"? "selected":""; ?> value="tk">türkmen dili</option>
    <option data-name="Twi" id="dtw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tw"? "selected":""; ?> value="tw">Twi</option>
    <option data-name="Wolof" id="dwo" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "wo"? "selected":""; ?> value="wo">Wolof</option>
    <option data-name="Ελληνικά" id="del" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "el"? "selected":""; ?> value="el">Ελληνικά</option>
    <option data-name="беларуская" id="dbe" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "be"? "selected":""; ?> value="be">беларуская</option>
    <option data-name="български" id="dbg" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "bg"? "selected":""; ?> value="bg">български</option>
    <option data-name="кыргызча" id="dky" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ky"? "selected":""; ?> value="ky">кыргызча</option>
    <option data-name="қазақ тілі" id="dkk" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "kk"? "selected":""; ?> value="kk">қазақ тілі</option>
    <option data-name="македонски" id="dmk" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mk"? "selected":""; ?> value="mk">македонски</option>
    <option data-name="монгол" id="dmn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mn"? "selected":""; ?> value="mn">монгол</option>
    <option data-name="српски" id="dsr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sr"? "selected":""; ?> value="sr">српски</option>
    <option data-name="татар" id="dtt" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tt"? "selected":""; ?> value="tt">татар</option>
    <option data-name="тоҷикӣ" id="dtg" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tg"? "selected":""; ?> value="tg">тоҷикӣ</option>
    <option data-name="українська" id="duk" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "uk"? "selected":""; ?> value="uk">українська</option>
    <option data-name="ქართული" id="dka" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ka"? "selected":""; ?> value="ka">ქართული</option>
    <option data-name="հայերեն" id="dhy" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "hy"? "selected":""; ?> value="hy">հայերեն</option>
    <option data-name="ייִדיש" id="dyi" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "yi"? "selected":""; ?> value="yi">ייִדיש</option>
    <option data-name="עברית" id="diw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "iw"? "selected":""; ?> value="iw">עברית</option>
    <option data-name="ئۇيغۇرچە" id="dug" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ug"? "selected":""; ?> value="ug">ئۇيغۇرچە</option>
    <option data-name="اردو" id="dur" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ur"? "selected":""; ?> value="ur">اردو</option>
    <option data-name="پښتو" id="dps" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ps"? "selected":""; ?> value="ps">پښتو</option>
    <option data-name="سنڌي" id="dsd" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sd"? "selected":""; ?> value="sd">سنڌي</option>
    <option data-name="فارسی" id="dfa" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fa"? "selected":""; ?> value="fa">فارسی</option>
    <option data-name="کوردیی ناوەندی" id="dckb" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ckb"? "selected":""; ?> value="ckb">کوردیی ناوەندی</option>
    <option data-name="ትግርኛ" id="dti" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ti"? "selected":""; ?> value="ti">ትግርኛ</option>
    <option data-name="አማርኛ" id="dam" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "am"? "selected":""; ?> value="am">አማርኛ</option>
    <option data-name="বাংলা" id="dbn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "bn"? "selected":""; ?> value="bn">বাংলা</option>
    <option data-name="नेपाली" id="dne" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ne"? "selected":""; ?> value="ne">नेपाली</option>
    <option data-name="मराठी" id="dmr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mr"? "selected":""; ?> value="mr">मराठी</option>
    <option data-name="हिन्दी" id="dhi" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "hi"? "selected":""; ?> value="hi">हिन्दी</option>
    <option data-name="ਪੰਜਾਬੀ" id="dpa" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "pa"? "selected":""; ?> value="pa">ਪੰਜਾਬੀ</option>
    <option data-name="ગુજરાતી" id="dgu" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "gu"? "selected":""; ?> value="gu">ગુજરાતી</option>
    <option data-name="ଓଡ଼ିଆ" id="dor" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "or"? "selected":""; ?> value="or">ଓଡ଼ିଆ</option>
    <option data-name="தமிழ்" id="dta" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ta"? "selected":""; ?> value="ta">தமிழ்</option>
    <option data-name="Assamese" id="Assamese" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "Assamese"? "selected":""; ?> value="Assamese">অসমীয়া</option>
    <option data-name="తెలుగు" id="dte" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "te"? "selected":""; ?> value="te">తెలుగు</option>
    <option data-name="ಕನ್ನಡ" id="dkn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "kn"? "selected":""; ?> value="kn">ಕನ್ನಡ</option>
    <option data-name="മലയാളം" id="dml" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ml"? "selected":""; ?> value="ml">മലയാളം</option>
    <option data-name="සිංහල" id="dsi" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "si"? "selected":""; ?> value="si">සිංහල</option>
    <option data-name="ລາວ" id="dlo" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "lo"? "selected":""; ?> value="lo">ລາວ</option>
    <option data-name="မြန်မာ" id="dmy" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "my"? "selected":""; ?> value="my">မြန်မာ</option>
    <option data-name="ខ្មែរ" id="dkm" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "km"? "selected":""; ?> value="km">ខ្មែរ</option>
    <option data-name="ᏣᎳᎩ" id="dchr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "chr"? "selected":""; ?> value="chr">ᏣᎳᎩ</option>
    <?php
}
function aiwa_get_language_text($key="en"){
    $text = '{"de":"Deutsch","en":"English","es":"espa\u00f1ol","es-419":"espa\u00f1ol (Latinoam\u00e9rica)","fr":"fran\u00e7ais","hr":"hrvatski","it":"italiano","nl":"Nederlands","pl":"polski","pt-BR":"portugu\u00eas (Brasil)","pt-PT":"portugu\u00eas (Portugal)","vi":"Ti\u1ebfng Vi\u1ec7t","tr":"T\u00fcrk\u00e7e","ru":"\u0440\u0443\u0441\u0441\u043a\u0438\u0439","ar":"\u0627\u0644\u0639\u0631\u0628\u064a\u0629","th":"\u0e44\u0e17\u0e22","ko":"\ud55c\uad6d\uc5b4","zh-CN":"\u4e2d\u6587 (\u7b80\u4f53)","zh-TW":"\u4e2d\u6587 (\u7e41\u9ad4)","zh-HK":"\u9999\u6e2f\u4e2d\u6587","ja":"\u65e5\u672c\u8a9e","ach":"Acoli","af":"Afrikaans","ak":"Akan","az":"az\u0259rbaycan","ban":"Balinese","su":"Basa Sunda","xx-bork":"Bork, bork, bork!","bs":"bosanski","br":"brezhoneg","ca":"catal\u00e0","ceb":"Cebuano","cs":"\u010de\u0161tina","sn":"chiShona","co":"Corsican","ht":"cr\u00e9ole ha\u00eftien","cy":"Cymraeg","da":"dansk","yo":"\u00c8d\u00e8 Yor\u00f9b\u00e1","et":"eesti","xx-elmer":"Elmer Fudd","eo":"esperanto","eu":"euskara","ee":"E\u028begbe","tl":"Filipino","fil":"Filipino","fo":"f\u00f8royskt","fy":"Frysk","gaa":"Ga","ga":"Gaeilge","gd":"G\u00e0","gl":"galego","gn":"Guarani","xx-hacker":"Hacker","ha":"Hausa","haw":"\u02bb\u014clelo Hawai\u02bbi","bem":"Ichibemba","ig":"Igbo","rn":"Ikirundi","id":"Indonesia","ia":"interlingua","xh":"IsiXhosa","zu":"isiZulu","is":"\u00edslenska","jw":"Jawa","rw":"Kinyarwanda","sw":"Kiswahili","tlh":"Klingon","kg":"Kongo","mfe":"kreol morisien","kri":"Krio (Sierra Leone)","la":"Latin","lv":"latvie\u0161u","to":"lea fakatonga","lt":"lietuvi\u0173","ln":"ling\u00e1la","loz":"Lozi","lua":"Luba-Lulua","lg":"Luganda","hu":"magyar","mg":"Malagasy","mt":"Malti","mi":"M\u0101ori","ms":"Melayu","pcm":"Nigerian P","no":"norsk","nn":"norsk nynorsk","nso":"Northern Sotho","ny":"Nyanja","uz":"o\u2018zbek","oc":"Occitan","om":"Oromoo","xx-pirate":"Pirate","ro":"rom\u00e2n\u0103","rm":"rumantsch","qu":"Runasimi","nyn":"Runyankore","crs":"Seychellois Creole","sq":"shqip","sk":"sloven\u010dina","sl":"sloven\u0161\u010dina","so":"Soomaali","st":"Southern Sotho","sr-ME":"srpski (Crna Gora)","sr-Latn":"srpski (latinica)","fi":"suomi","sv":"svenska","tn":"Tswana","tum":"Tumbuka","tk":"t\u00fcrkmen dili","tw":"Twi","wo":"Wolof","el":"\u0395\u03bb\u03bb\u03b7\u03bd\u03b9\u03ba\u03ac","be":"\u0431\u0435\u043b\u0430\u0440\u0443\u0441\u043a\u0430\u044f","bg":"\u0431\u044a\u043b\u0433\u0430\u0440\u0441\u043a\u0438","ky":"\u043a\u044b\u0440\u0433\u044b\u0437\u0447\u0430","kk":"\u049b\u0430\u0437\u0430\u049b \u0442\u0456\u043b\u0456","mk":"\u043c\u0430\u043a\u0435\u0434\u043e\u043d\u0441\u043a\u0438","mn":"\u043c\u043e\u043d\u0433\u043e\u043b","sr":"\u0441\u0440\u043f\u0441\u043a\u0438","tt":"\u0442\u0430\u0442\u0430\u0440","tg":"\u0442\u043e\u04b7\u0438\u043a\u04e3","uk":"\u0443\u043a\u0440\u0430\u0457\u043d\u0441\u044c\u043a\u0430","ka":"\u10e5\u10d0\u10e0\u10d7\u10e3\u10da\u10d8","hy":"\u0570\u0561\u0575\u0565\u0580\u0565\u0576","yi":"\u05d9\u05d9\u05b4\u05d3\u05d9\u05e9","iw":"\u05e2\u05d1\u05e8\u05d9\u05ea","ug":"\u0626\u06c7\u064a\u063a\u06c7\u0631\u0686\u06d5","ur":"\u0627\u0631\u062f\u0648","ps":"\u067e\u069a\u062a\u0648","sd":"\u0633\u0646\u068c\u064a","fa":"\u0641\u0627\u0631\u0633\u06cc","ckb":"\u06a9\u0648\u0631\u062f\u06cc\u06cc \u0646\u0627\u0648\u06d5\u0646\u062f\u06cc","ti":"\u1275\u130d\u122d\u129b","am":"\u12a0\u121b\u122d\u129b","bn":"\u09ac\u09be\u0982\u09b2\u09be","ne":"\u0928\u0947\u092a\u093e\u0932\u0940","mr":"\u092e\u0930\u093e\u0920\u0940","hi":"\u0939\u093f\u0928\u094d\u0926\u0940","pa":"\u0a2a\u0a70\u0a1c\u0a3e\u0a2c\u0a40","gu":"\u0a97\u0ac1\u0a9c\u0ab0\u0abe\u0aa4\u0ac0","or":"\u0b13\u0b21\u0b3c\u0b3f\u0b06","ta":"\u0ba4\u0bae\u0bbf\u0bb4\u0bcd","Assamese":"Assamese","te":"\u0c24\u0c46\u0c32\u0c41\u0c17\u0c41","kn":"\u0c95\u0ca8\u0ccd\u0ca8\u0ca1","ml":"\u0d2e\u0d32\u0d2f\u0d3e\u0d33\u0d02","si":"\u0dc3\u0dd2\u0d82\u0dc4\u0dbd","lo":"\u0ea5\u0eb2\u0ea7","my":"\u1019\u103c\u1014\u103a\u1019\u102c","km":"\u1781\u17d2\u1798\u17c2\u179a","chr":"\u13e3\u13b3\u13a9"}';
    $text = json_decode($text);
    if (isset($text->$key)){
        return $text->$key;
    }
}

function aiwa_update_option($option_name="aiwa_option", $value = "", $multiple = false){
    if ($multiple){
        $gop = get_option($option_name);

        if (!empty($gop)) {

            if (!empty($value)) {
                $gop[] = $value;
            }


        } else {
            $gop = array();
            $gop[] = $value;
        }

        update_option($option_name, $gop);

        return $gop;
    }
    else{
        update_option($option_name, $value);
    }

}

function aiwa_get_intro_conclusion_text($language_key){
    $data = '{"en":["Introduction","Conclusion"],"bn":["ভূমিকা","উপসংহার"],"es":["Introducción","Conclusión"],"de":["Einleitung","Schlussfolgerung"],"fr":["Introduction","Conclusion"],"hr":["Uvod","Zaključak"],"it":["Introduzione","Conclusione"],"nl":["Inleiding","Conclusie"],"pl":["Wprowadzenie","Wnioski"],"pt-BR":["Introdução","Conclusão"],"pt-PT":["Introdução","Conclusão"],"vi":["Giới thiệu","Kết luận"],"tr":["Giriş","Sonuç"],"ru":["Введение","Заключение"],"ar":["مقدمة","خاتمة"],"th":["บทนำ","บทสรุป"],"ko":["서론","결론"],"zh-CN":["引言","结论"],"zh-TW":["引言","結論"],"zh-HK":["引言","结论"],"ja":["「はじめに」","「結論」"],"ach":["Entwodiksyon","Konklizyon"],"af":["Inleiding","Gevolgtrekking"],"ak":["Pengantar","Kesimpulan"],"az":["Giriş","Nəticə"],"mk":["Вовед","Заклучок",["Вовед","Заклучок"]],"mn":["Танилцуулга","Дүгнэлт"],"hi":["परिचय","निष्कर्ष"],"sr":["Увод","Закључак"],"tt":["Кереш","Йомгаклау"],"tg":["Муқаддима","Хулоса"],"uk":["Вступ","Висновок"],"id":["Pendahuluan","Kesimpulan"],"ro":["Introducere","Concluzie"],"rm":["Ro-ràdh","Co-dhùnadh"],"sl":["Uvod","Zaključek"],"sk":["Úvod","Záver"],"tk":["Giriş","Netije"],"tw":["Nnianim asɛm","Awiei"],"be":["Уводзіны","Заключэнне"],"bg":["Въведение","Заключение"],"ky":["Кириш","Корутунду"],"kk":["Кіріспе","Қорытынды"],"fil":["Panimula","Konklusyon"],"fo":["Inngangur","Niðurstaða"],"fy":["Ynlieding","Konklúzje"],"ga":["Réamhrá","Conclúid"],"gd":["Ro-ràdh","Co-dhùnadh"],"gn":["Introduzione","Conclusione"],"haw":["Introduction","Hoʻopau"],"bem":["Sumo","Mhedziso"],"rn":["Intangiriro","Umwanzuro"],"xh":["Intshayelelo","Isiphelo"],"zu":["Isingeniso","Isiphetho"],"pa":["ਜਾਣ-ਪਛਾਣ","ਸਿੱਟਾ"]}';
    $data = json_decode($data);
    if (isset($data->$language_key)){
        return $data->$language_key;
    }
}
function aiwa_get_ai_data($params){
    if ( (!isset($params->id) || empty($params->id))
        && (!isset($params->title) || empty($params->title))
        && (!isset($params->scheduled_time) || empty($params->scheduled_time))
    ){
        return false;
    }
    $settingsKey = "ai_writing_assistant__";

    $id                = $params->id;
    $title             = $params->title;
    $scheduled_time    = $params->scheduled_time;
    $post_type         = (isset($params->post_type) && !empty($params->post_type)) ? $params->post_type : "post";
    $category          = (isset($params->category) && !empty($params->category) ? $params->category : "");
    $language          = (isset($params->language) && !empty($params->language) ? $params->language : "en");
    $content_structure = (isset($params->content_structure) && !empty($params->content_structure) ? $params->content_structure : "topic_wise");
    $content_length    = (isset($params->content_length) && !empty($params->content_length) ? $params->content_length : "medium");
    $keywords          = (isset($params->keywords) && !empty($params->keywords) ? $params->keywords : "");
    $writing_style     = (isset($params->writing_style) && !empty($params->writing_style) ? $params->writing_style : "normal");
    $writing_tone      = (isset($params->writing_tone) && !empty($params->writing_tone) ? $params->writing_tone : "informative");
    $generate_image    = (isset($params->generate_image) && !empty($params->generate_image) ? $params->generate_image : "0");


    //$ai_image_size = get_option($settingsKey."ai-image-size");
    $aiwa_topics_tag = get_option($settingsKey."aiwa-topics-tag");
    $topics_count = get_option($settingsKey."topics-count");
    $add_introduction = get_option($settingsKey."add-introduction");
    $introduction_size = get_option($settingsKey."introduction-size");
    $add_introduction_text = get_option($settingsKey."add-introduction-text");
    $add_conclusion = get_option($settingsKey."add-conclusion");
    $add_conclusion_text = get_option($settingsKey."add-conclusion-text");
    //$conclusion_size = get_option($settingsKey."conclusion-size");
    $add_call_to_action = get_option($settingsKey."add-call-to-action");
    $call_to_action_url = get_option($settingsKey."call-to-action-url");
    $call_to_action_position = get_option($settingsKey."call-to-action-position");
    //$pros_and_cons_count = get_option($settingsKey."pros-and-cons-count");
    //$list_items_count = get_option($settingsKey."list-items-count");
    $faq_items_count = get_option($settingsKey."faq-items-count");
    $article_paragraphs_count = get_option($settingsKey."article-paragraphs-count");
    //$first_person_name = get_option($settingsKey."first-person-name");
    //$second_person_name = get_option($settingsKey."second-person-name");
    //$generate_title = get_option($settingsKey."generate-title");
    //$image_experiments = get_option($settingsKey."image_experiments");

    $_POST['introduction-size'] = $_POST['conclusion-size'] = $_POST['content-length'] = $content_length;
    $_POST['include-keywords'] = $keywords;
    $_POST['writing-style'] = $writing_style;
    $_POST['writing-tone'] = $writing_tone;
    $_POST['aiwa_language_text'] = aiwa_get_language_text($language);
    $_POST['faq-items-count'] = $faq_items_count;
    $_POST['call-to-action-url'] = $call_to_action_url;
    $_POST['article-paragraphs-count'] = $article_paragraphs_count;
    $_POST['topics-count'] = $topics_count;

    $intro_and_conclusion = aiwa_get_intro_conclusion_text($language); //todo: need to get missing text (introduction and conclusion) dinamically.
    $html="";

    $unique = rand(1000, 9999999);
    $last_type = "";
    if ($generate_image=="1"){
        wp_schedule_single_event( time(), 'aiwa_ai_generate_image', array( $title, $id, $unique ) );
        aiwa_update_cron();
    }
    if ($add_call_to_action=="on" && $call_to_action_position === 'start'){
        //wp_schedule_single_event( time(), 'aiwa_ai_set_data_part_request_call_to_action', array( $title, $id ) );
        $html .= "<div class='cta'>" .  aiwa_request_for_data($title, 'call_to_action') . "</div>";
        aiwa_update_cron();
    }

    if ($add_introduction=="on"){
        //wp_schedule_single_event( time(), 'aiwa_ai_set_data_part_request_introduction', array( $title, $id ) );
        $introduction = aiwa_request_for_data($title, 'introduction');
        $introduction_text = "";
        if ($add_introduction_text=="on"){
            if (isset($intro_and_conclusion[0])){
                $introduction_text = "<h2>{$intro_and_conclusion[0]}</h2>";
            }
            else{
                $introduction_text = "<h2>Introduction</h2>";
            }
        }
        if (!empty($introduction)){
            $html .= "<div class='introduction'>{$introduction_text}<p>{$introduction}</p></div>";
        }
        aiwa_update_cron();
    }

    if ($content_structure==='topic_wise'){
        $last_type = "topic_wise";
        //wp_schedule_single_event( time(), 'aiwa_ai_set_data_part_request_topic_wise', array( $title, $id ) );

        $topics = aiwa_request_for_data($title, 'topic_wise');
        $topics = explode("<br>", $topics);

        $topics_contents = "";
        if (!empty($topics)){
            foreach ($topics as $topic) {
                $topic_detailes = aiwa_request_for_data($topic, 'topic_detailes');
                $topics_contents .= "<div class='topic'><{$aiwa_topics_tag}>" . aiwa_removeNumbers($topic) . "</{$aiwa_topics_tag}><p>{$topic_detailes}</p></div>";
                aiwa_update_cron();
            }
        }

        $html .= "<div class='topics'>{$topics_contents}</div>";
        aiwa_update_cron();
    }
    else{
        $last_type = $content_structure;
        //wp_schedule_single_event( time(), 'aiwa_ai_set_data_part_request_content_structure', array( $title, $content_structure, $id ) );
        $html .= "<div class='content-structure-{$content_structure}'>" . aiwa_request_for_data($title, $content_structure) ."</div>";
        aiwa_update_cron();
    }

    if ($add_conclusion=="on"){
        $last_type = "conclusion";
        //wp_schedule_single_event( time()+3, 'aiwa_ai_set_data_part_request_conclusion', array( $title, $id ) );
        $conclusion = aiwa_request_for_data($title, 'conclusion');
        $conclusion_text = "";
        if ($add_conclusion_text=="on"){
            if ($intro_and_conclusion[1]){
                $conclusion_text = "<h2>{$intro_and_conclusion[1]}</h2>";
            }
            else{
                $conclusion_text = "<h2>Conclusion</h2>";
            }
        }
        if (!empty($conclusion)){
            $html .= "<div class='introduction'>{$conclusion_text}<p>{$conclusion}</p></div>";
        }
        aiwa_update_cron();
    }

    if ($add_call_to_action=="on" && $call_to_action_position === 'end'){
        $last_type = "call_to_action";
        //wp_schedule_single_event( time()+4, 'aiwa_ai_set_data_part_request', array( $title, 'call_to_action', $id ) );
        $html .= "<div class='cta'>" .  aiwa_request_for_data($title, 'call_to_action') . "</div>";
        aiwa_update_cron();
    }

    // Create post object

    if (!empty($html)){
        $post = array(
            'post_title'    => sanitize_text_field($title),
            'post_content'  => $html,
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => $post_type,
        );

        if (!empty($category)){
            $post['post_category'] = array( $category );
        }

        // Insert the post into the database
        $post_id = wp_insert_post( $post );


        if (!empty(get_option('aiwa_'.$id.'-'.$unique.'_image'))){
            $image = get_option('aiwa_'.$id.'-'.$unique.'_image');
            if (isset($image['id']))
                set_post_thumbnail( $post_id, $image['id'] );
        }

        return $post_id;
    }
    else{
        return false;
    }


}


function aiwa_request_for_data($prompt, $type, $stream = false){
    $settingsKey = "ai_writing_assistant__";

    if (!class_exists('AIWA_Content_Switches')){
        require AIWA_DIR_PATH . 'admin/includes/content-switches.php';
    }
    $switch = new \AIWA_Content_Switches();

    //$best_of = get_option($settingsKey."best-of");
    $frequency_penalty = get_option($settingsKey."frequency-penalty");
    $max_tokens = get_option($settingsKey."max-tokens");
    $presence_penalty = get_option($settingsKey."presence-penalty");
    $temperature = get_option($settingsKey."temperature");
    $top_p = get_option($settingsKey."top-p");


    if (!empty(get_option($settingsKey . 'api-key'))){
        $ai = new \OpenAIAPI(get_option($settingsKey .'api-key'));
        $ai->setModel('gpt-3.5-turbo-instruct');

        $prompt = isset($prompt) ? sanitize_text_field($prompt) : "";
        $type = isset($type) ? sanitize_key($type) : "";
        $_POST['type'] = $type;

        $middle_prompt = $prompt;

        $temperature = isset($temperature) ? sanitize_text_field($temperature) : "0.4";
        $max_tokens = isset($max_tokens) ? sanitize_text_field($max_tokens) : "2000";
        $top_p = isset($_POST[$top_p]) ? sanitize_text_field($_POST[$top_p]) : "1.0";
        //$best_of = isset($best_of) ? sanitize_text_field($best_of) : "1.0";
        $frequency_penalty = isset($frequency_penalty) ? sanitize_text_field($frequency_penalty) : "0";
        $presence_penalty = isset($presence_penalty) ? sanitize_text_field($presence_penalty) : "0";

        $data = array(
            'prompt' => $switch->startingprompt() . $middle_prompt . $switch->endingprompt(),
            'temperature' => intval($temperature),
            'max_tokens' => intval($max_tokens), //short: 128 , medium: 128, long: 1000 (for topic detailes)
            'top_p' => intval($top_p),
            /*'best_of' => intval($best_of),*/
            'frequency_penalty' => intval($frequency_penalty),
            'presence_penalty' => intval($presence_penalty),
            'stream'=> $stream
        );

        $response = $ai->complete($data);
        $content = "";
        if (aiwa_is_json($response)){
            $text = json_decode($response);
            if (isset($text->choices)){
                $text = ($text->choices[0]->text);
                $content = aiwa_remove_starting_br(str_replace("\n", '<br>', $text));
            }
        }
        return $content;

    }
    else{
        echo '__api-empty__';
    }
}

function aiwa_removeNumbers($list) {
    return preg_replace('/\d\. +/', '', $list);
}

function aiwa_remove_starting_br($string) {
    return trim($string, "<br>");
}

function aiwa_upload_image_to_media_gallery($url, $data = array())
{
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    $image_url = 'http://example.com/image.jpg';

    $tmp = download_url( $url );

    $file_array = array(
        'name'     => basename( $image_url ),
        'tmp_name' => $tmp
    );

    $id = media_handle_sideload( $file_array, 0 );

    if ( is_wp_error( $id ) ) {
        @unlink( $file_array['tmp_name'] );
        return $id;
    }
    $attachment = array();
    $attachment['id'] = $id;
    $attachment['url'] = wp_get_attachment_url( $id );
    return $attachment;
}

function aiwa_generate_ai_image($prompt){
    $settingsKey = "ai_writing_assistant__";
    $image_experiments = get_option($settingsKey . 'image_experiments', array());
    $image_styles = '';
    if (!empty($image_experiments)){
        $image_styles = implode(', ', $image_experiments);
        $image_styles = ' | ' . rtrim($image_styles, ',').'.';
    }
    $ai = new \OpenAIAPI(get_option($settingsKey . 'api-key'));
    $ai->setModel('gpt-3.5-turbo-instruct');

    $image_size = get_option($settingsKey . 'ai-image-size', 'thumbnail');
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
    $data = array(
        'prompt' => $prompt . $image_styles,
        'n' => 1,
        'size' => $image_size_,
    );
    $response = $ai->image($data);

    $media_url = array();
    if (aiwa_is_json($response)){
        $json = json_decode($response);
        if (isset($json->data) && isset($json->data[0])){
            $url = $json->data[0]->url;

            $media_url = aiwa_upload_image_to_media_gallery($url);
        }
    }

    return $media_url;
}

function aiwa_ai_generate_image( $title, $id, $unique ) {
    $image = aiwa_generate_ai_image($title);
    $post_id = aiwa_get_scheduled_post_id_to_post_id($id);
    if (!$post_id){
        $post_thumbnail_id = get_post_thumbnail_id( $post_id );

        if (!empty($image)&&isset($image['id'])&&$post_thumbnail_id==0){
            set_post_thumbnail( $post_id, $image['id'] );
        }
    }
    update_option('aiwa_'.$id.'-'.$unique.'_image', $image);
}
add_action( 'aiwa_ai_generate_image', 'aiwa_ai_generate_image', 10, 2 );

function aiwa_update_cron(){
    wp_remote_get(home_url("/") . 'wp-cron.php');
}

function aiwa_get_scheduled_post_id_to_post_id($id){
    global $wpdb;
    $generated_post = $wpdb->get_results("SELECT post_id FROM {$wpdb->prefix}ai_writing_assistant_sceduled_posts WHERE id='{$id}'");
    if (!empty($generated_post)&&isset($generated_post[0])){
        return $generated_post[0]->post_id;
    }
    else{
        return false;
    }
}


