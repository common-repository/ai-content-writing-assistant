<h1><?php _e('Scheduled Content Generator', 'ai-writing-assistant'); ?></h1>
<?php
global $wpdb;
$trs = "";
$generated_posts_trs = "";

$table_name = $wpdb->prefix.'ai_writing_assistant_sceduled_posts';
$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

if ( $wpdb->get_var( $query ) == $table_name ) {
    $posts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ai_writing_assistant_sceduled_posts WHERE status != 'completed' ");

    if (!empty($posts)){
        foreach ($posts as $post) {
            $id = $post->id;
            $title = $post->title;
            $scheduled_time = $post->scheduled_time;
            $post_type = $post->post_type;
            $category = $post->category;
            $language = $post->language;
            $content_structure = $post->content_structure;
            $content_length = $post->content_length;
            $keywords = $post->keywords;
            $writing_style = $post->writing_style;
            $writing_tone = $post->writing_tone;
            $generate_image = $post->generate_image;
            $status = $post->status;
            $post_id = $post->post_id;

            /*if ($status=="completed"){
                continue;
            }*/



            $trs .= '<tr scope="row" class="active">
            <td class="id">'.$id.'</td>
            <td class="title">'.$title.'</td>
            <td class="post_type">'.aiwa_post_types_selectbox("posttype-".$id, $post_type, false, false, true).'</td>
            <td class="category">'. aiwa_category_selectbox('list-' . $id . '-cat-selectbox', $category, false, true).'</td>
            <td class="scheduled_time"><div class="time-input input-container"><input type="text" class="scheduled_time_input t-scheduled" value="'.$scheduled_time.'" readonly><span class="time_tooltip">Use YYYY-MM-DD HH:MM:SS format (e.g. 2023-02-11 21:42:00)</span><span class="time_tooltip_s"></span></div></td>
            <td class="language">'.aiwa_get_language_text($language).'</td>
            <td class="content_structure">'.aiwa_get_scheduled_content_structure_options("aiwa-scheduled-content-structure", $content_structure, false, true).'</td>
            <td class="content_length">'.aiwa_get_content_length_selectbox("", $content_length, false, true).'</td>
            <td class="keywords">'.$keywords.'</td>
            <td class="writing_style">'.aiwa_get_writing_styles_selectbox($writing_style, true).'</td>
            <td class="writing_tone">'.aiwa_get_writing_tones_selectbox($writing_tone, true).'</td>
            <td class="generate_image"><input type="checkbox" id="aiwa-is-generate-image" '.($generate_image==1?"checked": "").' disabled></td>
            <td class="action">
                <button class="scheduled-action-btn edit-sheduled">
                <svg class="aiwa_checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" style="display: none">
                      <circle class="aiwa_checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                      <path class="aiwa_checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                <svg class="aiwa_edit_icon" width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#333333"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill="#333333" d="M22.707 5.536l-4.243-4.243.707-.707c.782-.78 2.048-.78 2.83 0L23.413 2c.78.78.78 2.047 0 2.828l-.707.708zM17.38 5.208l1.412 1.412-4.586 4.586-2.53 2.53-5.756 5.756L4.852 20l-1.507.656L4 19.15l.51-1.068 5.755-5.756 2.53-2.53 4.585-4.588m0-2.828l-6 6-2.53 2.53-6 6-.67 1.41-2.15 4.94c-.04.12-.04.25 0 .37.04.07.1.13.16.18.05.06.11.12.18.16.06.02.12.03.19.03.06 0 .12-.01.18-.03l4.94-2.15 1.41-.67 6-6 2.53-2.53 6-6-4.24-4.24z"></path> </g></svg></button>
               
                <button class="scheduled-action-btn update-sheduled"><svg fill="#00af80" width="20px" height="20px" viewBox="0 0 30 30" id="_24_-_Save" data-name="24 - Save" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path id="Path_272" data-name="Path 272" d="M31,10.652a3,3,0,0,0-.962-2.2L22.854,1.8A3,3,0,0,0,20.816,1H4A3,3,0,0,0,1,4V28a3,3,0,0,0,3,3H28a3,3,0,0,0,3-3Zm-2,0V28a1,1,0,0,1-1,1H4a1,1,0,0,1-1-1V4A1,1,0,0,1,4,3H20.816a1,1,0,0,1,.68.266l7.183,6.652A1,1,0,0,1,29,10.652Z" transform="translate(-1 -1)" fill-rule="evenodd"></path> <path id="Path_273" data-name="Path 273" d="M17,2a1,1,0,0,0-1-1H7.6a1,1,0,0,0-1,1V4.667a3,3,0,0,0,3,3H14a3,3,0,0,0,3-3V2ZM15,3V4.667a1,1,0,0,1-1,1H9.6a1,1,0,0,1-1-1V3H15Z" transform="translate(-1 -1)" fill-rule="evenodd"></path> <path id="Path_274" data-name="Path 274" d="M23.215,22.667a3,3,0,0,0-3-3h-8.43a3,3,0,0,0-3,3V30a1,1,0,0,0,1,1h12.43a1,1,0,0,0,1-1Zm-2,0V29H10.785V22.667a1,1,0,0,1,1-1h8.43a1,1,0,0,1,1,1Z" transform="translate(-1 -1)" fill-rule="evenodd"></path> </g></svg></button>
                <button class="scheduled-action-btn delete-sheduled"><svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#cb2027"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M960 160h-291.2a160 160 0 0 0-313.6 0H64a32 32 0 0 0 0 64h896a32 32 0 0 0 0-64zM512 96a96 96 0 0 1 90.24 64h-180.48A96 96 0 0 1 512 96zM844.16 290.56a32 32 0 0 0-34.88 6.72A32 32 0 0 0 800 320a32 32 0 1 0 64 0 33.6 33.6 0 0 0-9.28-22.72 32 32 0 0 0-10.56-6.72zM832 416a32 32 0 0 0-32 32v96a32 32 0 0 0 64 0v-96a32 32 0 0 0-32-32zM832 640a32 32 0 0 0-32 32v224a32 32 0 0 1-32 32H256a32 32 0 0 1-32-32V320a32 32 0 0 0-64 0v576a96 96 0 0 0 96 96h512a96 96 0 0 0 96-96v-224a32 32 0 0 0-32-32z" fill="#cb2027"></path><path d="M384 768V352a32 32 0 0 0-64 0v416a32 32 0 0 0 64 0zM544 768V352a32 32 0 0 0-64 0v416a32 32 0 0 0 64 0zM704 768V352a32 32 0 0 0-64 0v416a32 32 0 0 0 64 0z" fill="#cb2027"></path></g></svg></button>
            </td>
        </tr>';
        }
    }


    $generated_posts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ai_writing_assistant_sceduled_posts WHERE status = 'completed'");

    if (!empty($generated_posts)){
        foreach ($generated_posts as $post) {
            $id = $post->id;
            $title = $post->title;
            $scheduled_time = $post->scheduled_time;
            $post_type = $post->post_type;
            $category = $post->category;

            $status = $post->status;
            $post_id = $post->post_id;

            /*if ($status!=="completed"){
                continue;
            }*/

            $generated_posts_trs .= '<tr scope="row" class="active">
            <td class="id">'.$id.'</td>
            <td class="title"><a href="'.get_permalink($post_id).'">'.$title.'</a></td>
            <td class="post_type">'.aiwa_post_types_selectbox("posttype-".$id, $post_type, false, false, true).'</td>
            <td class="category">'. aiwa_category_selectbox('list-' . $id . '-cat-selectbox', $category, false, true).'</td>
            <td class="scheduled_time"><input type="text" class="scheduled_time_input" value="'.$scheduled_time.'" readonly></td>
            
            <td class="action">
                <button class="scheduled-action-btn delete-sheduled"><svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#cb2027"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M960 160h-291.2a160 160 0 0 0-313.6 0H64a32 32 0 0 0 0 64h896a32 32 0 0 0 0-64zM512 96a96 96 0 0 1 90.24 64h-180.48A96 96 0 0 1 512 96zM844.16 290.56a32 32 0 0 0-34.88 6.72A32 32 0 0 0 800 320a32 32 0 1 0 64 0 33.6 33.6 0 0 0-9.28-22.72 32 32 0 0 0-10.56-6.72zM832 416a32 32 0 0 0-32 32v96a32 32 0 0 0 64 0v-96a32 32 0 0 0-32-32zM832 640a32 32 0 0 0-32 32v224a32 32 0 0 1-32 32H256a32 32 0 0 1-32-32V320a32 32 0 0 0-64 0v576a96 96 0 0 0 96 96h512a96 96 0 0 0 96-96v-224a32 32 0 0 0-32-32z" fill="#cb2027"></path><path d="M384 768V352a32 32 0 0 0-64 0v416a32 32 0 0 0 64 0zM544 768V352a32 32 0 0 0-64 0v416a32 32 0 0 0 64 0zM704 768V352a32 32 0 0 0-64 0v416a32 32 0 0 0 64 0z" fill="#cb2027"></path></g></svg></button>
            </td>
        </tr>';
        }
    }
}

