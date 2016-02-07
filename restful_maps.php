<?php
/**
 * RESTful Maps
 *
 * Creates a custom Pins post type and REST endpoints to access and display locations on a Google Map
 *
 * @package rmaps
 * @author Rob Ward <rwrobe@gmail.com>
 * @version 0.1
 *
 * @wordpress
 * Plugin Name: RESTful Maps
 * Plugin URI: https://github.com/rwrobe/restful_maps
 * Description: Creates a custom Pins post type and REST endpoints to access and display locations on a Google Map.
 * Author: <a href="http://notne.com">Rob Ward</a>
 * Version: 0.1
 * Text Domain: rmaps
 */


namespace notne\rmaps;


if ( ! defined( 'RM_BASE_FILE' ) )
	define( 'RM_BASE_FILE', __FILE__ );
if ( ! defined( 'RM_BASE_DIR' ) )
	define( 'RM_BASE_DIR',  WP_PLUGIN_URL . '/' . dirname( plugin_basename( RM_BASE_FILE ) ) );
if ( ! defined( 'RM_PLUGIN_URL' ) )
	define( 'RM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/** Add the Pins CPT */
require_once 'src/Pins.php';

/** Add the REST endpoints */
require_once 'src/RESTful_Maps.php';

/** Add an admin page */
require_once 'rmaps-options.php';