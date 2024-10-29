<?php

function aiwa_save_image_to_gallery(){
    ?>
    <div class="aiwa_modal_wrap" style="display: none">
        <div id="save-image-to-gallery">
            <div class="aiwa_modal">
                <div class="aiwa_single_variation_image">
                    <div class="theSingleImage"><img src=""></div>
                    <div class="image-form-container">
                        <form action="" method="post">
                            <div class="settings-item">
                                <label for="title"><span>Title</span></label>
                                <textarea name="title" id="title" class="form-control"></textarea>
                            </div>
                            <div class="settings-item">
                                <label for="alternative_text"><span>Alternative Text</span></label>
                                <textarea name="alternative_text" id="alternative_text" class="form-control"></textarea>
                            </div>
                            <div class="settings-item">
                                <label for="caption"><span>Caption</span></label>
                                <textarea name="caption" id="caption" class="form-control"></textarea>
                            </div>
                            <div class="settings-item">
                                <label for="description"><span>Description</span></label>
                                <textarea name="description" id="description" class="form-control"></textarea>
                            </div>
                            <div class="settings-item">
                                <label for="file_name"><span>File name</span></label>
                                <input type="text" name="file_name" id="file_name" class="form-control">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

add_action("admin_footer", "aiwa_save_image_to_gallery");