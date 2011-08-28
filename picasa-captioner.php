<?php
/**
Plugin Name: Picasa Captioner
Description: Fix up WordPress to read Picasa Captions from EXIF info properly.
Plugin URI: http://ottopress.com/2011/picasa-and-wordpress-and-captions/
Version: 1.0
Author: Otto
Author URI: http://ottodestruct.com/
**/

add_filter( 'wp_read_image_metadata', 'picasa_adjust_caption' );
function picasa_adjust_caption($meta) {
	if (empty($meta['caption']) && !empty($meta['title'])) {
		$meta['caption'] = $meta['title'];
		$meta['title'] = '';
	}
	return $meta;
}

add_action( 'add_attachment', 'picasa_adjust_attachment' );
function picasa_adjust_attachment($id) {
	$attachment = & get_post( $id, ARRAY_A );
	if ( !empty( $attachment ) ) {
		$attachment['post_excerpt'] = $attachment['post_content'];
		$attachment['post_content'] = '';
		wp_update_post($attachment);
	}
} ?>
