<?php
    $key = 'ai_writing_assistant__';
?>
    <!-- API Key input field with tooltip -->
<?php if(current_user_can('administrator')): ?>
    <div class="settings-item">
        <label>
            <span><?php _e('API Key', 'ai-writing-assistant'); ?></span>
            <input type="text" name="api-key" value="<?php echo !empty(get_option($key.'api-key', '')) ? esc_attr(aiwa_open_api_chars(get_option($key.'api-key', ''))) : ''; ?>" placeholder="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" style="width: 310px;">
            <div class="tooltip">
                <svg viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" class="doc-what-icon" style="position: relative;margin-left: 0;top: 3px;"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="add" fill="#000000" transform="translate(42.666667, 42.666667)"> <path d="M213.333333,3.55271368e-14 C331.153707,3.55271368e-14 426.666667,95.51168 426.666667,213.333333 C426.666667,331.153707 331.153707,426.666667 213.333333,426.666667 C95.51296,426.666667 3.55271368e-14,331.153707 3.55271368e-14,213.333333 C3.55271368e-14,95.51168 95.51296,3.55271368e-14 213.333333,3.55271368e-14 Z M213.332053,282.666667 C198.60416,282.666667 186.665387,294.60544 186.665387,309.333333 C186.665387,324.061227 198.60416,336 213.332053,336 C228.059947,336 239.99872,324.061227 239.99872,309.333333 C239.99872,294.60544 228.059947,282.666667 213.332053,282.666667 Z M209.77344,93.3346133 C189.007787,93.3346133 171.554773,98.9922133 157.43488,110.274773 C140.703147,123.790507 132.34368,143.751253 132.34368,170.173227 L132.34368,170.173227 L177.7056,170.173227 L177.7056,169.868587 C177.7056,159.787733 179.829333,151.518293 184.065067,145.069013 C189.911467,136.398507 199.39328,132.059947 212.501333,132.059947 C220.56768,132.059947 227.4336,134.177067 233.070293,138.404907 C240.125013,144.26304 243.664,153.13024 243.664,165.028693 C243.664,172.49216 241.839787,179.143253 238.214827,184.994773 C235.188693,190.2368 230.350293,195.374933 223.686187,200.42048 C209.571627,210.098773 200.394453,219.679573 196.165333,229.162667 C192.53504,237.027413 190.710827,249.530027 190.710827,266.666667 L190.710827,266.666667 L233.376213,266.666667 C233.376213,255.371093 234.87744,246.90624 237.916587,241.257813 C240.331947,236.618667 245.378987,231.682347 253.042987,226.434987 C266.358187,216.549547 275.828267,207.371093 281.479253,198.90112 C288.33216,188.82176 291.76704,177.01952 291.76704,163.504 C291.76704,135.494827 280.37504,115.62624 257.571627,103.9232 C243.865813,96.86848 227.933653,93.3346133 209.77344,93.3346133 Z" id="Combined-Shape"> </path> </g> </g> </g></svg>
                <span class="tooltiptext">
                    <?php _e('Enter your GPT-3 API key here. You can get your API key from the <a href="https://beta.openai.com/account/api-keys">OpenAI Beta Signup</a> page.', 'ai-writing-assistant'); ?>
                </span>
            </div>
            <p><?php _e('Enter your API key to use the GPT-3 API. <a href="https://beta.openai.com/account/api-keys" target="_blank">Get the API key</a>', 'ai-writing-assistant'); ?></p>
        </label>
    </div>
<?php endif;

    aiwa_api_settings();
?>
<!--    <label>
        <span>Frequency penalty</span>
        <input type="number" name="frequency-penalty" value="0">
        <div class="tooltip">
            ?
            <span class="tooltiptext">
        The frequency penalty controls the frequency of words in the generated text. A higher value will result in a lower frequency of words, while a lower value will result in a higher frequency of words.
      </span>
        </div>
        <p>Adjust the frequency penalty to control the frequency of words in the generated text.</p>
    </label>-->

<!--    <label>
        <span>Presence penalty</span>
        <input type="number" name="presence-penalty" value="0">
        <div class="tooltip">
            ?
            <span class="tooltiptext">
        The presence penalty controls the presence of words in the generated text. A higher value will result in a lower presence of words, while a lower value will result in a higher presence of words.
      </span>
        </div>
        <p>Adjust the presence penalty to control the presence of words in the generated text.</p>
    </label>-->

<input type="hidden" id="aiwa-placeholders-is-set" value="<?php echo !empty(get_option('aiwa-placeholders', '')) ? '1': '0'; ?>">

<?php
