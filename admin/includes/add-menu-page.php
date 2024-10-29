<?php
class AI_Writing_Assistant_Menu {

    private $admin;

    // Constructor function
    public function __construct($a) {
        // Add an action hook to create the menu page

        $this->admin = $a;

        if ($a->hasAccess()){
            add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        }

    }


    // Function to add the menu page
    public function add_menu_page() {
        // Use the add_menu_page function to add a new menu page to the WordPress dashboard

        add_menu_page(
            __('AI Writing Assistant', 'ai-writing-assistant'), // Page title
            __('AI Writing Assistant', 'ai-writing-assistant'), // Menu title
            'publish_posts', // Capability (user role) required to access the menu page
            'ai-writing-assistant', // Menu slug (unique identifier)
            array( $this, 'render_menu_page' ), // Function to render the menu page
            'dashicons-text', // Icon URL
            4  // Menu position
        );

        add_submenu_page(
            'ai-writing-assistant', // Parent menu slug
            __('Single Content Generator', 'ai-writing-assistant'), // Page title
            __('Single Content Generator', 'ai-writing-assistant'), // Menu title
            'publish_posts', // Capability
            'single-content-generator', // Menu slug
            array($this, 'single_content_generator') // Function to display the page content
        );

        add_submenu_page(
            'ai-writing-assistant', // Parent menu slug
            __('Scheduled Content Generator', 'ai-writing-assistant'), // Page title
            __('Scheduled Content Generator', 'ai-writing-assistant'), // Menu title
            'publish_posts', // Capability
            'scheduled-content-generator', // Menu slug
            array($this, 'auto_content_generator') // Function to display the page content
        );
        add_submenu_page(
            'ai-writing-assistant', // Parent menu slug
            __('AI Image Generator', 'ai-writing-assistant'), // Page title
            __('AI Image Generator', 'ai-writing-assistant'), // Menu title
            'publish_posts', // Capability
            'ai-image-generator', // Menu slug
            array($this, 'ai_image_generator') // Function to display the page content
        );
        add_submenu_page(
            'ai-writing-assistant', // Parent menu slug
            __('GPT Playground', 'ai-writing-assistant'), // Page title
            __('GPT Playground', 'ai-writing-assistant'), // Menu title
            'publish_posts', // Capability
            'chat-gpt-playground', // Menu slug
            array($this, 'chat_gpt_playground') // Function to display the page content
        );

    }

    // Function to render the menu page content
    public function render_menu_page() {
        // Render the menu page content here
        require 'menu-pages/settings-page-display.php';
    }
    public function single_content_generator() {
        require 'menu-pages/generate-single-post.php';
    }
    public function auto_content_generator() {
        require 'menu-pages/auto-content-writer.php';
    }
    public function ai_image_generator() {
        require 'menu-pages/image-generator.php';
    }
    public function chat_gpt_playground() {
        //echo aiwa_coming_soon();
        require 'menu-pages/chatgpt-playground.php';
    }
}


