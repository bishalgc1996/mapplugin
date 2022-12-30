<?php defined( 'ABSPATH' ) or die(); ?>
<?php

// Add an nonce field so we can check for it later.
wp_nonce_field( 'map_meta', 'map_meta_nonce_nonce' );

// Use get_post_meta to retrieve an existing value from the database.
$value = get_post_meta( $post->ID, 'map_meta', false);



// Display the form, using the current value.

?>
<label>latitude :</label>
<input type="text" name="map_meta[latitude]" placeholder="Enter the latitude" value="<?php echo esc_attr( $value['0']['latitude'] ); ?>"  />
<br/><br/>
<label>longitute :</label>
<input type="text" name="map_meta[longitute]" placeholder="Enter the longitude" value=<?php echo esc_attr( $value['0']['longitute'] ); ?> />

