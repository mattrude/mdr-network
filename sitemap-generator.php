<?php
/*
Plugin Name: Sitemap Generator
Plugin URI: https://github.com/mattrude/wp-plugin-sitemap-generator
Description: Automatic generate standard XML sitemap (http://example.com/sitemap.xml) that supports the protocol including Google, Yahoo, MSN, Ask.com, and others. No files stored on your disk, the sitemap.xml file is generate as needed, like your feeds.
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com/
*/

function sitemap_flush_rules() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
}

add_action('init', 'sitemap_flush_rules');

function xml_feed_rewrite($wp_rewrite) {
        $feed_rules = array(
                '.*sitemap.xml$' => 'index.php?feed=sitemap'
        );

        $wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'xml_feed_rewrite');

function sitemap_no_trailing_slash( $redirect_url ) {
    if ( is_feed() && strpos( $redirect_url, 'sitemap.xml/' ) !== FALSE )
		return;

    return $redirect_url;
}
add_filter( 'redirect_canonical', 'sitemap_no_trailing_slash' );

function do_feed_sitemap() {
        $template_dir = dirname(__FILE__) . '/templates';
        load_template( $template_dir . '/feed-sitemap.php' );
}

add_action('do_feed_sitemap', 'do_feed_sitemap', 10, 1);

?>
