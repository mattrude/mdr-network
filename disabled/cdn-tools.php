<?php
/*
Plugin Name: CDN MDR Tools
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com
*/

$cdn_network = "http://c3123652.r52.cf0.rackcdn.com";
global $cdn_network;

//CDN Support
add_filter('the_header', 'cdn_urls', 10, 2);
add_filter('the_content', 'cdn_urls', 10, 2);
add_filter('stylesheet_uri','cdn_urls', 10, 2);
function cdn_urls($content) {
  global $cdn_network;
  $domain = $_SERVER["SERVER_NAME"];
  $tof = "http://".$domain;
  $content = str_replace($tof."/wp-includes/css", $cdn_network."/wp-includes/css", $content);
  $content = str_replace($tof."/wp-includes/js", $cdn_network."/wp-includes/js", $content);
  $content = str_replace($tof."/wp-includes/images", $cdn_network."/wp-includes/images", $content);
  $content = str_replace($tof."/wp-content/plugins", $cdn_network."/wp-content/plugins", $content);
  $content = str_replace($tof."/wp-content/themes", $cdn_network."/wp-content/themes", $content);
  return $content;
}

add_action('init', 'cdn_js');
function cdn_js() {
  global $cdn_network;
  wp_deregister_script( 'admin-bar' );
  wp_deregister_script( 'comment-reply' );
  wp_deregister_script( 'l10n' );
  wp_deregister_script( 'jquery' );
  wp_deregister_script( 'jquery-noconflict' );

  wp_register_script( 'admin-bar', $cdn_network.'/wp-includes/js/admin-bar.js','','20110131' );
  wp_register_script( 'jquery-noconflict', $cdn_network.'/wp-content/plugins/cdn-tools/cdn_classes/jquery-noconflict.js','','3.1.1-alpha-17490' );
  wp_enqueue_script( 'comment-reply', $cdn_network.'/wp-includes/js/comment-reply.js','','20090102' );
  wp_enqueue_script( 'l10n', $cdn_network.'/wp-includes/js/l10n.js','','20101110' );
  wp_enqueue_script( 'jquery', $cdn_network.'/wp-includes/js/jquery/jquery.js','','1.4.4' );
}

// Per Site CDN rewrite
if ( get_option('cdntools_baseuri') != NULL ) {
  add_filter('the_content', 'cdn_urls_site', 10, 2);
  add_filter('post_thumbnail_html', 'cdn_urls_site', 10, 2);
  add_filter('post_gallery', 'cdn_urls_site', 10, 2);
  add_filter('attachment_url', 'cdn_urls_site', 10, 2);
  add_filter('wp_get_attachment_url', 'cdn_urls_site', 10, 2);
  function cdn_urls_site($content) {
    $domain = $_SERVER["SERVER_NAME"];
    $rep = get_option('cdntools_baseuri');
    $tof = "http://".$domain;
    $content = str_replace($tof."/wp-content/uploads/", $rep."/", $content);
    $content = str_replace($tof."/files", $rep, $content);
    return $content;
  }
}

?>
