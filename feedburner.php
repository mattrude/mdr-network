<?php
/*
Plugin Name: Feedburner
Plugin URI: http://gh.mattrude.com/mdr-network/
Description: Redirects all sites feeds to the entred Feedburner feed. See: Settings -> Reading
Author: Matt Rude
Author URI: http://mattrude.com/
Version: 1.0
*/ 

define('FEEDBURNER_TEXTDOMAIN', 'mdr-network');

if (function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain(FEEDBURNER_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)).'/languages' );
}

add_action('admin_init', 'feedburner_config_page');

function feedburner_config_page() {
	add_settings_section( 'feedburner_section', __('Feedburner Service', FEEDBURNER_TEXTDOMAIN), 'feedburner_head_config', 'reading', 'feedburner_config' );
	add_settings_field( 'feedburner_url', __('Feedburner URL', FEEDBURNER_TEXTDOMAIN), 'feedburner_url_conf', 'reading', 'feedburner_section' );
	add_settings_field( 'feedburner_comment_url', __('Feedburner Comment URL', FEEDBURNER_TEXTDOMAIN), 'feedburner_comment_url_conf', 'reading', 'feedburner_section' );
	add_settings_field( 'feedburner_append_cats', __('Append category/tag to URL', FEEDBURNER_TEXTDOMAIN), 'feedburner_append_cats_conf', 'reading', 'feedburner_section' );
	add_settings_field( 'feedburner_no_cats', __('Do not redirect category or tag feeds', FEEDBURNER_TEXTDOMAIN), 'feedburner_no_cats_conf', 'reading', 'feedburner_section' );
	add_settings_field( 'feedburner_no_search', __('Do not redirect search result feeds', FEEDBURNER_TEXTDOMAIN), 'feedburner_no_search_conf', 'reading', 'feedburner_section' );
	register_setting( 'reading', 'feedburner_url' );
	register_setting( 'reading', 'feedburner_comment_url' );
	register_setting( 'reading', 'feedburner_append_cats' );
	register_setting( 'reading', 'feedburner_no_cats' );
	register_setting( 'reading', 'feedburner_no_search' );
}

function feedburner_head_config() {
	echo '<p>' . __('<strong>Feedburner</strong> is a free feed management service, providing custom <a href="http://en.wikipedia.org/wiki/RSS" target="_blank">RSS</a> feeds and management tools such as traffic analysis and an optional advertising system.', FEEDBURNER_TEXTDOMAIN) . '</p>';

	echo '<p>' . __('First go to <a href="http://feedburner.com/" target="_blank" >Feedburner.com</a> and burn your feed. Enter the URL Feedburner created for you. You may optionally redirect your comments feed using the same procedure. To disable redirection, disable the plugin or erase the URLs.', FEEDBURNER_TEXTDOMAIN) . '</p>';

	echo '<p>' . __('Once you enter URLs your feeds will be redirected automatically and you do not need to take any further action. Note that your feeds may not appear to redirect to Feedburner until you add a new post.', FEEDBURNER_TEXTDOMAIN) . '</p>';

	echo '<p>' . __('You may disable the Feedburner redircted by removing BOTH URLs.', FEEDBURNER_TEXTDOMAIN) . '</p>';
}

function feedburner_url_conf() {
	$options = get_option('site_feedburner');
	if (!isset($options['feedburner_url'])) $options['feedburner_url'] = null;
	echo "<input id='feedburner_url' name='feedburner_url' type='text' maxlength='200' size='60px' value='" . get_option('feedburner_url') . "' />";
}

function feedburner_comment_url_conf() {
	$options = get_option('site_feedburner');
	if (!isset($options['feedburner_comment_url'])) $options['feedburner_comment_url'] = null;
	echo "<input id='feedburner_comment_url' name='feedburner_comment_url' type='text' maxlength='200' size='60px' value='" . get_option('feedburner_comment_url') . "' />";
}

