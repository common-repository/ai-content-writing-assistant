<?php

// Declare a namespace for the class
namespace WpWritingAssistant;


class ImageGenerator{

    /**
     * ScheduledPostGeneration constructor.
     */
    public function __construct()
    {
        if (isset($_GET['page']) && sanitize_text_field($_GET['page'])=='ai-image-generator'){
            add_action('aiwa_codebox', array($this, 'aiwa_codebox'));
            add_filter('aiwa_promptbox_title', array($this, 'promptbox_title_for_auto_write'));
            add_filter('aiwa_metabox_settings', array($this, 'metabox_settings'));
            add_filter('aiwa_generate_button_text', array($this, 'aiwa_generate_button_text'));
            add_action('aiwa_promptbox_footer_buttons', array($this, 'promptbox_footer_buttons'));
            add_action('aiwa_after_promptbox_fields', array($this, 'after_promptbox_fields'));
        }
    }

    public function promptbox_footer_buttons()
    {
        ?>
        <div class="aiwa-promptbox-button">
            <a href="#" id="aiwa-image-settings-btn" class="aiwa-settings-btn" data-settings="image-settings"><?php _e('Image settings', 'ai-writing-assistant'); ?></a>
        </div>
        <?php
    }

    public function after_promptbox_fields()
    {
        $key = 'ai_writing_assistant__';$image_experiments = (array) get_option($key.'image_experiments', array('realistic', '3D_render', 'four_k', 'high_resolution', 'trending_in_artstation', 'artstation_three', 'digital_painting'));
        $image_experiments = array_map('esc_attr', $image_experiments);

        ?>
        <div class="ai_response_hidden prompt-settings-item" id="image-settings" data-tab="image-settings">
            <div class="image-settings-box code-box-dark">
                <div class="code-box-header">
                    <div class="title"><?php _e('Image settings', 'ai-writing-assistant'); ?></div>
                    <button class="minimize-btn" title="<?php _e('Minimize image settings panel', 'ai-writing-assistant'); ?>">
                        <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" id="minus" class="icon glyph" stroke="#000000" width="24px" height="24px">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M19,13H5a1,1,0,0,1,0-2H19a1,1,0,0,1,0,2Z"></path>
                            </g>
                        </svg>
                    </button>
                </div>
                <div class="aiwa-image-settings-panel aiwa-settings-panel-item">
                    <div class="settings-item">
                        <label for="aiwa-new-images-with-existing"><span><?php _e('Add new images to existing images', 'ai-writing-assistant'); ?></span>
                            <input id="aiwa-new-images-with-existing" class="content-settings-input" type="checkbox" name="new_images_with_existing" <?php echo esc_attr(get_option($key.'new_images_with_existing', 'on')) =='on' ? 'checked': ''; ?>>
                        </label>
                        <p><?php _e('Select this to add new images with existing generated images.', 'ai-writing-assistant'); ?></p>
                    </div>


                    <div class="settings-item">
                        <label for="aiwa-number-of-image"><span><?php _e('How many image?', 'ai-writing-assistant'); ?></span>
                            <input id="aiwa-number-of-image" class="content-settings-input" type="number" value="<?php echo esc_attr(get_option($key.'number_of_image', '3')); ?>" name="number_of_image" placeholder="3">
                        </label>
                        <p><?php _e('Enter the number you want to generate image before save to media library.', 'ai-writing-assistant'); ?></p>
                    </div>

                    <div class="settings-item">
                        <label>
                            <span><?php _e('Image Size', 'ai-writing-assistant'); ?></span>
                        </label>
                        <select name="ai-image-size">
                            <option <?php echo esc_attr(get_option($key.'ai-image-size', 'medium-plus'))=='thumbnail' ? 'selected': ''; ?> value="thumbnail"><?php _e('Thumbnail (256x256px)', 'ai-writing-assistant'); ?></option>
                            <option <?php echo esc_attr(get_option($key.'ai-image-size', 'medium-plus'))=='medium' ? 'selected': ''; ?> value="medium"><?php _e('Medium (512x512px)', 'ai-writing-assistant'); ?></option>
                            <option <?php echo esc_attr(get_option($key.'ai-image-size', 'medium-plus'))=='large' ? 'selected': ''; ?> value="large"><?php _e('Large (1024x1024px)', 'ai-writing-assistant'); ?></option>
                        </select>
                        <p><?php _e('Choose the size of the image you want to generate with <a href="https://openai.com/dall-e-2/">' . __("DALL-E", "ai-writing-assistant") .'</a>.', 'ai-writing-assistant'); ?></p>
                    </div>

                    <?php aiwa_image_beautifier_setting(); ?>

                    <div class="settings-item">
                        <label>
                            <span><?php _e('Image Presets', 'ai-writing-assistant'); ?></span>
                        </label>

                        <select id="aiwa_imagePresets" name="image_presets">
                            <option value="realistic,four_k,high_resolution,trending_in_artstation, artstation_three, 3D_render, digital_painting"><?php _e('High Quality Art', 'ai-writing-assistant'); ?></option>
                            <option value="realistic,3D_render,eight_k,high_resolution,professional"><?php _e('Realistic', 'ai-writing-assistant'); ?></option>
                            <option value="amazing_art,trending_in_artstation,artstation_3,oil_painting,digital_paintinghigh_resolution"><?php _e('Amazing Art', 'ai-writing-assistant'); ?></option>
                            <option value="Expert,Stunning,Creative,Popular,Inspired,four_k,trending_in_artstationhigh_resolution"><?php _e('Expert', 'ai-writing-assistant'); ?></option>
                            <option value="surreal,abstract,fantasy,pop_art,vector"><?php _e('Surreal', 'ai-writing-assistant'); ?></option>
                            <option value="landscape,portrait,iconic,neo_expressionist,four_k"><?php _e('Landscape', 'ai-writing-assistant'); ?></option>
                            <option value="realistic,3D_render,eight_k,high-resolution,professional,trending_in_artstation, artstation_three"><?php _e('High Resolution', 'ai-writing-assistant'); ?></option>
                            <option value="amazing_art,trending_in_artstation,artstation_3,oil_painting,digital_painting,four_k"><?php _e('Digital Painting', 'ai-writing-assistant'); ?></option>
                            <option value="Expert,Stunning,Creative,Popular,Inspired"><?php _e('Pop Art', 'ai-writing-assistant'); ?></option>
                            <option value="landscape,iconic,neo_expressionist,four_k,high_resolution"><?php _e('Landscape Painting', 'ai-writing-assistant'); ?></option>
                            <option value="realistic,3D_render,four_k,high-resolution,professional"><?php _e('Realistic Art', 'ai-writing-assistant'); ?></option>
                            <option value="amazing_art,trending_in_artstation,artstation_3,oil_painting,digital_painting,four_k,high_resolution"><?php _e('Digital Art', 'ai-writing-assistant'); ?></option>
                            <option value="Expert,Stunning,Creative,Popular,Inspired,eight_k"><?php _e('Abstract Art', 'ai-writing-assistant'); ?></option>
                            <option value="surreal,abstract,fantasy,pop_art,vector"><?php _e('Surrealistic Art', 'ai-writing-assistant'); ?></option>
                            <option value="landscape,portrait,iconic,neo_expressionist,four_k"><?php _e('Portrait Painting', 'ai-writing-assistant'); ?></option>
                            <option value="neon,realistic,3D_render,eight_k,high_resolution,professional"><?php _e('Neon Light', 'ai-writing-assistant'); ?></option>
                        </select>

                    </div>

                    <div class="settings-item">
                        <label>
                            <span><?php _e('Image Experiments', 'ai-writing-assistant'); ?></span>
                        </label>
                        <br>
                        <label for="aiwa_no_text" class="image-experiments"><input id="aiwa_no_text" <?php echo in_array('no_text', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[no_text]" disabled> <?php _e('No-text', 'ai-writing-assistant'); ?></label>
                        <p class="" style="margin: 0;color: #b01111;">
                            <?php echo __('Pro version only!', 'ai-writing-assistant'); ?>
                        </p>
                        <br>
                        <label for="aiwa_realistic" class="image-experiments"><input id="aiwa_realistic" <?php echo in_array('realistic', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[realistic]"> <?php _e('Realistic', 'ai-writing-assistant'); ?></label>
                        <label for="aiwa_3D_render" class="image-experiments"><input id="aiwa_3D_render" <?php echo in_array('3D_render', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[3D_render]"> <?php _e('3D render', 'ai-writing-assistant'); ?></label>
                        <label for="aiwa_four_k" class="image-experiments"><input id="aiwa_four_k" type="checkbox" <?php echo in_array('four_k', $image_experiments) ? 'checked': ''; ?> name="image_experiments[four_k]"> <?php _e('4K', 'ai-writing-assistant'); ?></label>
                        <label for="aiwa_eight_k" class="image-experiments"><input id="aiwa_eight_k" type="checkbox" <?php echo in_array('eight_k', $image_experiments) ? 'checked': ''; ?> name="image_experiments[eight_k]"> <?php _e('8K', 'ai-writing-assistant'); ?></label>
                        <label for="aiwa_amazing_art" class="image-experiments"><input id="aiwa_amazing_art" <?php echo in_array('amazing_art', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[amazing_art]"> <?php _e('Amazing art', 'ai-writing-assistant'); ?></label>
                        <label for="aiwa_high_resolution" class="image-experiments"><input id="aiwa_high_resolution" <?php echo in_array('high_resolution', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[high_resolution]"><?php _e('High resolution', 'ai-writing-assistant'); ?></label>
                        <br>
                        <label for="aiwa_trending_in_artstation" class="image-experiments"><input id="aiwa_trending_in_artstation" <?php echo in_array('trending_in_artstation', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[trending_in_artstation]"> <?php _e('Trending in artstation', 'ai-writing-assistant'); ?></label>
                        <label for="aiwa_artstation_3" class="image-experiments"><input id="aiwa_artstation_3" type="checkbox" <?php echo in_array('artstation_three', $image_experiments) ? 'checked': ''; ?> name="image_experiments[artstation_three]"> <?php _e('Artstation 3', 'ai-writing-assistant'); ?></label>
                        <label for="aiwa_oil_painting" class="image-experiments"><input id="aiwa_oil_painting" type="checkbox" <?php echo in_array('oil_painting', $image_experiments) ? 'checked': ''; ?> name="image_experiments[oil_painting]"> <?php _e('Oil painting', 'ai-writing-assistant'); ?></label>
                        <label for="aiwa_digital_painting" class="image-experiments"><input id="aiwa_digital_painting" <?php echo in_array('digital_painting', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[digital_painting]"> <?php _e('Digital painting', 'ai-writing-assistant'); ?></label>

                        <br>
                        <label for="aiwa_professional" class="image-experiments">
                            <input id="aiwa_professional" <?php echo in_array('professional', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[professional]">
                            <?php _e('Professional', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_Expert" class="image-experiments">
                            <input id="aiwa_Expert" <?php echo in_array('Expert', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[Expert]">
                            <?php _e('Expert', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_Stunning" class="image-experiments">
                            <input id="aiwa_Stunning" <?php echo in_array('Stunning', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[Stunning]">
                            <?php _e('Stunning', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_Creative" class="image-experiments">
                            <input id="aiwa_Creative" <?php echo in_array('Creative', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[Creative]">
                            <?php _e('Creative', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_Popular" class="image-experiments">
                            <input id="aiwa_Popular" <?php echo in_array('Popular', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[Popular]">
                            <?php _e('Popular', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_Inspired" class="image-experiments">
                            <input id="aiwa_Inspired" <?php echo in_array('Inspired', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[Inspired]">
                            <?php _e('Inspired', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_surreal" class="image-experiments">
                            <input id="aiwa_surreal" <?php echo in_array('surreal', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[surreal]">
                            <?php _e('Surreal', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_abstract" class="image-experiments">
                            <input id="aiwa_abstract" <?php echo in_array('abstract', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[abstract]">
                            <?php _e('Abstract', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_fantasy" class="image-experiments">
                            <input id="aiwa_fantasy" <?php echo in_array('fantasy', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[fantasy]">
                            <?php _e('Fantasy', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_pop_art" class="image-experiments">
                            <input id="aiwa_pop_art" <?php echo in_array('pop_art', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[pop_art]">
                            <?php _e('Pop Art', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_neo_expressionist" class="image-experiments">
                            <input id="aiwa_neo_expressionist" <?php echo in_array('neo_expressionist', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[neo_expressionist]">
                            <?php _e('Neo-expressionist', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_vector" class="image-experiments">
                            <input id="aiwa_vector" <?php echo in_array('vector', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[vector]">
                            <?php _e('Vector', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_neon" class="image-experiments">
                            <input id="aiwa_neon" <?php echo in_array('neon', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[neon]">
                            <?php _e('Neon', 'ai-writing-assistant'); ?>
                        </label>

                        <label for="aiwa_landscape" class="image-experiments">
                            <input id="aiwa_landscape" <?php echo in_array('land_scape', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[landscape]">
                            <?php _e('Landscape', 'ai-writing-assistant'); ?>
                        </label>
                        <label for="aiwa_portrait" class="image-experiments">
                            <input id="aiwa_portrait" <?php echo in_array('portrait', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[portrait]">
                            <?php _e('Portrait', 'ai-writing-assistant'); ?>
                        </label>
                        <label for="aiwa_iconic" class="image-experiments">
                            <input id="aiwa_iconic" <?php echo in_array('iconic', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[iconic]">
                            <?php _e('Iconic', 'ai-writing-assistant'); ?>
                        </label>

                        <p><?php _e('Choose the above styles to generate image.', 'ai-writing-assistant'); ?></p>

                        <p style="margin-bottom: 5px;color: #b01111;margin-top: 15px;"><?php _e('Some images may appear with broken or misspelled text, this issue come from OpenAI.', 'ai-writing-assistant'); ?></p>

                    </div>

                    <div class="aiwa-save-for-future-setting settings-item">
                        <button id="aiwa-save-for-future-use" class="aiwa-save-for-future-use">Save for Future</button><span style="font-size: 13px;font-weight: normal;position: relative;top: -20px;" class="future-save-btn badge badge-success aiwa-hidden"><?php _e('Saved!', 'ai-writing-assistant'); ?></span>
                        <p style="margin: 0;color: #b01111;"><?php _e('It is not necessary to press this button every time after changing the above fields. Press only when you need those settings again in the future.', 'ai-writing-assistant'); ?></p>
                    </div>

                    <?php aiwa_support_and_feature_request_text(); ?>
                </div>
            </div>
        </div>

        <input type="hidden" name="image_generation_mode" value="true">

        <?php
    }

    public function metabox_settings($settings)
    {

        return array('generate_images');
    }

    public function aiwa_generate_button_text($text)
    {
        $text = __("Generate Image", "ai-writing-assistant");
        return $text;
    }

    public function promptbox_title_for_auto_write($title)
    {
        $title = __('Image generation prompt', 'ai-writing-assistant');

        return $title;
    }


    public function aiwa_codebox()
    {
        ?>
        <div class="aiwa-images">
            <div class="aiwa-image-variation-items"></div>;
        </div>
        <?php
    }

}


new ImageGenerator();