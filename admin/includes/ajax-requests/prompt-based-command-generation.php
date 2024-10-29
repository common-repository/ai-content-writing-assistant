<?php
namespace WpWritingAssistant\AjaxRequests;

class promptBasedGeneration{

    private $ajax;
    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_prompt_based_generation", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();

        @ini_set('zlib.output_compression',0);
        @ini_set('implicit_flush',1);
        @ob_end_clean();

        header('Content-Type: text/event-stream');

        /*if (!class_exists('Browser')){
            require AIWA_DIR_PATH . 'includes/browser-class.php';
        }
        $browser = new \Browser();
        if ($browser->getBrowser()!=="Edge" && $browser->getBrowser()!=="Chrome" && !isset($_POST['previously_failed'])){
            header('Cache-Control: no-cache');
            header( 'Content-Encoding: none; ' );
        }*/

        require AIWA_DIR_PATH . 'admin/includes/content-switches.php';

        $switch = new \AIWA_Content_Switches();

        if (!empty($this->ajax->getSettings('api-key'))){
            $ai = new \OpenAIAPI($this->ajax->getSettings('api-key'));
            $model = isset($_POST['model']) && !empty($_POST['model']) ? sanitize_text_field($_POST['model']) : 'gpt-3.5-turbo-instruct';
            $ai->setModel($model);

            $prompt = isset($_POST['prompt']) ? sanitize_text_field($_POST['prompt']) : "";
            $type = isset($_POST['type']) ? sanitize_key($_POST['type']) : "";
            //$first_prompt = isset($_POST['first_prompt']) ? sanitize_text_field($_POST['first_prompt']) : "";

            $middle_prompt = $prompt;
/*            if ($type=="call_to_action" && !empty($first_prompt)){
                $middle_prompt = $first_prompt;
            }*/
            if ($type == "what_language"){
                $middle_prompt = '"'.$middle_prompt.'"';
            }

            $temperature = isset($_POST['temperature']) ? sanitize_text_field($_POST['temperature']) : "0.8";
            $max_tokens = isset($_POST['max_tokens']) ? sanitize_text_field($_POST['max_tokens']) : "2000";
            $top_p = isset($_POST['top_p']) ? sanitize_text_field($_POST['top_p']) : "1.0";
            $best_of = isset($_POST['best_of']) ? sanitize_text_field($_POST['best_of']) : "1.0";
            $frequency_penalty = isset($_POST['frequency_penalty']) ? sanitize_text_field($_POST['frequency_penalty']) : "0";
            $presence_penalty = isset($_POST['presence_penalty']) ? sanitize_text_field($_POST['presence_penalty']) : "0";

            $stream = true;
            if (isset($_POST['super_fast_generation_mode'])||isset($_POST['previously_failed'])){
                $stream = false;
            }
            $data = array(
                'prompt' => $middle_prompt,
                'temperature' => floatval($temperature),
                'max_tokens' => floatval($max_tokens), //short: 128 , medium: 128, long: 1000 (for topic detailes)
                'top_p' => floatval($top_p),
                /*'best_of' => floatval($best_of),*/
                'frequency_penalty' => floatval($frequency_penalty),
                'presence_penalty' => floatval($presence_penalty),
                'stream'=> $stream,
                'model' => $model
            );

            if ($model=="gpt-3.5-turbo"){

                $message = $messages = array();
                $message['role'] = 'user';
                $message['content'] = $middle_prompt;

                unset($data['prompt']);
                $data['stream'] = false;

                $messages[] = (object) $message;

                $data['messages'] = $messages;



                $e = $ai->complete($data);

                if (aiwa_is_json($e)){
                    $e = json_decode($e);
                    if (isset($e->choices) && isset($e->choices[0]) && isset($e->choices[0]->message) && isset($e->choices[0]->message->content) ){
                        echo esc_html($e->choices[0]->message->content);
                    }
                }
/*
                if ($stream){
                    $e = $ai->complete($data, function($data, $ai){
                        echo esc_html($data);


                        if (strpos($data, 'data:')!==false){
                            $content = "";

                            $split = explode("\n", $data);
                            foreach ($split as $element) {
                                $element   = substr($element, 6); // Remove "data: " from the beginning of each line
                                $json = json_decode($element);

                                if (isset($json->choices[0]->delta)){
                                    $message = $json->choices[0]->delta;
                                    if (isset($message->content)){
                                        $content .= $message->content;
                                    }
                                }
                            }


                            echo esc_html($content);

                            flush();
                            //ob_flush();
                            if (ob_get_level() > 0) {ob_flush();}

                        }
                        else{
                            if (aiwa_is_json($data)){
                                $obj = json_decode($data);
                                if (isset($obj->error)){
                                    if ($obj->error->code == 'invalid_api_key'){
                                        echo '__invalid_api_key__';
                                    }elseif ($obj->error->type == "insufficient_quota"){
                                        echo '__insufficient_quota__';
                                    }
                                    elseif ($obj->error->type == "server_error"){
                                        echo '__server_error__';
                                    }
                                    else{
                                        wp_send_json_error($obj);
                                    }
                                }

                            }
                        }


                        //print_r($data);


                        return strlen($data);
                    });
                }


                if (!$stream){

                    if (!empty($chattings)){
                        $e = $ai->complete($data);
                        //if (aiwa_is_json($e)){
                            $obj = json_decode($e);
                            if (isset($obj->choices)){
                                if (isset($obj->choices[0]->message)){
                                    $content = "";
                                    $message = $obj->choices[0]->message;

                                    if (isset($message->content)){
                                        $content = $message->content;
                                    }
                                    echo esc_html($content);
                                }
                                else{
                                    echo '__message_empty__';
                                }

                            }
                            if (isset($obj->error)){
                                if ($obj->error->code == 'invalid_api_key'){
                                    echo '__invalid_api_key__';
                                }elseif ($obj->error->type == "insufficient_quota"){
                                    echo '__insufficient_quota__';
                                }
                                elseif ($obj->error->type == "server_error"){
                                    echo '__server_error__';
                                }
                                else{
                                    wp_send_json_error($obj);
                                }
                            }

                        //}
                    }
                }*/

                wp_die();
            }
            else{

                $e = $ai->complete($data, function($data, $ai){
                    if (strpos($data, 'data:')!==false){
                        $content = "";
                        $split = explode('data: ', $data);
                        if (count($split)) {
                            foreach ($split as $element) {
                                //if (rc_isJson($element)) {
                                $json = json_decode($element);
                                $text = $json->choices[0]->text;
                                $text = str_replace("\n", '<br>', $text);
                                $content .= $text;
                                //}
                            }
                        }
                        //$ai->setResponse(esc_html($content));
                        echo esc_html($content);
                        flush();
                        //ob_flush();
                        if (ob_get_level() > 0) {ob_flush();}

                    }
                    else{
                        if (aiwa_is_json($data)){
                            $obj = json_decode($data);
                            if (isset($obj->error)){
                                if ($obj->error->code == 'invalid_api_key'){
                                    echo '__invalid_api_key__';
                                }elseif ($obj->error->type == "insufficient_quota"){
                                    echo '__insufficient_quota__';
                                }
                                elseif ($obj->error->type == "server_error"){
                                    echo '__server_error__';
                                }
                                else{
                                    wp_send_json_error($obj);
                                }
                            }

                        }
                    }


                    return strlen($data);
                });
            }



            if (!$stream){
                //if (aiwa_is_json($e)){
                    $obj = json_decode($e);
                    if (isset($obj->choices)){
                        $text = $obj->choices[0]->text;
                        $text = str_replace("\n", '<br>', $text);
                        echo esc_html($text);
                    }
                    if (isset($obj->error)){
                        if ($obj->error->code == 'invalid_api_key'){
                            echo '__invalid_api_key__';
                        }elseif ($obj->error->type == "insufficient_quota"){
                            echo '__insufficient_quota__';
                        }
                        elseif ($obj->error->type == "server_error"){
                            echo '__server_error__';
                        }
                        else{
                            wp_send_json_error($obj);
                        }
                    }

                //}
            }

        }
        else{
            echo '__api-empty__';
        }

        wp_die();
    }

}