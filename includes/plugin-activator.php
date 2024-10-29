<?php
namespace WpWritingAssistant;

class PluginActivator{

    public function activator()
    {
        if ( is_plugin_active( 'ai-content-writing-assistant-pro/ai-writing-assistant.php' ) ) {
            deactivate_plugins( 'ai-content-writing-assistant-pro/ai-writing-assistant.php' );
        }
        if ( is_plugin_active( 'ai-content-writing-assistant-pro-premium/ai-writing-assistant.php' ) ) {
            deactivate_plugins( 'ai-content-writing-assistant-pro-premium/ai-writing-assistant.php' );
        }

        update_option( 'aiwa_wp_plugin_activation_date', time() );
        $this->createTable();
        aiwa_cron_events_activate();
    }

    public function createTable()
    {
        global $wpdb;

        $table_name = $wpdb->prefix.'ai_writing_assistant_sceduled_posts';
        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

        if ( ! $wpdb->get_var( $query ) == $table_name ) {

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE " . $table_name ." (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              title text NOT NULL,
              scheduled_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              post_type MEDIUMTEXT NOT NULL,
              category MEDIUMTEXT NOT NULL,
              language MEDIUMTEXT NOT NULL,
              content_structure MEDIUMTEXT NOT NULL,
              content_length MEDIUMTEXT NOT NULL,
              keywords MEDIUMTEXT NOT NULL,
              writing_style MEDIUMTEXT NOT NULL,
              writing_tone MEDIUMTEXT NOT NULL,
              generate_image INTEGER(1) NOT NULL,
              status MEDIUMTEXT NOT NULL,
              post_id INTEGER(9) NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";


            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }

}