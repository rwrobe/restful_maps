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

	public function __construct( $file )	{
		$this->textdomain	= 'rmaps';
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
		$this->posts['pins'] = $settings;

	}

	public function register_pins() {

		foreach( $this->posts as $key=>$value )
			register_post_type( $key, $value );

		$opts = array (
			'id' => 'acf_location',
			'title' => 'Location',
			'fields' => array (
				array (
					'key' => 'field_56b610ff22aed',
					'label' => 'Location',
					'name' => 'location',
					'type' => 'google_map',
					'required' => 1,
					'center_lat' => '',
					'center_lng' => '',
					'zoom' => '',
					'height' => '',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'pins',
						'order_no' => 0,
						'group_no' => 0,
					),
				),
			),
			'options' => array (
				'position' => 'acf_after_title',
				'layout' => 'no_box',
				'hide_on_screen' => array (
					0 => 'permalink',
					1 => 'the_content',
					2 => 'excerpt',
					3 => 'custom_fields',
					4 => 'discussion',
					5 => 'comments',
					6 => 'revisions',
					7 => 'slug',
					8 => 'author',
					9 => 'format',
					10 => 'send-trackbacks',
				),
			),
			'menu_order' => 0,
		);

		if( class_exists( 'acf' ) )
			register_field_group( $opts );

		/** @todo Add lat./lon., city or country metaboxes if ACF not present */
	}

	public function flush_rewrites(){

		//defines the post type so the rules can be flushed.
		$this->register_pins();

		//and flush the rules.
		flush_rewrite_rules();
	}

	public static function get_pins(){
		$query_args = array(
			
		);
	}

}