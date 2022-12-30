<?php


/*
Class to register shortcode for the plugin
*/


class Shortcode_Setup {


	function __construct() {
		add_shortcode( 'map_shortcode',
			array( $this, 'shortcode_generator' ) );
	}

	public function shortcode_generator( $atts ) {
		if ( isset( $atts ) ) {
			$args = array(
				'post_type'      => 'map',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'p'              => $atts['id']
			);
			ob_start();
			include( plugin_dir_path( __FILE__ )
			         . 'map-frontend-content.php' );
			$data = ob_get_contents();
			ob_end_clean();
			return $data;
		}

	}


}