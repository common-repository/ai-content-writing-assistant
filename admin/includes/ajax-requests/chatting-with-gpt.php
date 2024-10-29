<?php
namespace WpWritingAssistant\AjaxRequests;

class ChattingWithGPT
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_chatting_with_gpt", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();

        @ini_set('zlib.output_compression',0);
        @ini_set('implicit_flush',1);
        @ob_end_clean();

        header('Content-Type: text/event-stream');

        $ai = new \OpenAIAPI($this->ajax->getSettings('api-key'));
        $ai->setModel('gpt-3.5-turbo');
        $ai->setApiUrl('https://api.openai.com/v1/chat/completions');
        $stream = false;

        $chattings = isset($_POST['chattings']) && !empty($_POST['chattings']) ? sanitize_textarea_field($_POST['chattings']) : "";
        $chattings = stripslashes($chattings);

        $model = isset($_POST['model']) && !empty($_POST['model']) ? sanitize_text_field($_POST['model']) : 'gpt-3.5-turbo';

        if (!empty($chattings)){
            $messages = array();
            $chattings = json_decode($chattings);
            foreach ($chattings as $chatting) {
                $message = array();

                if (isset($chatting->user)){
                    $message['role'] = 'user';
                    $message['content'] = $chatting->user;
                }

                if (isset($chatting->ai)){
                    $message['role'] = 'assistant';
                    $message['content'] = $chatting->ai;
                }

                $messages[] = (object) $message;

            }
        }

        $data = array(
            'model' => $model,
            'messages' => $messages,
            'temperature' => 1,
            'stream' => $stream
        );


        aiwa_update_option('wiwa_test_1', $data);

        /*print_r($messages);
        die();*/

        if ($stream){
            $e = $ai->complete($data, function($data, $ai){
                if (strpos($data, 'data:')!==false){
                    $content = "";
                    $split = explode('data: ', $data);
                    if (count($split)) {
                        foreach ($split as $element) {
                            if (rc_isJson($element)) {
                                $json = json_decode($element);
                                if (isset($json->choices[0]->delta)){

                                    $message = $json->choices[0]->delta;
                                     /*if (isset($message->role)){
                                        $role = $message->role;
                                    }*/
                                    if (isset($message->content)){
                                        $content .= str_replace("\n", '<br>', $message->content);
                                    }

                                    /*if (isset($message->content)){
                                        $content .= $message->content;
                                    }*/
                                    /*$message = $json->choices[0]->message;
                                    $content .= $message;*/
                                }
                            }
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


                //print_r($data);


                return strlen($data);
            });
        }


        if (!$stream){

            if (!empty($chattings)){
                $e = $ai->complete($data);
                if (aiwa_is_json($e)){
                    $obj = json_decode($e);
                    if (isset($obj->choices)){
                        if (isset($obj->choices[0]->message)){
                            $content = "";
                            $message = $obj->choices[0]->message;
                            /*if (isset($message->role)){
                                $role = $message->role;
                            }*/
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

                }
            }
        }
        wp_die();

    }
}
