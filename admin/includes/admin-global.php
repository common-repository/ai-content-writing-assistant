<?php

function aiwa_open_api_chars($api){
    return 'sk-*****************************'.substr($api, -4);
}

function aiwa_is_json($string){
    $data = json_decode($string);

    if(json_last_error() !== JSON_ERROR_NONE) {
        return false;
    } else {
        return true;
    }
}

function aiwa_remove_first_br($content) {
    $index = strpos($content, "<br><br>");
    return substr($content, $index+2);
}

function aiwa_is_url($str) {
    return filter_var($str, FILTER_VALIDATE_URL) !== false;
}

function aiwa_coming_soon(){
    ?>
    <style>
        .coming-soon span{
            display: none;
        }
    </style>
    <div class="wrapper">

        <h1 class="coming-soon" style="font-size: 60px; text-align: center"><span style="color: <?php echo aiwa_random_color(); ?>">C</span><span style="color: <?php echo aiwa_random_color(); ?>">o</span><span style="color: <?php echo aiwa_random_color(); ?>">m</span><span style="color: <?php echo aiwa_random_color(); ?>">i</span><span style="color: <?php echo aiwa_random_color(); ?>">n</span><span style="color: <?php echo aiwa_random_color(); ?>">g</span>
            <span style="color: <?php echo aiwa_random_color(); ?>">v</span><span style="color: <?php echo aiwa_random_color(); ?>">e</span><span style="color: <?php echo aiwa_random_color(); ?>">r</span><span style="color: <?php echo aiwa_random_color(); ?>">y</span>
            <span style="color: <?php echo aiwa_random_color(); ?>">s</span><span style="color: #fd3267">o</span><span style="color: <?php echo aiwa_random_color(); ?>">o</span><span style="color: <?php echo aiwa_random_color(); ?>">n</span><span style="color: <?php echo aiwa_random_color(); ?>">!</span>
        </h1>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            var i =0;
            var AutoRefresh = setInterval(function(){
                i +=1;
                $('.coming-soon span:nth-child('+i+')').show();
                if (i>=15){
                    clearInterval(AutoRefresh);
                }
            }, 100);
        });
    </script>
    <?php
}

function aiwa_random_color(){
    $colors = array('#fd3267','#66D9EF','#A6E22E','#FD971F','#9B59B6','#1ABC9C','#3498db','#A6E22E','#fd3267','#fd3267');
    $rand = rand(0, count($colors)-1);
    return $colors[$rand];
}

function aiwa_get_remaining_time($date){
    $timestamp = strtotime($date);
    $remaining = $timestamp - time();

    $days = floor($remaining / 86400);
    $hours = floor(($remaining % 86400) / 3600);
    $minutes = floor(($remaining % 3600) / 60);
    $seconds = ($remaining % 60);

    $remaining_time = "$days day(s) $hours hour(s) $minutes minute(s) and $seconds second(s)";

    echo $remaining_time;
}

