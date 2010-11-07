<?php

/*
Plugin Name: Contact Info
Plugin URI: http://github.com/mattrude/mdr-network
Description: Adds Custome User Contact Methods.  Adds Facebook URL, Google Talk, & Twitter ID.  Removes AIM & Jabber. See: Users -> Your Profile
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com
*/

/********************************************************************************
  Add Custom User Contact Methods
*/

function add_mdr_contactmethod( $contactmethods ) {
  // Add Twitter
  $contactmethods['facebook'] = __('Facebook URL');
  $contactmethods['googletalk'] = __('Google Talk');
  $contactmethods['twitter'] = __('Twitter ID');

  // Remove AOL Messager, Jabber, & Yahoo IM
  unset($contactmethods['aim']);
  unset($contactmethods['jabber']);
  //unset($contactmethods['yim']);

  return $contactmethods;
}
add_filter('user_contactmethods','add_mdr_contactmethod',10,1);

?>
