<?php
/**
 * @package RMaps
 */


namespace notne\rmaps;


if ( ! defined( 'ABSPATH' ) ) {
	wp_die( '-1' );
}

class RESTful_Maps {

	/** @var string Plugin file location passed through the constructor */
	public $file = '';

	public function __construct( $file ) {
		register_activation_hook();
		add_action( 'rest_api_init', 'register_api_hooks' );
	}

	public function register_api_hooks() {
		$namespace = 'restful-maps/v1';

		register_rest_route( $namespace, '/get-pins/', array(
			'methods'  => 'GET',
			'callback' => 'get_pins',
		) );
	}

	public static function get_pins(){
		if ( 0 || false === ( $return = get_transient( 'rmaps_all_posts' ) ) ) {
			$query = apply_filters( 'rmaps_get_posts_query', array(
				'numberposts' => 20,
				'post_type'   => 'pin',
				'post_status' => 'publish',
			) );

			$posts  = get_posts( $query );
			$return = array();

			foreach ( $posts as $post ) {
				$return[] = array(
					'ID'        => $post->ID,
					'title'     => $post->post_title,
					'permalink' => get_permalink( $post->ID ),
					'lat'       => get_post_meta( '_rmaps_lat' ),
					'lon'       => get_post_meta( '_rmaps_lon' )
				);
			}

			/** Cache the query for 10 minutes */
			set_transient( 'rmaps_all_posts', $return, apply_filters( 'rmaps_posts_ttl', 60 * 10 ) );
		}

		$response = new WP_REST_Response( $return );
		$response->header( 'Access-Control-Allow-Origin', apply_filters( 'rmaps_access_control_allow_origin', '*' ) );

		return $response;
	}
}