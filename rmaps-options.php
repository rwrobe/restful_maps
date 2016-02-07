<?php
/**
 * RESTful Maps Options (currently used only to deal with the Google Maps API key.
 *
 * @package RMaps
 */

// create custom plugin settings menu
add_action('admin_menu', 'rmaps_create_menu');

function rmaps_create_menu() {

	add_menu_page(
		'RESTful Maps Settings',
		'RESTful Maps Settings',
		'administrator',
		__FILE__,
		'rmaps_settings_page',
		'dashicons-location-alt'
	);

	add_action( 'admin_init', 'register_rmaps_settings' );
}


function register_rmaps_settings() {
	//register our settings
	register_setting( 'rmaps-settings-group', 'gmaps_api' );
}

function rmaps_settings_page() {
	?>
	<div class="wrap">
		<h2>RESTful Maps Settings</h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'rmaps-settings-group' ); ?>
			<?php do_settings_sections( 'rmaps-settings-group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Google Maps API Key<br /><small>Get that <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">here</a>.</small></th>
					<td><input type="text" name="gmaps_api" value="<?php echo esc_attr( get_option( 'gmaps_api' ) ); ?>" /></td>
				</tr>
			</table>

			<?php submit_button(); ?>

		</form>
	</div>
<?php } ?>