<?php
/*
Plugin Name: Favicon
Plugin URI: http://github.com/mattrude/mdr-network
Description: Provides favicon support for WordPress
Version: 1.0
Author: Matt Rude
Author URI: http://mattrude.com
*/

function mdr_favicon() {
    $upload_dir = wp_upload_dir();
    if (file_exists($upload_dir['basedir'] . '/favicon.ico')) {
        $favicon = $upload_dir['baseurl'] . '/favicon.ico';
        echo "<link rel='shortcut icon' href='$favicon'>
";
    } elseif (file_exists($upload_dir['basedir'] . '/favicon.png')) {
        $favicon = $upload_dir['baseurl'] . '/favicon.png';
        echo "<link rel='shortcut icon' href='$favicon'>
";
    } else {
        $default = urlencode('http://therudes.com/favicon.ico');
        $emailhash = md5(strtolower(trim(get_settings('admin_email'))));
	$favicon = "http://www.gravatar.com/avatar/$emailhash?s=20&d=$default";
        echo "<link rel='shortcut icon' href='$favicon'>
";
    }
}

add_action('wp_head','mdr_favicon');
add_action('admin_head','mdr_favicon');

?>
