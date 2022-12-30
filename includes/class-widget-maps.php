<?php

/*
Class to register widget for the plugin
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function map_register_widget() {
	register_widget( 'Widget_Setup' );
}

add_action( 'widgets_init', 'map_register_widget' );


class Widget_Setup extends WP_Widget {

	function __construct() {
		parent::__construct(
// widget ID
			'map_widget',
// widget name
			__( 'Map  Widget', 'map_widget_domain' ),
			__( 'Map Display Widget', 'map_widget_domain' ),
// widget description
			array(
				'description' => __( 'Map   Widget',
					'map_widget_domain' ),
			)
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		//	echo $args['before widget'];
//if title is present
		if ( ! empty ( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
//output
		echo $args['after_widget'];
		echo '<br>';

		$args      = array(
			'post_type'      => 'map',
			'posts_per_page' => - 1
		);
		$the_query = new WP_Query( $args ); ?>

		<?php if ( $the_query->have_posts() ) : ?>

			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <h2><?php the_title(); ?></h2>
				<?php
				$map_locations = get_post_meta( get_the_ID(), 'map_meta',
					false );

				$short_code = '   [map_shortcode id=' . ' ' . get_the_ID()
				              . ' lat =' . $map_locations['0']['latitude']
				              . ' log =' . $map_locations[0]['longitute'] . ']';
				echo do_shortcode( $short_code );
				?>

			<?php endwhile; ?>

			<?php wp_reset_postdata(); ?>

		<?php endif; ?>

		<?php


	}

	public function form( $instance ) {

		?>
        <span class="map-person-icon dashicons dashicons-admin-users"></span>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) )
			? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}
