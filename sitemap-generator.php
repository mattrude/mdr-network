<?php
/*
Plugin Name: Sitemap Generator
Plugin URI: http://github.com/mattrude/mdr-network
Description: Automatic generate standard XML sitemap (http://example.com/sitemap.xml) that supports the protocol including Google, Yahoo, MSN, Ask.com, and others. No files stored on your disk, the sitemap.xml file is generate as needed, like your feeds.
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com/
*/

function sitemap_flush_rules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

remove_filter('pre_get_posts','category_excluder_exclude_categories');
add_action('init', 'sitemap_flush_rules');

function xml_feed_rewrite($wp_rewrite) {
	$feed_rules = array(
		'.*sitemap.xml$' => 'index.php?feed=sitemap'
	);

	$wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
}

add_filter('generate_rewrite_rules', 'xml_feed_rewrite');

function do_feed_sitemap() {
        $content_dir = WP_CONTENT_DIR;
	load_template( $content_dir . '/mu-plugins/templates/feed-sitemap.php' );
}

add_action('do_feed_sitemap', 'do_feed_sitemap', 10, 1);

?>
