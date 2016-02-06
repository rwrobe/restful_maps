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
		/** @todo Retrieve the pins */
		$pins = Pins::get_pins();
	}
}