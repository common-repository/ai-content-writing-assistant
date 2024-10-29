<?php
function aiwa_content_structure_settings_item(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label for="aiwa-generate-title"><span><?php _e('Randomely generate post title', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-generate-title" class="content-settings-input" type="checkbox" name="generate-title" <?php echo esc_attr(get_option($key.'generate-title', 'off')) == 'on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Select this to generate blog post title.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item">
        <label for="aiwa-content-structure">
            <span><?php _e('Content Structure', 'ai-writing-assistant'); ?></span><a href="https://myrecorp.com/documentation/documentation-ai-writing-assistant-content-structures/" target="_blank" title="Check example"><svg viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" style="width: 20px;position: relative;top: 6px;margin-left: 3px;"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="add" fill="#000000" transform="translate(42.666667, 42.666667)"> <path d="M213.333333,3.55271368e-14 C331.153707,3.55271368e-14 426.666667,95.51168 426.666667,213.333333 C426.666667,331.153707 331.153707,426.666667 213.333333,426.666667 C95.51296,426.666667 3.55271368e-14,331.153707 3.55271368e-14,213.333333 C3.55271368e-14,95.51168 95.51296,3.55271368e-14 213.333333,3.55271368e-14 Z M213.332053,282.666667 C198.60416,282.666667 186.665387,294.60544 186.665387,309.333333 C186.665387,324.061227 198.60416,336 213.332053,336 C228.059947,336 239.99872,324.061227 239.99872,309.333333 C239.99872,294.60544 228.059947,282.666667 213.332053,282.666667 Z M209.77344,93.3346133 C189.007787,93.3346133 171.554773,98.9922133 157.43488,110.274773 C140.703147,123.790507 132.34368,143.751253 132.34368,170.173227 L132.34368,170.173227 L177.7056,170.173227 L177.7056,169.868587 C177.7056,159.787733 179.829333,151.518293 184.065067,145.069013 C189.911467,136.398507 199.39328,132.059947 212.501333,132.059947 C220.56768,132.059947 227.4336,134.177067 233.070293,138.404907 C240.125013,144.26304 243.664,153.13024 243.664,165.028693 C243.664,172.49216 241.839787,179.143253 238.214827,184.994773 C235.188693,190.2368 230.350293,195.374933 223.686187,200.42048 C209.571627,210.098773 200.394453,219.679573 196.165333,229.162667 C192.53504,237.027413 190.710827,249.530027 190.710827,266.666667 L190.710827,266.666667 L233.376213,266.666667 C233.376213,255.371093 234.87744,246.90624 237.916587,241.257813 C240.331947,236.618667 245.378987,231.682347 253.042987,226.434987 C266.358187,216.549547 275.828267,207.371093 281.479253,198.90112 C288.33216,188.82176 291.76704,177.01952 291.76704,163.504 C291.76704,135.494827 280.37504,115.62624 257.571627,103.9232 C243.865813,96.86848 227.933653,93.3346133 209.77344,93.3346133 Z" id="Combined-Shape"> </path> </g> </g> </g></svg></a>
        </label>
        <select name="ai-content-structure" id="aiwa-content-structure" data-has-subsettings="">
            <?php
                aiwa_get_content_structure_options();
            ?>
        </select>

        <p><?php _e('Choose the content type of your blog post which fit your need!', 'ai-writing-assistant'); ?></p>
    </div>
    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='topic_wise'? 'aiwa-hidden': ''; ?>"  data-subsettings-of="aiwa-content-structure" data-sub-settings-key="topic_wise">
        <label for="aiwa-select-topics-before-generate"><span><?php _e('Select Topics Before Generate', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-select-topics-before-generate" class="content-settings-input" type="checkbox" name="select-topics-before-generate" data-has-subsettings="" <?php echo esc_attr(get_option($key.'select-topics-before-generate', 'off')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Check this if you want to select topics before the content generation.', 'ai-writing-assistant'); ?></p>

        <p class="title_exceeded" style="margin: 0;color: #b01111;">
            <?php echo __('Pro version only', 'ai-writing-assistant'); ?>
        </p>

    </div>
    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='topic_wise'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="topic_wise">
        <label for="aiwa-how-many-topics"><span><?php _e('How many topics', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-topics" class="content-settings-input" type="number" name="topics-count" value="<?php echo esc_attr(get_option($key.'topics-count', '5')); ?>" placeholder="5">
        </label>
        <p><?php _e('Enter a number of topics you want to add to your blog post.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='topic_wise'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="topic_wise">
        <label for="aiwa-topics-tag"><span><?php _e('Topics heading tag', 'ai-writing-assistant'); ?></span></label>
        <select name="aiwa-topics-tag" id="aiwa-topics-tag">
            <?php
                aiwa_get_topics_tag_options();
            ?>
        </select>
        <p><?php _e('Topics will automatically be wrapped by the selected heading tag.', 'ai-writing-assistant'); ?></p>
    </div>


    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='pros_and_cons'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="pros_and_cons">
        <label for="aiwa-how-many-pros-and-cons"><span><?php _e('How many pros and cons', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-pros-and-cons" class="content-settings-input" type="number" name="pros-and-cons-count" value="<?php echo esc_attr(get_option($key.'pros-and-cons-count', '7')); ?>" placeholder="7">
        </label>
        <p><?php _e('Enter a number of pros and cons you want to add to your blog post.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='list'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="list">
        <label for="aiwa-how-many-list-items"><span><?php _e('How many list items', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-list-items" class="content-settings-input" type="number" name="list-items-count" value="<?php echo esc_attr(get_option($key.'list-items-count', '10')); ?>" placeholder="10">
        </label>
        <p><?php _e('Enter a number of list items you want to add to your blog post.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='faq'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="faq">
        <label for="aiwa-how-many-faq-items"><span><?php _e('How many FAQ items', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-faq-items" class="content-settings-input" type="number" name="faq-items-count" value="<?php echo esc_attr(get_option($key.'faq-items-count', '7')); ?>" placeholder="7">
        </label>
        <p><?php _e('Enter a number of FAQ items you want to add on your blog post.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='article'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="article">
        <label for="aiwa-how-many-article-paragraphs"><span><?php _e('How many paragraphs', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-article-paragraphs" class="content-settings-input" type="number" name="article-paragraphs-count" value="<?php echo esc_attr(get_option($key.'article-paragraphs-count', '3')); ?>" placeholder="3">
        </label>
        <p><?php _e('Enter a number of paragraphs you want to add on your blog post article.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='youtube_script'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="youtube_script">
        <label for="aiwa-how-many-minutes"><span><?php _e('Minutes', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-minutes" class="content-settings-input" type="number" name="how_many_minutes" value="<?php echo esc_attr(get_option($key.'how_many_minutes', '10')); ?>" placeholder="3">
        </label>
        <p><?php _e('Enter a number of paragraphs you want to add on your blog post article.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='interviews'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="interviews">
        <label for="aiwa-first-persion-name"><span><?php _e('First persion name', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-first-persion-name" class="content-settings-input" type="text" name="first-person-name" value="<?php echo esc_attr(get_option($key.'first-person-name', '')); ?>" placeholder="John Doe">
        </label>
        <br/>
        <label for="aiwa-second-person-name"><span><?php _e('Second person name', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-second-person-name" class="content-settings-input" type="text" name="second-person-name" value="<?php echo esc_attr(get_option($key.'second-person-name', '')); ?>" placeholder="Harry Potter">
        </label>
        <p><?php _e('Enter the first and second-person names of the interview characters.', 'ai-writing-assistant'); ?></p>
    </div>
    <?php
}

function aiwa_support_and_feature_request_text(){
    ?>
    <div class="support my-2" style="background-color: #1fc6608f;padding: 10px;border-radius: 5px;margin-top: 10px;color: #000;">
        <span><?php _e('Do you have any issues, feature requests, or ideas? Feel free to share them on the ', 'ai-writing-assistant'); ?> </span> <a href="https://wordpress.org/support/plugin/ai-content-writing-assistant/"><?php _e('support forum.', 'ai-writing-assistant'); ?> </a><?php _e('Together, let\'s take the plugin to the next level', 'ai-writing-assistant'); ?> <img src="<?php echo AIWA_DIR_URL . 'admin/assets/images/cafe.png'; ?>" alt="" style="display: inline;width: 30px;position: absolute;margin-top: -4px;">
    </div>
    <?php
}
function aiwa_api_settings(){
    $key = 'ai_writing_assistant__';
    ?>
    <!-- Temperature input field with tooltip and description -->
    <p style="background-color: yellow; padding: 10px;"><?php _e('Check the <a href="https://myrecorp.com/documentation/ai-content-writing-assistant-api-settings/" target="_blank">documentation</a> before changing these settings.', 'ai-writing-assistant'); ?></p>

    <div class="settings-item">
        <div class="range-input">
            <label for="temperature"><span><?php _e('Temperature', 'ai-writing-assistant'); ?></span><a href="https://myrecorp.com/documentation/ai-content-writing-assistant-api-settings/#temperature" target="_blank" title="Check documentation"><svg viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" class="doc-what-icon"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="add" fill="#000000" transform="translate(42.666667, 42.666667)"> <path d="M213.333333,3.55271368e-14 C331.153707,3.55271368e-14 426.666667,95.51168 426.666667,213.333333 C426.666667,331.153707 331.153707,426.666667 213.333333,426.666667 C95.51296,426.666667 3.55271368e-14,331.153707 3.55271368e-14,213.333333 C3.55271368e-14,95.51168 95.51296,3.55271368e-14 213.333333,3.55271368e-14 Z M213.332053,282.666667 C198.60416,282.666667 186.665387,294.60544 186.665387,309.333333 C186.665387,324.061227 198.60416,336 213.332053,336 C228.059947,336 239.99872,324.061227 239.99872,309.333333 C239.99872,294.60544 228.059947,282.666667 213.332053,282.666667 Z M209.77344,93.3346133 C189.007787,93.3346133 171.554773,98.9922133 157.43488,110.274773 C140.703147,123.790507 132.34368,143.751253 132.34368,170.173227 L132.34368,170.173227 L177.7056,170.173227 L177.7056,169.868587 C177.7056,159.787733 179.829333,151.518293 184.065067,145.069013 C189.911467,136.398507 199.39328,132.059947 212.501333,132.059947 C220.56768,132.059947 227.4336,134.177067 233.070293,138.404907 C240.125013,144.26304 243.664,153.13024 243.664,165.028693 C243.664,172.49216 241.839787,179.143253 238.214827,184.994773 C235.188693,190.2368 230.350293,195.374933 223.686187,200.42048 C209.571627,210.098773 200.394453,219.679573 196.165333,229.162667 C192.53504,237.027413 190.710827,249.530027 190.710827,266.666667 L190.710827,266.666667 L233.376213,266.666667 C233.376213,255.371093 234.87744,246.90624 237.916587,241.257813 C240.331947,236.618667 245.378987,231.682347 253.042987,226.434987 C266.358187,216.549547 275.828267,207.371093 281.479253,198.90112 C288.33216,188.82176 291.76704,177.01952 291.76704,163.504 C291.76704,135.494827 280.37504,115.62624 257.571627,103.9232 C243.865813,96.86848 227.933653,93.3346133 209.77344,93.3346133 Z" id="Combined-Shape"> </path> </g> </g> </g></svg></a>
                <input id="temperature-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'temperature', '0.8')); ?>">
            </label>
            <input type="range" min="0" max="1" value="<?php echo esc_attr(get_option($key.'temperature', '0.8')); ?>" step="0.01" id="temperature" class="slider" name="temperature">
        </div>

        <p><?php _e('Control randomness: Lowering results in less random completions.  As the temperature approaches zero, the model will become deterministic and repetitive. If it approaches one, the model will become more randomness and creative.', 'ai-writing-assistant'); ?></p>
    </div>


    <!-- Max tokens input field with tooltip and description -->
    <div class="settings-item">
        <!--<input type="number" name="max-tokens" value="256">-->
        <div class="range-input">
            <label for="max-tokens"><span><?php _e('Max Tokens', 'ai-writing-assistant'); ?></span><a href="https://myrecorp.com/documentation/ai-content-writing-assistant-api-settings/#max-tokens" target="_blank" title="Check documentation"><svg viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" class="doc-what-icon"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="add" fill="#000000" transform="translate(42.666667, 42.666667)"> <path d="M213.333333,3.55271368e-14 C331.153707,3.55271368e-14 426.666667,95.51168 426.666667,213.333333 C426.666667,331.153707 331.153707,426.666667 213.333333,426.666667 C95.51296,426.666667 3.55271368e-14,331.153707 3.55271368e-14,213.333333 C3.55271368e-14,95.51168 95.51296,3.55271368e-14 213.333333,3.55271368e-14 Z M213.332053,282.666667 C198.60416,282.666667 186.665387,294.60544 186.665387,309.333333 C186.665387,324.061227 198.60416,336 213.332053,336 C228.059947,336 239.99872,324.061227 239.99872,309.333333 C239.99872,294.60544 228.059947,282.666667 213.332053,282.666667 Z M209.77344,93.3346133 C189.007787,93.3346133 171.554773,98.9922133 157.43488,110.274773 C140.703147,123.790507 132.34368,143.751253 132.34368,170.173227 L132.34368,170.173227 L177.7056,170.173227 L177.7056,169.868587 C177.7056,159.787733 179.829333,151.518293 184.065067,145.069013 C189.911467,136.398507 199.39328,132.059947 212.501333,132.059947 C220.56768,132.059947 227.4336,134.177067 233.070293,138.404907 C240.125013,144.26304 243.664,153.13024 243.664,165.028693 C243.664,172.49216 241.839787,179.143253 238.214827,184.994773 C235.188693,190.2368 230.350293,195.374933 223.686187,200.42048 C209.571627,210.098773 200.394453,219.679573 196.165333,229.162667 C192.53504,237.027413 190.710827,249.530027 190.710827,266.666667 L190.710827,266.666667 L233.376213,266.666667 C233.376213,255.371093 234.87744,246.90624 237.916587,241.257813 C240.331947,236.618667 245.378987,231.682347 253.042987,226.434987 C266.358187,216.549547 275.828267,207.371093 281.479253,198.90112 C288.33216,188.82176 291.76704,177.01952 291.76704,163.504 C291.76704,135.494827 280.37504,115.62624 257.571627,103.9232 C243.865813,96.86848 227.933653,93.3346133 209.77344,93.3346133 Z" id="Combined-Shape"> </path> </g> </g> </g></svg></a>
                <input id="max-tokens-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'max-tokens', '2000')); ?>">
            </label>

            <input type="range" min="5" max="4000" value="<?php echo esc_attr(get_option($key.'max-tokens', '2000')); ?>" step="1" id="max-tokens" class="slider" name="max-tokens">
        </div>
        <p><?php _e('Set the maximum number of tokens to generate in a single request.', 'ai-writing-assistant'); ?></p>
    </div>

    <!-- Top-P input field with tooltip and description -->
    <div class="settings-item">
        <div class="range-input">
            <label for="top-p"><span><?php _e('Top Prediction (Top-P)', 'ai-writing-assistant'); ?></span><a href="https://myrecorp.com/documentation/ai-content-writing-assistant-api-settings/#top-prediction" target="_blank" title="Check documentation"><svg viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" class="doc-what-icon"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="add" fill="#000000" transform="translate(42.666667, 42.666667)"> <path d="M213.333333,3.55271368e-14 C331.153707,3.55271368e-14 426.666667,95.51168 426.666667,213.333333 C426.666667,331.153707 331.153707,426.666667 213.333333,426.666667 C95.51296,426.666667 3.55271368e-14,331.153707 3.55271368e-14,213.333333 C3.55271368e-14,95.51168 95.51296,3.55271368e-14 213.333333,3.55271368e-14 Z M213.332053,282.666667 C198.60416,282.666667 186.665387,294.60544 186.665387,309.333333 C186.665387,324.061227 198.60416,336 213.332053,336 C228.059947,336 239.99872,324.061227 239.99872,309.333333 C239.99872,294.60544 228.059947,282.666667 213.332053,282.666667 Z M209.77344,93.3346133 C189.007787,93.3346133 171.554773,98.9922133 157.43488,110.274773 C140.703147,123.790507 132.34368,143.751253 132.34368,170.173227 L132.34368,170.173227 L177.7056,170.173227 L177.7056,169.868587 C177.7056,159.787733 179.829333,151.518293 184.065067,145.069013 C189.911467,136.398507 199.39328,132.059947 212.501333,132.059947 C220.56768,132.059947 227.4336,134.177067 233.070293,138.404907 C240.125013,144.26304 243.664,153.13024 243.664,165.028693 C243.664,172.49216 241.839787,179.143253 238.214827,184.994773 C235.188693,190.2368 230.350293,195.374933 223.686187,200.42048 C209.571627,210.098773 200.394453,219.679573 196.165333,229.162667 C192.53504,237.027413 190.710827,249.530027 190.710827,266.666667 L190.710827,266.666667 L233.376213,266.666667 C233.376213,255.371093 234.87744,246.90624 237.916587,241.257813 C240.331947,236.618667 245.378987,231.682347 253.042987,226.434987 C266.358187,216.549547 275.828267,207.371093 281.479253,198.90112 C288.33216,188.82176 291.76704,177.01952 291.76704,163.504 C291.76704,135.494827 280.37504,115.62624 257.571627,103.9232 C243.865813,96.86848 227.933653,93.3346133 209.77344,93.3346133 Z" id="Combined-Shape"> </path> </g> </g> </g></svg></a>
                <input id="top-p-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'top-p', '0.5')); ?>">
            </label>
            <input type="range" min="0" max="1" value="<?php echo esc_attr(get_option($key.'top-p', '0.5')); ?>" step="0.01" id="top-p" class="slider" name="top-p">
        </div>

        <p><?php _e('Adjust the Top-P (Top Prediction) parameter to control the diversity of the generated text.', 'ai-writing-assistant'); ?></p>
    </div>

    <!-- "Best of" input field with tooltip and description -->
    <div class="settings-item">
        <div class="range-input">
            <label for="best-of"><span><?php _e('Best of', 'ai-writing-assistant'); ?></span><a href="https://myrecorp.com/documentation/ai-content-writing-assistant-api-settings/#best-of" target="_blank" title="Check documentation"><svg viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" class="doc-what-icon"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="add" fill="#000000" transform="translate(42.666667, 42.666667)"> <path d="M213.333333,3.55271368e-14 C331.153707,3.55271368e-14 426.666667,95.51168 426.666667,213.333333 C426.666667,331.153707 331.153707,426.666667 213.333333,426.666667 C95.51296,426.666667 3.55271368e-14,331.153707 3.55271368e-14,213.333333 C3.55271368e-14,95.51168 95.51296,3.55271368e-14 213.333333,3.55271368e-14 Z M213.332053,282.666667 C198.60416,282.666667 186.665387,294.60544 186.665387,309.333333 C186.665387,324.061227 198.60416,336 213.332053,336 C228.059947,336 239.99872,324.061227 239.99872,309.333333 C239.99872,294.60544 228.059947,282.666667 213.332053,282.666667 Z M209.77344,93.3346133 C189.007787,93.3346133 171.554773,98.9922133 157.43488,110.274773 C140.703147,123.790507 132.34368,143.751253 132.34368,170.173227 L132.34368,170.173227 L177.7056,170.173227 L177.7056,169.868587 C177.7056,159.787733 179.829333,151.518293 184.065067,145.069013 C189.911467,136.398507 199.39328,132.059947 212.501333,132.059947 C220.56768,132.059947 227.4336,134.177067 233.070293,138.404907 C240.125013,144.26304 243.664,153.13024 243.664,165.028693 C243.664,172.49216 241.839787,179.143253 238.214827,184.994773 C235.188693,190.2368 230.350293,195.374933 223.686187,200.42048 C209.571627,210.098773 200.394453,219.679573 196.165333,229.162667 C192.53504,237.027413 190.710827,249.530027 190.710827,266.666667 L190.710827,266.666667 L233.376213,266.666667 C233.376213,255.371093 234.87744,246.90624 237.916587,241.257813 C240.331947,236.618667 245.378987,231.682347 253.042987,226.434987 C266.358187,216.549547 275.828267,207.371093 281.479253,198.90112 C288.33216,188.82176 291.76704,177.01952 291.76704,163.504 C291.76704,135.494827 280.37504,115.62624 257.571627,103.9232 C243.865813,96.86848 227.933653,93.3346133 209.77344,93.3346133 Z" id="Combined-Shape"> </path> </g> </g> </g></svg></a>
                <input id="best-of-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'best-of', '1')); ?>">
            </label>
            <input type="range" min="0" max="1" value="<?php echo esc_attr(get_option($key.'best-of', '1')); ?>" step="0.01" id="best-of" class="slider" name="best-of">
        </div>
        <p><?php _e('Set the number of generated sequences to return.', 'ai-writing-assistant'); ?></p>
    </div>

    <!-- "Frequency penalty" input field with tooltip and description -->
    <div class="settings-item">
        <div class="range-input">
            <label for="frequency-penalty"><span><?php _e('Frequency Penalty', 'ai-writing-assistant'); ?></span><a href="https://myrecorp.com/documentation/ai-content-writing-assistant-api-settings/#frequency-penalty" target="_blank" title="Check documentation"><svg viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" class="doc-what-icon"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="add" fill="#000000" transform="translate(42.666667, 42.666667)"> <path d="M213.333333,3.55271368e-14 C331.153707,3.55271368e-14 426.666667,95.51168 426.666667,213.333333 C426.666667,331.153707 331.153707,426.666667 213.333333,426.666667 C95.51296,426.666667 3.55271368e-14,331.153707 3.55271368e-14,213.333333 C3.55271368e-14,95.51168 95.51296,3.55271368e-14 213.333333,3.55271368e-14 Z M213.332053,282.666667 C198.60416,282.666667 186.665387,294.60544 186.665387,309.333333 C186.665387,324.061227 198.60416,336 213.332053,336 C228.059947,336 239.99872,324.061227 239.99872,309.333333 C239.99872,294.60544 228.059947,282.666667 213.332053,282.666667 Z M209.77344,93.3346133 C189.007787,93.3346133 171.554773,98.9922133 157.43488,110.274773 C140.703147,123.790507 132.34368,143.751253 132.34368,170.173227 L132.34368,170.173227 L177.7056,170.173227 L177.7056,169.868587 C177.7056,159.787733 179.829333,151.518293 184.065067,145.069013 C189.911467,136.398507 199.39328,132.059947 212.501333,132.059947 C220.56768,132.059947 227.4336,134.177067 233.070293,138.404907 C240.125013,144.26304 243.664,153.13024 243.664,165.028693 C243.664,172.49216 241.839787,179.143253 238.214827,184.994773 C235.188693,190.2368 230.350293,195.374933 223.686187,200.42048 C209.571627,210.098773 200.394453,219.679573 196.165333,229.162667 C192.53504,237.027413 190.710827,249.530027 190.710827,266.666667 L190.710827,266.666667 L233.376213,266.666667 C233.376213,255.371093 234.87744,246.90624 237.916587,241.257813 C240.331947,236.618667 245.378987,231.682347 253.042987,226.434987 C266.358187,216.549547 275.828267,207.371093 281.479253,198.90112 C288.33216,188.82176 291.76704,177.01952 291.76704,163.504 C291.76704,135.494827 280.37504,115.62624 257.571627,103.9232 C243.865813,96.86848 227.933653,93.3346133 209.77344,93.3346133 Z" id="Combined-Shape"> </path> </g> </g> </g></svg></a>
                <input id="frequency-penalty-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'frequency-penalty', '0')); ?>">
            </label>
            <input type="range" min="0" max="2" value="<?php echo esc_attr(get_option($key.'frequency-penalty', '0')); ?>" step="0.01" id="frequency-penalty" class="slider" name="frequency-penalty">
        </div>
        <p><?php _e('Adjust the frequency penalty to control the frequency of words in the generated text.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item">
        <div class="range-input">
            <label for="presence-penalty"><span><?php _e('Presence Penalty', 'ai-writing-assistant'); ?></span><a href="https://myrecorp.com/documentation/ai-content-writing-assistant-api-settings/#presence-penalty" target="_blank" title="Check documentation"><svg viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" class="doc-what-icon"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="add" fill="#000000" transform="translate(42.666667, 42.666667)"> <path d="M213.333333,3.55271368e-14 C331.153707,3.55271368e-14 426.666667,95.51168 426.666667,213.333333 C426.666667,331.153707 331.153707,426.666667 213.333333,426.666667 C95.51296,426.666667 3.55271368e-14,331.153707 3.55271368e-14,213.333333 C3.55271368e-14,95.51168 95.51296,3.55271368e-14 213.333333,3.55271368e-14 Z M213.332053,282.666667 C198.60416,282.666667 186.665387,294.60544 186.665387,309.333333 C186.665387,324.061227 198.60416,336 213.332053,336 C228.059947,336 239.99872,324.061227 239.99872,309.333333 C239.99872,294.60544 228.059947,282.666667 213.332053,282.666667 Z M209.77344,93.3346133 C189.007787,93.3346133 171.554773,98.9922133 157.43488,110.274773 C140.703147,123.790507 132.34368,143.751253 132.34368,170.173227 L132.34368,170.173227 L177.7056,170.173227 L177.7056,169.868587 C177.7056,159.787733 179.829333,151.518293 184.065067,145.069013 C189.911467,136.398507 199.39328,132.059947 212.501333,132.059947 C220.56768,132.059947 227.4336,134.177067 233.070293,138.404907 C240.125013,144.26304 243.664,153.13024 243.664,165.028693 C243.664,172.49216 241.839787,179.143253 238.214827,184.994773 C235.188693,190.2368 230.350293,195.374933 223.686187,200.42048 C209.571627,210.098773 200.394453,219.679573 196.165333,229.162667 C192.53504,237.027413 190.710827,249.530027 190.710827,266.666667 L190.710827,266.666667 L233.376213,266.666667 C233.376213,255.371093 234.87744,246.90624 237.916587,241.257813 C240.331947,236.618667 245.378987,231.682347 253.042987,226.434987 C266.358187,216.549547 275.828267,207.371093 281.479253,198.90112 C288.33216,188.82176 291.76704,177.01952 291.76704,163.504 C291.76704,135.494827 280.37504,115.62624 257.571627,103.9232 C243.865813,96.86848 227.933653,93.3346133 209.77344,93.3346133 Z" id="Combined-Shape"> </path> </g> </g> </g></svg></a>
                <input id="presence-penalty-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'presence-penalty', '0')); ?>"></label>
            <input type="range" min="0" max="2" value="<?php echo esc_attr(get_option($key.'presence-penalty', '0')); ?>" step="0.01" id="presence-penalty" class="slider" name="presence-penalty">
        </div>
        <p><?php _e('Adjust the presence penalty to control the presence of words in the generated text.', 'ai-writing-assistant'); ?></p>
    </div>

    <?php
}

function aiwa_writing_styles_settings_item(){
    $key = 'ai_writing_assistant__';
    ?>
    <div class="settings-item">
        <label for="aiwa-writing-style">
            <span><?php _e('Writing Style', 'ai-writing-assistant'); ?></span>
        </label>
        <select id="aiwa-writing-style" name="writing-style">
            <?php
                aiwa_get_writing_styles_options();
            ?>
        </select>

        <p><?php _e('Choose the writing style of your blog post which fit your need!', 'ai-writing-assistant'); ?></p>
    </div>
    <?php
}

function aiwa_writing_tone_settings_item(){
    $key = 'ai_writing_assistant__';
    ?>
    <div class="settings-item">
        <label for="aiwa-writing-tone">
            <span><?php _e('Writing Tone', 'ai-writing-assistant'); ?></span>
        </label>
        <select id="aiwa-writing-tone" name="writing-tone">
            <?php
                aiwa_get_writing_tone_options();
            ?>
        </select>

        <p><?php _e('Choose the writing tone of your blog post which fit your need!', 'ai-writing-assistant'); ?></p>
    </div>
    <?php
}


function aiwa_call_to_action_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>
    <div class="settings-item">
        <label for="aiwa-add-call-to-action"><span><?php _e('Add Call-to-Action', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-call-to-action" name="add-call-to-action" class="content-settings-input" type="checkbox" data-has-subsettings="" <?php echo esc_attr(get_option($key.'add-call-to-action', 'off')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Select to add "Conclution" text before conclution content.', 'ai-writing-assistant'); ?></p>
    </div>
    <div class="settings-item sub-settings-item  <?php echo esc_attr(get_option($key.'add-call-to-action', 'off')) =='on' ? '': 'aiwa-hidden'; ?>" data-sub-id="aiwa-add-call-to-action"  data-subsettings-of="aiwa-add-call-to-action">
        <label for="aiwa-call-to-action-url"><span><?php _e('Call-to-Action url', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-call-to-action-url" name="call-to-action-url" class="content-settings-input" type="text" placeholder="https://yourlink.com" value="<?php echo esc_url(get_option($key.'call-to-action-url', '')); ?>">
        </label>
        <p><?php _e('Enter an url to add this on call-to-action text.', 'ai-writing-assistant'); ?></p>
    </div>
    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'add-call-to-action', 'off')) =='on' ? '': 'aiwa-hidden'; ?>" data-sub-id="aiwa-add-call-to-action"  data-subsettings-of="aiwa-add-call-to-action">
        <label for="aiwa-call-to-action-position">
            <span><?php _e('Call-to-Action position', 'ai-writing-assistant'); ?></span>
        </label>
        <select name="call-to-action-position" id="aiwa-call-to-action-position">
            <option value="start" <?php echo esc_attr(get_option($key.'call-to-action-position', 'start')) =='start' ? 'selected': ''; ?>>Start</option>
            <option value="end" <?php echo esc_attr(get_option($key.'call-to-action-position', 'start')) =='end' ? 'selected': ''; ?>>End</option>
        </select>
        <p><?php _e('If you want to set the call-to-action section on top then select "Start", if bottom select "End"', 'ai-writing-assistant'); ?></p>
    </div>
    <?php
}

function aiwa_add_introduction_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label for="aiwa-add-introduction"><span><?php _e('Add introduction', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-introduction" class="content-settings-input" name="add-introduction" type="checkbox" data-has-subsettings="" <?php echo esc_attr(get_option($key.'add-introduction', 'on')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Add an introduction beginning of the topics.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'add-introduction', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-add-introduction">
        <label for="aiwa-add-introduction-text"><span><?php _e('Add "Introduction" text', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-introduction-text" class="content-settings-input" name="add-introduction-text" type="checkbox" <?php echo esc_attr(get_option($key.'add-introduction-text', 'off')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Select to add "Introduction:" text before the introduction content.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'add-introduction', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-add-introduction">
        <label for="aiwa-introduction-size"><span><?php _e('Introduction text size', 'ai-writing-assistant'); ?></span></label>
        <select name="introduction-size" id="aiwa-introduction-size">
            <option <?php echo esc_attr(get_option($key.'introduction-size', 'short')) =='short' ? 'selected': 'short'; ?> value=""><?php _e('Short', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'introduction-size', 'short')) =='medium' ? 'selected': 'medium'; ?> value=""><?php _e('Medium', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'introduction-size', 'short')) =='long' ? 'selected': 'long'; ?> value=""><?php _e('Long', 'ai-writing-assistant'); ?></option>
        </select>
        <p><?php _e('Select a size to set how long your introduction size is needed.', 'ai-writing-assistant'); ?></p>
    </div>

    <?php
}

function aiwa_add_conclusion_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label for="aiwa-add-conclusion"><span><?php _e('Add conclusion', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-conclusion" class="content-settings-input" type="checkbox" name="add-conclusion" data-has-subsettings="" <?php echo esc_attr(get_option($key.'add-conclusion', 'on')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Add conclusion end of the topics.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item  <?php echo esc_attr(get_option($key.'add-conclusion', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-add-conclusion">
        <label for="aiwa-add-conclusion-text"><span><?php _e('Add "Conclusion" text', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-conclusion-text" class="content-settings-input" name="add-conclusion-text" type="checkbox" data-has-subsettings="" <?php echo esc_attr(get_option($key.'add-conclusion-text', 'off')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Select to add "Conclusion:" text before the conclusion content.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'add-conclusion', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-add-conclusion">
        <label for="aiwa-conclusion-size"><span><?php _e('Introduction text size', 'ai-writing-assistant'); ?></span></label>
        <select name="conclusion-size" id="aiwa-conclusion-size">
            <option <?php echo esc_attr(get_option($key.'conclusion-size', 'short')) =='short' ? 'selected': 'short'; ?> value=""><?php _e('Short', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'conclusion-size', 'short')) =='medium' ? 'selected': 'medium'; ?> value=""><?php _e('Medium', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'conclusion-size', 'short')) =='long' ? 'selected': 'long'; ?> value=""><?php _e('Long', 'ai-writing-assistant'); ?></option>
        </select>
        <p><?php _e('Select a size for how long your conclusion size is needed.', 'ai-writing-assistant'); ?></p>
    </div>

    <?php
}

function aiwa_add_excerpt_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label for="aiwa-add-excerpt"><span><?php _e('Generate excerpt', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-excerpt" class="content-settings-input" type="checkbox" name="add-excerpt" data-has-subsettings="" <?php echo esc_attr(get_option($key.'add-excerpt', 'off')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Excerpts are optional hand-crafted summaries of your content that can be used in your theme.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item  <?php echo esc_attr(get_option($key.'add-excerpt', 'off')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-add-excerpt">
        <label for="aiwa-excerpt-number-of-words"><span><?php _e('Number of excerpt words', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-excerpt-number-of-words" class="content-settings-input" name="excerpt_number_of_words" type="number" value="<?php echo esc_attr(get_option($key.'excerpt_number_of_words', '50')); ?>">
        </label>
        <p><?php _e('Enter a number of words to get the excerpt content withing the number of words.', 'ai-writing-assistant'); ?></p>
    </div>

    <?php
}

function aiwa_content_length_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label for="aiwa-content-length"><span><?php _e('Content length', 'ai-writing-assistant'); ?></span> </label>
        <select name="content-length" id="aiwa-content-length">
            <option <?php echo esc_attr(get_option($key.'content-length', 'long')) =='long' ? 'selected': ''; ?> value="long">Long</option>
            <option <?php echo esc_attr(get_option($key.'content-length', 'long')) =='medium' ? 'selected': ''; ?> value="medium">Medium</option>
            <option <?php echo esc_attr(get_option($key.'content-length', 'long')) =='short' ? 'selected': ''; ?> value="short">Short</option>
        </select>
        <p><?php _e('Select a content length that fit your need.', 'ai-writing-assistant'); ?></p>
    </div>

    <?php
}

function aiwa_image_beautifier_setting(){
    ?>
    <div class="settings-item">
        <label for="aiwa_image_beautifier">
            <span><?php _e('Image Beautifier', 'ai-writing-assistant'); ?></span>
        </label>
        <input id="aiwa_image_beautifier" type="checkbox" name="aiwa_image_beautifier" disabled>

        <div class="compare-image-box">
            <div class="compared-image" title="Normal image">
                <a href="https://myrecorp.com/wp-content/uploads/2023/02/tree-on-a-field.png" target="_blank"><img src="https://myrecorp.com/wp-content/uploads/2023/02/tree-on-a-field.png" alt="" width="70"></a>
                <div class="desc"><?php _e('Normal image', 'ai-writing-assistant'); ?></div>
            </div>
            <div class="compared-image" title="With image beautifier">
                <a href="https://myrecorp.com/wp-content/uploads/2023/03/tree-on-a-field-with-image-beautifier.png" target="_blank"><img src="https://myrecorp.com/wp-content/uploads/2023/03/tree-on-a-field-with-image-beautifier.png" alt="" width="70"></a>
                <div class="desc"><?php _e('With Image Beautifier', 'ai-writing-assistant'); ?></div>
            </div>
        </div>
        <p class="" style="margin: 0;color: #b01111;">
            <?php echo __('Pro version only!', 'ai-writing-assistant'); ?>
        </p>

        <p><?php _e('If selected then the "Image Beautifier" mode will be activate and make your image awesome. This is must need option to generate you perfect image.', 'ai-writing-assistant'); ?></p>

    </div>
    <?php
}

function aiwa_auto_generate_image_settings_items(){
    $key = 'ai_writing_assistant__';

    $image_experiments = (array) get_option($key.'image_experiments', array('realistic', 'four_k', 'high_resolution', 'trending_in_artstation', 'artstation_three'));
    $image_experiments = array_map('esc_attr', $image_experiments);
    ?>
    <div class="settings-item">
        <label for="aiwa-auto-generate-image"><span><?php _e('Auto generate featured image', 'ai-writing-assistant'); ?></span> </label>
        <input id="aiwa-auto-generate-image" class="content-settings-input" name="auto-generate-image" type="checkbox" data-has-subsettings="" <?php echo esc_attr(get_option($key.'auto-generate-image', 'off')) =='on' ? 'checked': ''; ?>>
        <p><?php _e('Select this to auto-generate the thumbnail image. It will generate from your main prompt.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'auto-generate-image', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-auto-generate-image">
        <?php aiwa_image_beautifier_setting(); ?>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'auto-generate-image', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-auto-generate-image">
        <label>
            <span><?php _e('Image Size', 'ai-writing-assistant'); ?></span>
        </label>
        <select name="ai-image-size">
            <option <?php echo esc_attr(get_option($key.'ai-image-size', 'medium-plus'))=='thumbnail' ? 'selected': ''; ?> value="thumbnail"><?php _e('Thumbnail (256x256px)', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'ai-image-size', 'medium-plus'))=='medium' ? 'selected': ''; ?> value="medium"><?php _e('Medium (512x512px)', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'ai-image-size', 'medium-plus'))=='large' ? 'selected': ''; ?> value="large"><?php _e('Large (1024x1024px)', 'ai-writing-assistant'); ?></option>
        </select>
        <p><?php _e('Choose the size of the image you want to generate with <a href="https://openai.com/dall-e-2/">' . __("DALL-E", "ai-writing-assistant") .'</a>.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'auto-generate-image', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-auto-generate-image">
        <label>
            <span><?php _e('Image Presets', 'ai-writing-assistant'); ?></span>
        </label>

        <select id="aiwa_imagePresets" name="image_presets">
            <option value="realistic,four_k,high_resolution,trending_in_artstation, artstation_three, 3D_render, digital_painting"><?php _e('High Quality Art', 'ai-writing-assistant'); ?></option>
            <option value="realistic,3D_render,eight_k,high_resolution,professional"><?php _e('Realistic', 'ai-writing-assistant'); ?></option>
            <option value="amazing_art,trending_in_artstation,artstation_3,oil_painting,digital_paintinghigh_resolution"><?php _e('Amazing Art', 'ai-writing-assistant'); ?></option>
            <option value="Expert,Stunning,Creative,Popular,Inspired,four_k,trending_in_artstationhigh_resolution"><?php _e('Expert', 'ai-writing-assistant'); ?></option>
            <option value="surreal,abstract,fantasy,pop_art,vector"><?php _e('Surreal', 'ai-writing-assistant'); ?></option>
            <option value="landscape,portrait,iconic,neo_expressionist,four_k"><?php _e('Landscape', 'ai-writing-assistant'); ?></option>
            <option value="realistic,3D_render,eight_k,high-resolution,professional,trending_in_artstation, artstation_three"><?php _e('High Resolution', 'ai-writing-assistant'); ?></option>
            <option value="amazing_art,trending_in_artstation,artstation_3,oil_painting,digital_painting,four_k"><?php _e('Digital Painting', 'ai-writing-assistant'); ?></option>
            <option value="Expert,Stunning,Creative,Popular,Inspired"><?php _e('Pop Art', 'ai-writing-assistant'); ?></option>
            <option value="landscape,iconic,neo_expressionist,four_k,high_resolution"><?php _e('Landscape Painting', 'ai-writing-assistant'); ?></option>
            <option value="realistic,3D_render,four_k,high-resolution,professional"><?php _e('Realistic Art', 'ai-writing-assistant'); ?></option>
            <option value="amazing_art,trending_in_artstation,artstation_3,oil_painting,digital_painting,four_k,high_resolution"><?php _e('Digital Art', 'ai-writing-assistant'); ?></option>
            <option value="Expert,Stunning,Creative,Popular,Inspired,eight_k"><?php _e('Abstract Art', 'ai-writing-assistant'); ?></option>
            <option value="surreal,abstract,fantasy,pop_art,vector"><?php _e('Surrealistic Art', 'ai-writing-assistant'); ?></option>
            <option value="landscape,portrait,iconic,neo_expressionist,four_k"><?php _e('Portrait Painting', 'ai-writing-assistant'); ?></option>
            <option value="neon,realistic,3D_render,eight_k,high_resolution,professional"><?php _e('Neon Light', 'ai-writing-assistant'); ?></option>
        </select>

    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'auto-generate-image', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-auto-generate-image">
        <label>
            <span><?php _e('Image Experiments', 'ai-writing-assistant'); ?></span>
        </label>
        <br>
        <label for="aiwa_no_text" class="image-experiments"><input id="aiwa_no_text" <?php echo in_array('no_text', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[no_text]" disabled> <?php _e('No-text', 'ai-writing-assistant'); ?></label>
        <p class="" style="margin: 0;color: #b01111;">
            <?php echo __('Pro version only!', 'ai-writing-assistant'); ?>
        </p>
        <br>
        <label for="aiwa_realistic" class="image-experiments"><input id="aiwa_realistic" <?php echo in_array('realistic', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[realistic]"> <?php _e('Realistic', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_3D_render" class="image-experiments"><input id="aiwa_3D_render" <?php echo in_array('3D_render', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[3D_render]"> <?php _e('3D render', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_four_k" class="image-experiments"><input id="aiwa_four_k" type="checkbox" <?php echo in_array('four_k', $image_experiments) ? 'checked': ''; ?> name="image_experiments[four_k]"> <?php _e('4K', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_eight_k" class="image-experiments"><input id="aiwa_eight_k" type="checkbox" <?php echo in_array('eight_k', $image_experiments) ? 'checked': ''; ?> name="image_experiments[eight_k]"> <?php _e('8K', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_amazing_art" class="image-experiments"><input id="aiwa_amazing_art" <?php echo in_array('amazing_art', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[amazing_art]"> <?php _e('Amazing art', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_high_resolution" class="image-experiments"><input id="aiwa_high_resolution" <?php echo in_array('high_resolution', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[high_resolution]"><?php _e('High resolution', 'ai-writing-assistant'); ?></label>
        <br>
        <label for="aiwa_trending_in_artstation" class="image-experiments"><input id="aiwa_trending_in_artstation" <?php echo in_array('trending_in_artstation', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[trending_in_artstation]"> <?php _e('Trending in artstation', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_artstation_3" class="image-experiments"><input id="aiwa_artstation_3" type="checkbox" <?php echo in_array('artstation_three', $image_experiments) ? 'checked': ''; ?> name="image_experiments[artstation_three]"> <?php _e('Artstation 3', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_oil_painting" class="image-experiments"><input id="aiwa_oil_painting" type="checkbox" <?php echo in_array('oil_painting', $image_experiments) ? 'checked': ''; ?> name="image_experiments[oil_painting]"> <?php _e('Oil painting', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_digital_painting" class="image-experiments"><input id="aiwa_digital_painting" <?php echo in_array('digital_painting', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[digital_painting]"> <?php _e('Digital painting', 'ai-writing-assistant'); ?></label>

        <br>
        <label for="aiwa_professional" class="image-experiments">
            <input id="aiwa_professional" <?php echo in_array('professional', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[professional]">
            <?php _e('Professional', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_Expert" class="image-experiments">
            <input id="aiwa_Expert" <?php echo in_array('Expert', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[Expert]">
            <?php _e('Expert', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_Stunning" class="image-experiments">
            <input id="aiwa_Stunning" <?php echo in_array('Stunning', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[Stunning]">
            <?php _e('Stunning', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_Creative" class="image-experiments">
            <input id="aiwa_Creative" <?php echo in_array('Creative', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[Creative]">
            <?php _e('Creative', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_Popular" class="image-experiments">
            <input id="aiwa_Popular" <?php echo in_array('Popular', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[Popular]">
            <?php _e('Popular', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_Inspired" class="image-experiments">
            <input id="aiwa_Inspired" <?php echo in_array('Inspired', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[Inspired]">
            <?php _e('Inspired', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_surreal" class="image-experiments">
            <input id="aiwa_surreal" <?php echo in_array('surreal', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[surreal]">
            <?php _e('Surreal', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_abstract" class="image-experiments">
            <input id="aiwa_abstract" <?php echo in_array('abstract', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[abstract]">
            <?php _e('Abstract', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_fantasy" class="image-experiments">
            <input id="aiwa_fantasy" <?php echo in_array('fantasy', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[fantasy]">
            <?php _e('Fantasy', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_pop_art" class="image-experiments">
            <input id="aiwa_pop_art" <?php echo in_array('pop_art', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[pop_art]">
            <?php _e('Pop Art', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_neo_expressionist" class="image-experiments">
            <input id="aiwa_neo_expressionist" <?php echo in_array('neo_expressionist', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[neo_expressionist]">
            <?php _e('Neo-expressionist', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_vector" class="image-experiments">
            <input id="aiwa_vector" <?php echo in_array('vector', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[vector]">
            <?php _e('Vector', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_neon" class="image-experiments">
            <input id="aiwa_neon" <?php echo in_array('neon', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[neon]">
            <?php _e('Neon', 'ai-writing-assistant'); ?>
        </label>

        <label for="aiwa_landscape" class="image-experiments">
            <input id="aiwa_landscape" <?php echo in_array('land_scape', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[landscape]">
            <?php _e('Landscape', 'ai-writing-assistant'); ?>
        </label>
        <label for="aiwa_portrait" class="image-experiments">
            <input id="aiwa_portrait" <?php echo in_array('portrait', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[portrait]">
            <?php _e('Portrait', 'ai-writing-assistant'); ?>
        </label>
        <label for="aiwa_iconic" class="image-experiments">
            <input id="aiwa_iconic" <?php echo in_array('iconic', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[iconic]">
            <?php _e('Iconic', 'ai-writing-assistant'); ?>
        </label>

        <p><?php _e('Choose the above styles to generate image.', 'ai-writing-assistant'); ?></p>

        <p style="margin-bottom: 5px;color: #b01111;margin-top: 15px;"><?php _e('Some images may appear with broken or misspelled text, this issue come from OpenAI.', 'ai-writing-assistant'); ?></p>

    </div>

    <?php
}

function aiwa_languages_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label>
            <span><?php _e('Select Language', 'ai-writing-assistant'); ?></span>
        </label>
        <select name="aiwa-language" id="aiwa-language">
            <?php
                aiwa_get_languages_options();
            ?>
        </select>
        <input type="hidden" name="aiwa_language_text" id="aiwa_language_text" value="<?php echo esc_attr(get_option($key . 'aiwa_language_text', 'English')); ?>">

        <p><?php _e('Select a language to generate contents with the language.', 'ai-writing-assistant'); ?></p>
    </div>


    <?php
}


function aiwa_select_title_before_generate_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label for="aiwa-select-title-before-generate"><span><?php _e('Select Post Title Before Generate', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-select-title-before-generate" class="content-settings-input" type="checkbox" name="select-title-before-generate" data-has-subsettings="" <?php echo esc_attr(get_option($key.'select-title-before-generate', 'on')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Check this if you want to select the post title first before the content generation.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item  <?php echo esc_attr(get_option($key.'select-title-before-generate', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-select-title-before-generate">
        <label for="aiwa-how-many-titles-show-first"><span><?php _e('How many titles you want to show to select?', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-titles-show-first" class="content-settings-input" name="how-many-titles-show-first" type="number" value="<?php echo esc_attr(get_option($key . 'how-many-titles-show-first', '3')); ?>" placeholder="3">
        </label>
        <p><?php _e('Enter a number of titles you want to show first to select before the content generate.', 'ai-writing-assistant'); ?></p>
        <p class="title_exceeded" style="margin: 0;color: #b01111;">
            <?php echo sprintf(
                __('You can generate only 3 titles in the free version. %s for more!', 'ai-writing-assistant'),
                '<a href="https://myrecorp.com/ai-content-writing-assistant/" target="_blank">' . __('Upgrade to Pro', 'ai-writing-assistant') . '</a>'
            ); ?>
        </p>
    </div>


    <input type="hidden" name="aiwa_is_title_selected" value="0">
    <input type="hidden" name="aiwa_selected_title" class="aiwa_selected_title" value="">


    <?php
}

function aiwa_post_types_and_categories(){
    ?>
    <div class="settings-item">
            <label for="aiwa-single-post-type"><span><?php echo __('Post Type','ai-writing-assistant'); ?></span> </label>
            <select name="single_generation_post_type" id="aiwa-single-post-type" data-has-subsettings="">
                <?php
                $post_types = aiwa_get_post_types();
                foreach ( $post_types as $post_type ) {
                    $post_type_object = get_post_type_object($post_type);
                    $isChecked = in_array($post_type, array('post')) ? 'selected': '';
                    echo '<option id="aiwa-'.esc_attr($post_type).'" '.esc_attr($isChecked).' value="'.esc_attr($post_type).'"> '.esc_attr($post_type_object->labels->singular_name).'</option>';
                }
                ?>
            </select>
            <p><?php echo __('Select a post type','ai-writing-assistant'); ?></p>
        </div>
        <?php
        $post_types = aiwa_get_post_types();
        $key = 'ai_writing_assistant__';

        $postTypeHasCats = "";
        if (!empty($post_types)){
            foreach ($post_types as $post_type) {
                $taxonomies = get_object_taxonomies( $post_type );
                if ( in_array( 'category', $taxonomies ) ) {
                    $postTypeHasCats .= $post_type . ',';
                }

            }
            ?>
            <div class="settings-item sub-settings-item" data-subsettings-of="aiwa-single-post-type" data-sub-settings-key="<?php echo rtrim($postTypeHasCats, ','); ?>">
                <?php
                    aiwa_get_terms_hierarchical();
                ?>
                <p><?php echo __('Select a category','ai-writing-assistant'); ?></p>
            </div>

            <?php
        }
}

function aiwa_post_types_selectbox($id="aiwa-post-type-selectbox", $selectedValue="post", $label=true, $echo = true, $isDisabled=false){
    ob_start();
    $disabled = $isDisabled ? "disabled" : "";
?>
    <?php if($label): ?>
        <label for="<?php echo $id; ?>"><span><?php echo __('Post Type','ai-writing-assistant'); ?></span> </label>
    <?php endif; ?>

    <select name="aiwa_post_type_selectbox" id="<?php echo $id; ?>" <?php echo $disabled; ?>>
        <?php
        $post_types = aiwa_get_post_types();
        foreach ( $post_types as $post_type ) {
            $post_type_object = get_post_type_object($post_type);
            $isChecked = ($post_type == $selectedValue) ? 'selected': '';
            echo '<option id="aiwa-'.esc_attr($post_type).'" '.esc_attr($isChecked).' value="'.esc_attr($post_type).'"> '.esc_attr($post_type_object->labels->singular_name).'</option>';
        }
        ?>
    </select>

    <?php

    if ($echo){
        echo ob_get_clean();
    }else{
        return ob_get_clean();
    }

}

function aiwa_super_fast_generation_mode_section(){
    ?>
    <div class="settings-item" style="margin-bottom: 10px;">
        <label for="aiwa-super_fast_generation_mode"><span><?php _e('Super fast generation mode', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-super_fast_generation_mode" class="content-settings-input" type="checkbox" name="super_fast_generation_mode" disabled>
        </label>
        <p><?php _e('Select this to activate super fast generation mode, this will help you to generate a post 3X plus times.', 'ai-writing-assistant'); ?></p>
        <p class="title_exceeded" style="margin: 0;color: #b01111;">
            <?php _e('This is for the Pro version only.', 'ai-writing-assistant'); ?>
        </p>
    </div>
    <?php
}

function aiwa_get_content_length_selectbox($id="", $selectedValue="long", $echo = true, $isDisabled=false){
    $disabled = $isDisabled ? "disabled" : "";
    $html = '<select name="aiwa_content_length_selectbox" id="'.$id.'" '.$disabled.'>';
        $html .= '<option ' . (($selectedValue == "short") ? "selected": "") .' value="short">'. __('Short', 'ai-writing-assistant') . '</option>';
        $html .= '<option ' . (($selectedValue == "medium") ? "selected": "") .' value="medium">'. __('Medium', 'ai-writing-assistant') . '</option>';
        $html .= '<option ' . (($selectedValue == "long") ? "selected": "") .' value="long">'. __('Long', 'ai-writing-assistant') . '</option>';
    $html .= '</select>';

    if ($echo){
        echo $html;
    }
    else{
        return $html;
    }
}

function aiwa_category_selectbox($id="exportable-cat", $selectedCat="", $echo=true, $isDisabled=false){
    ob_start();
    $disabled = $isDisabled ? "disabled" : "";
    aiwa_get_terms_hierarchical($id, $selectedCat, $disabled);
    if ($echo){
        echo ob_get_clean();
    }
    else{
        return ob_get_clean();
    }
}

function aiwa_get_terms_hierarchical($id="aiwa-single-category", $selectedCat="", $disabled=false) {
    $terms = get_terms('category', array( 'hide_empty' => false, 'parent'=> 0));
//get_term_link($term->slug, 'species')
    if (!empty($terms)) {
        echo '<select id="'.$id.'" '.$disabled.'>';
        echo '<option '.($selectedCat=="" ? "selected": "" ).' value="">' . __('Select a category','ai-writing-assistant').'</option>';

        foreach ($terms as $key => $term) {


            printf( '<option '.($selectedCat==$term->term_id ? "selected": "" ).' value="%1$s">%2$s</option>',
                esc_attr( $term->term_id ),
                esc_html( $term->name )
            );

            $child_cats = get_terms('category', array( 'hide_empty' => false, 'parent'=> $term->term_id));

            if (!empty($child_cats)) {
                aiwa_get_terms_data_with_hierarchical($child_cats, 2, $selectedCat);
            }

        }
        echo '</select>';
    }
}

function aiwa_get_terms_data_with_hierarchical($terms, $level = 1, $selectedCat=""){
    $dash = "";
    for ($i=1; $i < $level; $i++){
        $dash .= "-";
    }
    if (!empty($terms)) {
        foreach ($terms as $key => $term) {

            printf( '<option '.($selectedCat==$term->term_id ? "selected": "" ).' value="%1$s">'.$dash.'%2$s</option>',
                esc_attr( $term->term_id ),
                esc_html( $term->name )
            );
            $child_cats = get_terms('category', array( 'hide_empty' => false, 'parent'=> $term->term_id));

            if (!empty($child_cats)) {
                aiwa_get_terms_data_with_hierarchical($child_cats, $level+1, $selectedCat);
            }

        }
    }
}