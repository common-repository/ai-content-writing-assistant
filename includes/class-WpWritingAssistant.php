<?php
namespace WpWritingAssistant;

class WpWritingAssistant
{
    public function run()
    {
        $this->require_dependencies();
    }

    public function require_dependencies()
    {
        //This class is dependent for all admin functionalities
        require AIWA_DIR_PATH . 'includes/global-functions.php';
        require AIWA_DIR_PATH . 'includes/OpenAi.php';
        require AIWA_DIR_PATH . 'admin/AI_Writing_Assistant_Admin.php';
        new AI_Writing_Assistant_Admin();
    }
}