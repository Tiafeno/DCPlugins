<?php
class Services{
  public function __construct(){}

  public static function getExperiences(){
      return file_get_contents(plugin_dir_path(__FILE__)."../model/schema/experiences.actions.json");
  }

}