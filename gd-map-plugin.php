<?php

/*
  Plugin Name: Gd Map Plugin
  Plugin URI:  https://bishalgc.com/gdmapplugin
  Description: A plugin to display map on your website pages.
  Version:     1.0.0
  Author:     Bishal GC
  Author URI:  https://bishalgc.com/
  License:     GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages
  Text Domain: gd-map-plugin

 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Gd_Map' ) ) {

	class Gd_Map {

		/** Singleton *************************************************************/

		private static $instance;


		private function __construct() {
			/* Do nothing here */
		}

		public static function instance() {

			if ( ! isset( self::$instance )
			     && ! ( self::$instance instanceof Gd_Map )
			) {
				self::$instance = new Gd_Map();
				self::$instance->setup_constants();
				add_action( 'wp_enqueue_scripts',
					array( self::$instance, 'gd_map_enqueue_style' ) );
				add_action( 'wp_enqueue_scripts',
					array( self::$instance, 'gd_map_enqueue_script' ) );
				add_action( 'admin_menu', array( self::$instance, 'admin' ) );
				add_filter( 'manage_map_posts_columns',
					array( self::$instance, 'custome_column' ) );
				add_action( 'manage_map_posts_custom_column',
					array( self::$instance, 'custome_column_content' ), 10, 2 );
				add_action( 'add_meta_boxes',
					array( self::$instance, 'map_display' ), 10, 2 );
				add_action( 'save_post_map',
					array( self::$instance, 'save_post_data' ), 10, 2 );
				self::$instance->includes();
				self::$instance->cpt       = new Register_Post_Type();
				self::$instance->widget    = new Widget_Setup();
				self::$instance->shortcode = new Shortcode_Setup();
			}

			return self::$instance;
		}

		/**
		 * Setup plugins constants.
		 *
		 * @access private
		 * @return void
		 * @since  1.0.0
		 */
		private function setup_constants() {
			// Plugin version.
			if ( ! defined( 'GD_MAP_VERSION' ) ) {
				define( 'GD_MAP_VERSION', '1.0' );
			}

			// Plugin folder Path.
			if ( ! defined( 'GD_MAP_PLUGIN_DIR' ) ) {
				define( 'GD_MAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin folder URL.
			if ( ! defined( 'GD_MAP_PLUGIN_URL' ) ) {
				define( 'GD_MAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin root file.
			if ( ! defined( 'GD_MAP_PLUGIN_FILE' ) ) {
				define( 'GD_MAP_PLUGIN_FILE', __FILE__ );
			}

		}

		/**
		 * Include required files.
		 *
		 * @access private
		 * @return void
		 * @since  1.0.0
		 */

		private function includes() {
			require_once GD_MAP_PLUGIN_DIR
			             . 'includes/class-register-post-type.php';
			require_once GD_MAP_PLUGIN_DIR . 'includes/class-widget-maps.php';
			require_once GD_MAP_PLUGIN_DIR
			             . 'includes/class-shortcode-register.php';
		}

		public function gd_map_enqueue_style() {
			$css_dir = GD_MAP_PLUGIN_URL . 'assets/css/';
			//	wp_enqueue_style( 'gd-map-application-style', $css_dir . 'main.css', false, '1.0' );

		}

		/**
		 * Enqueue script front-end
		 *
		 * @access public
		 * @return void
		 * @since  1.0.0
		 */
		public function gd_map_enqueue_script() {
			$js_dir = GD_MAP_PLUGIN_URL . 'assets/js/';
			// wp_enqueue_script( 'gd-map-application-js', $js_dir . 'main.js', array(), '1.0', true );
		}

		public function admin() {
			add_menu_page( 'Map', 'Map', 'manage_options', 'Map',
				array( $this, 'Map' ) );
		}

		public function map_display() {
			add_meta_box( 'map_display_section', _( 'Map Section' ),
				array( $this, 'map_display_setting' ), 'map' );
		}

		public function map_display_setting($post) {
			include( GD_MAP_PLUGIN_DIR . 'includes/map-metabox-setting.php' );
		}

		function sanitize_array( $array = array(), $sanitize_rule = array() ) {
			if ( ! is_array( $array ) || count( $array ) == 0 ) {
				return array();
			}

			foreach ( $array as $k => $v ) {
				if ( ! is_array( $v ) ) {

					$default_sanitize_rule = ( is_numeric( $k ) ) ? 'text'
						: 'html';
					$sanitize_type         = isset( $sanitize_rule[ $k ] )
						? $sanitize_rule[ $k ] : $default_sanitize_rule;
					$array[ $k ]           = $this->sanitize_value( $v,
						$sanitize_type );
				}
				if ( is_array( $v ) ) {
					$array[ $k ] = $this->sanitize_array( $v, $sanitize_rule );
				}
			}

			return $array;
		}

		function sanitize_value( $value = '', $sanitize_type = 'text' ) {
			switch ( $sanitize_type ) {
				case 'html':
					$allowed_html = wp_kses_allowed_html( 'post' );
					return wp_kses( $value, $allowed_html );
					break;
				default:
					return sanitize_text_field( $value );
					break;
			}
		}


		public function save_post_data( $post_id ) {
			if ( isset( $_POST['map_meta'] ) ) {

				// Check if our nonce is set.
				if ( ! isset( $_POST['map_meta_nonce'] ) ) {
					return $post_id;
				}

				$nonce = $_POST['map_meta_nonce'];


				// Verify that the nonce is valid.
				if ( ! wp_verify_nonce( $nonce, 'map_meta_nonce' ) ) {
					return $post_id;
				}


				/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
					return $post_id;
				}

				// Check the user's permissions.
				if ( 'page' == $_POST['post_type'] ) {
					if ( ! current_user_can( 'edit_page', $post_id ) ) {
						return $post_id;
					}
				} else {
					if ( ! current_user_can( 'edit_post', $post_id ) ) {
						return $post_id;
					}
				}


				update_post_meta( $post_id, 'map_meta',
					$this->sanitize_array( $_POST['map_meta'] ) );//santization is not form in $_POST have to write sanitize function.
			}

		}


		public function custome_column( $defaults ) {
			unset( $default['title'] );
			unset( $default['date'] );
			$defaults['shortcode'] = 'Shortcode';

			return $defaults;
		}

		public function custome_column_content( $columns, $post_id ) {
			$a = get_post_meta( $post_id, 'map_meta', false );
			if ( $columns = 'shortcode' ) {
				$id = $post_id;
				include( plugin_dir_path( __FIlE__ )
				         . 'includes/custome-column-content.php' );
			}
		}


		public function Map() {
			echo '<h3>' . 'Map' . '</h3>';
		}


	}

}


function run_gd_map() {
	return GD_MAP::instance();
}


global $gd_map_driver;
$gd_map_driver = run_gd_map();





