<?php
/*
function add_custom_button_to_comment_row($actions, $comment) {
    $custom_button = '<a href="#" class="aiwa-create-response">🔥<span style="color: #109b61;">'. __('Create Response with AI', 'ai-writing-assistant') .'</span></a>';
    $actions['custom_action'] = $custom_button;
    return $actions;
}
add_filter('comment_row_actions', 'add_custom_button_to_comment_row', 10, 2);

function generate_comment_reply() {
    wp_enqueue_script('generate-comment-reply', AIWA_DIR_URL . '/admin/assets/js/generate-comment-reply.js', array('jquery'), AIWA_VERSION, true);

}
add_action( 'admin_enqueue_scripts', 'generate_comment_reply' );*/


namespace GenerateAICommentReply;

class CustomCommentActions
{

    public function __construct()
    {
        add_filter('comment_row_actions', array($this, 'addCustomButtonToCommentRow'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'generateCommentReply'));
    }

    public function addCustomButtonToCommentRow($actions, $comment)
    {
        $custom_button            = '<a href="#" class="aiwa-create-response">🔥<span style="color: #109b61;">' . __('Create Response with AI', 'ai-writing-assistant') . '</span></a>';
        $actions['custom_action'] = $custom_button;
        return $actions;
    }

    public function generateCommentReply()
    {
        global $pagenow;

        if (is_admin() && $pagenow === 'edit-comments.php') {
            wp_enqueue_script('generate-comment-reply', AIWA_DIR_URL . '/admin/assets/js/generate-comment-reply.js', array('jquery'), AIWA_VERSION, true);
        }
    }
}

new CustomCommentActions();

