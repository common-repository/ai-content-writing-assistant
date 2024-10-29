<?php
namespace WpWritingAssistant;

class PluginDeactivator{

    public function deactivator()
    {
        $this->removeTable();
        aiwa_cron_events_deactivate();
    }

    public function removeTable()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ai_writing_assistant_sceduled_posts");
    }
}