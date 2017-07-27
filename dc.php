<?php
/*
  Plugin Name: DC. Module
  Plugin URI: https://github.com/Tiafeno/DCPlugins
  Description: Module Wordpress pour le template David Calmel
  Version: 1.0
  Author: Tiafeno Finel
  Author URI: http://tiafenofinel.falicrea.com
  License: A "Slug" license name e.g. GPL2
 */

 class DC_Plugins{
  protected $PostTypes;
  public function __construct(){
    add_action( 'init', array($this, '__init__' ));
    add_action('admin_menu', function () {
      $this->addMetaBox();
      
    });
    add_action('save_post', array($this, 'action_save_postdata'), 10, 2);
  }

  public function __init__(){
    $this->setPost();
  }

  public function setPost(){
    $this->PostTypes = [
      [ 'type' => '360deg', 'label' => '360Deg', 'icon' => 'dashicons-video-alt2'],
      [ 'type' => 'digital', 'label' => 'Digital', 'icon' => 'dashicons-networking'],
      [ 'type' => 'marketing', 'label' => 'Marketing', 'icon' => 'dashicons-chart-bar'],
      [ 'type' => 'advertising', 'label' => 'Advertising', 'icon' => 'dashicons-megaphone'],
      [ 'type' => 'edition', 'label' => 'Edition', 'icon' => 'dashicons-book'],
      [ 'type' => 'packaging', 'label' => 'Packaging', 'icon' => 'dashicons-archive'],
      [ 'type' => 'branding', 'label' => 'Branding', 'icon' => 'dashicons-lightbulb'],
      [ 'type' => 'event', 'label' => 'Event', 'icon' => 'dashicons-calendar-alt'],
      [ 'type' => 'store_booth', 'label' => 'Store & Booth', 'icon' => 'dashicons-store']
    ];
    $this->register_post();
  }

  public function register_post(){
    $positionMenu = 100;
    // for all post type
    while (list(, $postConfig) = each($this->PostTypes)) {
      # code...
      $post = (object) $postConfig;
      register_post_type($post->type, array(
        'label' => _x($post->label, 'General name for "Ad" post type'),
        'labels' => array(
            'name' => _x($post->label, "Plural name for {$post->label} post type"),
            'singular_name' => _x('Drawing', "Singular name for {$post->label} post type"),
            'add_new' => __('Add'),
            'add_new_item' => __("Add New {$post->label}"),
            'edit_item' => __('Edit'),
            'view_item' => __('View'),
            'search_items' => __("Search {$post->label}"),
            'not_found' => __("No {$post->label} found"),
            'not_found_in_trash' => __("No {$post->label} found in trash")
        ),
        'public' => true,
        'hierarchical' => false,
        'menu_position' => $positionMenu++,
        'menu_icon' => $post->icon,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt')
      )
    );
    }
  }

  public function addMetaBox(){
    add_meta_box('favorite_works', 'Favorite Works', 
    array($this, 'render_meta_box'), 
    [
      '360deg', 'digital', 'marketing', 'advertising',
      'edition', 'packaging', 'branding', 'event', 'store_booth'
    ], 'side', 'high');
  }

  public function action_save_postdata($post_id){
    if(!isset($_POST[ 'fw_nonce' ] ) || !wp_verify_nonce( $_POST['fw_nonce'],'fw_metabox_nonce') ) 
      return;

    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      || (defined('DOING_AJAX') && DOING_AJAX)
    ) return;

    if (!current_user_can('edit_posts') )
      return;
    $value = (isset($_POST[ 'favorite_works' ])) ? trim($_POST[ 'favorite_works' ]) : 0;
    update_post_meta($post_id, 'favorite_works', $value);
  }

  public function render_meta_box($post){
    $favorite_works_value = get_post_meta($post->ID, 'favorite_works', true);
    ?>
      <section>
        <label><?php wp_nonce_field( 'fw_metabox_nonce', 'fw_nonce' ); ?></label>
        <label>
          <input type="checkbox" id="favorite_works" name="favorite_works" <?= ( (int)$favorite_works_value == 1 ) ? 'checked' : '' ?> 
          value="<?= ( (int)$favorite_works_value ) ? (int)$favorite_works_value : 1 ?>">
          Favorite Works
        </label>
      </section>
    <?php
  }


 }

 new DC_Plugins();
