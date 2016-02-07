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
		$this->file = $file;

		add_action( 'rest_api_init', array( &$this, 'register_api_routes' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueues' ) );
	}

	public function enqueues(){
		/** Bail if not the Pins PT */
		global $post;

		if( 'pin' != get_post_type( $post ) )
			return;

		wp_enqueue_style( 'rmaps-styles', WP_PLUGIN_URL . '/' . dirname( plugin_basename( $this->file ) ) . '/css/restful_maps.css', false );
		if( ! wp_script_is( 'jquery' ) )
			wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'rmaps-appjs', WP_PLUGIN_URL . '/' . dirname( plugin_basename( $this->file ) ) . '/js/app.js', array( 'jquery' ), '1.0', true );
	}

	public function register_api_routes() {
		$namespace = 'restful-maps/v1';

		register_rest_route( $namespace, '/get-pins/', array(
			'methods'  => 'GET',
			'callback' => array( &$this, 'get_pins' ),
		) );
	}

	public function get_pins(){
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
					'lat'       => get_post_meta( $post->ID, 'latitude', true ),
					'lon'       => get_post_meta( $post->ID, 'longitude', true )
				);

			}

			/** Cache the query for 10 minutes */
			set_transient( 'rmaps_all_posts', $return, apply_filters( 'rmaps_posts_ttl', 60 * 10 ) );

		}

		$response = new \WP_REST_Response( $return );
		$response->header( 'Access-Control-Allow-Origin', apply_filters( 'rmaps_access_control_allow_origin', '*' ) );

		return $response;
	}
}