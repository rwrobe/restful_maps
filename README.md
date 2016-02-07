# RESTful Maps
Add custom metaboxes to any post to associate it with a map marker on Google Maps and access them through a WP-REST API endpoint.

## Usage
Simply enter the latitude and longitude of your map pins, a title and description and they will show up on a Google Map.

To show the map in any post, use the shortcode `[rmaps width=500px height=320px]`, with your desired width and height (defaults to dimensions shown).

To add the map to your template, you can use the template tag: `rmaps_template_tag( $height, $width )`.