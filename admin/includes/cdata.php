<?php

function ai_writing_assistant_cdata()
{
    if (aiwaHasAccess()){
        ?>
        <script>
            /* <![CDATA[ */
            var aiwa = {
                "ajax_url": "<?php echo admin_url('admin-ajax.php'); ?>",
                "nonce": "<?php echo wp_create_nonce('rc-nonce'); ?>",
                "home_url": "<?php echo home_url(); ?>",
            };
            /* ]]\> */
        </script>
        <?php
    }

}

add_action('admin_print_scripts', 'ai_writing_assistant_cdata');