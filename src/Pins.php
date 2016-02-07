<?php
/**
 * Create the metaboxes for post types passed in through the constructor, which will be retrieved for display by the REST API
 *
 * @package RMaps
 */


namespace notne\rmaps;


if ( ! defined( 'ABSPATH' ) ) {
	wp_die( '-1' );
}

class Pins {

	protected $textdomain;
	protected $taxonomies;
	protected $posts;
	protected $file;

	public function __construct( $file )	{
		$this->textdomain	= 'rmaps';
		$this->file         = $file;
		$this->posts		= array();
		$this->taxonomies 	= array();

		// Add the action hooks
		add_action( 'init', array( &$this, 'register_pins' ) ); // Register Custom Post Type

		register_deactivation_hook( $file, 'flush_rewrite_rules' );
		register_activation_hook( $file, 'flush_rewrites' );

		$this->pins_init();

	}

	public function pins_init() {

		// Define the settings
		$settings = array(
			'labels'			 => array(
				'name'				 => __( 'Pins', $this->textdomain ),
				'singular_name'		 => __( 'Pin', $this->textdomain ),
				'add_new'			 => __( 'Add New', $this->textdomain ),
				'add_new_item'		 => __( 'Add New Pin', $this->textdomain ),
				'edit'				 => __( 'Edit', $this->textdomain ),
				'edit_item'			 => __( 'Edit Pin', $this->textdomain ),
				'new_item'			 => __( 'New Pin', $this->textdomain ),
				'view'				 => __( 'View Pin', $this->textdomain ),
				'view_item'			 => __( 'View Pin', $this->textdomain ),
				'search_items'		 => __( 'Search Pins', $this->textdomain ),
				'not_found'			 => __( 'No pins found', $this->textdomain ),
				'not_found_in_trash' => __( 'No pins found in Trash', $this->textdomain ), /** Chuckle */
				'parent'			 => __( 'Parent Pin', $this->textdomain ),
			),
			'public'				 => true,
			'publicly_queryable'	 => true,
			'show_ui'				 => true,
			'query_var'				 => true,
			'capability_type'		 => 'post',
			'hierarchical'	 		 => false,
			'menu_position'			 => null,
			'menu_icon' 			 => 'dashicons-location',
			'supports'				 => array( 'title', 'author', 'editor', 'thumbnail', 'revisions' ),
			'rewrite'				 => array(
				'slug' => 'pin'
			)
		); // End $settings


		// Store the settings in the post array
		$this->posts['pin'] = $settings;

	}

	public function register_pins() {

		foreach( $this->posts as $key=>$value )
			register_post_type( $key, $value );

		if( class_exists( 'acf' ) ) {
			$opts = array(
				'id'         => 'acf_latitudelongitude',
				'title'      => 'Latitude/Longitude',
				'fields'     => array(
					array(
						'key'           => 'field_56b70ac065fef',
						'label'         => 'Latitude',
						'name'          => 'latitude',
						'type'          => 'text',
						'required'      => 1,
						'default_value' => '',
						'placeholder'   => '16.7758N',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_56b70ae565ff0',
						'label'         => 'Longitude',
						'name'          => 'longitude',
						'type'          => 'text',
						'required'      => 1,
						'default_value' => '',
						'placeholder'   => '3.0094W',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'pin',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(
						0 => 'permalink',
						1 => 'excerpt',
						2 => 'custom_fields',
						3 => 'discussion',
						4 => 'comments',
						5 => 'revisions',
						6 => 'slug',
						7 => 'author',
						8 => 'format',
						9 => 'send-trackbacks',
					),
				),
				'menu_order' => 0,
			);

			register_field_group( $opts );
		}

		/** @todo Add lat./lon., city or country metaboxes if ACF not present */
	}

	public function flush_rewrites(){

		//defines the post type so the rules can be flushed.
		$this->register_pins();

		//and flush the rules.
		flush_rewrite_rules();
	}

	public static function get_pins( $args ){
		$defaults = array(
			'numberposts' => 5,
			'category' => 0, 'orderby' => 'date',
			'order' => 'DESC', 'include' => array(),
			'exclude' => array(), 'meta_key' => '',
			'meta_value' =>'', 'post_type' => 'pin',
			'suppress_filters' => true
		);

		$p = wp_parse_args( $args, $defaults );

		$get_pins = new WP_Query;
		return $get_pins->query( $p );
	}

}