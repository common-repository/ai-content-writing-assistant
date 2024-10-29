<?php
/**
 * OpenAIAPI
 *
 * Copyright (c) 2023 Rayhan Kabir
 *
 * @author Rayhan Kabir <rayhankabir1000@gmail.com>
 * @copyright 2023 Rayhan Kabir
 * @license MIT
 * @link https://myrecorp.com
 *
 * Created at: 1 Jan 2023
 */
class OpenAIAPI {
    private $api_key;
    private $model;
    private $stream_method;
    public $response;
    private $api_url;

    public function __construct($api_key) {
        $this->api_key = $api_key;

        add_action('http_api_curl', array($this, 'filterCurlForStream'));
    }

    public function setModel($model) {
        $this->model = $model;
    }

    public function setResponse($content="")
    {
        $this->response = $content;
    }
    public function setApiUrl($url="")
    {
        $this->api_url = $url;
    }
    public function filterCurlForStream($handle)
    {
        if ($this->stream_method !== null){
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($handle, CURLOPT_WRITEFUNCTION, function ($curl_info, $data) {
                return call_user_func($this->stream_method, $data, $this);
            });
        }
    }

    private function sendRequest($endpoint, $data) {
        if (isset($this->api_url)&&!empty($this->api_url)){
            $url = $this->api_url;
        }
        elseif ($endpoint=="completions"){
            $url = "https://api.openai.com/v1/engines/{$this->model}/completions";
        }
        elseif ($endpoint == "image"){
            $url = "https://api.openai.com/v1/images/generations";
        }

        if (isset($data['model']) && !empty($data['model'])){
            if ($this->model=="gpt-3.5-turbo"){
                $url = "https://api.openai.com/v1/chat/completions";
            }
            else{
                $url = "https://api.openai.com/v1/".$endpoint;
            }
        }

        $stream = false;
        if (isset($data['stream']) && $data['stream'] == true){
            $stream = true;
        }

        $response = wp_remote_post( $url, array(
                'method' => 'POST',
                'timeout' => 100,
                'httpversion' => '1.0',
                //'blocking' => true,
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$this->api_key,
                ),
                'body' => json_encode( $data ),
                'stream' => $stream
            )
        );

        if ($stream){
            return $this->response;
        }
        else{
            return wp_remote_retrieve_body($response);
        }

    }

    public function listModels() {
        $response = $this->sendRequest("models", array());
        return $response;
    }

    public function retrieveModel($model_id) {
        $response = $this->sendRequest("models/" . $model_id, array());
        return $response;
    }

    public function complete($data, $stream = null) {
        if (isset($data['stream']) && $data['stream'] == true){
            $this->stream_method = $stream;
        }

        $response = $this->sendRequest("completions", $data, $stream);
        return $response;
    }
    public function image($data, $stream = null) {
        $this->stream_method = $stream;
        $response = $this->sendRequest("image", $data);
        return $response;
    }

}

