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
	public $textdomain = '';

	public function __construct() {
		$this->textdomain = 'rmaps';

		add_action( 'rest_api_init', array( &$this, 'register_api_routes' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueues' ) );
	}

	public function enqueues(){
		/** Bail if not the Pins PT */
		global $post;

		if( 'pin' != get_post_type( $post ) )
			return;

		wp_enqueue_style( 'rmaps-styles', RM_BASE_DIR . '/css/restful_maps.css', false );
		if( ! wp_script_is( 'jquery' ) )
			wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'rmaps-google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . get_option( 'gmaps_api' ), true );
		wp_enqueue_script( 'rmaps-appjs', RM_BASE_DIR . '/js/app.js', array( 'jquery' ), '1.0', true );
	}

	public function register_api_routes() {
		$namespace = 'restful-maps/v1';

		register_rest_route( $namespace, '/get-pins/', array(
			'methods'  => 'GET',
			'callback' => array( &$this, 'get_pins' ),
		) );

		register_rest_route( $namespace, '/get-api-key/', array(
			'methods'  => 'GET',
			'callback' => array( &$this, 'get_maps_api_key' ),
		) );
	}

	public function get_pins(){
		if ( 0 || false === ( $return = get_transient( 'rmaps_all_posts' ) ) ) {

			$query = apply_filters( 'rmaps_get_posts_query', array(
				'numberposts' => 20,
				'post_type'   => 'pin',
				'post_status' => 'publish',
			) );

			$pins  = get_posts( $query );
			$return = array();

			foreach ( $pins as $pin ) {
				$return[] = array(
					'ID'        => $pin->ID,
					'title'     => esc_attr( $pin->post_title ),
					'infowindow'=> wp_kses_post( $pin->post_content ),
					'see_more'  => _x( 'See More', 'Link', $this->textdomain ),
					'permalink' => esc_url( get_permalink( $pin->ID ) ),
					'lat'       => esc_attr( get_post_meta( $pin->ID, 'latitude', true ) ),
					'lon'       => esc_attr( get_post_meta( $pin->ID, 'longitude', true ) )
				);

			}

			/** Cache the query for 3 minutes */
			set_transient( 'rmaps_all_posts', $return, apply_filters( 'rmaps_posts_ttl', 60 * 3 ) );

		}

		$response = new \WP_REST_Response( $return );
		$response->header( 'Access-Control-Allow-Origin', apply_filters( 'rmaps_access_control_allow_origin', '*' ) );

		return $response;
	}

	public function get_maps_api_key(){
		return get_option( 'gmaps_api' );
	}
}

$rmaps = new RESTful_Maps();