?>

<div id="aiwa-auto-content-writer" class="aiwa-menu-page">

    <div class="aiwa-scheduled-posts-table-section">
        <?php if (isset($_GET['generated_posts'])): ?>
            <a id="check-generated-posts-btn" style="" href="admin.php?page=scheduled-content-generator"><?php _e('Queued Posts (Scheduled)', 'ai-writing-assistant'); ?></a>
            <?php if (!empty($generated_posts_trs)): ?>
                <table class="table custom-table generated-scheduled-lists">
                    <thead>
                    <tr class="active">
                        <th class="id" scope="col"><?php _e('ID', 'ai-writing-assistant'); ?></th>
                        <th class="title" scope="col"><?php _e('Post Title', 'ai-writing-assistant'); ?></th>
                        <th class="post_type" scope="col"><?php _e('Post Type', 'ai-writing-assistant'); ?></th>
                        <th class="category" scope="col"><?php _e('Category', 'ai-writing-assistant'); ?></th>
                        <th class="scheduled_time" scope="col"><?php _e('Sheduled', 'ai-writing-assistant'); ?></th>
                        <th class="status" scope="col"><?php _e('Action', 'ai-writing-assistant'); ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                        echo $generated_posts_trs;
                    ?>

                    </tbody>
                </table>
            <?php else: ?>
                <p style="font-size: 20px;"><?php _e("There has no generated posts (scheduled) yet!", "ai-writing-assistant"); ?></p>
            <?php endif; ?>
        <?php else: ?>
            <?php if (!empty($generated_posts_trs)): ?>
                <a id="check-generated-posts-btn" style="" href="admin.php?page=scheduled-content-generator&generated_posts"><?php _e('Generated Scheduled Posts', 'ai-writing-assistant'); ?></a>
            <?php endif; ?>
            <?php if(!empty($trs)): ?>
                <button id="expand-table-btn" style=""><?php _e('Expand the table', 'ai-writing-assistant'); ?></button>
                <table class="table custom-table scheduled-lists">
                    <thead>
                    <tr class="active">
                        <th class="id" scope="col"><?php _e('ID', 'ai-writing-assistant'); ?></th>
                        <th class="title" scope="col"><?php _e('Post Title', 'ai-writing-assistant'); ?></th>
                        <th class="post_type" scope="col"><?php _e('Post Type', 'ai-writing-assistant'); ?></th>
                        <th class="category" scope="col"><?php _e('Category', 'ai-writing-assistant'); ?></th>
                        <th class="scheduled_time" scope="col"><?php _e('Sheduled', 'ai-writing-assistant'); ?></th>
                        <th class="language" scope="col"><?php _e('Language', 'ai-writing-assistant'); ?></th>
                        <th class="content_structure" scope="col"><?php _e('Content Structure', 'ai-writing-assistant'); ?></th>
                        <th class="content_length" scope="col"><?php _e('Content Length', 'ai-writing-assistant'); ?></th>
                        <th class="keywords" scope="col"><?php _e('Keywords', 'ai-writing-assistant'); ?></th>
                        <th class="writing_style" scope="col"><?php _e('Writing Style', 'ai-writing-assistant'); ?></th>
                        <th class="writing_tone" scope="col"><?php _e('Writing Tone', 'ai-writing-assistant'); ?></th>
                        <th class="generate_image" scope="col"><?php _e('Generate Image', 'ai-writing-assistant'); ?></th>
                        <th class="status" scope="col"><?php _e('Action', 'ai-writing-assistant'); ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    echo $trs;
                    ?>

                    </tbody>
                </table>
            <?php else: ?>
                <p style="font-size: 20px;"><?php _e("There has no scheduled posts in queued list.", "ai-writing-assistant"); ?></p>
            <?php endif; ?>

        <div class="scheduled_post_add_section" class="settings-item">

            <h1><?php _e("Add a scheduled post", "ai-writing-assistant"); ?></h1>
            <div id="scheduled-post-titlediv">
                <div id="scheduled-post-titlewrap">
                    <label for="scheduled-post-title" style="font-size: 16px;font-weight: bold;">Title</label>
                    <input type="text" name="post_title" size="30" class="aiwa-scheduled-content-title" value="" id="scheduled-post-title" spellcheck="true" placeholder="<?php echo __("Enter a title", "ai-writing-assistant"); ?>" autocomplete="off">
                </div>
            </div>

            <div class="settings-item" style="margin-top: 20px;">
                <label for="aiwa-scheduled-language"><span><?php _e('Language', 'ai-writing-assistant'); ?></span></label>
                <select name="aiwa-scheduled-language" id="aiwa-scheduled-language">
                    <?php aiwa_get_languages_options(); ?>
                </select>
                <p><?php _e('Select a language to generate contents with the language.', 'ai-writing-assistant'); ?></p>
            </div>

            <div class="settings-item">
                <?php
                    aiwa_post_types_selectbox("scheduled-post-posttype");
                ?>
                <p><?php _e("Select a post type", "ai-writing-assistant"); ?></p>
            </div>

            <div class="settings-item">
                <label for="scheduled-post-cats"><?php echo __("Category", "ai-writing-assistant"); ?></label>
                <?php
                aiwa_category_selectbox('scheduled-post-cats');
                ?>
                <p><?php _e("Select a post category", "ai-writing-assistant"); ?></p>
            </div>

            <div class="settings-item sheduled-content-generator">
                <label for="publish-scheduled-time-picker"><span><?php _e('Scheduled time', 'ai-writing-assistant'); ?></span>
                    <input type="text" id="aiwa-scheduled-date-picker" class="aiwa-scheduled-date-picker" name="aiwa_date_picker" data-dd-opt-default-date="<?php echo current_time("Y/m/d"); ?>" value="<?php echo current_time("Y-m-d"); ?>">
                    <input type="text" id="publish-scheduled-time-picker" class="aiwa_scheduled-time_picker" name="aiwa-time-picker" value="<?php echo aiwa_get_time_after(); ?>" data-timepicki-tim="<?php echo aiwa_get_time_after('10 minutes', 'g'); ?>" data-timepicki-mini="<?php echo aiwa_get_time_after('10 minutes', 'i'); ?>" data-timepicki-meri="<?php echo aiwa_get_time_after('10 minutes', 'A'); ?>">
                </label>
                <p><?php _e('Posts will be published after this time you selected.', 'ai-writing-assistant'); ?></p>
            </div>

            <div class="settings-item">
                <label for="aiwa-scheduled-content-length"><?php echo __("Content length", "ai-writing-assistant"); ?></label>
                <?php
                    aiwa_get_content_length_selectbox("aiwa-scheduled-content-length");
                ?>
                <p><?php _e(__("Select a content length that fit your need.", "ai-writing-assistant")); ?></p>
            </div>

            <div class="settings-item">
                <label for="aiwa-scheduled-include-keywords"><span><?php _e('Include Keywords', 'ai-writing-assistant'); ?></span>
                    <input id="aiwa-scheduled-include-keywords" class="content-settings-input" type="text" value="" name="include-keywords" style="min-width: 270px;" placeholder="New York, Washington, Grand Canyon">
                </label>
                <p><?php _e('Enter some keywords to include these in generated content. Seperated by comma (,). AI may create related keywords of these keywords strong as well.', 'ai-writing-assistant'); ?></p>
            </div>

            <div class="settings-item">
                <label for="aiwa-add-scheduled-content-structure"><span><?php _e('Content structure', 'ai-writing-assistant'); ?></span></label>
                <select name="aiwa-add-scheduled-content-structure" id="aiwa-add-scheduled-content-structure">
                    <?php aiwa_get_content_structure_options(); ?>
                </select>
                <p><?php _e('Choose the writing style of your blog post which fit your need!', 'ai-writing-assistant'); ?></p>
            </div>

            <div class="settings-item">
                <label for="aiwa-scheduled-writing-style"><span><?php _e('Writing styles', 'ai-writing-assistant'); ?></span></label>
                <select name="aiwa-scheduled-writing-style" id="aiwa-scheduled-writing-style">
                    <?php aiwa_get_writing_styles_options(); ?>
                </select>
                <p><?php _e('Choose the writing style of your blog post which fit your need!', 'ai-writing-assistant'); ?></p>
            </div>

            <div class="settings-item">
                <label for="aiwa-scheduled-writing-tone"><span><?php _e('Writing tone', 'ai-writing-assistant'); ?></span></label>
                <select name="aiwa-scheduled-writing-tone" id="aiwa-scheduled-writing-tone">
                    <?php aiwa_get_writing_tone_options(); ?>
                </select>
                <p><?php _e('Choose the writing tone of your blog post which fit your need!', 'ai-writing-assistant'); ?></p>
            </div>


            <div class="settings-item">
                <label for="aiwa-scheduled-generate-image-checkbox"><span><?php _e('Generate featured image', 'ai-writing-assistant'); ?></span></label>
                <input type="checkbox" id="aiwa-scheduled-generate-image-checkbox">
                <p><?php _e('Select this to auto-generate the thumbnail image. It will generate from your main prompt.', 'ai-writing-assistant'); ?></p>
            </div>

            <input type="hidden" class="aiwa_get_wp_current_timestamp" value="<?php echo wp_date('Y-m-d H:i:s'); ?>">

            <button id="add-scheduled-post-btn" style=""><?php _e('Add scheduled post', 'ai-writing-assistant'); ?></button>
        </div>

        <?php endif; ?>
    </div>


    <style>
        .notice {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }

        .notice h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        .notice p {
            margin: 0;
        }

        .notice a {
            color: #0073aa;
            text-decoration: underline;
        }
    </style>

    <div class="notice">
        <h3><?php _e("Attention:", "ai-writing-assistant"); ?></h3>
        <p><?php _e("WordPress Cron (WP Cron) has some limitations and can sometimes lead to issues with scheduling and performance. In order to ensure reliable and accurate scheduling of events, we recommend setting up a system cron job.", "ai-writing-assistant"); ?></p>
        <p><?php _e("To set up a system cron job, you will need access to your server's command line interface and the ability to edit cron jobs. You can follow one of the following tutorials for more information and step-by-step instructions:", "ai-writing-assistant"); ?></p>
        <ul>
            <li><a href="https://www.wpbeginner.com/wp-tutorials/how-to-disable-wp-cron-in-wordpress-and-set-up-proper-cron-jobs/"><?php _e("How to Disable WP Cron in WordPress and Set Up Proper Cron Jobs (WPBeginner)", "ai-writing-assistant"); ?></a></li>
            <li><a href="https://themeisle.com/blog/disable-wp-cron/"><?php _e("How to Disable WP Cron in WordPress (ThemeIsle Blog)", "ai-writing-assistant"); ?></a></li>
        </ul>
        <p><?php _e("Please note that every hosting server has its own method for adding cron jobs. If you are unable to set up a system cron job on your own, we recommend reaching out to your hosting support for assistance.", "ai-writing-assistant"); ?></p>
    </div>




</div>