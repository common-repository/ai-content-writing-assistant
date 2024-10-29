<?php
namespace WpWritingAssistant;

ini_set('max_execution_time', '500');

class Cron_Tasks{

    /**
     * Cron_Tasks constructor.
     */
    public function __construct()
    {
        add_filter( 'cron_schedules', array($this, 'every_minute_add_cron_interval') );

        if ( ! wp_next_scheduled( 'aiwa_per_minutes_cron_task' ) ) {
            wp_schedule_event( time(), 'aiwa_per_minute', 'aiwa_per_minutes_cron_task' );
        }

        add_action('aiwa_per_minutes_cron_task', array($this, 'check_for_cron_task'));
    }

    public function every_minute_add_cron_interval( $schedules ) {
        $schedules['aiwa_per_minute'] = array(
            'interval' => 60,
            'display'  => esc_html__( 'Every Minutes' ), );
        return $schedules;
    }

    public function check_for_cron_task()
    {
        global $wpdb;
        $titles = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ai_writing_assistant_sceduled_posts WHERE status != 'completed' AND status != 'queued' ");
        if (!empty($titles)) {
            //foreach ($titles as $title) {
            if (isset($titles[0])){
                $title = $titles[0];
                $scheduled_time    = $title->scheduled_time;
                $timezone = get_option('timezone_string');
                date_default_timezone_set($timezone);

                $scheduled_timestamp = strtotime($scheduled_time);
                if ($scheduled_timestamp <= current_time( 'timestamp' )) {
                    $this->setGenerationQueued($title->id);

                    $postId = aiwa_get_ai_data($title);
                    if($postId!==false){
                        $this->setGenerationComplete($title->id, $postId);
                    }
                    else{
                        $this->clearFromQueuedList($title->id);
                    }
                }
            }

            //}
        }
    }

    private function setGenerationComplete($id, $postId)
    {
        global $wpdb;
        $table_name = $wpdb->prefix.'ai_writing_assistant_sceduled_posts';
        $isUpdate = $wpdb->update(
            $table_name,
            array(
                'status' => "completed",
                'post_id' => intval($postId),
            ),
            array( 'id' => intval($id) ),
            array(
                '%s',
                '%d',
            ),
            array( '%d' )
        );

        return true;
    }

    private function setGenerationQueued($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix.'ai_writing_assistant_sceduled_posts';
        $isUpdate = $wpdb->update(
            $table_name,
            array(
                'status' => "queued",
            ),
            array( 'id' => intval($id) ),
            array(
                '%s',
            ),
            array( '%d' )
        );

        return true;
    }

    private function clearFromQueuedList($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix.'ai_writing_assistant_sceduled_posts';
        $isUpdate = $wpdb->update(
            $table_name,
            array(
                'status' => "",
            ),
            array( 'id' => intval($id) ),
            array(
                '%s',
            ),
            array( '%d' )
        );

        return true;
    }
}