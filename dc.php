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
            'add_new_item' => __('Add New'),
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
    $this->register_taxo_();
  }

  public function register_taxo_(){
    register_taxonomy(
      'favorite_works',
      [
        '360deg', 'digital', 'marketing', 'advertising', 'edition', 
        'packaging', 'branding', 'event', 'store_booth'
      ],
      array(
        'label' => __( 'Favorite Works' ),
        'rewrite' => array( 'slug' => 'favorite_works' ),
        'hierarchical' => true,
        'show_ui' => true
      )
    );
  }

 }

 new DC_Plugins();