function feedburner_append_cats_conf() {
	$checked = "";
 	if (get_option('feedburner_append_cats')) 
 		$checked = " checked='checked' ";
	echo "<input {$checked} name='feedburner_append_cats' type='checkbox' value='eg_setting_name' />";
}

function feedburner_no_cats_conf() {
	$checked = "";
 	if (get_option('feedburner_no_cats')) 
 		$checked = " checked='checked' ";
	echo "<input {$checked} name='feedburner_no_cats' type='checkbox' value='eg_setting_name' />";
}

function feedburner_no_search_conf() {
	$checked = "";
 	if (get_option('feedburner_no_search')) 
 		$checked = " checked='checked' ";
	echo "<input {$checked} name='feedburner_no_search' type='checkbox' value='eg_setting_name' />";
}


function feedburner_redirect() {
	global $feed, $withcomments, $wp, $wpdb, $wp_version, $wp_db_version;
	
	// Do nothing if not a feed
	if (!is_feed()) return;
	
	// Do nothing if feedburner is the user-agent
	if (preg_match('/feedburner/i', $_SERVER['HTTP_USER_AGENT'])) return;
	
	// Do nothing if not configured
	$options = get_option('site_feedburner');
	if (!isset($options['feedburner_url'])) $options['feedburner_url'] = null;
	if (!isset($options['feedburner_comment_url'])) $options['feedburner_comment_url'] = null;
	if (!isset($options['feedburner_append_cats'])) $options['feedburner_append_cats'] = 0;
	if (!isset($options['feedburner_no_cats'])) $options['feedburner_no_cats'] = 0;
	if (!isset($options['feedburner_no_search'])) $options['feedburner_no_search'] = 0;
	$feed_url = $options['feedburner_url'];
	$comment_url = $options['feedburner_comment_url'];
	if ($feed_url == null && $comment_url == null) return;
	
	// Get category
	$cat = null;
	if ($wp->query_vars['category_name'] != null) {
		$cat = $wp->query_vars['category_name'];
	}
	if ($wp->query_vars['cat'] != null) {
		if ($wp_db_version >= 6124) {
			// 6124 = WP 2.3
			$cat = $wpdb->get_var("SELECT slug FROM $wpdb->terms WHERE term_id = '".$wp->query_vars['cat']."' LIMIT 1");
		} else {
			$cat = $wpdb->get_var("SELECT category_nicename FROM $wpdb->categories WHERE cat_ID = '".$wp->query_vars['cat']."' LIMIT 1");
		}
	}
	if ($options['feedburner_append_cats'] == 1 && $cat) {
		$feed_url .= '_'.$cat;
	}
	
	// Get tag
	$tag = null;
	if ($wp->query_vars['tag'] != null) {
		$tag = $wp->query_vars['tag'];
	}
	if ($options['feedburner_append_cats'] == 1 && $tag) {
		$feed_url .= '_'.$tag;
	}

	// Get search terms
	$search = null;
	if ($wp->query_vars['s'] != null) {
		$search = $wp->query_vars['s'];
	}

	// Redirect comment feed
	if ($feed == 'comments-rss2' || is_single() || $withcomments) {
		if ($comment_url != null) {
			header("Location: ".$comment_url);
			die;
		}
	} else {
		// Other feeds
		switch($feed) {
			case 'feed':
			case 'rdf':
			case 'rss':
			case 'rss2':
			case 'atom':
				if (($cat || $tag) && $options['feedburner_no_cats'] == 1) {
					// If this is a category/tag feed and redirect is disabled, do nothing
				} else if ($search && $options['feedburner_no_search'] == 1) {
					// If this is a search result feed and redirect is disabled, do nothing
				} else {
					if ($feed_url != null) {
						// Redirect the feed
						header("Location: ".$feed_url);
						die;
					}
				}
		}
	}
}

add_action('template_redirect', 'feedburner_redirect');
?>
