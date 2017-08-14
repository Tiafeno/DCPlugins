<?php

require_once( plugin_dir_path(__FILE__)."../services/services.class.php" );

class Experiences{
  protected $db;
  protected $company_id;
  protected $company_name;
  protected $company_description;
  protected $company_logo;

  public $Option;
  public $Options = [
    'skills',
    'awards'
  ];

  public function __construct(){
    global $wpdb;
    $this->db = &$wpdb;
  }

  public static function Factory(){
    if (!isset($_REQUEST['vendor'])) return;
    $vendor = (isset($_REQUEST['vendor'])) ? $_REQUEST['vendor'] : null;
    $vendor = trim( $vendor );
    try{
      $ServicesExp = Services::getExperiences();
      $Experiences = json_decode($ServicesExp);
      //exit(print_r($Experiences));
      if (is_array( $Experiences )) {
        while(list(, $experience) = each( $Experiences )) {
          if ($vendor != $experience->vendor) continue;
          $Reflect = new ReflectionMethod('Experiences', $experience->vendor);
          $Reflect->invoke( new Experiences() );
          break;
        }
      }
    } catch( Exception $e ){}
  }

  public function action_add_option_experiences(){
    while (list(, $option) = each($this->Options)):
      $nonce = $option.'_nonce';
      $verify = $option.'_experiences_add_nonce';
      if (!isset($_POST[ $nonce ] ) || !wp_verify_nonce( $_POST[ $nonce ], $verify) ) 
        continue;

      if (isset( $_POST[ $option ])){
        $op = $_POST[ $option ];
        
        $this->Option = trim( $op ) ; //nl2br
        if (is_null( get_option( $option, null ) )){
          add_option( $option, $this->Option);
        } else { update_option( $option, $this->Option); }

        break;
      }
    endwhile;
  }

  public function action_delete_experiences(){
    if ( isset( $_GET[ 'id' ] ) ) {
      if (!is_int( (int)$_GET[ 'id' ]) ) return;
      $this->company_id = (int)trim( $_GET[ 'id' ] );
      $req = $this->db->delete( $this->db->prefix.'dc_experiences', array('id' => esc_sql($this->company_id)), array('%d') );
      if ($req){
        wp_redirect(admin_url('/admin.php?page=dc_experiences', 'http'), 301);
      }
    }
  }

  public function action_add_experiences(){
    if ($this->verification()){
      
      $this->company_name = (isset($_POST[ 'company_name' ])) ? trim($_POST[ 'company_name' ]) : null;
      $this->company_logo = (isset($_POST[ 'image_attachment_id' ])) ? trim($_POST[ 'image_attachment_id' ]) : null;
      $this->company_description = (isset($_POST[ 'company_description' ])) ? trim( $_POST[ 'company_description' ] ) : null;
      if ($this->company_name != null || $this->company_description != null){
        $row = $this->db->get_row($this->db->prepare("SELECT * FROM {$this->db->prefix}dc_experiences WHERE name = %s", [esc_sql( $this->company_name )] ));
        
        if (is_null($row)):
          $insert = $this->db->insert($this->db->prefix."dc_experiences",
            array(
              'name' => esc_sql( $this->company_name ),
              'description' => esc_sql( $this->company_description ),
              'logo' => esc_sql( $this->company_logo )
            ), ["%s", "%s"] );
          return ($insert) ? true : false;
        else : return false;
        endif;
      }
    }
  }

  protected function verification(){
    if (!isset($_POST[ 'exp_nonce' ] ) || 
    !wp_verify_nonce( $_POST[ 'exp_nonce' ], 'experiences_add_nonce') ) 
      return false ;
    return true ;
  }
}