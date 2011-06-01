<?php

/*
Plugin Name: Page Speed
Plugin URI: http://github.com/mattrude/mdr-network
Description: Provides small imporvments for page loading, currently set to remove l10n.js.
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com
*/


if ( !is_admin() ) {
	function my_init_method() {
		wp_deregister_script( 'l10n' );
	}
	add_action('init', 'my_init_method'); 
}


?>
