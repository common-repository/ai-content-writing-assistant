<?php

function aiwa_title_suggestion(){
    ?>
    <div class="aiwa_modal_wrap" style="display: none">
        <div id="suggestion_title_modal">
            <div class="aiwa_modal">
                <h1 class="select_suggested_title" style="display: none"><?php _e("Select a title which you like", "ai-writing-assistant"); ?></h1>
                <h2><?php _e("New title for ", "ai-writing-assistant"); ?>"<span class='title_for_suggestion'></span>"</h2>

                <div class="aiwa_suggested_titles">
                    <span class="suggest_titles aiwa_spinner"></span>
                </div>
            </div>
        </div>
    </div>
    <?php
}

add_action("admin_footer", "aiwa_title_suggestion");