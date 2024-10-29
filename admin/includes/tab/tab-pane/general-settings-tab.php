<?php
    $key = 'ai_writing_assistant__';
    $selected_user_roles = (array) get_option($key.'user_roles', array());
    $selected_user_roles = array_map('esc_attr', $selected_user_roles);

    $selected_post_types = (array) get_option($key.'post_types', array('post', 'page', 'product'));
    $selected_post_types = array_map('esc_attr', $selected_post_types);

    $page_builders = (array) get_option($key.'page_builders', array());
    $page_builders = array_map('esc_attr', $page_builders);

    $disabled_builder = array('elementor','divi','beaver_builder','visual_composer');
    /*if (!defined( 'ELEMENTOR_VERSION' )){
        $disabled_builder[] = 'elementor';
    }
    if (!class_exists( 'ET_Builder_Plugin' )){
        $disabled_builder[] = 'divi';
    }
    if (!is_plugin_active( 'beaver-builder-lite-version/fl-builder.php' ) && !is_plugin_active( 'beaver-builder/fl-builder.php' )){
        $disabled_builder[] = 'beaver_builder';
    }
    if (!is_plugin_active( 'js_composer/js_composer.php' ) && !is_plugin_active( 'wpbakery/js_composer.php' )){
        $disabled_builder[] = 'visual_composer';
    }*/

?>

<?php if(current_user_can('administrator')): ?>
    <div class="settings-item">
        <label>
            <span><?php _e('User roles can access', 'ai-writing-assistant'); ?></span>
        </label>
        <br>
        <br>

        <?php
        $wp_roles = wp_roles()->get_names();
        foreach ( $wp_roles as $role => $name ) {
            if ($role=="administrator"){
                echo '<label for="aiwa-administrator" class="checkbox-label aiwa-user-roles"><input id="aiwa-administrator" type="checkbox" name="administrator_" checked disabled> Administrator</label>';
            }
            else{
                  $isChecked = in_array($role, $selected_user_roles) ? 'checked': '';
                  echo '<label for="aiwa-'.esc_attr($role).'" class="checkbox-label aiwa-user-roles"><input id="aiwa-'.esc_attr($role).'" '.esc_attr($isChecked).' type="checkbox" name="user_roles['.esc_attr($role).']"> '.esc_attr($name).'</label>';
                }
            }
          ?>
        <p><?php _e('Select user roles to access the "AI Writing Assistant" option.', 'ai-writing-assistant'); ?></p>
    </div>

<div class="settings-item">
    <label>
        <span><?php _e('Enable AI Writing Assistant for', 'ai-writing-assistant'); ?></span>
    </label>
    <br>
    <br>

    <?php
    $post_types = aiwa_get_post_types();
    foreach ( $post_types as $post_type ) {
        $post_type_object = get_post_type_object($post_type);
        $isChecked = in_array($post_type, $selected_post_types) ? 'checked': '';
        echo '<label for="aiwa-'.esc_attr($post_type).'" class="checkbox-label aiwa-post-types"><input id="aiwa-'.esc_attr($post_type).'" '.esc_attr($isChecked).' type="checkbox" name="post_types['.esc_attr($post_type).']"> '.esc_attr($post_type_object->labels->singular_name).'</label>';
    }
    ?>
    <p><?php _e('Choose the post types where you want to use AI Writing Assistant.', 'ai-writing-assistant'); ?></p>
</div>

<div class="settings-item">
    <label for="aiwa-collapse-aiwa"><span><?php _e('Collapse AI Assistant after Insert', 'ai-writing-assistant'); ?></span>
        <input id="aiwa-collapse-aiwa" class="content-settings-input" type="checkbox" name="collapse_aiwa" <?php echo esc_attr(get_option($key.'collapse_aiwa', 'off')) == 'on' ? 'checked': ''; ?>>
    </label>
    <p><?php _e('When enabled, the AI Assistant will be automatically collapsed after inserting text.', 'ai-writing-assistant'); ?></p>
</div>



<div class="settings-item">
    <label>
        <span><?php _e('Page Builders', 'ai-writing-assistant'); ?></span>
    </label>
    <br>
    <label for="aiwa-elementor" class="checkbox-label page-builders"><input id="aiwa-elementor" <?php echo in_array('elementor', $page_builders) ? 'checked=""': ''; ?> <?php echo in_array('elementor', $disabled_builder) ? 'disabled': ''; ?> type="checkbox" name="page_builders[elementor]"> <?php _e('Elementor (coming soon)', 'ai-writing-assistant'); ?></label>
    <label for="aiwa-divi" class="checkbox-label page-builders"><input id="aiwa-divi" <?php echo in_array('divi', $page_builders) ? 'checked=""': ''; ?> <?php echo in_array('divi', $disabled_builder) ? 'disabled': ''; ?> type="checkbox" name="page_builders[divi]"> <?php _e('Divi Builder (coming soon)', 'ai-writing-assistant'); ?></label>
    <label for="aiwa-beaver-builder" class="checkbox-label page-builders"><input id="aiwa-beaver-builder" <?php echo in_array('beaver_builder', $page_builders) ? 'checked=""': ''; ?> <?php echo in_array('beaver_builder', $disabled_builder) ? 'disabled': ''; ?> type="checkbox" name="page_builders[beaver_builder]"> <?php _e('Beaver Builder (coming soon)', 'ai-writing-assistant'); ?></label>
    <label for="aiwa-visual-composer" class="checkbox-label page-builders"><input id="aiwa-visual-composer" <?php echo in_array('visual_composer', $page_builders) ? 'checked=""': ''; ?> <?php echo in_array('visual_composer', $disabled_builder) ? 'disabled': ''; ?> type="checkbox" name="page_builders[visual_composer]"> <?php _e('Visual Composer/WP Bakery (coming soon)', 'ai-writing-assistant'); ?></label>

    <p><?php _e('Select the page builders you want to integrate with this plugin.', 'ai-writing-assistant'); ?></p>
</div>

<?php endif; ?>

