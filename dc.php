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
    add_action('wp_ajax_action_get_posttypes', array($this, 'action_get_posttypes'));
    add_action('wp_ajax_nopriv_action_get_posttype', array($this, 'action_get_posttypes'));

    $this->setPost();
  }

  public function setPost(){
    $this->PostTypes = [
      [ 'type' => '360deg', 'label' => '360°', 'icon' => 'dashicons-video-alt2'],
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
  // Ajax send all post type and label
  public function action_get_posttypes(){
    wp_send_json( $this->PostTypes );
  }
  // Ajax send all contents with type
  public function action_get_postcontent(){
    if (empty( $this->PostTypes )) return;
    $AllContents = [];
    while (list(, $args) = each( $this->PostTypes )){
      $argv = (object) $args;
      $params = [
        'post_type' => $argv->type
      ];
      $Contents = new WP_Query( $params );
      if ( $Contents->have_posts() ){
        $AllContents[ $argv->type ] = [];
        while ( $Contents->have_posts() ) : $Contents->the_post();
          if ( (int)get_post_meta($Contents->post->ID, 'favorite_works', true) === 1 ){
            $AllContents[ $argv->type ][] = [
              'title' => get_the_title( $Contents->post->ID ),
              'content' => $Contents->post->post_content,
              'thumbnail_url' => get_the_post_thumbnail_url( $Contents->post->ID, 'full' ),
              'link' => get_permalink( $Contents->post->ID )
            ];
          }
        endwhile;
      }
    }

    wp_send_json( $AllContents ); 
  }

  public function register_post(){
    $positionMenu = 100;
    // for all post type
    while (list(, $postConfig) = each( $this->PostTypes )) {
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
    $TypeContents = [
      '360deg', 'digital', 'marketing', 'advertising',
      'edition', 'packaging', 'branding', 'event', 'store_booth'
    ];

    /*
    * Add meta box from all Type content in $this->PostType
    */
    add_meta_box('favorite_works', 'Favorite Works', array($this, 'render_meta_box_fw'), $TypeContents, 'side', 'high');
    /*
    * Add meta box in page type content
    */
    add_meta_box( 'content_type', 'Content Post Type', array($this, 'render_meta_box_posttype'), 
      [
        'page'
      ], 'side', 'high' );
  }

  private function verification(){
    if ((defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) || (defined( 'DOING_AJAX' ) && DOING_AJAX)) 
      return false;
    if (!current_user_can( 'edit_posts' ))
      return false;
    return true;
  }

  public function action_save_postdata( $post_id ){
    if (isset($_POST[ 'favorite_works' ])){
      if (!isset($_POST[ 'fw_nonce' ] ) || !wp_verify_nonce( $_POST[ 'fw_nonce' ], 'fw_metabox_nonce') ) 
        return;
      if (!$this->verification()) return;
      $value = (isset($_POST[ 'favorite_works' ])) ? trim($_POST[ 'favorite_works' ]) : 0;
      update_post_meta($post_id, 'favorite_works', $value);
    }

    if (isset($_POST[ 'content_type' ])){
      if (!$this->verification()) return;
      $ContentValue = (isset($_POST[ 'content_type' ])) ? trim($_POST[ 'content_type' ]) : 0;
      update_post_meta( $post_id, 'content_type', $ContentValue);
    }
    
  }

  public function render_meta_box_fw( $post ){
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

  public function render_meta_box_posttype( $post ){
    $content_type = get_post_meta($post->ID, 'content_type', true);
    if (is_array($this->PostTypes) && empty($this->PostTypes)) return false;
    ?>
     <fieldset>
        <input type="radio" name="content_type" class="post-format" value="0" 
         <?= (empty( $content_type )) ? ' checked="checked" ': '' ?>
         id="content-type-0"></input>
        <label for="content-type-0" class="post-format-icon">
          DEFAULT
        </label>
        <br>
     <?php foreach ($this->PostTypes as $posttype): ?>
        <input type="radio" name="content_type" class="post-format" value="<?= $posttype[ 'type' ] ?>" 
          <?= (!empty( $content_type ) && $content_type == $posttype[ 'type' ]) ? ' checked="checked" ': '' ?>
          id="<?= 'content-type-'.$posttype[ 'type' ] ?>"></input>
        <label for="<?= 'content-type-'.$posttype[ 'type' ] ?>" class="post-format-icon">
          <?= strtoupper($posttype[ 'label' ]) ?>
        </label>
        <br>
      <?php endforeach; ?>
      </fieldset>
    <?php
  }


 }

 new DC_Plugins();
