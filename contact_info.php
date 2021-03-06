<?php

/*
Plugin Name: Contact Info
Plugin URI: http://gh.mattrude.com/mdr-network/
Description: Adds Custome User Contact Methods.  Adds Facebook URL, Google Talk, & Twitter ID.  Removes AIM & Jabber. See: Users -> Your Profile
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com
*/

define('CONTACT_INFO_TEXTDOMAIN', 'mdr-network');

if (function_exists('load_plugin_textdomain')) {
        load_plugin_textdomain(CONTACT_INFO_TEXTDOMAIN, false, dirname(__FILE__).'/languages' );
}

function add_mdr_contactmethod( $contactmethods ) {
  // Add Facebook, Google+, Google Talk, & Twitter
  $contactmethods['facebook'] = __('Facebook ID', CONTACT_INFO_TEXTDOMAIN);
  $contactmethods['googleplus'] = __('Google+ ID', CONTACT_INFO_TEXTDOMAIN);
  $contactmethods['googletalk'] = __('Google Talk', CONTACT_INFO_TEXTDOMAIN);
  $contactmethods['twitter'] = __('Twitter ID', CONTACT_INFO_TEXTDOMAIN);

  // Remove Jabber, & Yahoo IM
  unset($contactmethods['aim']);
  unset($contactmethods['jabber']);

  return $contactmethods;
}
add_filter('user_contactmethods','add_mdr_contactmethod',10,1);

?>
