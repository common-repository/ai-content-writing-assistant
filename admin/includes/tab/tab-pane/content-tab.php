<?php aiwa_languages_settings_items(); ?>
<div class="settings-item">
    <label for="aiwa-previously_failed"><span><?php _e('Previously failed', 'ai-writing-assistant'); ?></span>
        <input id="aiwa-previously_failed" class="content-settings-input" type="checkbox" name="previously_failed" <?php echo esc_attr(get_option($key.'previously_failed', 'off')) =='on' ? 'checked': ''; ?>>
    </label>
    <p><?php _e('Select this if generation failed previously, if selected then it will disable live (streaming) generation.', 'ai-writing-assistant'); ?></p>
    </p>
</div>

<?php aiwa_super_fast_generation_mode_section(); ?>

<?php aiwa_select_title_before_generate_settings_items(); ?>

<!--Get the content types selectbox settings item-->
<?php aiwa_content_structure_settings_item(); ?>

<!--Get the content length selectbox settings item-->
<?php aiwa_content_length_settings_items(); ?>




<!--Get the writing styles selectbox settings item-->
<?php aiwa_writing_styles_settings_item(); ?>

<!--Get the writing tone selectbox settings item-->
<?php aiwa_writing_tone_settings_item(); ?>

<?php aiwa_add_excerpt_settings_items(); ?>

<?php aiwa_add_introduction_settings_items(); ?>
<?php aiwa_add_conclusion_settings_items(); ?>

<!--Get the Call-to-action settings item-->
<?php aiwa_call_to_action_settings_items(); ?>

<?php aiwa_auto_generate_image_settings_items(); ?>
