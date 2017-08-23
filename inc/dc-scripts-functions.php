<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function load_custom_wp_admin_style( $hook ) {
  // Load only on ?page=dc_experiences
  $regex = ['dc_page_dc_experiences', 'dc_page_dc_configs', 'toplevel_page_dc_settings'];
  if (!in_array( $hook, $regex )) return;
  wp_enqueue_style( 'uikit', plugins_url('../dist/css/uikit.css', __FILE__));
  wp_enqueue_style( 'custom', plugins_url('../assets/css/custom.css', __FILE__));
  wp_enqueue_script( 'uikit', plugins_url('../dist/js/uikit.js', __FILE__));
  wp_enqueue_script( 'uikit-icons',plugins_url('../dist/js/uikit-icons.js', __FILE__));

  if ($hook == 'dc_page_dc_experiences')
    wp_enqueue_script( 'davidcalmel-experiences', plugins_url('../assets/js/scripts.js', __FILE__ ));
  if ($hook == 'dc_page_dc_configs'){
    wp_enqueue_script( 'davidcalmel-configs', plugins_url('../assets/js/configs.js', __FILE__ ));
    wp_localize_script( 'davidcalmel-configs', 'configs', array(
      'ajax'   => admin_url( 'admin-ajax.php' ),
    ));
  }
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

