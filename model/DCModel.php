<?php

class DCModel{
  public function __construct(){
    return $this;
  }

  public static function getExperiences(){
    global $wpdb;
    $Experiences = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}dc_experiences");
    return ($wpdb->num_rows) ? $Experiences : false;
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