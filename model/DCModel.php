<?php

class DCModel{
  public static $DefaultActivitiesClients = [];
  public function __construct(){
    return $this;
  }

  public static function getExperiences(){
    global $wpdb;
    $Experiences = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}dc_experiences");
    return ($wpdb->num_rows) ? $Experiences : false;
  }

  public static function getResults( $dbname, $where = [] ){
    global $wpdb;
    $Results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}{$dbname}");
    return ($wpdb->num_rows) ? $Results : false;
  }

  public static function setDefaultActivityTerms( $taxonomy ){
    self::$DefaultActivitiesClients = [
      ['term' => 'Food', 'slug' => 'food'],
      ['term' => 'Telco', 'slug' => 'telco'],
      ['term' => 'Bank & Insurance', 'slug' => sanitize_title( 'Bank & Insurance' )],
      ['term' => 'Mines & Petroleum', 'slug' => sanitize_title( 'Mines & Petroleum' )],
      ['term' => 'Services', 'slug' => 'services'],
      ['term' => 'Ngo & Relief', 'slug' => sanitize_title( 'Ngo & Relief' )],
      ['term' => 'Institutional', 'slug' => 'institutional']
    ];
    if (taxonomy_exists( $taxonomy )){
      while (list(, $activity) = each( self::$DefaultActivitiesClients )):
        $obj_activity = (object) $activity;
        $termExist = term_exists( $obj_activity->slug, $taxonomy );
        if (is_null( $termExist ))
          wp_insert_term(
            $obj_activity->term, // the term 
            $taxonomy, // the taxonomy
            array(
              'slug' => $obj_activity->slug
            )
          );
      endwhile;
    }
  }

  public static function install(){
    global $wpdb;
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dc_experiences"
      . "(id INT AUTO_INCREMENT PRIMARY KEY,"
      ."name VARCHAR(100) NOT NULL,"
      ."description LONGTEXT NOT NULL,"
      ."logo INT(50) NULL DEFAULT NULL,"
      ."add_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP"
      .");");
  }

  public static function uninstall(){
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}dc_experiences;");
  }
}