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


require_once 'src/RESTful_Maps.php';
$rmaps = new RESTful_Maps( __FILE__ );

require_once 'src/Pins.php';
$rmaps = new Pins( __FILE__ );

require_once 'rmaps-options.php';