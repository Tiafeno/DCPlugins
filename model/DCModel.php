<?php

class DCModel {
	public static $DefaultActivitiesClients = [ ];
	public $db;

	public function __construct() {
		global $wpdb;
		$this->db = &$wpdb;

		return $this;
	}

	public function setSettings( $args = [ ] ) {
		if ( empty( $args ) ) {
			return;
		}
		$params = (object) $args;
		$this->db->get_results( "SELECT * FROM {$this->db->prefix}dc_posttype_attachment WHERE post_type = '{$params->post_type}'" );
		$Request = $this->db->num_rows ?
			$this->db->update( $this->db->prefix . "dc_posttype_attachment", [
				'page_id' => esc_sql( $params->page_id )
			], [
				'post_type' => esc_sql( $params->post_type )
			], [
				'%s'
			], [
				'%d'
			] ) : $this->db->insert( $this->db->prefix . "dc_posttype_attachment", array(
				'post_type' => esc_sql( $params->post_type ),
				'page_id'   => esc_sql( $params->page_id )
			),
				array(
					'%s',
					'%d'
				)
			);

		if ( ! $Request ) {
			return [ 'type' => 'error', 'msg' => $this->db->print_error() ];
		}

		return [ 'type' => 'success', 'content' => $Request ];
	}

	public function getSettings( $by = null, $w = [ ] ) {
		if ( is_null( $by ) ) {
			return;
		}
		$sec = (is_int( $w[1] )) ? $w[1] : " '{$w[1]}'";
		$where   = ( empty( $w ) ) ? '' : " WHERE {$w[0]} = {$sec} ";
		$Request = $this->db->get_var( "SELECT {$by} FROM {$this->db->prefix}dc_posttype_attachment {$where}" );

		return $Request;
	}

	public static function getExperiences() {
		global $wpdb;
		$Experiences = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}dc_experiences" );

		return ( $wpdb->num_rows ) ? $Experiences : false;
	}

	public static function getAttachmentPostType() {
		global $wpdb;
		$Attachment = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}dc_posttype_attachment" );

		return ( $wpdb->num_rows ) ? $Attachment : false;
	}

	public static function getResults( $dbname ) {
		global $wpdb;
		$Results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}{$dbname}" );

		return ( $wpdb->num_rows ) ? $Results : false;
	}

	public static function setDefaultActivityTerms( $taxonomy ) {
		self::$DefaultActivitiesClients = [
			[ 'term' => 'Food', 'slug' => 'food' ],
			[ 'term' => 'Telco', 'slug' => 'telco' ],
			[ 'term' => 'Bank & Insurance', 'slug' => sanitize_title( 'Bank & Insurance' ) ],
			[ 'term' => 'Mines & Petroleum', 'slug' => sanitize_title( 'Mines & Petroleum' ) ],
			[ 'term' => 'Services', 'slug' => 'services' ],
			[ 'term' => 'Ngo & Relief', 'slug' => sanitize_title( 'Ngo & Relief' ) ],
			[ 'term' => 'Institutional', 'slug' => 'institutional' ]
		];
		if ( taxonomy_exists( $taxonomy ) ) {
			while ( list( , $activity ) = each( self::$DefaultActivitiesClients ) ):
				$obj_activity = (object) $activity;
				$termExist    = term_exists( $obj_activity->slug, $taxonomy );
				if ( is_null( $termExist ) ) {
					wp_insert_term(
						$obj_activity->term, // the term
						$taxonomy, // the taxonomy
						array(
							'slug' => $obj_activity->slug
						)
					);
				}
			endwhile;
		}
	}

	public static function install() {
		global $wpdb;
		$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dc_experiences"
		              . "(id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,"
		              . "name VARCHAR(100) NOT NULL,"
		              . "description LONGTEXT NOT NULL,"
		              . "logo INT(50) NULL DEFAULT NULL,"
		              . "add_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP"
		              . ");" );

		$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dc_posttype_attachment"
		              . "(id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,"
		              . "post_type VARCHAR(100) NOT NULL,"
		              . "page_id BIGINT(20) UNSIGNED NOT NULL,"
		              . "add_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
		              . "UNIQUE (page_id)"
		              . ");" );
	}

	public static function uninstall() {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}dc_experiences;" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}dc_posttype_attachment;" );
	}
}