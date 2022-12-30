<?php

/*
Class to register post type for plugin
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Register_Post_Type {

	public function __construct() {
		add_action( 'init', array( $this, 'post_type_setup' ) );
	}

	public function post_type_setup() {
		$args = array(
			'public'             => true,
			'label'              => _( 'Map' ),
			'description'        => _( 'This is Map post type' ),
			'menu-icon'          => 'dashicons-location',
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'map' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
		);
		register_post_type( 'map', $args );
	}

}
