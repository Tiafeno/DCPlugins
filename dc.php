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
      [ 'type' => '360deg', 'label' => '360Deg'],
      [ 'type' => 'digital', 'label' => 'Digital'],
      [ 'type' => 'marketing', 'label' => 'Marketing'],
      [ 'type' => 'advertising', 'label' => 'Advertising'],
      [ 'type' => 'edition', 'label' => 'Edition'],
      [ 'type' => 'packaging', 'label' => 'Packaging'],
      [ 'type' => 'branding', 'label' => 'Branding'],
      [ 'type' => 'event', 'label' => 'Event'],
      [ 'type' => 'store_booth', 'label' => 'Store & Booth']
    ];
    $this->register_post();
  }

  public function register_post(){
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
        'menu_position' => 20,
        'menu_icon' => 'dashicons-location-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt')
      )
    );
    }
    $this->register_taxo_();
  }

  public function register_taxo_(){
    $object_type = [];
    while (list(, $postConfig) = each($this->PostTypes)) {
      $post = (object) $postConfig;
      array_push($object_type, $post->type);
    }
    register_taxonomy(
      'favorite_works',
      $object_type,
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
